<?php
class View {
    public static function render($path, $data = []) {
        global $pdo;

        // Auto-inject
        $data['user']      = $data['user']      ?? Auth::user();
        $data['flash']     = $data['flash']     ?? Helper::getFlash();
        $data['appName']   = APP_NAME;
        $data['baseUrl']   = BASE_URL;
        $data['pageTitle'] = $data['pageTitle'] ?? 'Dashboard';
        $data['activePage']= $data['activePage']?? 'dashboard';

        // Global data cache (chỉ query 1 lần / request)
        static $sources = null, $users = null;
        if ($sources === null) {
            $sources = $pdo->query("SELECT * FROM sources WHERE is_active = 1 ORDER BY name")->fetchAll();
        }
        if ($users === null) {
            $users = $pdo->query("SELECT id, full_name, role FROM users 
                                  WHERE role IN ('sales','manager','admin') AND is_active = 1 
                                  ORDER BY full_name")->fetchAll();
        }
        $data['sources'] = $data['sources'] ?? $sources;
        $data['users']   = $data['users']   ?? $users;

        extract($data, EXTR_SKIP);
        require ROOT_PATH . '/app/views/' . $path . '.php';
    }
}