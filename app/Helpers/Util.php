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
    
    public static function formatCurrency($amount, $currency = 'Q') {
        return self::formatMoney($amount, $currency);
    }
    
    public static function formatPhone($phone) {
        if (!$phone) return '';
        
        // Remover caracteres no numéricos
        $cleaned = preg_replace('/[^0-9]/', '', $phone);
        
        // Formatear según la longitud
        $len = strlen($cleaned);
        
        if ($len == 8) {
            // Formato: 1234-5678
            return substr($cleaned, 0, 4) . '-' . substr($cleaned, 4);
        } elseif ($len == 10) {
            // Formato: (123) 456-7890
            return '(' . substr($cleaned, 0, 3) . ') ' . substr($cleaned, 3, 3) . '-' . substr($cleaned, 6);
        } elseif ($len == 11) {
            // Formato: 1 (234) 567-8901
            return substr($cleaned, 0, 1) . ' (' . substr($cleaned, 1, 3) . ') ' . substr($cleaned, 4, 3) . '-' . substr($cleaned, 7);
        }
        
        // Si no coincide con ningún formato, devolver el original
        return $phone;
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
    
    // Funciones adicionales útiles
    
    public static function timeAgo($datetime) {
        if (!$datetime) return '';
        
        $timestamp = strtotime($datetime);
        $difference = time() - $timestamp;
        
        if ($difference < 60) {
            return 'Hace ' . $difference . ' segundos';
        } elseif ($difference < 3600) {
            $minutes = floor($difference / 60);
            return 'Hace ' . $minutes . ' minuto' . ($minutes > 1 ? 's' : '');
        } elseif ($difference < 86400) {
            $hours = floor($difference / 3600);
            return 'Hace ' . $hours . ' hora' . ($hours > 1 ? 's' : '');
        } elseif ($difference < 604800) {
            $days = floor($difference / 86400);
            return 'Hace ' . $days . ' día' . ($days > 1 ? 's' : '');
        } else {
            return self::formatDate($datetime);
        }
    }
    
    public static function getInitials($name, $lastName = '') {
        $initials = '';
        if ($name) {
            $initials .= strtoupper(substr($name, 0, 1));
        }
        if ($lastName) {
            $initials .= strtoupper(substr($lastName, 0, 1));
        }
        return $initials;
    }
    
    public static function generatePassword($length = 10) {
        $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*';
        $password = '';
        $max = strlen($chars) - 1;
        
        for ($i = 0; $i < $length; $i++) {
            $password .= $chars[random_int(0, $max)];
        }
        
        return $password;
    }
    
    public static function uploadFile($file, $destinationPath, $allowedExtensions = [], $maxSize = 5242880) {
        // Validar que se haya subido el archivo
        if (!isset($file['tmp_name']) || !is_uploaded_file($file['tmp_name'])) {
            return ['success' => false, 'message' => 'No se seleccionó ningún archivo'];
        }
        
        // Validar tamaño
        if ($file['size'] > $maxSize) {
            $maxSizeMB = $maxSize / 1048576;
            return ['success' => false, 'message' => "El archivo excede el tamaño máximo de {$maxSizeMB}MB"];
        }
        
        // Obtener extensión
        $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        
        // Validar extensión si se especificaron extensiones permitidas
        if (!empty($allowedExtensions) && !in_array($extension, $allowedExtensions)) {
            return ['success' => false, 'message' => 'Tipo de archivo no permitido'];
        }
        
        // Generar nombre único
        $fileName = uniqid() . '_' . time() . '.' . $extension;
        $fullPath = $destinationPath . '/' . $fileName;
        
        // Crear directorio si no existe
        if (!is_dir($destinationPath)) {
            mkdir($destinationPath, 0755, true);
        }
        
        // Mover archivo
        if (move_uploaded_file($file['tmp_name'], $fullPath)) {
            return ['success' => true, 'fileName' => $fileName, 'fullPath' => $fullPath];
        }
        
        return ['success' => false, 'message' => 'Error al subir el archivo'];
    }
    
    public static function deleteFile($filePath) {
        if (file_exists($filePath)) {
            return unlink($filePath);
        }
        return false;
    }
    
    public static function resizeImage($sourcePath, $destinationPath, $maxWidth, $maxHeight) {
        list($origWidth, $origHeight, $type) = getimagesize($sourcePath);
        
        // Calcular nuevas dimensiones manteniendo proporción
        $ratio = min($maxWidth / $origWidth, $maxHeight / $origHeight);
        $newWidth = round($origWidth * $ratio);
        $newHeight = round($origHeight * $ratio);
        
        // Crear imagen según tipo
        switch ($type) {
            case IMAGETYPE_JPEG:
                $source = imagecreatefromjpeg($sourcePath);
                break;
            case IMAGETYPE_PNG:
                $source = imagecreatefrompng($sourcePath);
                break;
            case IMAGETYPE_GIF:
                $source = imagecreatefromgif($sourcePath);
                break;
            default:
                return false;
        }
        
        // Crear nueva imagen redimensionada
        $newImage = imagecreatetruecolor($newWidth, $newHeight);
        
        // Preservar transparencia para PNG y GIF
        if ($type == IMAGETYPE_PNG || $type == IMAGETYPE_GIF) {
            imagealphablending($newImage, false);
            imagesavealpha($newImage, true);
        }
        
        // Redimensionar
        imagecopyresampled($newImage, $source, 0, 0, 0, 0, $newWidth, $newHeight, $origWidth, $origHeight);
        
        // Guardar según tipo
        switch ($type) {
            case IMAGETYPE_JPEG:
                imagejpeg($newImage, $destinationPath, 90);
                break;
            case IMAGETYPE_PNG:
                imagepng($newImage, $destinationPath, 9);
                break;
            case IMAGETYPE_GIF:
                imagegif($newImage, $destinationPath);
                break;
        }
        
        // Liberar memoria
        imagedestroy($source);
        imagedestroy($newImage);
        
        return true;
    }
}
?>