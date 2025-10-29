<?php
require_once APP_PATH . '/Modelos/Cita.php';
require_once APP_PATH . '/Modelos/Paciente.php';
require_once APP_PATH . '/Modelos/Medico.php';

class CitasControlador extends BaseControlador {
    private $citaModel;
    private $pacienteModel;
    private $medicoModel;
    
    public function __construct() {
        parent::__construct();
        $this->requireAuth();
        
        $this->citaModel = new Cita();
        $this->pacienteModel = new Paciente();
        $this->medicoModel = new Medico();
    }
    
    public function index() {
        $search = $this->getGet('search', '');
        $fecha = $this->getGet('fecha', '');
        $estado = $this->getGet('estado', '');
        
        if ($search) {
            $citas = $this->citaModel->searchCitas($search);
        } elseif ($fecha) {
            $citas = $this->citaModel->getCitasDelDia($fecha);
        } else {
            // Obtener citas recientes con filtros
            $sql = "SELECT c.*, 
                           CONCAT(p.nombre, ' ', p.apellidos) as paciente_nombre,
                           p.telefono as paciente_telefono,
                           p.codigo_paciente,
                           CONCAT(u.nombre, ' ', u.apellidos) as medico_nombre,
                           e.nombre as especialidad,
                           m.consultorio
                    FROM citas c
                    INNER JOIN pacientes p ON c.paciente_id = p.id
                    INNER JOIN medicos m ON c.medico_id = m.id
                    INNER JOIN usuarios u ON m.usuario_id = u.id
                    INNER JOIN especialidades e ON m.especialidad_id = e.id";
            
            $where = [];
            $params = [];
            
            if ($estado) {
                $where[] = "c.estado = ?";
                $params[] = $estado;
            }
            
            if (!empty($where)) {
                $sql .= " WHERE " . implode(' AND ', $where);
            }
            
            $sql .= " ORDER BY c.fecha_cita DESC, c.hora_cita DESC LIMIT 50";
            
            $citas = $this->citaModel->query($sql, $params);
        }
        
        // Filtrar por rol de usuario
        if (Auth::hasRole('medico')) {
            // Los médicos solo ven sus propias citas
            $medicoInfo = $this->medicoModel->findByUsuario(Auth::id());
            if ($medicoInfo) {
                $citas = array_filter($citas, function($cita) use ($medicoInfo) {
                    return $cita['medico_id'] == $medicoInfo['id'];
                });
            }
        }
        
        // Obtener estadísticas
        $stats = $this->citaModel->getEstadisticas();
        
        $this->data['title'] = 'Gestión de Citas';
        $this->data['citas'] = $citas;
        $this->data['search'] = $search;
        $this->data['fecha'] = $fecha;
        $this->data['estado'] = $estado;
        $this->data['stats'] = $stats;
        
        $this->render('citas/index');
    }
    
    public function crear() {
        // Solo secretarios y administradores pueden crear citas
        if (!Auth::hasRole('administrador') && !Auth::hasRole('secretario')) {
            Flash::error('No tienes permisos para crear citas');
            $this->redirect('citas');
        }
        
        if ($this->isPost()) {
            return $this->guardar();
        }
        
        $pacienteId = $this->getGet('paciente_id');
        $medicoId = $this->getGet('medico_id');
        
        // Obtener pacientes activos
        $pacientes = $this->pacienteModel->getAllActive();
        
        // Obtener médicos activos
        $medicos = $this->medicoModel->getAllWithInfo();
        $medicosActivos = array_filter($medicos, function($medico) {
            return $medico['usuario_activo'] && $medico['is_active'];
        });
        
        $this->data['title'] = 'Nueva Cita';
        $this->data['pacientes'] = $pacientes;
        $this->data['medicos'] = $medicosActivos;
        $this->data['paciente_seleccionado'] = $pacienteId;
        $this->data['medico_seleccionado'] = $medicoId;
        
        $this->render('citas/crear');
    }
    
    public function guardar() {
        $data = [
            'paciente_id' => $this->getPost('paciente_id'),
            'medico_id' => $this->getPost('medico_id'),
            'usuario_registro_id' => Auth::id(),
            'fecha_cita' => $this->getPost('fecha_cita'),
            'hora_cita' => $this->getPost('hora_cita'),
            'duracion_minutos' => $this->getPost('duracion_minutos', 30),
            'motivo_consulta' => $this->getPost('motivo_consulta'),
            'tipo_cita' => $this->getPost('tipo_cita', 'primera_vez'),
            'notas' => $this->getPost('notas'),
            'estado' => 'programada'
        ];
        
        // Validaciones
        $errors = [];
        
        if (empty($data['paciente_id'])) {
            $errors[] = 'Debe seleccionar un paciente';
        }
        
        if (empty($data['medico_id'])) {
            $errors[] = 'Debe seleccionar un médico';
        }
        
        if (empty($data['fecha_cita'])) {
            $errors[] = 'La fecha de la cita es requerida';
        } elseif ($data['fecha_cita'] < date('Y-m-d')) {
            $errors[] = 'La fecha de la cita no puede ser anterior a hoy';
        }
        
        if (empty($data['hora_cita'])) {
            $errors[] = 'La hora de la cita es requerida';
        }
        
        if (empty($data['motivo_consulta'])) {
            $errors[] = 'El motivo de consulta es requerido';
        }
        
        // Verificar disponibilidad del médico
        if ($data['medico_id'] && $data['fecha_cita'] && $data['hora_cita']) {
            if (!$this->citaModel->verificarDisponibilidad($data['medico_id'], $data['fecha_cita'], $data['hora_cita'])) {
                $errors[] = 'El médico no está disponible en esa fecha y hora';
            }
        }
        
        if (!empty($errors)) {
            foreach ($errors as $error) {
                Flash::error($error);
            }
            $this->redirect('citas/crear');
        }
        
        try {
            $id = $this->citaModel->create($data);
            if ($id) {
                Flash::success('Cita programada exitosamente');
                $this->redirect('citas/ver?id=' . $id);
            } else {
                Flash::error('Error al programar la cita');
                $this->redirect('citas/crear');
            }
        } catch (Exception $e) {
            Flash::error('Error al programar la cita: ' . $e->getMessage());
            $this->redirect('citas/crear');
        }
    }
    
    public function ver() {
        $id = $this->getGet('id');
        if (!$id) {
            Flash::error('ID de cita no válido');
            $this->redirect('citas');
        }
        
        $cita = $this->citaModel->findWithInfo($id);
        if (!$cita) {
            Flash::error('Cita no encontrada');
            $this->redirect('citas');
        }
        
        // Verificar permisos
        if (Auth::hasRole('medico')) {
            $medicoInfo = $this->medicoModel->findByUsuario(Auth::id());
            if (!$medicoInfo || $cita['medico_id'] != $medicoInfo['id']) {
                Flash::error('No tienes permisos para ver esta cita');
                $this->redirect('citas');
            }
        }
        
        $this->data['title'] = 'Cita ' . $cita['codigo_cita'];
        $this->data['cita'] = $cita;
        
        $this->render('citas/ver');
    }
    
    public function editar() {
        // Solo secretarios y administradores pueden editar citas
        if (!Auth::hasRole('administrador') && !Auth::hasRole('secretario')) {
            Flash::error('No tienes permisos para editar citas');
            $this->redirect('citas');
        }
        
        $id = $this->getGet('id');
        if (!$id) {
            Flash::error('ID de cita no válido');
            $this->redirect('citas');
        }
        
        if ($this->isPost()) {
            return $this->actualizar();
        }
        
        $cita = $this->citaModel->findWithInfo($id);
        if (!$cita) {
            Flash::error('Cita no encontrada');
            $this->redirect('citas');
        }
        
        // No permitir editar citas completadas o canceladas
        if (in_array($cita['estado'], ['completada', 'cancelada'])) {
            Flash::error('No se puede editar una cita ' . $cita['estado']);
            $this->redirect('citas/ver?id=' . $id);
        }
        
        // Obtener pacientes y médicos
        $pacientes = $this->pacienteModel->getAllActive();
        $medicos = $this->medicoModel->getAllWithInfo();
        
        $this->data['title'] = 'Editar Cita';
        $this->data['cita'] = $cita;
        $this->data['pacientes'] = $pacientes;
        $this->data['medicos'] = $medicos;
        
        $this->render('citas/editar');
    }
    
    public function actualizar() {
        $id = $this->getPost('id');
        $data = [
            'fecha_cita' => $this->getPost('fecha_cita'),
            'hora_cita' => $this->getPost('hora_cita'),
            'duracion_minutos' => $this->getPost('duracion_minutos'),
            'motivo_consulta' => $this->getPost('motivo_consulta'),
            'tipo_cita' => $this->getPost('tipo_cita'),
            'notas' => $this->getPost('notas'),
            'estado' => $this->getPost('estado')
        ];
        
        // Validaciones básicas
        $errors = [];
        
        if (empty($data['fecha_cita'])) {
            $errors[] = 'La fecha de la cita es requerida';
        }
        
        if (empty($data['hora_cita'])) {
            $errors[] = 'La hora de la cita es requerida';
        }
        
        if (empty($data['motivo_consulta'])) {
            $errors[] = 'El motivo de consulta es requerido';
        }
        
        if (!empty($errors)) {
            foreach ($errors as $error) {
                Flash::error($error);
            }
            $this->redirect('citas/editar?id=' . $id);
        }
        
        try {
            $result = $this->citaModel->update($id, $data);
            if ($result) {
                Flash::success('Cita actualizada exitosamente');
                $this->redirect('citas/ver?id=' . $id);
            } else {
                Flash::error('Error al actualizar la cita');
                $this->redirect('citas/editar?id=' . $id);
            }
        } catch (Exception $e) {
            Flash::error('Error al actualizar la cita: ' . $e->getMessage());
            $this->redirect('citas/editar?id=' . $id);
        }
    }
    
    public function cambiarEstado() {
        $id = $this->getGet('id');
        $estado = $this->getGet('estado');
        
        if (!$id || !$estado) {
            Flash::error('Parámetros inválidos');
            $this->redirect('citas');
        }
        
        try {
            $result = $this->citaModel->cambiarEstado($id, $estado);
            if ($result) {
                Flash::success('Estado de la cita actualizado');
            } else {
                Flash::error('Error al cambiar el estado');
            }
        } catch (Exception $e) {
            Flash::error('Error: ' . $e->getMessage());
        }
        
        $this->redirect('citas/ver?id=' . $id);
    }
    
    public function calendario() {
        $mes = $this->getGet('mes', date('m'));
        $ano = $this->getGet('ano', date('Y'));
        
        $citas = $this->citaModel->getCalendario($mes, $ano);
        
        // Filtrar por médico si es necesario
        if (Auth::hasRole('medico')) {
            $medicoInfo = $this->medicoModel->findByUsuario(Auth::id());
            if ($medicoInfo) {
                $citas = array_filter($citas, function($cita) use ($medicoInfo) {
                    return $cita['medico_id'] == $medicoInfo['id'];
                });
            }
        }
        
        // Organizar citas por día
        $citasPorDia = [];
        foreach ($citas as $cita) {
            $dia = date('j', strtotime($cita['fecha_cita']));
            if (!isset($citasPorDia[$dia])) {
                $citasPorDia[$dia] = [];
            }
            $citasPorDia[$dia][] = $cita;
        }
        
        $this->data['title'] = 'Calendario de Citas';
        $this->data['mes'] = $mes;
        $this->data['ano'] = $ano;
        $this->data['citas_por_dia'] = $citasPorDia;
        
        $this->render('citas/calendario');
    }
    
    public function agenda() {
        // Vista específica para médicos
        $this->requireAuth();
        
        if (!Auth::hasRole('medico')) {
            Flash::error('Solo los médicos pueden acceder a la agenda');
            $this->redirect('dashboard');
        }
        
        $medicoInfo = $this->medicoModel->findByUsuario(Auth::id());
        if (!$medicoInfo) {
            Flash::error('No se encontró información del médico');
            $this->redirect('dashboard');
        }
        
        $fecha = $this->getGet('fecha', date('Y-m-d'));
        
        // Obtener citas del médico para la fecha
        $citas = $this->citaModel->getCitasPorMedico($medicoInfo['id'], $fecha, $fecha);
        
        $this->data['title'] = 'Mi Agenda - ' . Util::formatDate($fecha);
        $this->data['fecha'] = $fecha;
        $this->data['citas'] = $citas;
        $this->data['medico'] = $medicoInfo;
        
        $this->render('citas/agenda');
    }
    
    // AJAX endpoints
    public function obtenerHorariosDisponibles() {
        header('Content-Type: application/json');
        
        $medicoId = $this->getGet('medico_id');
        $fecha = $this->getGet('fecha');
        
        if (!$medicoId || !$fecha) {
            echo json_encode(['error' => 'Parámetros faltantes']);
            return;
        }
        
        // Obtener horarios ocupados
        $horariosOcupados = $this->citaModel->getHorariosOcupados($medicoId, $fecha);
        
        // Obtener información del médico
        $medico = $this->medicoModel->findWithInfo($medicoId);
        
        if (!$medico) {
            echo json_encode(['error' => 'Médico no encontrado']);
            return;
        }
        
        // Generar horarios disponibles
        $inicio = strtotime($medico['horario_inicio']);
        $fin = strtotime($medico['horario_fin']);
        $duracion = 30 * 60; // 30 minutos en segundos
        
        $horariosDisponibles = [];
        for ($hora = $inicio; $hora < $fin; $hora += $duracion) {
            $horaFormato = date('H:i:s', $hora);
            if (!in_array($horaFormato, $horariosOcupados)) {
                $horariosDisponibles[] = [
                    'valor' => $horaFormato,
                    'texto' => date('H:i', $hora)
                ];
            }
        }
        
        echo json_encode($horariosDisponibles);
    }
}
?>