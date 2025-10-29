<?php
/**
 * Modelo: Médico
 * Descripción: Manejo de datos de médicos
 */

class Medico extends BaseModelo {
    protected $table = 'medicos';
    
    protected $fillable = [
        'usuario_id', 'cedula_profesional', 'especialidad_id', 'experiencia_anos',
        'consultorio', 'horario_inicio', 'horario_fin', 'dias_atencion',
        'costo_consulta', 'observaciones', 'is_active'
    ];
    
    /**
     * Obtener todos los médicos con información completa
     */
    public function getAllWithInfo() {
        $sql = "SELECT m.*, 
                       u.nombre, u.apellidos, u.email, u.telefono, u.username,
                       u.is_active as usuario_activo, u.imagen,
                       e.nombre as especialidad
                FROM medicos m
                INNER JOIN usuarios u ON m.usuario_id = u.id
                INNER JOIN especialidades e ON m.especialidad_id = e.id
                ORDER BY u.nombre, u.apellidos";
        
        return $this->db->fetchAll($sql);
    }
    
    /**
     * Buscar un médico por ID con información completa
     */
    public function findWithInfo($id) {
        $sql = "SELECT m.*, 
                       u.nombre, u.apellidos, u.email, u.telefono, u.direccion, 
                       u.imagen, u.username, u.is_active,
                       e.nombre as especialidad, e.id as especialidad_id
                FROM medicos m
                INNER JOIN usuarios u ON m.usuario_id = u.id
                INNER JOIN especialidades e ON m.especialidad_id = e.id
                WHERE m.id = ?";
        
        return $this->db->fetch($sql, [$id]);
    }
    
    /**
     * Buscar médico por ID de usuario
     */
    public function findByUsuario($usuarioId) {
        $sql = "SELECT m.*, 
                       e.nombre as especialidad
                FROM medicos m
                INNER JOIN especialidades e ON m.especialidad_id = e.id
                WHERE m.usuario_id = ?";
        
        return $this->db->fetch($sql, [$usuarioId]);
    }
    
    /**
     * Buscar médico por cédula profesional
     */
    public function findByCedula($cedula) {
        return $this->findBy('cedula_profesional', $cedula);
    }
    
    /**
     * Verificar si existe una cédula profesional
     */
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
    
    /**
     * Obtener médicos por especialidad
     */
    public function getByEspecialidad($especialidadId) {
        $sql = "SELECT m.*, 
                       u.nombre, u.apellidos, u.email, u.telefono,
                       e.nombre as especialidad
                FROM medicos m
                INNER JOIN usuarios u ON m.usuario_id = u.id
                INNER JOIN especialidades e ON m.especialidad_id = e.id
                WHERE m.especialidad_id = ? 
                AND u.is_active = 1
                ORDER BY u.nombre, u.apellidos";
        
        return $this->db->fetchAll($sql, [$especialidadId]);
    }
    
    /**
     * Obtener médicos disponibles para una fecha y hora específica
     */
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
        
        $sql = "SELECT m.*, 
                       u.nombre, u.apellidos, u.email, u.telefono,
                       e.nombre as especialidad
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
    
    /**
     * Obtener citas próximas de un médico
     */
    public function getCitasProximas($medicoId, $limite = 10) {
        $sql = "SELECT c.*, 
                       CONCAT(p.nombre, ' ', p.apellidos) as paciente_nombre,
                       p.telefono as paciente_telefono,
                       p.codigo_paciente
                FROM citas c
                INNER JOIN pacientes p ON c.paciente_id = p.id
                WHERE c.medico_id = ? 
                AND c.fecha_cita >= CURDATE()
                AND c.estado IN ('programada', 'confirmada')
                ORDER BY c.fecha_cita, c.hora_cita
                LIMIT ?";
        
        return $this->db->fetchAll($sql, [$medicoId, $limite]);
    }
    
    /**
     * Obtener estadísticas de un médico o de todos
     */
    public function getEstadisticas($medicoId = null) {
        $whereClause = $medicoId ? "WHERE m.id = ?" : "";
        $params = $medicoId ? [$medicoId] : [];
        
        $stats = [];
        
        // Total de médicos activos (solo si no es un médico específico)
        if (!$medicoId) {
            $stats['total'] = $this->db->fetch("
                SELECT COUNT(*) as total 
                FROM medicos m 
                INNER JOIN usuarios u ON m.usuario_id = u.id 
                WHERE u.is_active = 1
            ")['total'];
        }
        
        // Base SQL para consultas de citas
        $baseSql = "SELECT COUNT(*) as total 
                    FROM citas c 
                    INNER JOIN medicos m ON c.medico_id = m.id";
        
        // Citas programadas
        $stats['citas_programadas'] = $this->db->fetch(
            $baseSql . " $whereClause 
            AND c.fecha_cita >= CURDATE() 
            AND c.estado IN ('programada', 'confirmada')", 
            $params
        )['total'];
        
        // Consultas del mes
        $stats['consultas_mes'] = $this->db->fetch(
            $baseSql . " 
            INNER JOIN consultas con ON c.id = con.cita_id $whereClause
            AND MONTH(c.fecha_cita) = MONTH(CURDATE()) 
            AND YEAR(c.fecha_cita) = YEAR(CURDATE())", 
            $params
        )['total'] ?? 0;
        
        // Consultas de hoy
        $stats['consultas_hoy'] = $this->db->fetch(
            $baseSql . " 
            INNER JOIN consultas con ON c.id = con.cita_id $whereClause
            AND DATE(c.fecha_cita) = CURDATE()", 
            $params
        )['total'] ?? 0;
        
        // Consultas de esta semana
        $stats['consultas_semana'] = $this->db->fetch(
            $baseSql . " 
            INNER JOIN consultas con ON c.id = con.cita_id $whereClause
            AND YEARWEEK(c.fecha_cita, 1) = YEARWEEK(CURDATE(), 1)", 
            $params
        )['total'] ?? 0;
        
        // Total de pacientes atendidos
        if ($medicoId) {
            $stats['total_pacientes'] = $this->db->fetch(
                "SELECT COUNT(DISTINCT c.paciente_id) as total
                FROM citas c
                WHERE c.medico_id = ?
                AND c.estado = 'completada'",
                [$medicoId]
            )['total'] ?? 0;
            
            // Promedio de pacientes por día
            $promedio = $this->db->fetch(
                "SELECT COUNT(*) / COUNT(DISTINCT DATE(c.fecha_cita)) as promedio
                FROM citas c
                WHERE c.medico_id = ?
                AND c.estado = 'completada'
                AND c.fecha_cita >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)",
                [$medicoId]
            );
            $stats['promedio_pacientes_dia'] = $promedio['promedio'] ?? 0;
        }
        
        // Especialidades (solo si no es médico específico)
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
    
    /**
     * Obtener horario completo de un médico
     */
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
    
    /**
     * Verificar si un médico está disponible en una fecha y hora
     */
    public function estaDisponible($medicoId, $fecha, $hora) {
        $medico = $this->findWithInfo($medicoId);
        if (!$medico || !$medico['is_active']) {
            return false;
        }
        
        // Verificar día de la semana
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
        
        $diasAtencion = explode(',', $medico['dias_atencion']);
        if (!in_array($dia, $diasAtencion)) {
            return false;
        }
        
        // Verificar horario
        if ($hora < $medico['horario_inicio'] || $hora > $medico['horario_fin']) {
            return false;
        }
        
        // Verificar si ya tiene cita en ese horario
        $citaExistente = $this->db->fetch(
            "SELECT COUNT(*) as count FROM citas 
            WHERE medico_id = ? AND fecha_cita = ? AND hora_cita = ? 
            AND estado NOT IN ('cancelada', 'no_asistio')",
            [$medicoId, $fecha, $hora]
        );
        
        return $citaExistente['count'] == 0;
    }
}
?>