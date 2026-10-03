<?php
class CustomerController
{

    public function index()
    {
        Auth::requireLogin();
        global $pdo;
        $uid = Auth::id();
        $isManager = Auth::isManager();

        $q = trim($_GET['q'] ?? '');
        $status = $_GET['status'] ?? '';
        $page = max(1, (int)($_GET['page'] ?? 1));
        $perPage = 20;
        $offset = ($page - 1) * $perPage;

        $sql = "SELECT c.*, s.name source_name, u.full_name assigned_name 
                FROM customers c
                LEFT JOIN sources s ON c.source_id = s.id
                LEFT JOIN users u ON c.assigned_to = u.id
                WHERE 1=1";
        $params = [];

        if (!$isManager) {
            $sql .= " AND c.assigned_to = ?";
            $params[] = $uid;
        }
        if ($q) {
            $sql .= " AND (c.full_name LIKE ? OR c.phone LIKE ? OR c.code LIKE ?)";
            $params[] = "%$q%";
            $params[] = "%$q%";
            $params[] = "%$q%";
        }
        if ($status) {
            $sql .= " AND c.status = ?";
            $params[] = $status;
        }

        // Count
        $countSql = preg_replace('/SELECT .+? FROM/s', 'SELECT COUNT(*) FROM', $sql);
        $st = $pdo->prepare($countSql);
        $st->execute($params);
        $total = $st->fetchColumn();

        $sql .= " ORDER BY c.created_at DESC LIMIT $perPage OFFSET $offset";
        $st = $pdo->prepare($sql);
        $st->execute($params);
        $customers = $st->fetchAll();

        $pages = ceil($total / $perPage);

        View::render('customers/index', [
            'pageTitle'  => 'Khách hàng',
            'activePage' => 'customer',
            'customers'  => $customers,
            'q'          => $q,
            'status'     => $status,
            'page'       => $page,
            'pages'      => $pages,
            'total'      => $total,
        ]);
    }

    public function create()
    {
        Auth::requireLogin();
        global $pdo, $sqlsrv;

        // 1. Nguồn cũ trong MySQL (Facebook, Google...)
        $sourcesLocal = $pdo->query("
        SELECT id, name, category, icon, color 
        FROM sources 
        WHERE is_active = 1 AND (external_source IS NULL OR external_source = '')
        ORDER BY name
    ")->fetchAll();

        // 2. Nguồn đã đồng bộ từ SQL Server (nếu có)
        $sourcesSynced = $pdo->query("
        SELECT id, name, category, icon, color 
        FROM sources 
        WHERE is_active = 1 AND external_source = 'DKKhamDoan'
        ORDER BY category, name
    ")->fetchAll();

        // 3. Nguồn SQL Server LIVE (chưa đồng bộ) — query trực tiếp
        $sourcesSqlServer = [];
        if ($sqlsrv) {
            try {
                $sourcesSqlServer = $sqlsrv->query("
                SELECT ID, MaCty, Ten, Phanloai
                FROM DKKhamDoan
                WHERE (Denngay IS NULL OR Denngay <= GETDATE())
                ORDER BY Phanloai, Ten
            ")->fetchAll();
            } catch (Exception $e) {
                $sourcesSqlServer = [];
            }
        }

        // Nhóm sources SQL theo Phanloai
        $groupedSql = [];
        foreach ($sourcesSqlServer as $s) {
            $cat = $s['Phanloai'] ?: 'Khác';
            $groupedSql[$cat][] = $s;
        }

        View::render('customers/form', [
            'pageTitle'      => 'Thêm khách hàng',
            'activePage'     => 'customer',
            'sourcesLocal'   => $sourcesLocal,
            'sourcesSynced'  => $sourcesSynced,
            'groupedSql'     => $groupedSql,
        ]);
    }

    public function store()
    {
        Auth::requireLogin();
        global $pdo, $sqlsrv;
        $d = $_POST;

        if (empty($d['full_name']) || empty($d['phone'])) {
            Helper::flash('danger', 'Vui lòng nhập họ tên và số điện thoại');
            Helper::redirect(BASE_URL . '/index.php?c=customer&a=create');
        }

        // Check duplicate phone
        $check = $pdo->prepare("SELECT id FROM customers WHERE phone = ?");
        $check->execute([$d['phone']]);
        if ($check->fetch()) {
            Helper::flash('warning', 'Số điện thoại này đã tồn tại!');
            Helper::redirect(BASE_URL . '/index.php?c=customer&a=create');
        }

        // ═══ XỬ LÝ NGUỒN ═══
        $sourceId = null;

        // Case 1: Chọn nguồn SQL Server chưa đồng bộ (từ dropdown)
        if (!empty($d['source_sql_id'])) {
            $sqlId = (int)$d['source_sql_id'];

            // Kiểm tra đã đồng bộ chưa
            $checkSynced = $pdo->prepare("SELECT id FROM sources 
                                       WHERE external_source = 'DKKhamDoan' 
                                       AND external_id = ?");
            $checkSynced->execute([$sqlId]);

            if ($existing = $checkSynced->fetch()) {
                // Đã có trong MySQL → dùng luôn
                $sourceId = $existing['id'];
            } else {
                // Chưa có → tự động đồng bộ luôn
                if ($sqlsrv) {
                    try {
                        $st = $sqlsrv->prepare("SELECT * FROM DKKhamDoan WHERE ID = ?");
                        $st->execute([$sqlId]);
                        $src = $st->fetch();

                        if ($src) {
                            $ins = $pdo->prepare("
                            INSERT INTO sources 
                            (external_id, external_source, name, type, category, description, 
                             icon, color, is_active, start_date, end_date)
                            VALUES (?, 'DKKhamDoan', ?, 'other', ?, ?, 'users', '#06b6d4', 1, ?, ?)
                        ");
                            $ins->execute([
                                $src['ID'],
                                $src['Ten'],
                                $src['Phanloai'] ?: 'Khác',
                                trim(($src['MaCty'] ? $src['MaCty'] . ' · ' : '') . ($src['Phanloai'] ?: '')),
                                $src['Tungay'] ? date('Y-m-d', strtotime($src['Tungay'])) : null,
                                $src['Denngay'] ? date('Y-m-d', strtotime($src['Denngay'])) : null,
                            ]);
                            $sourceId = $pdo->lastInsertId();
                        }
                    } catch (Exception $e) {
                        // Bỏ qua lỗi, để source_id = null
                    }
                }
            }
        }
        // Case 2: Chọn nguồn MySQL bình thường
        elseif (!empty($d['source_id'])) {
            $sourceId = (int)$d['source_id'];
        }

        // ═══ INSERT CUSTOMER ═══
        $code = 'KH' . date('ymd') . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);

        $st = $pdo->prepare("INSERT INTO customers 
        (code, full_name, phone, email, gender, birth_year, address, city,
         source_id, assigned_to, status, interest, estimated_value, note, next_followup_at, created_by)
        VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)");
        $st->execute([
            $code,
            $d['full_name'],
            $d['phone'],
            $d['email'] ?? null,
            $d['gender'] ?? 'other',
            !empty($d['birth_year']) ? (int)$d['birth_year'] : null,
            $d['address'] ?? null,
            $d['city'] ?? null,
            $sourceId,
            !empty($d['assigned_to']) ? (int)$d['assigned_to'] : Auth::id(),
            $d['status'] ?? 'new',
            $d['interest'] ?? null,
            (float)($d['estimated_value'] ?? 0),
            $d['note'] ?? null,
            !empty($d['next_followup_at']) ? $d['next_followup_at'] : null,
            Auth::id()
        ]);
        $cid = $pdo->lastInsertId();

        // Log activity
        $pdo->prepare("INSERT INTO activities (customer_id, user_id, type, content) VALUES (?, ?, 'note', ?)")
            ->execute([$cid, Auth::id(), 'Tạo khách hàng mới']);

        Helper::flash('success', 'Thêm khách hàng thành công!');
        Helper::redirect(BASE_URL . "/index.php?c=customer&a=show&id=$cid");
    }

    public function show()
    {
        Auth::requireLogin();
        global $pdo;
        $id = (int)($_GET['id'] ?? 0);

        $st = $pdo->prepare("SELECT c.*, s.name source_name, u.full_name assigned_name 
                             FROM customers c
                             LEFT JOIN sources s ON c.source_id = s.id
                             LEFT JOIN users u ON c.assigned_to = u.id
                             WHERE c.id = ?");
        $st->execute([$id]);
        $customer = $st->fetch();

        if (!$customer) {
            Helper::flash('danger', 'Không tìm thấy khách hàng');
            Helper::redirect(BASE_URL . '/index.php?c=customer');
        }

        if (!Auth::isManager() && $customer['assigned_to'] != Auth::id()) {
            http_response_code(403);
            die('Bạn không có quyền xem khách hàng này');
        }

        $acts = $pdo->prepare("SELECT a.*, u.full_name user_name 
                               FROM activities a
                               JOIN users u ON a.user_id = u.id 
                               WHERE a.customer_id = ? 
                               ORDER BY a.created_at DESC");
        $acts->execute([$id]);
        $activities = $acts->fetchAll();

        View::render('customers/show', [
            'pageTitle'  => 'Chi tiết khách hàng',
            'activePage' => 'customer',
            'customer'   => $customer,
            'activities' => $activities,
        ]);
    }

    public function update()
    {
        Auth::requireLogin();
        global $pdo;
        $id = (int)($_POST['id'] ?? 0);
        $d = $_POST;

        $old = $pdo->prepare("SELECT status FROM customers WHERE id = ?");
        $old->execute([$id]);
        $oldStatus = $old->fetchColumn();

        $st = $pdo->prepare("UPDATE customers SET 
            full_name=?, phone=?, email=?, gender=?, birth_year=?, address=?, city=?,
            source_id=?, assigned_to=?, status=?, interest=?, estimated_value=?, note=?, next_followup_at=?
            WHERE id=?");
        $st->execute([
            $d['full_name'],
            $d['phone'],
            $d['email'] ?? null,
            $d['gender'] ?? 'other',
            !empty($d['birth_year']) ? (int)$d['birth_year'] : null,
            $d['address'] ?? null,
            $d['city'] ?? null,
            !empty($d['source_id']) ? (int)$d['source_id'] : null,
            !empty($d['assigned_to']) ? (int)$d['assigned_to'] : null,
            $d['status'],
            $d['interest'] ?? null,
            (float)($d['estimated_value'] ?? 0),
            $d['note'] ?? null,
            !empty($d['next_followup_at']) ? $d['next_followup_at'] : null,
            $id
        ]);

        if ($oldStatus !== $d['status']) {
            $pdo->prepare("INSERT INTO activities (customer_id, user_id, type, content) VALUES (?, ?, 'status_change', ?)")
                ->execute([$id, Auth::id(), "Trạng thái: " . Helper::statusLabel($oldStatus) . " → " . Helper::statusLabel($d['status'])]);
        }

        Helper::flash('success', 'Cập nhật thành công!');
        Helper::redirect(BASE_URL . "/index.php?c=customer&a=show&id=$id");
    }

    public function addActivity()
    {
        Auth::requireLogin();
        global $pdo;
        $cid = (int)$_POST['customer_id'];
        $pdo->prepare("INSERT INTO activities (customer_id, user_id, type, content, result) VALUES (?, ?, ?, ?, ?)")
            ->execute([$cid, Auth::id(), $_POST['type'], $_POST['content'], $_POST['result'] ?? 'pending']);
        $pdo->prepare("UPDATE customers SET last_contact_at = NOW() WHERE id = ?")->execute([$cid]);
        Helper::flash('success', 'Đã ghi nhận tương tác');
        Helper::redirect(BASE_URL . "/index.php?c=customer&a=show&id=$cid");
    }
}
