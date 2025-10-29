<?php
require_once APP_PATH . '/Modelos/Usuario.php';

class InicioControlador extends BaseControlador {
    
    public function index() {
        $this->requireAuth();
        
        $db = Database::getInstance();
        
        // Obtener estadísticas generales
        $stats = [
            'pacientes' => $db->fetch("SELECT COUNT(*) as total FROM pacientes WHERE is_active = 1")['total'] ?? 0,
            'citas_hoy' => $db->fetch("SELECT COUNT(*) as total FROM citas WHERE fecha_cita = CURDATE() AND estado != 'cancelada'")['total'] ?? 0,
            'medicos' => $db->fetch("SELECT COUNT(*) as total FROM medicos m INNER JOIN usuarios u ON m.usuario_id = u.id WHERE u.is_active = 1")['total'] ?? 0,
            'consultas_mes' => $db->fetch("SELECT COUNT(*) as total FROM consultas WHERE MONTH(created_at) = MONTH(CURDATE()) AND YEAR(created_at) = YEAR(CURDATE())")['total'] ?? 0
        ];
        
        // Obtener citas del día
        $citasHoy = $db->fetchAll("
            SELECT c.*, 
                   CONCAT(p.nombre, ' ', p.apellidos) as paciente_nombre,
                   CONCAT(u.nombre, ' ', u.apellidos) as medico_nombre,
                   e.nombre as especialidad
            FROM citas c
            INNER JOIN pacientes p ON c.paciente_id = p.id
            INNER JOIN medicos m ON c.medico_id = m.id
            INNER JOIN usuarios u ON m.usuario_id = u.id
            INNER JOIN especialidades e ON m.especialidad_id = e.id
            WHERE c.fecha_cita = CURDATE() 
            AND c.estado != 'cancelada'
            ORDER BY c.hora_cita ASC
            LIMIT 10
        ");
        
        // Obtener pacientes recientes
        $pacientesRecientes = $db->fetchAll("
            SELECT id, codigo_paciente, nombre, apellidos, telefono, created_at
            FROM pacientes 
            WHERE is_active = 1
            ORDER BY created_at DESC 
            LIMIT 5
        ");
        
        $this->data['title'] = 'Dashboard - ' . APP_NAME;
        $this->data['stats'] = $stats;
        $this->data['citas_hoy'] = $citasHoy;
        $this->data['pacientes_recientes'] = $pacientesRecientes;
        
        $this->render('dashboard/inicio');
    }
}
?>