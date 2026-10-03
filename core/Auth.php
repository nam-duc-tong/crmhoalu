<?php
class Auth {
    public static function check() { return !empty($_SESSION['user']); }
    public static function user()  { return $_SESSION['user'] ?? null; }
    public static function id()    { return $_SESSION['user']['id'] ?? 0; }
    public static function role()  { return $_SESSION['user']['role'] ?? null; }
    public static function isAdmin()   { return self::role() === 'admin'; }
    public static function isManager() { return in_array(self::role(), ['admin', 'manager']); }

    public static function requireLogin() {
        if (!self::check()) {
            Helper::redirect(BASE_URL . '/index.php?c=auth&a=login');
        }
    }

    public static function requireRole($roles) {
        self::requireLogin();
        if (!in_array(self::role(), (array)$roles)) {
            http_response_code(403);
            die('Bạn không có quyền truy cập trang này.');
        }
    }

    public static function login($email, $password, $pdo) {
        $st = $pdo->prepare("SELECT * FROM users WHERE email = ? AND is_active = 1");
        $st->execute([$email]);
        $u = $st->fetch();
        if ($u && password_verify($password, $u['password'])) {
            unset($u['password']);
            $_SESSION['user'] = $u;
            $pdo->prepare("UPDATE users SET last_login = NOW() WHERE id = ?")->execute([$u['id']]);
            return true;
        }
        return false;
    }

    public static function logout() {
        session_destroy();
    }
}