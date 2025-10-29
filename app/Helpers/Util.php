<?php
class Util {
    
    public static function sanitize($input) {
        if (is_array($input)) {
            return array_map([self::class, 'sanitize'], $input);
        }
        return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
    }
    
    public static function formatDate($date, $format = DATE_FORMAT) {
        if (!$date) return '';
        return date($format, strtotime($date));
    }
    
    public static function formatDateTime($datetime, $format = DATETIME_FORMAT) {
        if (!$datetime) return '';
        return date($format, strtotime($datetime));
    }
    
    public static function formatTime($time, $format = TIME_FORMAT) {
        if (!$time) return '';
        return date($format, strtotime($time));
    }
    
    public static function calculateAge($birthDate) {
        if (!$birthDate) return 0;
        $today = new DateTime();
        $birth = new DateTime($birthDate);
        return $today->diff($birth)->y;
    }
    
    public static function generateCode($prefix, $length = 6) {
        $number = str_pad(rand(1, pow(10, $length) - 1), $length, '0', STR_PAD_LEFT);
        return $prefix . $number;
    }
    
    public static function slugify($text) {
        $text = strtolower($text);
        $text = preg_replace('/[^a-z0-9\s-]/', '', $text);
        $text = preg_replace('/[\s-]+/', '-', $text);
        return trim($text, '-');
    }
    
    public static function truncate($text, $length = 100, $suffix = '...') {
        if (strlen($text) <= $length) {
            return $text;
        }
        return substr($text, 0, $length) . $suffix;
    }
    
    public static function formatMoney($amount, $currency = 'Q') {
        return $currency . ' ' . number_format($amount, 2, '.', ',');
    }
    
    public static function validateEmail($email) {
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }
    
    public static function validatePhone($phone) {
        $phone = preg_replace('/[^0-9]/', '', $phone);
        return strlen($phone) >= 8 && strlen($phone) <= 15;
    }
    
    public static function validateDPI($dpi) {
        $dpi = preg_replace('/[^0-9]/', '', $dpi);
        return strlen($dpi) === 13;
    }
    
    public static function redirect($url) {
        Router::redirect($url);
    }
    
    public static function asset($path) {
        return ASSETS_URL . '/' . ltrim($path, '/');
    }
    
    public static function url($path = '') {
        return Router::url($path);
    }
    
    public static function isPost() {
        return $_SERVER['REQUEST_METHOD'] === 'POST';
    }
    
    public static function isGet() {
        return $_SERVER['REQUEST_METHOD'] === 'GET';
    }
    
    public static function getPost($key = null, $default = null) {
        if ($key === null) {
            return $_POST;
        }
        return isset($_POST[$key]) ? self::sanitize($_POST[$key]) : $default;
    }
    
    public static function getGet($key = null, $default = null) {
        if ($key === null) {
            return $_GET;
        }
        return isset($_GET[$key]) ? self::sanitize($_GET[$key]) : $default;
    }
    
    public static function dump($data) {
        echo '<pre>';
        var_dump($data);
        echo '</pre>';
    }
    
    public static function dd($data) {
        self::dump($data);
        die();
    }
}
?>