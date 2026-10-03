<?php
class ReportController {
    public function index() {
        Auth::requireRole(['admin', 'manager']);
        global $pdo;

        $from = $_GET['from'] ?? date('Y-m-01');
        $to   = $_GET['to']   ?? date('Y-m-d');

        $bySource = $pdo->prepare("
            SELECT s.name, COUNT(c.id) total,
                SUM(CASE WHEN c.status IN ('surgery','done') THEN 1 ELSE 0 END) won,
                COALESCE(SUM(CASE WHEN c.status IN ('surgery','done') THEN c.estimated_value ELSE 0 END),0) revenue
            FROM sources s 
            LEFT JOIN customers c ON c.source_id = s.id AND DATE(c.created_at) BETWEEN ? AND ?
            GROUP BY s.id 
            ORDER BY revenue DESC
        ");
        $bySource->execute([$from, $to]);

        $bySale = $pdo->prepare("
            SELECT u.full_name, u.kpi_target,
                COUNT(c.id) total,
                SUM(CASE WHEN c.status IN ('surgery','done') THEN 1 ELSE 0 END) won,
                COALESCE(SUM(CASE WHEN c.status IN ('surgery','done') THEN c.estimated_value ELSE 0 END),0) revenue
            FROM users u 
            LEFT JOIN customers c ON c.assigned_to = u.id AND DATE(c.created_at) BETWEEN ? AND ?
            WHERE u.role IN ('sales','manager')
            GROUP BY u.id 
            ORDER BY revenue DESC
        ");
        $bySale->execute([$from, $to]);

        View::render('reports/index', [
            'pageTitle'  => 'Báo cáo',
            'activePage' => 'report',
            'from'       => $from,
            'to'         => $to,
            'bySource'   => $bySource->fetchAll(),
            'bySale'     => $bySale->fetchAll(),
        ]);
    }
}