<?php
class SearchController {
    public function api() {
        Auth::requireLogin();
        global $pdo;
        header('Content-Type: application/json');
        $q = trim($_GET['q'] ?? '');
        if (strlen($q) < 2) { echo json_encode([]); return; }
        
        $st = $pdo->prepare("SELECT id, code, full_name, phone, status 
                             FROM customers 
                             WHERE full_name LIKE ? OR phone LIKE ? OR code LIKE ?
                             LIMIT 10");
        $st->execute(["%$q%", "%$q%", "%$q%"]);
        echo json_encode($st->fetchAll());
    }
}