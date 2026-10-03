<?php
class SettingController {

    // ═════════════════════════════════════════
    // TRANG CÀI ĐẶT CHÍNH
    // ═════════════════════════════════════════
    public function index() {
        Auth::requireRole(['admin', 'manager']);
        global $pdo;

        $sources = $pdo->query("SELECT * FROM sources ORDER BY is_active DESC, name")->fetchAll();
        $diseases = $pdo->query("SELECT * FROM diseases ORDER BY sort_order, name")->fetchAll();
        $users = $pdo->query("SELECT * FROM users ORDER BY full_name")->fetchAll();

        // Đếm số KH theo từng nguồn
        $sourceStats = $pdo->query("
            SELECT source_id, COUNT(*) total, 
                   COALESCE(SUM(estimated_value),0) revenue
            FROM customers 
            GROUP BY source_id
        ")->fetchAll();
        $sourceStatsMap = [];
        foreach ($sourceStats as $s) $sourceStatsMap[$s['source_id']] = $s;

        $stats = [
            'customers'    => $pdo->query("SELECT COUNT(*) FROM customers")->fetchColumn(),
            'activities'   => $pdo->query("SELECT COUNT(*) FROM activities")->fetchColumn(),
            'appointments' => $pdo->query("SELECT COUNT(*) FROM appointments")->fetchColumn(),
            'tickets'      => $pdo->query("SELECT COUNT(*) FROM tickets")->fetchColumn(),
        ];

        View::render('settings/index', [
            'pageTitle'       => 'Cài đặt hệ thống',
            'activePage'      => 'setting',
            'sources'         => $sources,
            'diseases'        => $diseases,
            'users'           => $users,
            'sourceStatsMap'  => $sourceStatsMap,
            'stats'           => $stats,
        ]);
    }

    // ═════════════════════════════════════════
    // SOURCES CRUD
    // ═════════════════════════════════════════
    public function sourceStore() {
        Auth::requireRole(['admin', 'manager']);
        global $pdo;
        $d = $_POST;

        $st = $pdo->prepare("INSERT INTO sources (name, icon, color, type, description, is_active) VALUES (?,?,?,?,?,?)");
        $st->execute([
            $d['name'],
            $d['icon'] ?? 'globe',
            $d['color'] ?? '#06b6d4',
            $d['type'] ?? 'other',
            $d['description'] ?? null,
            isset($d['is_active']) ? 1 : 0,
        ]);

        Helper::flash('success', 'Đã thêm nguồn khách mới!');
        Helper::redirect(BASE_URL . '/index.php?c=setting#sources');
    }

    public function sourceUpdate() {
        Auth::requireRole(['admin', 'manager']);
        global $pdo;
        $d = $_POST;
        $id = (int)$d['id'];

        $st = $pdo->prepare("UPDATE sources SET name=?, icon=?, color=?, type=?, description=?, is_active=? WHERE id=?");
        $st->execute([
            $d['name'],
            $d['icon'] ?? 'globe',
            $d['color'] ?? '#06b6d4',
            $d['type'] ?? 'other',
            $d['description'] ?? null,
            isset($d['is_active']) ? 1 : 0,
            $id,
        ]);

        Helper::flash('success', 'Đã cập nhật nguồn khách!');
        Helper::redirect(BASE_URL . '/index.php?c=setting#sources');
    }

    public function sourceDelete() {
        Auth::requireRole(['admin']);
        global $pdo;
        $id = (int)($_GET['id'] ?? 0);
        if ($id) {
            $pdo->prepare("DELETE FROM sources WHERE id = ?")->execute([$id]);
            Helper::flash('success', 'Đã xóa nguồn khách');
        }
        Helper::redirect(BASE_URL . '/index.php?c=setting#sources');
    }

    public function sourceToggle() {
        Auth::requireRole(['admin', 'manager']);
        global $pdo;
        header('Content-Type: application/json');
        $id = (int)$_POST['id'];
        $pdo->prepare("UPDATE sources SET is_active = 1 - is_active WHERE id = ?")->execute([$id]);
        $st = $pdo->prepare("SELECT is_active FROM sources WHERE id = ?");
        $st->execute([$id]);
        echo json_encode(['ok' => true, 'active' => (int)$st->fetchColumn()]);
    }

    // ═════════════════════════════════════════
    // DISEASES CRUD
    // ═════════════════════════════════════════
    public function diseaseStore() {
        Auth::requireRole(['admin', 'manager']);
        global $pdo;
        $d = $_POST;

        $code = 'BENH' . str_pad(rand(1, 99999), 5, '0', STR_PAD_LEFT);
        // Kiểm tra trùng
        do {
            $check = $pdo->prepare("SELECT id FROM diseases WHERE code = ?");
            $check->execute([$code]);
            if (!$check->fetch()) break;
            $code = 'BENH' . str_pad(rand(1, 99999), 5, '0', STR_PAD_LEFT);
        } while (true);

        $st = $pdo->prepare("INSERT INTO diseases 
            (code, name, category, icon, color, description, price_from, price_to, sort_order, is_active)
            VALUES (?,?,?,?,?,?,?,?,?,?)");
        $st->execute([
            $code,
            $d['name'],
            $d['category'] ?? 'other',
            $d['icon'] ?? 'eye',
            $d['color'] ?? '#06b6d4',
            $d['description'] ?? null,
            (float)($d['price_from'] ?? 0),
            (float)($d['price_to'] ?? 0),
            (int)($d['sort_order'] ?? 0),
            isset($d['is_active']) ? 1 : 0,
        ]);

        Helper::flash('success', "Đã thêm bệnh lý: {$d['name']}");
        Helper::redirect(BASE_URL . '/index.php?c=setting#diseases');
    }

    public function diseaseUpdate() {
        Auth::requireRole(['admin', 'manager']);
        global $pdo;
        $d = $_POST;
        $id = (int)$d['id'];

        $st = $pdo->prepare("UPDATE diseases SET 
            name=?, category=?, icon=?, color=?, description=?, price_from=?, price_to=?, sort_order=?, is_active=?
            WHERE id=?");
        $st->execute([
            $d['name'],
            $d['category'] ?? 'other',
            $d['icon'] ?? 'eye',
            $d['color'] ?? '#06b6d4',
            $d['description'] ?? null,
            (float)($d['price_from'] ?? 0),
            (float)($d['price_to'] ?? 0),
            (int)($d['sort_order'] ?? 0),
            isset($d['is_active']) ? 1 : 0,
            $id,
        ]);

        Helper::flash('success', 'Đã cập nhật bệnh lý!');
        Helper::redirect(BASE_URL . '/index.php?c=setting#diseases');
    }

    public function diseaseDelete() {
        Auth::requireRole(['admin']);
        global $pdo;
        $id = (int)($_GET['id'] ?? 0);
        if ($id) {
            $pdo->prepare("DELETE FROM diseases WHERE id = ?")->execute([$id]);
            Helper::flash('success', 'Đã xóa bệnh lý');
        }
        Helper::redirect(BASE_URL . '/index.php?c=setting#diseases');
    }

    public function diseaseToggle() {
        Auth::requireRole(['admin', 'manager']);
        global $pdo;
        header('Content-Type: application/json');
        $id = (int)$_POST['id'];
        $pdo->prepare("UPDATE diseases SET is_active = 1 - is_active WHERE id = ?")->execute([$id]);
        $st = $pdo->prepare("SELECT is_active FROM diseases WHERE id = ?");
        $st->execute([$id]);
        echo json_encode(['ok' => true, 'active' => (int)$st->fetchColumn()]);
    }
}