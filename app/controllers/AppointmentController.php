<?php
class AppointmentController {
    public function index() {
        Auth::requireLogin();
        global $pdo;
        $isManager = Auth::isManager();
        $uid = Auth::id();

        $where = $isManager ? "" : "WHERE a.user_id = " . (int)$uid;
        
        $apts = $pdo->query("
            SELECT a.*, c.full_name customer_name, c.phone customer_phone, u.full_name user_name
            FROM appointments a
            JOIN customers c ON a.customer_id = c.id
            LEFT JOIN users u ON a.user_id = u.id
            $where
            ORDER BY a.scheduled_at DESC
            LIMIT 50
        ")->fetchAll();

        $upcoming = $pdo->query("
            SELECT a.*, c.full_name customer_name, c.phone customer_phone
            FROM appointments a
            JOIN customers c ON a.customer_id = c.id
            WHERE a.scheduled_at >= NOW() AND a.status IN ('pending','confirmed')
            ORDER BY a.scheduled_at ASC LIMIT 5
        ")->fetchAll();

        $customers = $pdo->query("SELECT id, full_name, phone FROM customers ORDER BY full_name LIMIT 200")->fetchAll();

        View::render('appointments/index', [
            'pageTitle' => 'Lịch hẹn',
            'activePage' => 'appointment',
            'apts' => $apts,
            'upcoming' => $upcoming,
            'customers' => $customers,
        ]);
    }

    public function create() {
        Auth::requireLogin();
        global $pdo;
        $customers = $pdo->query("SELECT id, full_name, phone FROM customers ORDER BY full_name")->fetchAll();
        View::render('appointments/create', [
            'pageTitle' => 'Đặt lịch hẹn',
            'activePage' => 'appointment',
            'customers' => $customers,
        ]);
    }

    public function store() {
        Auth::requireLogin();
        global $pdo;
        $d = $_POST;

        $st = $pdo->prepare("INSERT INTO appointments (customer_id, user_id, title, scheduled_at, doctor, status, note)
                             VALUES (?,?,?,?,?,?,?)");
        $st->execute([
            (int)$d['customer_id'],
            Auth::id(),
            $d['title'] ?? 'Khám bệnh',
            $d['scheduled_at'],
            $d['doctor'] ?? null,
            $d['status'] ?? 'pending',
            $d['note'] ?? null
        ]);

        Helper::flash('success', 'Đã đặt lịch hẹn thành công!');
        Helper::redirect(BASE_URL . '/index.php?c=appointment');
    }

    public function updateStatus() {
        Auth::requireLogin();
        global $pdo;
        header('Content-Type: application/json');
        $id = (int)$_POST['id'];
        $status = $_POST['status'];
        $pdo->prepare("UPDATE appointments SET status = ? WHERE id = ?")->execute([$status, $id]);
        echo json_encode(['ok' => true]);
    }
}