<?php

class ExpedienteArchivo extends BaseModelo {
    protected $table = 'expediente_archivos';
    
    protected $fillable = [
        'expediente_id', 'nombre_original', 'nombre_archivo', 'ruta_archivo',
        'tipo_archivo', 'tamano_bytes', 'tipo_documento', 'descripcion', 'usuario_id'
    ];
    
    // Tipos de archivo permitidos
    const TIPOS_PERMITIDOS = [
        'pdf' => 'application/pdf',
        'jpg' => 'image/jpeg',
        'jpeg' => 'image/jpeg',
        'png' => 'image/png',
        'gif' => 'image/gif',
        'doc' => 'application/msword',
        'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        'xls' => 'application/vnd.ms-excel',
        'xlsx' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
    ];
    
    // Tamaño máximo de archivo: 10 MB
    const MAX_FILE_SIZE = 10485760; // 10 MB en bytes
    
    /**
     * Obtener todos los archivos de un expediente
     */
    public function getByExpediente($expedienteId, $tipoDocumento = null) {
        $sql = "SELECT ea.*, 
                       CONCAT(u.nombre, ' ', u.apellidos) as subido_por
                FROM expediente_archivos ea
                INNER JOIN usuarios u ON ea.usuario_id = u.id
                WHERE ea.expediente_id = ?";
        
        $params = [$expedienteId];
        
        if ($tipoDocumento) {
            $sql .= " AND ea.tipo_documento = ?";
            $params[] = $tipoDocumento;
        }
        
        $sql .= " ORDER BY ea.created_at DESC";
        
        return $this->db->fetchAll($sql, $params);
    }
    
    /**
     * Validar archivo antes de subir
     */
    public function validarArchivo($archivo) {
        $errores = [];
        
        // Verificar si hay archivo
        if (!isset($archivo['tmp_name']) || empty($archivo['tmp_name'])) {
            $errores[] = 'No se ha seleccionado ningún archivo';
            return $errores;
        }
        
        // Verificar errores de carga
        if ($archivo['error'] !== UPLOAD_ERR_OK) {
            $errores[] = 'Error al subir el archivo: ' . $this->getUploadErrorMessage($archivo['error']);
            return $errores;
        }
        
        // Verificar tamaño
        if ($archivo['size'] > self::MAX_FILE_SIZE) {
            $errores[] = 'El archivo es demasiado grande. Tamaño máximo: 10 MB';
        }
        
        // Verificar extensión
        $extension = strtolower(pathinfo($archivo['name'], PATHINFO_EXTENSION));
        if (!array_key_exists($extension, self::TIPOS_PERMITIDOS)) {
            $errores[] = 'Tipo de archivo no permitido. Extensiones permitidas: ' . 
                        implode(', ', array_keys(self::TIPOS_PERMITIDOS));
        }
        
        // Verificar tipo MIME
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = finfo_file($finfo, $archivo['tmp_name']);
        finfo_close($finfo);
        
        if (!in_array($mimeType, self::TIPOS_PERMITIDOS)) {
            $errores[] = 'Tipo de archivo no válido';
        }
        
        return $errores;
    }
    
    /**
     * Subir archivo al servidor
     */
    public function subirArchivo($archivo, $expedienteId, $tipoDocumento, $descripcion, $usuarioId) {
        // Validar archivo
        $errores = $this->validarArchivo($archivo);
        if (!empty($errores)) {
            return ['success' => false, 'errors' => $errores];
        }
        
        // Crear directorio si no existe
        $uploadDir = ROOT_PATH . '/public/uploads/expedientes/' . $expedienteId . '/';
        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }
        
        // Generar nombre único para el archivo
        $extension = strtolower(pathinfo($archivo['name'], PATHINFO_EXTENSION));
        $nombreArchivo = uniqid() . '_' . time() . '.' . $extension;
        $rutaCompleta = $uploadDir . $nombreArchivo;
        
        // Mover archivo
        if (!move_uploaded_file($archivo['tmp_name'], $rutaCompleta)) {
            return ['success' => false, 'errors' => ['Error al guardar el archivo en el servidor']];
        }
        
        // Guardar en base de datos
        $data = [
            'expediente_id' => $expedienteId,
            'nombre_original' => $archivo['name'],
            'nombre_archivo' => $nombreArchivo,
            'ruta_archivo' => '/uploads/expedientes/' . $expedienteId . '/' . $nombreArchivo,
            'tipo_archivo' => $extension,
            'tamano_bytes' => $archivo['size'],
            'tipo_documento' => $tipoDocumento,
            'descripcion' => $descripcion,
            'usuario_id' => $usuarioId
        ];
        
        $id = $this->create($data);
        
        if ($id) {
            return [
                'success' => true, 
                'id' => $id,
                'ruta' => $data['ruta_archivo']
            ];
        }
        
        // Si falla la BD, eliminar el archivo
        unlink($rutaCompleta);
        return ['success' => false, 'errors' => ['Error al registrar el archivo en la base de datos']];
    }
    
    /**
     * Eliminar archivo
     */
    public function eliminarArchivo($id) {
        $archivo = $this->find($id);
        if (!$archivo) {
            return ['success' => false, 'error' => 'Archivo no encontrado'];
        }
        
        // Eliminar archivo físico
        $rutaCompleta = ROOT_PATH . '/public' . $archivo['ruta_archivo'];
        if (file_exists($rutaCompleta)) {
            unlink($rutaCompleta);
        }
        
        // Eliminar registro de BD
        $result = $this->delete($id);
        
        return ['success' => $result];
    }
    
    /**
     * Obtener estadísticas de archivos por expediente
     */
    public function getEstadisticas($expedienteId) {
        $sql = "SELECT 
                    COUNT(*) as total_archivos,
                    SUM(tamano_bytes) as tamano_total,
                    tipo_documento,
                    COUNT(*) as cantidad
                FROM expediente_archivos
                WHERE expediente_id = ?
                GROUP BY tipo_documento";
        
        $stats = $this->db->fetchAll($sql, [$expedienteId]);
        
        $total = $this->db->fetch(
            "SELECT COUNT(*) as total, SUM(tamano_bytes) as tamano 
             FROM expediente_archivos WHERE expediente_id = ?",
            [$expedienteId]
        );
        
        return [
            'total' => $total['total'] ?? 0,
            'tamano_total' => $total['tamano'] ?? 0,
            'por_tipo' => $stats
        ];
    }
    
    /**
     * Formatear tamaño de archivo
     */
    public static function formatearTamano($bytes) {
        if ($bytes >= 1073741824) {
            return number_format($bytes / 1073741824, 2) . ' GB';
        } elseif ($bytes >= 1048576) {
            return number_format($bytes / 1048576, 2) . ' MB';
        } elseif ($bytes >= 1024) {
            return number_format($bytes / 1024, 2) . ' KB';
        } else {
            return $bytes . ' bytes';
        }
    }
    
    /**
     * Obtener icono según tipo de archivo
     */
    public static function getIcono($extension) {
        $iconos = [
            'pdf' => 'bi-file-pdf text-danger',
            'jpg' => 'bi-file-image text-primary',
            'jpeg' => 'bi-file-image text-primary',
            'png' => 'bi-file-image text-primary',
            'gif' => 'bi-file-image text-primary',
            'doc' => 'bi-file-word text-info',
            'docx' => 'bi-file-word text-info',
            'xls' => 'bi-file-excel text-success',
            'xlsx' => 'bi-file-excel text-success'
        ];
        
        return $iconos[$extension] ?? 'bi-file-earmark text-secondary';
    }
    
    /**
     * Mensaje de error de upload
     */
    private function getUploadErrorMessage($errorCode) {
        $errors = [
            UPLOAD_ERR_INI_SIZE => 'El archivo excede el tamaño máximo permitido',
            UPLOAD_ERR_FORM_SIZE => 'El archivo excede el tamaño máximo del formulario',
            UPLOAD_ERR_PARTIAL => 'El archivo fue subido parcialmente',
            UPLOAD_ERR_NO_FILE => 'No se subió ningún archivo',
            UPLOAD_ERR_NO_TMP_DIR => 'Falta carpeta temporal',
            UPLOAD_ERR_CANT_WRITE => 'Error al escribir en disco',
            UPLOAD_ERR_EXTENSION => 'Una extensión de PHP detuvo la carga'
        ];
        
        return $errors[$errorCode] ?? 'Error desconocido al subir archivo';
    }
}