<?php
class Consulta extends BaseModelo {
    protected $table = 'consultas';
    
    protected $fillable = [
        'cita_id', 'numero_consulta', 'peso', 'altura', 'temperatura',
        'presion_sistolica', 'presion_diastolica', 'frecuencia_cardiaca',
        'sintomas', 'exploracion_fisica', 'diagnostico_principal',
        'diagnosticos_secundarios', 'plan_tratamiento', 'indicaciones',
        'proxima_cita', 'observaciones'
    ];
    
    public function getAllWithInfo() {
        $sql = "SELECT con.*, 
                       c.fecha_cita, c.hora_cita, c.codigo_cita, c.motivo_consulta,
                       CONCAT(p.nombre, ' ', p.apellidos) as paciente_nombre,
                       p.codigo_paciente, p.telefono as paciente_telefono,
                       p.email as paciente_email, p.id as paciente_id,
                       CONCAT(u.nombre, ' ', u.apellidos) as medico_nombre,
                       u.id as medico_usuario_id,
                       e.nombre as especialidad,
                       m.consultorio, m.costo_consulta, m.id as medico_id,
                       c.id as cita_id
                FROM consultas con
                INNER JOIN citas c ON con.cita_id = c.id
                INNER JOIN pacientes p ON c.paciente_id = p.id
                INNER JOIN medicos m ON c.medico_id = m.id
                INNER JOIN usuarios u ON m.usuario_id = u.id
                INNER JOIN especialidades e ON m.especialidad_id = e.id
                ORDER BY c.fecha_cita DESC, c.hora_cita DESC";
        
        return $this->db->fetchAll($sql);
    }
    
    public function findWithInfo($id) {
        $sql = "SELECT con.*, 
                       c.fecha_cita, c.hora_cita, c.codigo_cita, c.motivo_consulta,
                       c.tipo_cita, c.estado as estado_cita, c.costo as costo_cita,
                       CONCAT(p.nombre, ' ', p.apellidos) as paciente_nombre,
                       p.codigo_paciente, p.telefono as paciente_telefono,
                       p.email as paciente_email, p.fecha_nacimiento,
                       p.genero, p.tipo_sangre, p.id as paciente_id,
                       CONCAT(u.nombre, ' ', u.apellidos) as medico_nombre,
                       u.telefono as medico_telefono, u.email as medico_email,
                       u.id as medico_usuario_id,
                       e.nombre as especialidad,
                       m.consultorio, m.costo_consulta, m.cedula_profesional,
                       m.id as medico_id, c.id as cita_id,
                       CONCAT(ur.nombre, ' ', ur.apellidos) as registrado_por
                FROM consultas con
                INNER JOIN citas c ON con.cita_id = c.id
                INNER JOIN pacientes p ON c.paciente_id = p.id
                INNER JOIN medicos m ON c.medico_id = m.id
                INNER JOIN usuarios u ON m.usuario_id = u.id
                INNER JOIN especialidades e ON m.especialidad_id = e.id
                INNER JOIN usuarios ur ON c.usuario_registro_id = ur.id
                WHERE con.id = ?";
        
        return $this->db->fetch($sql, [$id]);
    }
    
    public function findByCita($citaId) {
        return $this->findBy('cita_id', $citaId);
    }
    
    public function getConsultasPorPaciente($pacienteId, $limite = null) {
        $sql = "SELECT con.*, 
                       c.fecha_cita, c.hora_cita,
                       CONCAT(u.nombre, ' ', u.apellidos) as medico_nombre,
                       e.nombre as especialidad
                FROM consultas con
                INNER JOIN citas c ON con.cita_id = c.id
                INNER JOIN medicos m ON c.medico_id = m.id
                INNER JOIN usuarios u ON m.usuario_id = u.id
                INNER JOIN especialidades e ON m.especialidad_id = e.id
                WHERE c.paciente_id = ?
                ORDER BY c.fecha_cita DESC, c.hora_cita DESC";
        
        if ($limite) {
            $sql .= " LIMIT ?";
            return $this->db->fetchAll($sql, [$pacienteId, $limite]);
        }
        
        return $this->db->fetchAll($sql, [$pacienteId]);
    }
    
    public function getConsultasPorMedico($medicoId, $fechaInicio = null, $fechaFin = null) {
        $sql = "SELECT con.*, 
                       c.fecha_cita, c.hora_cita, c.codigo_cita,
                       CONCAT(p.nombre, ' ', p.apellidos) as paciente_nombre,
                       p.codigo_paciente, p.telefono as paciente_telefono
                FROM consultas con
                INNER JOIN citas c ON con.cita_id = c.id
                INNER JOIN pacientes p ON c.paciente_id = p.id
                WHERE c.medico_id = ?";
        
        $params = [$medicoId];
        
        if ($fechaInicio) {
            $sql .= " AND c.fecha_cita >= ?";
            $params[] = $fechaInicio;
        }
        
        if ($fechaFin) {
            $sql .= " AND c.fecha_cita <= ?";
            $params[] = $fechaFin;
        }
        
        $sql .= " ORDER BY c.fecha_cita DESC, c.hora_cita DESC";
        
        return $this->db->fetchAll($sql, $params);
    }
    
    public function searchConsultas($search, $fechaInicio = null, $fechaFin = null, $medicoId = null, $especialidadId = null) {
        $searchTerm = "%{$search}%";
        
        $sql = "SELECT con.*, 
                       c.fecha_cita, c.hora_cita, c.codigo_cita,
                       CONCAT(p.nombre, ' ', p.apellidos) as paciente_nombre,
                       p.codigo_paciente,
                       CONCAT(u.nombre, ' ', u.apellidos) as medico_nombre,
                       e.nombre as especialidad,
                       m.consultorio
                FROM consultas con
                INNER JOIN citas c ON con.cita_id = c.id
                INNER JOIN pacientes p ON c.paciente_id = p.id
                INNER JOIN medicos m ON c.medico_id = m.id
                INNER JOIN usuarios u ON m.usuario_id = u.id
                INNER JOIN especialidades e ON m.especialidad_id = e.id
                WHERE (p.nombre LIKE ? OR p.apellidos LIKE ? 
                OR con.diagnostico_principal LIKE ? OR con.numero_consulta LIKE ?
                OR p.codigo_paciente LIKE ? OR u.nombre LIKE ? OR u.apellidos LIKE ?)";
        
        $params = [$searchTerm, $searchTerm, $searchTerm, $searchTerm, $searchTerm, $searchTerm, $searchTerm];
        
        if ($fechaInicio) {
            $sql .= " AND c.fecha_cita >= ?";
            $params[] = $fechaInicio;
        }
        
        if ($fechaFin) {
            $sql .= " AND c.fecha_cita <= ?";
            $params[] = $fechaFin;
        }
        
        if ($medicoId) {
            $sql .= " AND c.medico_id = ?";
            $params[] = $medicoId;
        }
        
        if ($especialidadId) {
            $sql .= " AND m.especialidad_id = ?";
            $params[] = $especialidadId;
        }
        
        $sql .= " ORDER BY c.fecha_cita DESC, c.hora_cita DESC LIMIT 100";
        
        return $this->db->fetchAll($sql, $params);
    }
    
    public function getConsultasDelDia($fecha = null, $medicoId = null) {
        $fecha = $fecha ?: date('Y-m-d');
        
        $sql = "SELECT con.*, 
                       c.fecha_cita, c.hora_cita, c.codigo_cita,
                       CONCAT(p.nombre, ' ', p.apellidos) as paciente_nombre,
                       p.telefono as paciente_telefono,
                       CONCAT(u.nombre, ' ', u.apellidos) as medico_nombre,
                       e.nombre as especialidad,
                       m.consultorio
                FROM consultas con
                INNER JOIN citas c ON con.cita_id = c.id
                INNER JOIN pacientes p ON c.paciente_id = p.id
                INNER JOIN medicos m ON c.medico_id = m.id
                INNER JOIN usuarios u ON m.usuario_id = u.id
                INNER JOIN especialidades e ON m.especialidad_id = e.id
                WHERE c.fecha_cita = ?";
        
        $params = [$fecha];
        
        if ($medicoId) {
            $sql .= " AND c.medico_id = ?";
            $params[] = $medicoId;
        }
        
        $sql .= " ORDER BY c.hora_cita";
        
        return $this->db->fetchAll($sql, $params);
    }
    
    public function getPrescripciones($consultaId) {
        $sql = "SELECT p.*, 
                       m.nombre_comercial, m.nombre_generico,
                       m.presentacion, m.concentracion
                FROM prescripciones p
                INNER JOIN medicamentos m ON p.medicamento_id = m.id
                WHERE p.consulta_id = ?
                ORDER BY p.created_at";
        
        return $this->db->fetchAll($sql, [$consultaId]);
    }
    
    public function getSignosVitales($consultaId) {
        $consulta = $this->find($consultaId);
        if (!$consulta) return null;
        
        return [
            'peso' => $consulta['peso'],
            'altura' => $consulta['altura'],
            'temperatura' => $consulta['temperatura'],
            'presion_sistolica' => $consulta['presion_sistolica'],
            'presion_diastolica' => $consulta['presion_diastolica'],
            'frecuencia_cardiaca' => $consulta['frecuencia_cardiaca'],
            'imc' => $this->calcularIMC($consulta['peso'], $consulta['altura'])
        ];
    }
    
    public function calcularIMC($peso, $altura) {
        if (!$peso || !$altura || $altura <= 0) {
            return null;
        }
        
        $alturaMetros = $altura / 100;
        return round($peso / ($alturaMetros * $alturaMetros), 2);
    }
    
    public function getEstadisticas($medicoId = null, $fechaInicio = null, $fechaFin = null) {
        $whereClause = "WHERE 1=1";
        $params = [];
        
        if ($medicoId) {
            $whereClause .= " AND c.medico_id = ?";
            $params[] = $medicoId;
        }
        
        if ($fechaInicio) {
            $whereClause .= " AND c.fecha_cita >= ?";
            $params[] = $fechaInicio;
        }
        
        if ($fechaFin) {
            $whereClause .= " AND c.fecha_cita <= ?";
            $params[] = $fechaFin;
        }
        
        $stats = [];
        
        // Total de consultas
        $sql = "SELECT COUNT(*) as total FROM consultas con 
                INNER JOIN citas c ON con.cita_id = c.id $whereClause";
        $stats['total'] = $this->db->fetch($sql, $params)['total'];
        
        // Consultas por día de la semana
        $sql = "SELECT DAYNAME(c.fecha_cita) as dia, COUNT(*) as cantidad
                FROM consultas con
                INNER JOIN citas c ON con.cita_id = c.id
                $whereClause
                GROUP BY DAYNAME(c.fecha_cita), DAYOFWEEK(c.fecha_cita)
                ORDER BY DAYOFWEEK(c.fecha_cita)";
        $stats['por_dia_semana'] = $this->db->fetchAll($sql, $params);
        
        // Consultas por especialidad
        $sql = "SELECT e.nombre as especialidad, COUNT(*) as cantidad
                FROM consultas con
                INNER JOIN citas c ON con.cita_id = c.id
                INNER JOIN medicos m ON c.medico_id = m.id
                INNER JOIN especialidades e ON m.especialidad_id = e.id
                $whereClause
                GROUP BY e.id, e.nombre
                ORDER BY cantidad DESC";
        $stats['por_especialidad'] = $this->db->fetchAll($sql, $params);
        
        // Promedio de signos vitales
        $sql = "SELECT 
                    ROUND(AVG(con.temperatura), 1) as temp_promedio,
                    ROUND(AVG(con.presion_sistolica), 0) as sistolica_promedio,
                    ROUND(AVG(con.presion_diastolica), 0) as diastolica_promedio,
                    ROUND(AVG(con.frecuencia_cardiaca), 0) as fc_promedio,
                    ROUND(AVG(con.peso), 1) as peso_promedio
                FROM consultas con
                INNER JOIN citas c ON con.cita_id = c.id
                $whereClause";
        $stats['promedios'] = $this->db->fetch($sql, $params);
        
        // Diagnósticos más frecuentes
        $sql = "SELECT con.diagnostico_principal, COUNT(*) as cantidad
                FROM consultas con
                INNER JOIN citas c ON con.cita_id = c.id
                $whereClause
                AND con.diagnostico_principal IS NOT NULL 
                AND con.diagnostico_principal != ''
                GROUP BY con.diagnostico_principal
                ORDER BY cantidad DESC
                LIMIT 10";
        $stats['diagnosticos_frecuentes'] = $this->db->fetchAll($sql, $params);
        
        return $stats;
    }
    
    public function getConsultasRecientes($limite = 10, $medicoId = null) {
        $sql = "SELECT con.*, 
                       c.fecha_cita, c.hora_cita,
                       CONCAT(p.nombre, ' ', p.apellidos) as paciente_nombre,
                       p.codigo_paciente,
                       CONCAT(u.nombre, ' ', u.apellidos) as medico_nombre,
                       e.nombre as especialidad
                FROM consultas con
                INNER JOIN citas c ON con.cita_id = c.id
                INNER JOIN pacientes p ON c.paciente_id = p.id
                INNER JOIN medicos m ON c.medico_id = m.id
                INNER JOIN usuarios u ON m.usuario_id = u.id
                INNER JOIN especialidades e ON m.especialidad_id = e.id";
        
        $params = [];
        
        if ($medicoId) {
            $sql .= " WHERE c.medico_id = ?";
            $params[] = $medicoId;
        }
        
        $sql .= " ORDER BY con.created_at DESC LIMIT ?";
        $params[] = $limite;
        
        return $this->db->fetchAll($sql, $params);
    }
    
    public function create($data) {
        // Generar número de consulta si no se proporciona
        if (empty($data['numero_consulta'])) {
            $data['numero_consulta'] = $this->generateNumeroConsulta();
        }
        
        return parent::create($data);
    }
    
    private function generateNumeroConsulta() {
        $fechaFormato = date('Ymd');
        
        // Obtener el siguiente número secuencial para el día
        $result = $this->db->fetch("
            SELECT COUNT(*) + 1 as siguiente 
            FROM consultas 
            WHERE DATE(created_at) = CURDATE()
        ");
        
        $secuencial = str_pad($result['siguiente'], 4, '0', STR_PAD_LEFT);
        
        return 'CON' . $fechaFormato . $secuencial;
    }
    
    public function actualizarSignosVitales($id, $signosVitales) {
        $data = [
            'peso' => $signosVitales['peso'] ?? null,
            'altura' => $signosVitales['altura'] ?? null,
            'temperatura' => $signosVitales['temperatura'] ?? null,
            'presion_sistolica' => $signosVitales['presion_sistolica'] ?? null,
            'presion_diastolica' => $signosVitales['presion_diastolica'] ?? null,
            'frecuencia_cardiaca' => $signosVitales['frecuencia_cardiaca'] ?? null
        ];
        
        return $this->update($id, $data);
    }
    
    public function duplicarConsulta($consultaId, $nuevaCitaId) {
        $consultaOriginal = $this->find($consultaId);
        if (!$consultaOriginal) {
            return false;
        }
        
        // Preparar datos para nueva consulta (sin ID ni timestamps)
        $nuevaConsulta = $consultaOriginal;
        unset($nuevaConsulta['id']);
        unset($nuevaConsulta['created_at']);
        unset($nuevaConsulta['updated_at']);
        unset($nuevaConsulta['numero_consulta']);
        
        $nuevaConsulta['cita_id'] = $nuevaCitaId;
        
        return $this->create($nuevaConsulta);
    }
    
    public function validarSignosVitales($datos) {
        $errores = [];
        
        // Validar temperatura
        if (isset($datos['temperatura']) && $datos['temperatura']) {
            if ($datos['temperatura'] < 30 || $datos['temperatura'] > 45) {
                $errores['temperatura'] = 'La temperatura debe estar entre 30°C y 45°C';
            }
        }
        
        // Validar presión arterial
        if (isset($datos['presion_sistolica']) && isset($datos['presion_diastolica'])) {
            if ($datos['presion_sistolica'] && $datos['presion_diastolica']) {
                if ($datos['presion_sistolica'] <= $datos['presion_diastolica']) {
                    $errores['presion'] = 'La presión sistólica debe ser mayor que la diastólica';
                }
                if ($datos['presion_sistolica'] < 70 || $datos['presion_sistolica'] > 250) {
                    $errores['presion_sistolica'] = 'La presión sistólica debe estar entre 70 y 250 mmHg';
                }
                if ($datos['presion_diastolica'] < 40 || $datos['presion_diastolica'] > 150) {
                    $errores['presion_diastolica'] = 'La presión diastólica debe estar entre 40 y 150 mmHg';
                }
            }
        }
        
        // Validar frecuencia cardíaca
        if (isset($datos['frecuencia_cardiaca']) && $datos['frecuencia_cardiaca']) {
            if ($datos['frecuencia_cardiaca'] < 30 || $datos['frecuencia_cardiaca'] > 250) {
                $errores['frecuencia_cardiaca'] = 'La frecuencia cardíaca debe estar entre 30 y 250 ppm';
            }
        }
        
        // Validar peso
        if (isset($datos['peso']) && $datos['peso']) {
            if ($datos['peso'] < 1 || $datos['peso'] > 500) {
                $errores['peso'] = 'El peso debe estar entre 1 y 500 kg';
            }
        }
        
        // Validar altura
        if (isset($datos['altura']) && $datos['altura']) {
            if ($datos['altura'] < 30 || $datos['altura'] > 250) {
                $errores['altura'] = 'La altura debe estar entre 30 y 250 cm';
            }
        }
        
        return $errores;
    }
    
    public function getReporteMensual($mes, $ano, $medicoId = null) {
        $sql = "SELECT 
                    DATE(c.fecha_cita) as fecha,
                    COUNT(*) as total_consultas,
                    COUNT(DISTINCT c.paciente_id) as pacientes_unicos,
                    AVG(con.temperatura) as temp_promedio,
                    AVG(con.presion_sistolica) as sistolica_promedio,
                    AVG(con.presion_diastolica) as diastolica_promedio
                FROM consultas con
                INNER JOIN citas c ON con.cita_id = c.id
                WHERE MONTH(c.fecha_cita) = ? AND YEAR(c.fecha_cita) = ?";
        
        $params = [$mes, $ano];
        
        if ($medicoId) {
            $sql .= " AND c.medico_id = ?";
            $params[] = $medicoId;
        }
        
        $sql .= " GROUP BY DATE(c.fecha_cita) ORDER BY fecha";
        
        return $this->db->fetchAll($sql, $params);
    }
}