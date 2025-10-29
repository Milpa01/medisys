<?php
class Paciente extends BaseModelo {
    protected $table = 'pacientes';
    
    protected $fillable = [
        'codigo_paciente', 'nombre', 'apellidos', 'fecha_nacimiento', 'genero',
        'tipo_sangre', 'email', 'telefono', 'celular', 'direccion', 'ciudad',
        'dpi', 'contacto_emergencia', 'telefono_emergencia', 'seguro_medico',
        'numero_seguro', 'alergias', 'medicamentos_actuales', 'enfermedades_cronicas',
        'observaciones', 'imagen', 'is_active'
    ];
    
    public function getAllActive() {
        return $this->where(['is_active' => 1], 'nombre, apellidos');
    }
    
    public function findByCode($codigo) {
        return $this->findBy('codigo_paciente', $codigo);
    }
    
    public function findByDPI($dpi) {
        return $this->findBy('dpi', $dpi);
    }
    
    public function existsDPI($dpi, $excludeId = null) {
        $sql = "SELECT COUNT(*) as count FROM pacientes WHERE dpi = ? AND dpi IS NOT NULL";
        $params = [$dpi];
        
        if ($excludeId) {
            $sql .= " AND id != ?";
            $params[] = $excludeId;
        }
        
        $result = $this->db->fetch($sql, $params);
        return $result['count'] > 0;
    }
    
    public function existsEmail($email, $excludeId = null) {
        if (!$email) return false;
        
        $sql = "SELECT COUNT(*) as count FROM pacientes WHERE email = ?";
        $params = [$email];
        
        if ($excludeId) {
            $sql .= " AND id != ?";
            $params[] = $excludeId;
        }
        
        $result = $this->db->fetch($sql, $params);
        return $result['count'] > 0;
    }
    
    public function search($term) {
        $searchTerm = "%{$term}%";
        $sql = "SELECT * FROM pacientes 
                WHERE (nombre LIKE ? OR apellidos LIKE ? OR codigo_paciente LIKE ? OR dpi LIKE ?)
                AND is_active = 1
                ORDER BY nombre, apellidos
                LIMIT 20";
        
        return $this->db->fetchAll($sql, [$searchTerm, $searchTerm, $searchTerm, $searchTerm]);
    }
    
    public function getWithAge() {
        $sql = "SELECT *, 
                TIMESTAMPDIFF(YEAR, fecha_nacimiento, CURDATE()) as edad
                FROM pacientes 
                WHERE is_active = 1 
                ORDER BY created_at DESC";
        
        return $this->db->fetchAll($sql);
    }
    
    public function getHistorialMedico($pacienteId) {
        $sql = "SELECT c.fecha_cita, c.motivo_consulta, 
                       con.diagnostico_principal, con.plan_tratamiento,
                       CONCAT(u.nombre, ' ', u.apellidos) as medico_nombre,
                       e.nombre as especialidad
                FROM citas c
                LEFT JOIN consultas con ON c.id = con.cita_id
                INNER JOIN medicos m ON c.medico_id = m.id
                INNER JOIN usuarios u ON m.usuario_id = u.id
                INNER JOIN especialidades e ON m.especialidad_id = e.id
                WHERE c.paciente_id = ? AND c.estado = 'completada'
                ORDER BY c.fecha_cita DESC";
        
        return $this->db->fetchAll($sql, [$pacienteId]);
    }
    
    public function getCitasPendientes($pacienteId) {
        $sql = "SELECT c.*, 
                       CONCAT(u.nombre, ' ', u.apellidos) as medico_nombre,
                       e.nombre as especialidad
                FROM citas c
                INNER JOIN medicos m ON c.medico_id = m.id
                INNER JOIN usuarios u ON m.usuario_id = u.id
                INNER JOIN especialidades e ON m.especialidad_id = e.id
                WHERE c.paciente_id = ? 
                AND c.estado IN ('programada', 'confirmada')
                AND c.fecha_cita >= CURDATE()
                ORDER BY c.fecha_cita, c.hora_cita";
        
        return $this->db->fetchAll($sql, [$pacienteId]);
    }
    
    public function getEstadisticas() {
        $stats = [];
        
        // Total de pacientes activos
        $stats['total'] = $this->count(['is_active' => 1]);
        
        // Pacientes por género
        $generos = $this->db->fetchAll("
            SELECT genero, COUNT(*) as cantidad 
            FROM pacientes 
            WHERE is_active = 1 
            GROUP BY genero
        ");
        $stats['generos'] = array_column($generos, 'cantidad', 'genero');
        
        // Pacientes por grupo de edad
        $edades = $this->db->fetchAll("
            SELECT 
                CASE 
                    WHEN TIMESTAMPDIFF(YEAR, fecha_nacimiento, CURDATE()) < 18 THEN 'Menores'
                    WHEN TIMESTAMPDIFF(YEAR, fecha_nacimiento, CURDATE()) BETWEEN 18 AND 30 THEN 'Jóvenes'
                    WHEN TIMESTAMPDIFF(YEAR, fecha_nacimiento, CURDATE()) BETWEEN 31 AND 50 THEN 'Adultos'
                    ELSE 'Mayores'
                END as grupo_edad,
                COUNT(*) as cantidad
            FROM pacientes 
            WHERE is_active = 1 
            GROUP BY grupo_edad
        ");
        $stats['edades'] = array_column($edades, 'cantidad', 'grupo_edad');
        
        // Nuevos pacientes este mes
        $stats['nuevos_mes'] = $this->db->fetch("
            SELECT COUNT(*) as total 
            FROM pacientes 
            WHERE is_active = 1 
            AND MONTH(created_at) = MONTH(CURDATE()) 
            AND YEAR(created_at) = YEAR(CURDATE())
        ")['total'];
        
        return $stats;
    }
    
    public function create($data) {
        // Generar código si no se proporciona
        if (empty($data['codigo_paciente'])) {
            $data['codigo_paciente'] = $this->generateCode();
        }
        
        return parent::create($data);
    }
    
    private function generateCode() {
        // Obtener el último ID
        $lastId = $this->db->fetch("SELECT MAX(id) as max_id FROM pacientes")['max_id'] ?? 0;
        $nextId = $lastId + 1;
        
        return 'PAC' . str_pad($nextId, 6, '0', STR_PAD_LEFT);
    }
    
    public function getExpediente($pacienteId) {
        $sql = "SELECT e.*, p.nombre, p.apellidos
                FROM expedientes e
                INNER JOIN pacientes p ON e.paciente_id = p.id
                WHERE p.id = ?";
        
        return $this->db->fetch($sql, [$pacienteId]);
    }
    
    public function createExpediente($pacienteId, $data = []) {
        $expedienteData = array_merge([
            'paciente_id' => $pacienteId,
            'numero_expediente' => 'EXP' . str_pad($pacienteId, 6, '0', STR_PAD_LEFT)
        ], $data);
        
        $sql = "INSERT INTO expedientes (paciente_id, numero_expediente, created_at) 
                VALUES (?, ?, NOW())";
        
        return $this->db->execute($sql, [
            $expedienteData['paciente_id'],
            $expedienteData['numero_expediente']
        ]);
    }
}
?>