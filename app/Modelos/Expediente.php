<?php
class Expediente extends BaseModelo {
    protected $table = 'expedientes';
    
    protected $fillable = [
        'paciente_id', 'numero_expediente', 'antecedentes_familiares',
        'antecedentes_personales', 'antecedentes_quirurgicos', 'antecedentes_alergicos',
        'vacunas', 'grupo_sanguineo', 'factor_rh', 'peso_actual', 'altura_actual',
        'estado_civil', 'ocupacion', 'escolaridad', 'observaciones_generales'
    ];
    
    /**
     * Obtener todos los expedientes con información de pacientes
     */
    public function getAllWithPacientes() {
        $sql = "SELECT e.*, 
                       p.codigo_paciente, p.nombre, p.apellidos, p.fecha_nacimiento, 
                       p.genero, p.telefono, p.email, p.imagen,
                       TIMESTAMPDIFF(YEAR, p.fecha_nacimiento, CURDATE()) as edad
                FROM expedientes e
                INNER JOIN pacientes p ON e.paciente_id = p.id
                WHERE p.is_active = 1
                ORDER BY e.updated_at DESC";
        
        return $this->db->fetchAll($sql);
    }
    
    /**
     * Buscar expediente con información completa del paciente
     */
    public function findWithPaciente($id) {
        $sql = "SELECT e.*, 
                       p.codigo_paciente, p.nombre, p.apellidos, p.fecha_nacimiento,
                       p.genero, p.tipo_sangre, p.telefono, p.celular, p.email,
                       p.direccion, p.ciudad, p.dpi, p.contacto_emergencia,
                       p.telefono_emergencia, p.seguro_medico, p.numero_seguro,
                       p.alergias, p.medicamentos_actuales, p.enfermedades_cronicas,
                       p.imagen,
                       TIMESTAMPDIFF(YEAR, p.fecha_nacimiento, CURDATE()) as edad
                FROM expedientes e
                INNER JOIN pacientes p ON e.paciente_id = p.id
                WHERE e.id = ?";
        
        return $this->db->fetch($sql, [$id]);
    }
    
    /**
     * Buscar expediente por ID de paciente
     */
    public function findByPaciente($pacienteId) {
        $sql = "SELECT e.*, 
                       p.codigo_paciente, p.nombre, p.apellidos, p.fecha_nacimiento,
                       p.genero, p.tipo_sangre, p.telefono, p.email,
                       TIMESTAMPDIFF(YEAR, p.fecha_nacimiento, CURDATE()) as edad
                FROM expedientes e
                INNER JOIN pacientes p ON e.paciente_id = p.id
                WHERE e.paciente_id = ?";
        
        return $this->db->fetch($sql, [$pacienteId]);
    }
    
    /**
     * Buscar expediente por número de expediente
     */
    public function findByNumero($numeroExpediente) {
        $sql = "SELECT e.*, 
                       p.codigo_paciente, p.nombre, p.apellidos
                FROM expedientes e
                INNER JOIN pacientes p ON e.paciente_id = p.id
                WHERE e.numero_expediente = ?";
        
        return $this->db->fetch($sql, [$numeroExpediente]);
    }
    
    /**
     * Obtener historial completo de consultas del expediente
     */
    public function getHistorialConsultas($expedienteId) {
        $expediente = $this->find($expedienteId);
        if (!$expediente) return [];
        
        $sql = "SELECT con.*, 
                       cit.motivo_consulta, cit.fecha_cita, cit.hora_cita, cit.tipo_cita,
                       CONCAT(u.nombre, ' ', u.apellidos) as medico_nombre,
                       e.nombre as especialidad
                FROM consultas con
                INNER JOIN citas cit ON con.cita_id = cit.id
                INNER JOIN medicos m ON cit.medico_id = m.id
                INNER JOIN usuarios u ON m.usuario_id = u.id
                INNER JOIN especialidades e ON m.especialidad_id = e.id
                WHERE cit.paciente_id = ?
                ORDER BY cit.fecha_cita DESC, cit.hora_cita DESC";
        
        return $this->db->fetchAll($sql, [$expediente['paciente_id']]);
    }
    
    /**
     * Obtener prescripciones asociadas al expediente
     */
    public function getPrescripciones($expedienteId) {
        $expediente = $this->find($expedienteId);
        if (!$expediente) return [];
        
        $sql = "SELECT p.*, 
                       med.nombre_comercial, med.nombre_generico, med.presentacion,
                       con.numero_consulta, con.created_at as fecha_consulta,
                       CONCAT(u.nombre, ' ', u.apellidos) as medico_nombre
                FROM prescripciones p
                INNER JOIN medicamentos med ON p.medicamento_id = med.id
                INNER JOIN consultas con ON p.consulta_id = con.id
                INNER JOIN citas cit ON con.cita_id = cit.id
                INNER JOIN medicos m ON cit.medico_id = m.id
                INNER JOIN usuarios u ON m.usuario_id = u.id
                WHERE cit.paciente_id = ?
                ORDER BY p.created_at DESC";
        
        return $this->db->fetchAll($sql, [$expediente['paciente_id']]);
    }
    
    /**
     * Buscar expedientes (para búsqueda)
     */
    public function search($term) {
        $searchTerm = "%{$term}%";
        $sql = "SELECT e.*, 
                       p.codigo_paciente, p.nombre, p.apellidos, p.telefono,
                       TIMESTAMPDIFF(YEAR, p.fecha_nacimiento, CURDATE()) as edad
                FROM expedientes e
                INNER JOIN pacientes p ON e.paciente_id = p.id
                WHERE (p.nombre LIKE ? OR p.apellidos LIKE ? 
                       OR p.codigo_paciente LIKE ? OR e.numero_expediente LIKE ?
                       OR p.dpi LIKE ?)
                AND p.is_active = 1
                ORDER BY p.nombre, p.apellidos
                LIMIT 50";
        
        return $this->db->fetchAll($sql, [$searchTerm, $searchTerm, $searchTerm, $searchTerm, $searchTerm]);
    }
    
    /**
     * Obtener estadísticas de expedientes
     */
    public function getEstadisticas() {
        $stats = [];
        
        // Total de expedientes
        $stats['total'] = $this->db->fetch("
            SELECT COUNT(*) as total 
            FROM expedientes e
            INNER JOIN pacientes p ON e.paciente_id = p.id
            WHERE p.is_active = 1
        ")['total'];
        
        // Expedientes actualizados este mes
        $stats['actualizados_mes'] = $this->db->fetch("
            SELECT COUNT(*) as total 
            FROM expedientes
            WHERE MONTH(updated_at) = MONTH(CURDATE()) 
            AND YEAR(updated_at) = YEAR(CURDATE())
        ")['total'];
        
        // Expedientes con consultas recientes (último mes)
        $stats['con_consultas_recientes'] = $this->db->fetch("
            SELECT COUNT(DISTINCT e.id) as total
            FROM expedientes e
            INNER JOIN pacientes p ON e.paciente_id = p.id
            INNER JOIN citas c ON p.id = c.paciente_id
            WHERE c.fecha_cita >= DATE_SUB(CURDATE(), INTERVAL 1 MONTH)
            AND p.is_active = 1
        ")['total'];
        
        // Distribución por grupo sanguíneo
        $stats['grupos_sanguineos'] = $this->db->fetchAll("
            SELECT 
                CONCAT(grupo_sanguineo, factor_rh) as tipo,
                COUNT(*) as cantidad
            FROM expedientes
            WHERE grupo_sanguineo IS NOT NULL
            GROUP BY grupo_sanguineo, factor_rh
            ORDER BY cantidad DESC
        ");
        
        return $stats;
    }
    
    /**
     * Calcular IMC
     */
    public function calcularIMC($peso, $altura) {
        if ($peso <= 0 || $altura <= 0) return null;
        
        $alturaMetros = $altura / 100;
        $imc = $peso / ($alturaMetros * $alturaMetros);
        
        return round($imc, 2);
    }
    
    /**
     * Clasificar IMC
     */
    public function clasificarIMC($imc) {
        if ($imc === null) return 'No calculado';
        
        if ($imc < 18.5) return 'Bajo peso';
        if ($imc < 25) return 'Peso normal';
        if ($imc < 30) return 'Sobrepeso';
        if ($imc < 35) return 'Obesidad I';
        if ($imc < 40) return 'Obesidad II';
        return 'Obesidad III';
    }
    
    /**
     * Verificar si existe expediente para un paciente
     */
    public function existeParaPaciente($pacienteId) {
        $sql = "SELECT COUNT(*) as count FROM expedientes WHERE paciente_id = ?";
        $result = $this->db->fetch($sql, [$pacienteId]);
        return $result['count'] > 0;
    }
    
    /**
     * Crear expediente automáticamente
     */
    public function crearParaPaciente($pacienteId, $data = []) {
        if ($this->existeParaPaciente($pacienteId)) {
            return false;
        }
        
        $expedienteData = array_merge([
            'paciente_id' => $pacienteId,
            'numero_expediente' => 'EXP' . str_pad($pacienteId, 6, '0', STR_PAD_LEFT)
        ], $data);
        
        return $this->create($expedienteData);
    }
    
    /**
     * Actualizar signos vitales y medidas
     */
    public function actualizarMedidas($expedienteId, $peso, $altura) {
        $data = [
            'peso_actual' => $peso,
            'altura_actual' => $altura
        ];
        
        return $this->update($expedienteId, $data);
    }
    
    /**
     * Obtener citas próximas del paciente
     */
    public function getCitasProximas($expedienteId, $limite = 5) {
        $expediente = $this->find($expedienteId);
        if (!$expediente) return [];
        
        $sql = "SELECT cit.*, 
                       CONCAT(u.nombre, ' ', u.apellidos) as medico_nombre,
                       e.nombre as especialidad,
                       m.consultorio
                FROM citas cit
                INNER JOIN medicos m ON cit.medico_id = m.id
                INNER JOIN usuarios u ON m.usuario_id = u.id
                INNER JOIN especialidades e ON m.especialidad_id = e.id
                WHERE cit.paciente_id = ?
                AND cit.fecha_cita >= CURDATE()
                AND cit.estado IN ('programada', 'confirmada')
                ORDER BY cit.fecha_cita, cit.hora_cita
                LIMIT ?";
        
        return $this->db->fetchAll($sql, [$expediente['paciente_id'], $limite]);
    }
}
?>