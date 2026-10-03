<?php
class TicketController {
    public function index() {
        Auth::requireLogin();
        global $pdo;
        
        $tickets = $pdo->query("
            SELECT t.*, c.full_name customer_name, c.phone customer_phone, u.full_name user_name
            FROM tickets t
            JOIN customers c ON t.customer_id = c.id
            LEFT JOIN users u ON t.assigned_to = u.id
            ORDER BY 
                CASE t.priority 
                    WHEN 'urgent' THEN 1 
                    WHEN 'high' THEN 2 
                    WHEN 'normal' THEN 3 
                    ELSE 4 
                END,
                t.created_at DESC
            LIMIT 100
        ")->fetchAll();

        View::render('tickets/index', [
            'pageTitle' => 'Vé CSKH',
            'activePage' => 'ticket',
            'tickets' => $tickets,
        ]);
    }

    public function create() {
        Auth::requireLogin();
        global $pdo;
        $customers = $pdo->query("SELECT id, full_name, phone FROM customers ORDER BY full_name")->fetchAll();
        View::render('tickets/create', [
            'pageTitle' => 'Tạo vé CSKH',
            'activePage' => 'ticket',
            'customers' => $customers,
        ]);
    }

    public function store() {
        Auth::requireLogin();
        global $pdo;
        $d = $_POST;
        $code = 'TK' . date('ymd') . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);

        $st = $pdo->prepare("INSERT INTO tickets (code, customer_id, assigned_to, subject, content, priority, status)
                             VALUES (?,?,?,?,?,?,?)");
        $st->execute([
            $code,
            (int)$d['customer_id'],
            !empty($d['assigned_to']) ? (int)$d['assigned_to'] : Auth::id(),
            $d['subject'],
            $d['content'] ?? null,
            $d['priority'] ?? 'normal',
            'open'
        ]);

        Helper::flash('success', "Đã tạo vé $code");
        Helper::redirect(BASE_URL . '/index.php?c=ticket');
    }

    public function updateStatus() {
        Auth::requireLogin();
        global $pdo;
        header('Content-Type: application/json');
        $id = (int)$_POST['id'];
        $status = $_POST['status'];
        $resolvedAt = $status === 'resolved' ? date('Y-m-d H:i:s') : null;
        $pdo->prepare("UPDATE tickets SET status = ?, resolved_at = COALESCE(?, resolved_at) WHERE id = ?")
            ->execute([$status, $resolvedAt, $id]);
        echo json_encode(['ok' => true]);
    }
}