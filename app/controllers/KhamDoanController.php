<?php

/**
 * Controller: Đồng bộ nguồn khách từ SQL Server DKKhamDoan → MySQL sources
 */
class KhamDoanController
{

    private $model;

    public function __construct()
    {
        global $sqlsrv;
        require_once ROOT_PATH . '/app/models/PhongKhamModel.php';
        $this->model = $sqlsrv ? new PhongKhamModel($sqlsrv) : null;
    }

    /**
     * TRANG CHÍNH — Danh sách nguồn từ SQL Server
     */
    /**
     * TRANG CHÍNH — Danh sách nguồn từ SQL Server (CÓ PHÂN TRANG)
     */
    public function index()
    {
        Auth::requireRole(['admin', 'manager']);
        global $sqlsrv, $pdo;

        if (!$sqlsrv || !$this->model) {
            View::render('khamdoan/error', [
                'pageTitle'  => 'Nguồn khách — SQL Server',
                'activePage' => 'khamdoan',
            ]);
            return;
        }

        // ═══ Phân trang ═══
        $q       = trim($_GET['q'] ?? '');
        $page    = max(1, (int)($_GET['page'] ?? 1));
        $perPage = 50;  // 50 nguồn / trang

        try {
            // Lấy data có phân trang
            $rows  = $this->model->getSourcesPaged($page, $perPage, $q);
            $total = $this->model->countSourcesFiltered($q);

            // Stats tổng thể (không filter)
            $totalAll     = $this->model->countSources();
            $activeCount  = $this->model->countActive();
            $categories   = $this->model->countByCategory();
        } catch (Exception $e) {
            Helper::flash('danger', 'Lỗi SQL Server: ' . $e->getMessage());
            $rows = [];
            $categories = [];
            $total = 0;
            $totalAll = 0;
            $activeCount = 0;
        }

        $pages = max(1, ceil($total / $perPage));

        // Nếu page vượt quá → về trang cuối
        if ($page > $pages) {
            $page = $pages;
        }

        // Kiểm tra nguồn nào đã có trong MySQL (chỉ check các nguồn đang hiển thị)
        $syncedIds = [];
        if (!empty($rows)) {
            $ids = array_column($rows, 'ID');
            $placeholders = implode(',', array_fill(0, count($ids), '?'));
            $st = $pdo->prepare("SELECT external_id FROM sources 
                                 WHERE external_source = 'DKKhamDoan' 
                                 AND external_id IN ($placeholders)");
            $st->execute($ids);
            while ($id = $st->fetchColumn()) {
                $syncedIds[$id] = true;
            }
        }

        // Đếm tổng đã sync (không filter)
        $syncedCount = $pdo->query("SELECT COUNT(*) FROM sources 
                                     WHERE external_source = 'DKKhamDoan'")->fetchColumn();

        View::render('khamdoan/index', [
            'pageTitle'    => 'Nguồn khách — SQL Server',
            'activePage'   => 'khamdoan',
            'rows'         => $rows,
            'categories'   => $categories,
            'total'        => $total,
            'totalAll'     => $totalAll,
            'activeCount'  => $activeCount,
            'syncedIds'    => $syncedIds,
            'syncedCount'  => $syncedCount,
            'q'            => $q,
            'page'         => $page,
            'pages'        => $pages,
            'perPage'      => $perPage,
        ]);
    }

    /**
     * Đồng bộ 1 nguồn → MySQL
     */
    public function syncOne()
    {
        Auth::requireRole(['admin', 'manager']);
        global $pdo;

        header('Content-Type: application/json');

        try {
            $id = (int)($_POST['id'] ?? 0);
            if (!$id) throw new Exception('ID không hợp lệ');

            // Kiểm tra đã tồn tại chưa
            $check = $pdo->prepare("SELECT id FROM sources WHERE external_source = 'DKKhamDoan' AND external_id = ?");
            $check->execute([$id]);
            if ($check->fetch()) {
                echo json_encode(['ok' => true, 'existing' => true, 'message' => 'Đã tồn tại']);
                return;
            }

            // Lấy từ SQL Server
            $src = $this->model->getSourceById($id);
            if (!$src) throw new Exception('Không tìm thấy nguồn');

            // Insert vào MySQL
            $st = $pdo->prepare("
                INSERT INTO sources 
                (external_id, external_source, name, type, category, description, icon, color, 
                 is_active, start_date, end_date)
                VALUES (?, 'DKKhamDoan', ?, ?, ?, ?, ?, ?, ?, ?, ?)
            ");
            $st->execute([
                $src['ID'],
                $src['Ten'] ?: ('Nguồn ' . $src['MaCty']),
                'other',
                $src['Phanloai'] ?: 'Khác',
                trim(($src['MaCty'] ? $src['MaCty'] . ' · ' : '') . ($src['Phanloai'] ?: '')),
                'users',
                '#06b6d4',
                1,
                $src['Tungay'] ? date('Y-m-d', strtotime($src['Tungay'])) : null,
                $src['Denngay'] ? date('Y-m-d', strtotime($src['Denngay'])) : null,
            ]);

            echo json_encode(['ok' => true, 'new' => true, 'sources_id' => $pdo->lastInsertId()]);
        } catch (Exception $e) {
            echo json_encode(['ok' => false, 'error' => $e->getMessage()]);
        }
    }

    /**
     * Đồng bộ TẤT CẢ nguồn → MySQL
     */
    public function syncAll()
    {
        Auth::requireRole(['admin', 'manager']);
        global $pdo;

        header('Content-Type: application/json');

        try {
            $rows = $this->model->getAllSources();
            $added = 0;
            $skipped = 0;
            $errors = [];

            // Chuẩn bị statement check
            $check = $pdo->prepare("SELECT id FROM sources WHERE external_source = 'DKKhamDoan' AND external_id = ?");
            $insert = $pdo->prepare("
                INSERT INTO sources 
                (external_id, external_source, name, type, category, description, icon, color, 
                 is_active, start_date, end_date)
                VALUES (?, 'DKKhamDoan', ?, ?, ?, ?, ?, ?, ?, ?, ?)
            ");

            foreach ($rows as $src) {
                try {
                    $check->execute([$src['ID']]);
                    if ($check->fetch()) {
                        $skipped++;
                        continue;
                    }

                    $insert->execute([
                        $src['ID'],
                        $src['Ten'] ?: ('Nguồn ' . $src['MaCty']),
                        'other',
                        $src['Phanloai'] ?: 'Khác',
                        trim(($src['MaCty'] ? $src['MaCty'] . ' · ' : '') . ($src['Phanloai'] ?: '')),
                        'users',
                        '#06b6d4',
                        1,
                        $src['Tungay'] ? date('Y-m-d', strtotime($src['Tungay'])) : null,
                        $src['Denngay'] ? date('Y-m-d', strtotime($src['Denngay'])) : null,
                    ]);
                    $added++;
                } catch (Exception $e) {
                    $errors[] = "ID {$src['ID']}: " . $e->getMessage();
                }
            }

            echo json_encode([
                'ok' => true,
                'added' => $added,
                'skipped' => $skipped,
                'total' => count($rows),
                'errors' => array_slice($errors, 0, 5),
            ]);
        } catch (Exception $e) {
            echo json_encode(['ok' => false, 'error' => $e->getMessage()]);
        }
    }

    /**
     * Xóa nguồn đã đồng bộ khỏi MySQL (nhưng giữ trong SQL Server)
     */
    public function unsync()
    {
        Auth::requireRole(['admin']);
        global $pdo;

        header('Content-Type: application/json');
        $id = (int)($_POST['id'] ?? 0);

        if (!$id) {
            echo json_encode(['ok' => false, 'error' => 'ID không hợp lệ']);
            return;
        }

        try {
            $st = $pdo->prepare("DELETE FROM sources WHERE external_source = 'DKKhamDoan' AND external_id = ?");
            $st->execute([$id]);

            echo json_encode(['ok' => true, 'deleted' => $st->rowCount()]);
        } catch (Exception $e) {
            echo json_encode(['ok' => false, 'error' => $e->getMessage()]);
        }
    }
}
