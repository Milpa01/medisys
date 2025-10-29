<?php
class Medico extends BaseModelo {
    protected $table = 'medicos';
    
    protected $fillable = [
        'usuario_id', 'cedula_profesional', 'especialidad_id', 'experiencia_anos',
        'consultorio', 'horario_inicio', 'horario_fin', 'dias_atencion',
        'costo_consulta', 'observaciones', 'is_active'
    ];
    
    public function getAllWithInfo() {
        $sql = "SELECT m.*, 
                       u.nombre, u.apellidos, u.email, u.telefono, u.username, u.is_active as usuario_activo,
                       e.nombre as especialidad_nombre
                FROM medicos m
                INNER JOIN usuarios u ON m.usuario_id = u.id
                INNER JOIN especialidades e ON m.especialidad_id = e.id
                ORDER BY u.nombre, u.apellidos";
        
        return $this->db->fetchAll($sql);
    }
    
    public function findWithInfo($id) {
        $sql = "SELECT m.*, 
                       u.nombre, u.apellidos, u.email, u.telefono, u.direccion, u.imagen, 
                       u.username, u.is_active, u.ultimo_acceso, u.rol_id,
                       e.nombre as especialidad_nombre, e.nombre as especialidad,
                       r.nombre as rol_nombre
                FROM medicos m
                INNER JOIN usuarios u ON m.usuario_id = u.id
                INNER JOIN especialidades e ON m.especialidad_id = e.id
                LEFT JOIN roles r ON u.rol_id = r.id
                WHERE m.id = ?";
        
        return $this->db->fetch($sql, [$id]);
    }
    
    public function findByUsuario($usuarioId) {
        return $this->findBy('usuario_id', $usuarioId);
    }
    
    public function findByCedula($cedula) {
        return $this->findBy('cedula_profesional', $cedula);
    }
    
    public function existsCedula($cedula, $excludeId = null) {
        $sql = "SELECT COUNT(*) as count FROM medicos WHERE cedula_profesional = ?";
        $params = [$cedula];
        
        if ($excludeId) {
            $sql .= " AND id != ?";
            $params[] = $excludeId;
        }
        
        $result = $this->db->fetch($sql, $params);
        return $result['count'] > 0;
    }
    
    public function getByEspecialidad($especialidadId) {
        $sql = "SELECT m.*, u.nombre, u.apellidos
                FROM medicos m
                INNER JOIN usuarios u ON m.usuario_id = u.id
                WHERE m.especialidad_id = ? AND u.is_active = 1
                ORDER BY u.nombre, u.apellidos";
        
        return $this->db->fetchAll($sql, [$especialidadId]);
    }
    
    public function getDisponibles($fecha, $hora) {
        $diaSemana = strtolower(date('l', strtotime($fecha)));
        $diasEspanol = [
            'monday' => 'lunes',
            'tuesday' => 'martes', 
            'wednesday' => 'miercoles',
            'thursday' => 'jueves',
            'friday' => 'viernes',
            'saturday' => 'sabado',
            'sunday' => 'domingo'
        ];
        $dia = $diasEspanol[$diaSemana] ?? $diaSemana;
        
        $sql = "SELECT m.*, u.nombre, u.apellidos, e.nombre as especialidad
                FROM medicos m
                INNER JOIN usuarios u ON m.usuario_id = u.id
                INNER JOIN especialidades e ON m.especialidad_id = e.id
                WHERE u.is_active = 1 
                AND FIND_IN_SET(?, m.dias_atencion) > 0
                AND TIME(?) BETWEEN m.horario_inicio AND m.horario_fin
                AND m.id NOT IN (
                    SELECT medico_id FROM citas 
                    WHERE fecha_cita = ? AND hora_cita = ? 
                    AND estado NOT IN ('cancelada', 'no_asistio')
                )
                ORDER BY u.nombre, u.apellidos";
        
        return $this->db->fetchAll($sql, [$dia, $hora, $fecha, $hora]);
    }
    
    public function getCitasProximas($medicoId, $limite = 10) {
        $sql = "SELECT c.*, 
                       CONCAT(p.nombre, ' ', p.apellidos) as paciente_nombre,
                       p.telefono as paciente_telefono
                FROM citas c
                INNER JOIN pacientes p ON c.paciente_id = p.id
                WHERE c.medico_id = ? 
                AND c.fecha_cita >= CURDATE()
                AND c.estado IN ('programada', 'confirmada')
                ORDER BY c.fecha_cita, c.hora_cita
                LIMIT ?";
        
        return $this->db->fetchAll($sql, [$medicoId, $limite]);
    }
    
    public function getEstadisticas($medicoId = null) {
        $whereClause = $medicoId ? "WHERE m.id = ?" : "";
        $params = $medicoId ? [$medicoId] : [];
        
        $stats = [];
        
        // Total de médicos activos
        if (!$medicoId) {
            $stats['total'] = $this->db->fetch("
                SELECT COUNT(*) as total 
                FROM medicos m 
                INNER JOIN usuarios u ON m.usuario_id = u.id 
                WHERE u.is_active = 1
            ")['total'];
        }
        
        // Citas programadas
        $baseSql = "SELECT COUNT(*) as total FROM citas c INNER JOIN medicos m ON c.medico_id = m.id";
        
        $stats['citas_programadas'] = $this->db->fetch($baseSql . " $whereClause 
            AND c.fecha_cita >= CURDATE() 
            AND c.estado IN ('programada', 'confirmada')", $params)['total'];
        
        $stats['consultas_mes'] = $this->db->fetch($baseSql . " 
            INNER JOIN consultas con ON c.id = con.cita_id $whereClause
            AND MONTH(con.created_at) = MONTH(CURDATE()) 
            AND YEAR(con.created_at) = YEAR(CURDATE())", $params)['total'];
        
        // Por especialidades (solo si no es médico específico)
        if (!$medicoId) {
            $especialidades = $this->db->fetchAll("
                SELECT e.nombre, COUNT(m.id) as cantidad
                FROM especialidades e
                LEFT JOIN medicos m ON e.id = m.especialidad_id
                LEFT JOIN usuarios u ON m.usuario_id = u.id AND u.is_active = 1
                GROUP BY e.id, e.nombre
                ORDER BY cantidad DESC
            ");
            $stats['especialidades'] = $especialidades;
        }
        
        return $stats;
    }
    
    public function getHorarioCompleto($medicoId) {
        $medico = $this->findWithInfo($medicoId);
        if (!$medico) return null;
        
        $diasSemana = explode(',', $medico['dias_atencion']);
        $horarios = [];
        
        foreach ($diasSemana as $dia) {
            $horarios[trim($dia)] = [
                'inicio' => $medico['horario_inicio'],
                'fin' => $medico['horario_fin']
            ];
        }
        
        return $horarios;
    }
}
?>