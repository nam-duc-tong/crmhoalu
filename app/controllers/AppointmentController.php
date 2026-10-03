<?php
class AppointmentController {

    // ══════════════════════════════════════════
    // DANH SÁCH
    // ══════════════════════════════════════════
    public function index() {
        Auth::requireLogin();
        global $pdo;
        $uid = Auth::id();
        $isManager = Auth::isManager();

        $where = $isManager ? "1=1" : "a.user_id = " . (int)$uid;
        $filterStatus = $_GET['status'] ?? '';
        $filterDate   = $_GET['date'] ?? '';

        $conds = [$where];
        $params = [];
        if ($filterStatus) { $conds[] = "a.status = ?"; $params[] = $filterStatus; }
        if ($filterDate)   { $conds[] = "DATE(a.scheduled_at) = ?"; $params[] = $filterDate; }
        $whereSql = implode(' AND ', $conds);

        $sql = "SELECT a.*, c.full_name customer_name, c.phone customer_phone, 
                       c.interest, u.full_name user_name
                FROM appointments a
                JOIN customers c ON a.customer_id = c.id
                LEFT JOIN users u ON a.user_id = u.id
                WHERE $whereSql
                ORDER BY a.scheduled_at DESC
                LIMIT 200";
        $st = $pdo->prepare($sql);
        $st->execute($params);
        $apts = $st->fetchAll();

        $upcoming = $pdo->query("
            SELECT a.*, c.full_name customer_name, c.phone customer_phone, c.interest
            FROM appointments a
            JOIN customers c ON a.customer_id = c.id
            WHERE a.scheduled_at >= NOW() 
              AND a.status IN ('pending','confirmed')
              " . ($isManager ? "" : "AND a.user_id = " . (int)$uid) . "
            ORDER BY a.scheduled_at ASC
            LIMIT 5
        ")->fetchAll();

        $stats = [
            'total'     => count($apts),
            'upcoming'  => count($upcoming),
            'done'      => count(array_filter($apts, fn($a) => $a['status'] === 'done')),
            'pending'   => count(array_filter($apts, fn($a) => $a['status'] === 'pending')),
            'confirmed' => count(array_filter($apts, fn($a) => $a['status'] === 'confirmed')),
            'cancelled' => count(array_filter($apts, fn($a) => in_array($a['status'], ['cancelled', 'no_show']))),
            'today'     => count(array_filter($apts, fn($a) => date('Y-m-d', strtotime($a['scheduled_at'])) === date('Y-m-d'))),
        ];

        $users = $pdo->query("SELECT id, full_name FROM users WHERE role IN ('sales','manager','admin') ORDER BY full_name")->fetchAll();
        $doctors = $pdo->query("SELECT DISTINCT doctor FROM appointments WHERE doctor IS NOT NULL AND doctor != '' ORDER BY doctor")->fetchAll();

        $month = $_GET['month'] ?? date('Y-m');
        $calendarStart = $month . '-01';
        $calendarEnd = date('Y-m-t', strtotime($calendarStart));
        
        $calSql = "SELECT a.*, c.full_name customer_name
                   FROM appointments a
                   JOIN customers c ON a.customer_id = c.id
                   WHERE DATE(a.scheduled_at) BETWEEN ? AND ?
                     " . ($isManager ? "" : "AND a.user_id = " . (int)$uid) . "
                   ORDER BY a.scheduled_at ASC";
        $calSt = $pdo->prepare($calSql);
        $calSt->execute([$calendarStart, $calendarEnd]);
        $calendarApts = $calSt->fetchAll();

        $aptsByDate = [];
        foreach ($calendarApts as $apt) {
            $d = date('Y-m-d', strtotime($apt['scheduled_at']));
            $aptsByDate[$d][] = $apt;
        }

        $customers = $pdo->query("SELECT id, full_name, phone FROM customers ORDER BY full_name LIMIT 500")->fetchAll();

        View::render('appointments/index', [
            'pageTitle'    => 'Lịch hẹn',
            'activePage'   => 'appointment',
            'apts'         => $apts,
            'upcoming'     => $upcoming,
            'stats'        => $stats,
            'users'        => $users,
            'doctors'      => $doctors,
            'customers'    => $customers,
            'aptsByDate'   => $aptsByDate,
            'month'        => $month,
            'filterStatus' => $filterStatus,
            'filterDate'   => $filterDate,
        ]);
    }

    public function create() {
        Auth::requireLogin();
        global $pdo;
        $customers = $pdo->query("SELECT id, full_name, phone FROM customers ORDER BY full_name")->fetchAll();
        
        View::render('appointments/create', [
            'pageTitle'  => 'Đặt lịch hẹn',
            'activePage' => 'appointment',
            'customers'  => $customers,
        ]);
    }

    // ══════════════════════════════════════════
    // STORE — API JSON, KHÔNG in gì khác
    // ══════════════════════════════════════════
    public function store() {
        // Tắt hết warning trước khi output
        while (ob_get_level() > 0) ob_end_clean();
        ini_set('display_errors', 0);
        ini_set('display_startup_errors', 0);
        error_reporting(0);
        
        Auth::requireLogin();
        global $pdo;
        
        header('Content-Type: application/json; charset=utf-8');
        
        try {
            $d = $_POST;

            // Validate
            if (empty($d['customer_id']))  throw new Exception('Vui lòng chọn khách hàng');
            if (empty($d['scheduled_at'])) throw new Exception('Vui lòng chọn thời gian');

            // Chuẩn hóa datetime
            $scheduled = str_replace('T', ' ', $d['scheduled_at']);
            if (strlen($scheduled) === 16) $scheduled .= ':00';

            $reminderAt = null;
            if (!empty($d['reminder_at'])) {
                $reminderAt = str_replace('T', ' ', $d['reminder_at']);
                if (strlen($reminderAt) === 16) $reminderAt .= ':00';
            }

            // Check conflict
            if (empty($d['force'])) {
                $conflict = $this->checkConflict($scheduled, $d['doctor'] ?? null);
                if (!empty($conflict)) {
                    echo json_encode(['ok' => false, 'conflict' => $conflict]);
                    return;
                }
            }

            // Detect cột tồn tại
            $hasDuration = $this->columnExists('appointments', 'duration_minutes');
            $hasReminder = $this->columnExists('appointments', 'reminder_at');
            $hasCreatedBy = $this->columnExists('appointments', 'created_by');

            // Build SQL động theo cột có sẵn
            $cols = ['customer_id', 'user_id', 'title', 'scheduled_at', 'doctor', 'status', 'note'];
            $vals = [
                (int)$d['customer_id'],
                Auth::id(),
                !empty($d['title']) ? trim($d['title']) : 'Khám bệnh',
                $scheduled,
                !empty($d['doctor']) ? trim($d['doctor']) : null,
                $d['status'] ?? 'pending',
                !empty($d['note']) ? trim($d['note']) : null,
            ];

            if ($hasDuration) {
                $cols[] = 'duration_minutes';
                $vals[] = !empty($d['duration_minutes']) ? (int)$d['duration_minutes'] : 30;
            }
            if ($hasReminder) {
                $cols[] = 'reminder_at';
                $vals[] = $reminderAt;
            }
            if ($hasCreatedBy) {
                $cols[] = 'created_by';
                $vals[] = Auth::id();
            }

            $placeholders = implode(',', array_fill(0, count($cols), '?'));
            $sql = "INSERT INTO appointments (" . implode(',', $cols) . ") VALUES ($placeholders)";
            
            $st = $pdo->prepare($sql);
            $st->execute($vals);
            $id = $pdo->lastInsertId();

            // Log activity (không quan trọng nếu fail)
            try {
                $pdo->prepare("INSERT INTO activities (customer_id, user_id, type, content, created_at) 
                               VALUES (?, ?, 'appointment', ?, NOW())")
                    ->execute([
                        (int)$d['customer_id'],
                        Auth::id(),
                        "Đặt lịch: " . ($d['title'] ?? 'Khám bệnh') . " lúc " . date('d/m/Y H:i', strtotime($scheduled))
                    ]);
            } catch (Exception $e) { /* ignore */ }

            echo json_encode(['ok' => true, 'id' => $id]);
            return;

        } catch (Throwable $e) {
            echo json_encode([
                'ok' => false,
                'error' => $e->getMessage(),
                'file' => basename($e->getFile()),
                'line' => $e->getLine(),
            ]);
            return;
        }
    }

    // ══════════════════════════════════════════
    // UPDATE
    // ══════════════════════════════════════════
    public function update() {
        while (ob_get_level() > 0) ob_end_clean();
        ini_set('display_errors', 0);
        error_reporting(0);
        
        Auth::requireLogin();
        global $pdo;
        header('Content-Type: application/json; charset=utf-8');

        try {
            $d = $_POST;
            $id = (int)($d['id'] ?? 0);
            if (!$id) throw new Exception('ID không hợp lệ');

            $scheduled = str_replace('T', ' ', $d['scheduled_at']);
            if (strlen($scheduled) === 16) $scheduled .= ':00';

            $reminderAt = null;
            if (!empty($d['reminder_at'])) {
                $reminderAt = str_replace('T', ' ', $d['reminder_at']);
                if (strlen($reminderAt) === 16) $reminderAt .= ':00';
            }

            $hasReminder = $this->columnExists('appointments', 'reminder_at');
            
            if ($hasReminder) {
                $st = $pdo->prepare("UPDATE appointments SET 
                    title = ?, scheduled_at = ?, doctor = ?, status = ?, note = ?, reminder_at = ?
                    WHERE id = ?");
                $st->execute([
                    $d['title'],
                    $scheduled,
                    $d['doctor'] ?? null,
                    $d['status'],
                    $d['note'] ?? null,
                    $reminderAt,
                    $id
                ]);
            } else {
                $st = $pdo->prepare("UPDATE appointments SET 
                    title = ?, scheduled_at = ?, doctor = ?, status = ?, note = ?
                    WHERE id = ?");
                $st->execute([
                    $d['title'],
                    $scheduled,
                    $d['doctor'] ?? null,
                    $d['status'],
                    $d['note'] ?? null,
                    $id
                ]);
            }

            echo json_encode(['ok' => true]);
        } catch (Throwable $e) {
            echo json_encode(['ok' => false, 'error' => $e->getMessage()]);
        }
    }

    // ══════════════════════════════════════════
    // UPDATE STATUS (inline)
    // ══════════════════════════════════════════
    public function updateStatus() {
        while (ob_get_level() > 0) ob_end_clean();
        ini_set('display_errors', 0);
        error_reporting(0);
        
        Auth::requireLogin();
        global $pdo;
        header('Content-Type: application/json; charset=utf-8');
        
        try {
            $id = (int)($_POST['id'] ?? 0);
            $status = $_POST['status'] ?? '';
            
            $valid = ['pending', 'confirmed', 'done', 'cancelled', 'no_show'];
            if (!in_array($status, $valid)) throw new Exception('Status không hợp lệ');

            $pdo->prepare("UPDATE appointments SET status = ? WHERE id = ?")->execute([$status, $id]);
            
            if ($status === 'done') {
                $apt = $pdo->query("SELECT customer_id, title FROM appointments WHERE id = $id")->fetch();
                if ($apt) {
                    $pdo->prepare("INSERT INTO activities (customer_id, user_id, type, content) VALUES (?, ?, 'meeting', ?)")
                        ->execute([$apt['customer_id'], Auth::id(), "Đã khám xong: {$apt['title']}"]);
                }
            }

            echo json_encode(['ok' => true]);
        } catch (Throwable $e) {
            echo json_encode(['ok' => false, 'error' => $e->getMessage()]);
        }
    }

    // ══════════════════════════════════════════
    // DELETE
    // ══════════════════════════════════════════
    public function delete() {
        while (ob_get_level() > 0) ob_end_clean();
        ini_set('display_errors', 0);
        error_reporting(0);
        
        Auth::requireLogin();
        global $pdo;
        header('Content-Type: application/json; charset=utf-8');
        
        try {
            $id = (int)($_POST['id'] ?? 0);
            $pdo->prepare("DELETE FROM appointments WHERE id = ?")->execute([$id]);
            echo json_encode(['ok' => true]);
        } catch (Throwable $e) {
            echo json_encode(['ok' => false, 'error' => $e->getMessage()]);
        }
    }

    // ══════════════════════════════════════════
    // CHECK CONFLICT
    // ══════════════════════════════════════════
    private function checkConflict($scheduledAt, $doctor = null, $excludeId = null) {
        global $pdo;
        
        try {
            $ts = strtotime($scheduledAt);
            if (!$ts) return [];
            
            $from = date('Y-m-d H:i:s', $ts - 1800);
            $to   = date('Y-m-d H:i:s', $ts + 1800);
            
            $sql = "SELECT a.id, a.scheduled_at, a.title, c.full_name customer_name
                    FROM appointments a
                    JOIN customers c ON a.customer_id = c.id
                    WHERE a.scheduled_at BETWEEN ? AND ?
                      AND a.status NOT IN ('cancelled','no_show')";
            $params = [$from, $to];
            
            if ($doctor) {
                $sql .= " AND a.doctor = ?";
                $params[] = $doctor;
            }
            if ($excludeId) {
                $sql .= " AND a.id != ?";
                $params[] = $excludeId;
            }
            
            $st = $pdo->prepare($sql);
            $st->execute($params);
            return $st->fetchAll();
        } catch (Throwable $e) {
            return [];
        }
    }

    // ══════════════════════════════════════════
    // API: Lấy lịch theo ngày
    // ══════════════════════════════════════════
    public function byDate() {
        while (ob_get_level() > 0) ob_end_clean();
        ini_set('display_errors', 0);
        error_reporting(0);
        
        Auth::requireLogin();
        global $pdo;
        header('Content-Type: application/json; charset=utf-8');
        
        try {
            $date = $_GET['date'] ?? date('Y-m-d');
            $st = $pdo->prepare("
                SELECT a.*, c.full_name customer_name, c.phone customer_phone, c.interest,
                       u.full_name user_name
                FROM appointments a
                JOIN customers c ON a.customer_id = c.id
                LEFT JOIN users u ON a.user_id = u.id
                WHERE DATE(a.scheduled_at) = ?
                ORDER BY a.scheduled_at ASC
            ");
            $st->execute([$date]);
            echo json_encode($st->fetchAll());
        } catch (Throwable $e) {
            echo json_encode([]);
        }
    }

    // ══════════════════════════════════════════
    // API: Lấy 1 lịch hẹn
    // ══════════════════════════════════════════
    public function get() {
        while (ob_get_level() > 0) ob_end_clean();
        ini_set('display_errors', 0);
        error_reporting(0);
        
        Auth::requireLogin();
        global $pdo;
        header('Content-Type: application/json; charset=utf-8');
        
        try {
            $id = (int)($_GET['id'] ?? 0);
            $st = $pdo->prepare("
                SELECT a.*, c.full_name customer_name, c.phone customer_phone
                FROM appointments a
                JOIN customers c ON a.customer_id = c.id
                WHERE a.id = ?
            ");
            $st->execute([$id]);
            echo json_encode($st->fetch() ?: null);
        } catch (Throwable $e) {
            echo json_encode(null);
        }
    }

    // ══════════════════════════════════════════
    // HELPER: Kiểm tra cột tồn tại
    // ══════════════════════════════════════════
    private function columnExists($table, $column) {
        global $pdo;
        static $cache = [];
        $key = "$table.$column";
        if (isset($cache[$key])) return $cache[$key];
        
        try {
            $st = $pdo->prepare("SHOW COLUMNS FROM `$table` LIKE ?");
            $st->execute([$column]);
            $cache[$key] = (bool)$st->fetch();
        } catch (Throwable $e) {
            $cache[$key] = false;
        }
        return $cache[$key];
    }
}