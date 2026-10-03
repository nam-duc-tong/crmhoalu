<?php
class DashboardController {
    public function index() {
        Auth::requireLogin();
        global $pdo;
        $uid = Auth::id();
        $isManager = Auth::isManager();
        $where = $isManager ? "" : "WHERE assigned_to = " . (int)$uid;
        $and   = $isManager ? "WHERE" : "AND";

        $stats = [
            'total'          => $pdo->query("SELECT COUNT(*) FROM customers $where")->fetchColumn(),
            'new_today'      => $pdo->query("SELECT COUNT(*) FROM customers $where $and DATE(created_at)=CURDATE()")->fetchColumn(),
            'appointment'    => $pdo->query("SELECT COUNT(*) FROM customers $where $and status='appointment'")->fetchColumn(),
            'surgery'        => $pdo->query("SELECT COUNT(*) FROM customers $where $and status='surgery'")->fetchColumn(),
            'revenue'        => $pdo->query("SELECT COALESCE(SUM(estimated_value),0) FROM customers $where $and status IN ('surgery','done')")->fetchColumn(),
            'followup_today' => $pdo->query("SELECT COUNT(*) FROM customers $where $and DATE(next_followup_at)=CURDATE()")->fetchColumn(),
        ];

        $pipeline = $pdo->query("SELECT status, COUNT(*) c FROM customers $where GROUP BY status")->fetchAll();
        $pipelineMap = array_column($pipeline, 'c', 'status');

        $topSales = [];
        if ($isManager) {
            $topSales = $pdo->query("
                SELECT u.id, u.full_name, 
                       COUNT(c.id) total,
                       SUM(CASE WHEN c.status IN ('surgery','done') THEN 1 ELSE 0 END) won,
                       COALESCE(SUM(CASE WHEN c.status IN ('surgery','done') THEN c.estimated_value ELSE 0 END),0) revenue
                FROM users u 
                LEFT JOIN customers c ON c.assigned_to = u.id
                WHERE u.role IN ('sales','manager')
                GROUP BY u.id 
                ORDER BY revenue DESC 
                LIMIT 10
            ")->fetchAll();
        }

        $followups = $pdo->query("
            SELECT c.*, u.full_name assigned_name 
            FROM customers c
            LEFT JOIN users u ON c.assigned_to = u.id
            $where $and DATE(c.next_followup_at) <= CURDATE() 
            AND c.status NOT IN ('done','lost')
            ORDER BY c.next_followup_at ASC 
            LIMIT 10
        ")->fetchAll();

        $recent = $pdo->query("
            SELECT a.*, c.full_name customer_name, c.id customer_id, u.full_name user_name
            FROM activities a
            JOIN customers c ON a.customer_id = c.id
            JOIN users u ON a.user_id = u.id
            " . ($isManager ? "" : "WHERE a.user_id = " . (int)$uid) . "
            ORDER BY a.created_at DESC 
            LIMIT 8
        ")->fetchAll();

        View::render('dashboard/index', [
            'pageTitle'   => 'Dashboard',
            'activePage'  => 'dashboard',
            'stats'       => $stats,
            'pipelineMap' => $pipelineMap,
            'topSales'    => $topSales,
            'followups'   => $followups,
            'recent'      => $recent,
        ]);
    }
}