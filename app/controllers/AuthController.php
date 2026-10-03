<?php
class AuthController {
    public function login() {
        if (Auth::check()) {
            Helper::redirect(BASE_URL . '/index.php?c=dashboard');
        }
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            global $pdo;
            $email = trim($_POST['email'] ?? '');
            $pass  = $_POST['password'] ?? '';

            if (empty($email) || empty($pass)) {
                Helper::flash('danger', 'Vui lòng nhập đầy đủ email và mật khẩu');
            } elseif (Auth::login($email, $pass, $pdo)) {
                Helper::flash('success', 'Đăng nhập thành công!');
                Helper::redirect(BASE_URL . '/index.php?c=dashboard');
            } else {
                Helper::flash('danger', 'Email hoặc mật khẩu không đúng!');
            }
        }
        require ROOT_PATH . '/app/views/auth/login.php';
    }

    public function logout() {
        Auth::logout();
        Helper::redirect(BASE_URL . '/index.php?c=auth&a=login');
    }
}