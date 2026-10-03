<?php
class PipelineController {
    public function index() {
        Auth::requireLogin();
        global $pdo;
        $uid = Auth::id();
        $isManager = Auth::isManager();

        $sql = "SELECT c.*, u.full_name assigned_name FROM customers c
                LEFT JOIN users u ON c.assigned_to = u.id
                WHERE c.status NOT IN ('done','lost')";
        $params = [];
        if (!$isManager) { $sql .= " AND c.assigned_to = ?"; $params[] = $uid; }
        
        $st = $pdo->prepare($sql);
        $st->execute($params);
        $all = $st->fetchAll();

        $stages = ['new','contacted','appointment','consulted','surgery'];
        $pipeline = [];
        foreach ($stages as $s) $pipeline[$s] = [];
        foreach ($all as $c) $pipeline[$c['status']][] = $c;

        View::render('pipeline/index', [
            'pageTitle'  => 'Pipeline',
            'activePage' => 'pipeline',
            'pipeline'   => $pipeline,
            'stages'     => $stages,
        ]);
    }

    public function move() {
        Auth::requireLogin();
        global $pdo;
        header('Content-Type: application/json');
        $id = (int)($_POST['id'] ?? 0);
        $status = $_POST['status'] ?? '';
        if (!$id || !$status) { echo json_encode(['ok' => false]); return; }
        $pdo->prepare("UPDATE customers SET status = ? WHERE id = ?")->execute([$status, $id]);
        $pdo->prepare("INSERT INTO activities (customer_id, user_id, type, content) VALUES (?, ?, 'status_change', ?)")
            ->execute([$id, Auth::id(), "Chuyển sang: " . Helper::statusLabel($status)]);
        echo json_encode(['ok' => true]);
    }
}