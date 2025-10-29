<?php
class Flash {
    
    public static function success($message) {
        Session::flash('success', $message);
    }
    
    public static function error($message) {
        Session::flash('error', $message);
    }
    
    public static function warning($message) {
        Session::flash('warning', $message);
    }
    
    public static function info($message) {
        Session::flash('info', $message);
    }
    
    public static function getSuccess() {
        return Session::getFlash('success');
    }
    
    public static function getError() {
        return Session::getFlash('error');
    }
    
    public static function getWarning() {
        return Session::getFlash('warning');
    }
    
    public static function getInfo() {
        return Session::getFlash('info');
    }
    
    public static function hasSuccess() {
        return Session::hasFlash('success');
    }
    
    public static function hasError() {
        return Session::hasFlash('error');
    }
    
    public static function hasWarning() {
        return Session::hasFlash('warning');
    }
    
    public static function hasInfo() {
        return Session::hasFlash('info');
    }
    
    public static function hasAny() {
        return self::hasSuccess() || self::hasError() || self::hasWarning() || self::hasInfo();
    }
    
    public static function display() {
        $html = '';
        
        if (self::hasSuccess()) {
            $message = self::getSuccess();
            $html .= '<div class="alert alert-success alert-dismissible fade show" role="alert">';
            $html .= '<i class="bi bi-check-circle-fill me-2"></i>' . htmlspecialchars($message);
            $html .= '<button type="button" class="btn-close" data-bs-dismiss="alert"></button>';
            $html .= '</div>';
        }
        
        if (self::hasError()) {
            $message = self::getError();
            $html .= '<div class="alert alert-danger alert-dismissible fade show" role="alert">';
            $html .= '<i class="bi bi-exclamation-triangle-fill me-2"></i>' . htmlspecialchars($message);
            $html .= '<button type="button" class="btn-close" data-bs-dismiss="alert"></button>';
            $html .= '</div>';
        }
        
        if (self::hasWarning()) {
            $message = self::getWarning();
            $html .= '<div class="alert alert-warning alert-dismissible fade show" role="alert">';
            $html .= '<i class="bi bi-exclamation-triangle-fill me-2"></i>' . htmlspecialchars($message);
            $html .= '<button type="button" class="btn-close" data-bs-dismiss="alert"></button>';
            $html .= '</div>';
        }
        
        if (self::hasInfo()) {
            $message = self::getInfo();
            $html .= '<div class="alert alert-info alert-dismissible fade show" role="alert">';
            $html .= '<i class="bi bi-info-circle-fill me-2"></i>' . htmlspecialchars($message);
            $html .= '<button type="button" class="btn-close" data-bs-dismiss="alert"></button>';
            $html .= '</div>';
        }
        
        return $html;
    }
}
?>