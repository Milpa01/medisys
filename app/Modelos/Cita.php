<?php
class Cita extends BaseModelo {
    protected $table = 'citas';
    
    protected $fillable = [
        'codigo_cita', 'paciente_id', 'medico_id', 'usuario_registro_id',
        'fecha_cita', 'hora_cita', 'duracion_minutos', 'motivo_consulta',
        'notas', 'estado', 'tipo_cita', 'costo', 'observaciones'
    ];
    
    public function getAllWithInfo() {
        $sql = "SELECT c.*, 
                       CONCAT(p.nombre, ' ', p.apellidos) as paciente_nombre,
                       p.telefono as paciente_telefono,
                       p.codigo_paciente,
                       CONCAT(u.nombre, ' ', u.apellidos) as medico_nombre,
                       e.nombre as especialidad,
                       m.consultorio,
                       CONCAT(ur.nombre, ' ', ur.apellidos) as registrado_por
                FROM citas c
                INNER JOIN pacientes p ON c.paciente_id = p.id
                INNER JOIN medicos m ON c.medico_id = m.id
                INNER JOIN usuarios u ON m.usuario_id = u.id
                INNER JOIN especialidades e ON m.especialidad_id = e.id
                INNER JOIN usuarios ur ON c.usuario_registro_id = ur.id
                ORDER BY c.fecha_cita DESC, c.hora_cita DESC";
        
        return $this->db->fetchAll($sql);
    }
    
    public function findWithInfo($id) {
        $sql = "SELECT c.*, 
                       CONCAT(p.nombre, ' ', p.apellidos) as paciente_nombre,
                       p.telefono as paciente_telefono,
                       p.email as paciente_email,
                       p.codigo_paciente,
                       CONCAT(u.nombre, ' ', u.apellidos) as medico_nombre,
                       e.nombre as especialidad,
                       m.consultorio,
                       m.costo_consulta,
                       CONCAT(ur.nombre, ' ', ur.apellidos) as registrado_por
                FROM citas c
                INNER JOIN pacientes p ON c.paciente_id = p.id
                INNER JOIN medicos m ON c.medico_id = m.id
                INNER JOIN usuarios u ON m.usuario_id = u.id
                INNER JOIN especialidades e ON m.especialidad_id = e.id
                INNER JOIN usuarios ur ON c.usuario_registro_id = ur.id
                WHERE c.id = ?";
        
        return $this->db->fetch($sql, [$id]);
    }
    
    public function getCitasDelDia($fecha = null) {
        $fecha = $fecha ?: date('Y-m-d');
        
        $sql = "SELECT c.*, 
                       CONCAT(p.nombre, ' ', p.apellidos) as paciente_nombre,
                       p.telefono as paciente_telefono,
                       CONCAT(u.nombre, ' ', u.apellidos) as medico_nombre,
                       e.nombre as especialidad,
                       m.consultorio
                FROM citas c
                INNER JOIN pacientes p ON c.paciente_id = p.id
                INNER JOIN medicos m ON c.medico_id = m.id
                INNER JOIN usuarios u ON m.usuario_id = u.id
                INNER JOIN especialidades e ON m.especialidad_id = e.id
                WHERE c.fecha_cita = ? AND c.estado != 'cancelada'
                ORDER BY c.hora_cita";
        
        return $this->db->fetchAll($sql, [$fecha]);
    }
    
    public function getCitasPorMedico($medicoId, $fechaInicio = null, $fechaFin = null) {
        $fechaInicio = $fechaInicio ?: date('Y-m-d');
        $fechaFin = $fechaFin ?: date('Y-m-d', strtotime('+30 days'));
        
        $sql = "SELECT c.*, 
                       CONCAT(p.nombre, ' ', p.apellidos) as paciente_nombre,
                       p.telefono as paciente_telefono
                FROM citas c
                INNER JOIN pacientes p ON c.paciente_id = p.id
                WHERE c.medico_id = ? 
                AND c.fecha_cita BETWEEN ? AND ?
                AND c.estado != 'cancelada'
                ORDER BY c.fecha_cita, c.hora_cita";
        
        return $this->db->fetchAll($sql, [$medicoId, $fechaInicio, $fechaFin]);
    }
    
    public function verificarDisponibilidad($medicoId, $fecha, $hora, $excludeId = null) {
        $sql = "SELECT COUNT(*) as count FROM citas 
                WHERE medico_id = ? AND fecha_cita = ? AND hora_cita = ? 
                AND estado NOT IN ('cancelada', 'no_asistio')";
        $params = [$medicoId, $fecha, $hora];
        
        if ($excludeId) {
            $sql .= " AND id != ?";
            $params[] = $excludeId;
        }
        
        $result = $this->db->fetch($sql, $params);
        return $result['count'] == 0;
    }
    
    public function getHorariosOcupados($medicoId, $fecha) {
        $sql = "SELECT hora_cita FROM citas 
                WHERE medico_id = ? AND fecha_cita = ? 
                AND estado NOT IN ('cancelada', 'no_asistio')";
        
        $result = $this->db->fetchAll($sql, [$medicoId, $fecha]);
        return array_column($result, 'hora_cita');
    }
    
    public function searchCitas($search) {
        $searchTerm = "%{$search}%";
        
        $sql = "SELECT c.*, 
                       CONCAT(p.nombre, ' ', p.apellidos) as paciente_nombre,
                       p.codigo_paciente,
                       CONCAT(u.nombre, ' ', u.apellidos) as medico_nombre,
                       e.nombre as especialidad
                FROM citas c
                INNER JOIN pacientes p ON c.paciente_id = p.id
                INNER JOIN medicos m ON c.medico_id = m.id
                INNER JOIN usuarios u ON m.usuario_id = u.id
                INNER JOIN especialidades e ON m.especialidad_id = e.id
                WHERE (p.nombre LIKE ? OR p.apellidos LIKE ? 
                OR c.codigo_cita LIKE ? OR p.codigo_paciente LIKE ?
                OR u.nombre LIKE ? OR u.apellidos LIKE ?)
                ORDER BY c.fecha_cita DESC, c.hora_cita DESC
                LIMIT 50";
        
        return $this->db->fetchAll($sql, [$searchTerm, $searchTerm, $searchTerm, $searchTerm, $searchTerm, $searchTerm]);
    }
    
    public function getCitasPendientes($limite = 20) {
        $sql = "SELECT c.*, 
                       CONCAT(p.nombre, ' ', p.apellidos) as paciente_nombre,
                       p.telefono as paciente_telefono,
                       CONCAT(u.nombre, ' ', u.apellidos) as medico_nombre,
                       e.nombre as especialidad
                FROM citas c
                INNER JOIN pacientes p ON c.paciente_id = p.id
                INNER JOIN medicos m ON c.medico_id = m.id
                INNER JOIN usuarios u ON m.usuario_id = u.id
                INNER JOIN especialidades e ON m.especialidad_id = e.id
                WHERE c.fecha_cita >= CURDATE() 
                AND c.estado IN ('programada', 'confirmada')
                ORDER BY c.fecha_cita, c.hora_cita
                LIMIT ?";
        
        return $this->db->fetchAll($sql, [$limite]);
    }
    
    public function getEstadisticas() {
        $stats = [];
        
        // Total de citas
        $stats['total'] = $this->count();
        
        // Citas por estado
        $estados = $this->db->fetchAll("
            SELECT estado, COUNT(*) as cantidad 
            FROM citas 
            GROUP BY estado
        ");
        $stats['por_estado'] = array_column($estados, 'cantidad', 'estado');
        
        // Citas de hoy
        $stats['hoy'] = $this->count(['fecha_cita' => date('Y-m-d')]);
        
        // Citas de esta semana
        $inicioSemana = date('Y-m-d', strtotime('monday this week'));
        $finSemana = date('Y-m-d', strtotime('sunday this week'));
        
        $stats['semana'] = $this->db->fetch("
            SELECT COUNT(*) as total FROM citas 
            WHERE fecha_cita BETWEEN ? AND ?
        ", [$inicioSemana, $finSemana])['total'];
        
        // Citas del mes
        $stats['mes'] = $this->db->fetch("
            SELECT COUNT(*) as total FROM citas 
            WHERE MONTH(fecha_cita) = MONTH(CURDATE()) 
            AND YEAR(fecha_cita) = YEAR(CURDATE())
        ")['total'];
        
        return $stats;
    }
    
    public function create($data) {
        // Generar código de cita si no se proporciona
        if (empty($data['codigo_cita'])) {
            $data['codigo_cita'] = $this->generateCodigoCita($data['fecha_cita']);
        }
        
        // Establecer duración por defecto si no se especifica
        if (empty($data['duracion_minutos'])) {
            $data['duracion_minutos'] = 30;
        }
        
        return parent::create($data);
    }
    
    private function generateCodigoCita($fecha) {
        $fechaFormato = date('Ymd', strtotime($fecha));
        
        // Obtener el siguiente número secuencial para el día
        $result = $this->db->fetch("
            SELECT COUNT(*) + 1 as siguiente 
            FROM citas 
            WHERE DATE(created_at) = CURDATE()
        ");
        
        $secuencial = str_pad($result['siguiente'], 4, '0', STR_PAD_LEFT);
        
        return 'CIT' . $fechaFormato . $secuencial;
    }
    
    public function cambiarEstado($id, $nuevoEstado) {
        $estadosValidos = ['programada', 'confirmada', 'en_curso', 'completada', 'cancelada', 'no_asistio'];
        
        if (!in_array($nuevoEstado, $estadosValidos)) {
            return false;
        }
        
        return $this->update($id, ['estado' => $nuevoEstado]);
    }
    
    public function getCalendario($mes = null, $ano = null) {
        $mes = $mes ?: date('m');
        $ano = $ano ?: date('Y');
        
        $sql = "SELECT c.*, 
                       CONCAT(p.nombre, ' ', p.apellidos) as paciente_nombre,
                       CONCAT(u.nombre, ' ', u.apellidos) as medico_nombre,
                       e.nombre as especialidad
                FROM citas c
                INNER JOIN pacientes p ON c.paciente_id = p.id
                INNER JOIN medicos m ON c.medico_id = m.id
                INNER JOIN usuarios u ON m.usuario_id = u.id
                INNER JOIN especialidades e ON m.especialidad_id = e.id
                WHERE MONTH(c.fecha_cita) = ? AND YEAR(c.fecha_cita) = ?
                AND c.estado != 'cancelada'
                ORDER BY c.fecha_cita, c.hora_cita";
        
        return $this->db->fetchAll($sql, [$mes, $ano]);
    }
}
?>