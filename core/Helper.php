<?php
class Helper {
    public static function e($s) {
        return htmlspecialchars($s ?? '', ENT_QUOTES, 'UTF-8');
    }

    public static function redirect($url) {
        header("Location: $url");
        exit;
    }

    public static function flash($type, $msg) {
        $_SESSION['flash'] = ['type' => $type, 'msg' => $msg];
    }

    public static function getFlash() {
        if (!empty($_SESSION['flash'])) {
            $f = $_SESSION['flash'];
            unset($_SESSION['flash']);
            return $f;
        }
        return null;
    }

    public static function money($n) {
        if ($n >= 1000000000) return number_format($n / 1000000000, 1, ',', '.') . ' tỷ';
        if ($n >= 1000000)    return number_format($n / 1000000, 1, ',', '.') . ' tr';
        return number_format($n, 0, ',', '.') . 'đ';
    }

    public static function moneyFull($n) {
        return number_format($n, 0, ',', '.') . 'đ';
    }

    public static function timeAgo($dt) {
        if (!$dt) return '—';
        $diff = time() - strtotime($dt);
        if ($diff < 60)     return 'vừa xong';
        if ($diff < 3600)   return floor($diff / 60) . ' phút trước';
        if ($diff < 86400)  return floor($diff / 3600) . ' giờ trước';
        if ($diff < 604800) return floor($diff / 86400) . ' ngày trước';
        return date('d/m/Y', strtotime($dt));
    }

    public static function statusLabel($s) {
        return [
            'new'         => 'Mới',
            'contacted'   => 'Đã liên hệ',
            'appointment' => 'Đã hẹn khám',
            'consulted'   => 'Đã tư vấn',
            'surgery'     => 'Đã phẫu thuật',
            'done'        => 'Hoàn tất',
            'lost'        => 'Mất KH',
        ][$s] ?? $s;
    }
}