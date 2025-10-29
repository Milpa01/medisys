<?php
require_once APP_PATH . '/Modelos/Medico.php';
require_once APP_PATH . '/Modelos/Usuario.php';

class MedicosControlador extends BaseControlador {
    private $medicoModel;
    private $usuarioModel;
    
    public function __construct() {
        parent::__construct();
        $this->requireAuth();
        
        // Solo administradores pueden gestionar médicos
        if (!Auth::hasRole('administrador')) {
            Flash::error('No tienes permisos para acceder a esta sección');
            Router::redirect('dashboard');
        }
        
        $this->medicoModel = new Medico();
        $this->usuarioModel = new Usuario();
    }
    
    public function index() {
        $search = $this->getGet('search', '');
        
        if ($search) {
            // Búsqueda personalizada
            $sql = "SELECT m.*, 
                           u.nombre, u.apellidos, u.email, u.telefono, u.is_active as usuario_activo,
                           e.nombre as especialidad_nombre
                    FROM medicos m
                    INNER JOIN usuarios u ON m.usuario_id = u.id
                    INNER JOIN especialidades e ON m.especialidad_id = e.id
                    WHERE u.nombre LIKE ? OR u.apellidos LIKE ? 
                    OR m.cedula_profesional LIKE ? OR e.nombre LIKE ?
                    ORDER BY u.nombre, u.apellidos";
            
            $searchTerm = "%{$search}%";
            $medicos = $this->medicoModel->query($sql, [$searchTerm, $searchTerm, $searchTerm, $searchTerm]);
        } else {
            $medicos = $this->medicoModel->getAllWithInfo();
        }
        
        // Obtener estadísticas
        $stats = $this->medicoModel->getEstadisticas();
        
        $this->data['title'] = 'Gestión de Médicos';
        $this->data['medicos'] = $medicos;
        $this->data['search'] = $search;
        $this->data['stats'] = $stats;
        
        $this->render('medicos/index');
    }
    
    public function crear() {
        if ($this->isPost()) {
            return $this->guardar();
        }
        
        // Obtener usuarios sin asignar como médicos
        $usuariosMedicos = $this->usuarioModel->getByRole('medico');
        $medicosAsignados = $this->medicoModel->all();
        $medicosIds = array_column($medicosAsignados, 'usuario_id');
        
        $usuariosDisponibles = array_filter($usuariosMedicos, function($usuario) use ($medicosIds) {
            return !in_array($usuario['id'], $medicosIds);
        });
        
        // Obtener especialidades
        $db = Database::getInstance();
        $especialidades = $db->fetchAll("SELECT * FROM especialidades WHERE is_active = 1 ORDER BY nombre");
        
        $this->data['title'] = 'Registrar Médico';
        $this->data['usuarios_disponibles'] = $usuariosDisponibles;
        $this->data['especialidades'] = $especialidades;
        $this->data['medico'] = null;
        
        $this->render('medicos/crear');
    }
    
    public function guardar() {
        $data = [
            'usuario_id' => $this->getPost('usuario_id'),
            'cedula_profesional' => $this->getPost('cedula_profesional'),
            'especialidad_id' => $this->getPost('especialidad_id'),
            'experiencia_anos' => $this->getPost('experiencia_anos', 0),
            'consultorio' => $this->getPost('consultorio'),
            'horario_inicio' => $this->getPost('horario_inicio', '08:00'),
            'horario_fin' => $this->getPost('horario_fin', '17:00'),
            'dias_atencion' => implode(',', $this->getPost('dias_atencion', [])),
            'costo_consulta' => $this->getPost('costo_consulta', 0),
            'observaciones' => $this->getPost('observaciones'),
            'is_active' => 1
        ];
        
        // Validaciones
        $errors = [];
        
        if (empty($data['usuario_id'])) {
            $errors[] = 'Debe seleccionar un usuario';
        }
        
        if (empty($data['cedula_profesional'])) {
            $errors[] = 'La cédula profesional es requerida';
        } elseif ($this->medicoModel->existsCedula($data['cedula_profesional'])) {
            $errors[] = 'La cédula profesional ya está registrada';
        }
        
        if (empty($data['especialidad_id'])) {
            $errors[] = 'Debe seleccionar una especialidad';
        }
        
        if ($data['horario_inicio'] >= $data['horario_fin']) {
            $errors[] = 'La hora de inicio debe ser menor que la hora de fin';
        }
        
        if (empty($data['dias_atencion'])) {
            $errors[] = 'Debe seleccionar al menos un día de atención';
        }
        
        if (!empty($errors)) {
            foreach ($errors as $error) {
                Flash::error($error);
            }
            $this->redirect('medicos/crear');
        }
        
        try {
            $id = $this->medicoModel->create($data);
            if ($id) {
                Flash::success('Médico registrado exitosamente');
                $this->redirect('medicos');
            } else {
                Flash::error('Error al registrar el médico');
                $this->redirect('medicos/crear');
            }
        } catch (Exception $e) {
            Flash::error('Error al registrar el médico: ' . $e->getMessage());
            $this->redirect('medicos/crear');
        }
    }
    
    public function ver() {
        $id = $this->getGet('id');
        if (!$id) {
            Flash::error('ID de médico no válido');
            $this->redirect('medicos');
        }
        
        $medico = $this->medicoModel->findWithInfo($id);
        if (!$medico) {
            Flash::error('Médico no encontrado');
            $this->redirect('medicos');
        }
        
        // Obtener citas próximas
        $citasProximas = $this->medicoModel->getCitasProximas($id);
        
        // Obtener estadísticas del médico
        $stats = $this->medicoModel->getEstadisticas($id);
        
        // Obtener horarios
        $horarios = $this->medicoModel->getHorarioCompleto($id);
        
        $this->data['title'] = 'Dr. ' . $medico['nombre'] . ' ' . $medico['apellidos'];
        $this->data['medico'] = $medico;
        $this->data['citas_proximas'] = $citasProximas;
        $this->data['stats'] = $stats;
        $this->data['horarios'] = $horarios;
        
        $this->render('medicos/ver');
    }
    
    public function editar() {
        $id = $this->getGet('id');
        if (!$id) {
            Flash::error('ID de médico no válido');
            $this->redirect('medicos');
        }
        
        if ($this->isPost()) {
            return $this->actualizar();
        }
        
        $medico = $this->medicoModel->findWithInfo($id);
        if (!$medico) {
            Flash::error('Médico no encontrado');
            $this->redirect('medicos');
        }
        
        // Obtener especialidades
        $db = Database::getInstance();
        $especialidades = $db->fetchAll("SELECT * FROM especialidades WHERE is_active = 1 ORDER BY nombre");
        
        // Convertir días de atención a array
        $medico['dias_atencion_array'] = explode(',', $medico['dias_atencion']);
        
        $this->data['title'] = 'Editar Médico';
        $this->data['medico'] = $medico;
        $this->data['especialidades'] = $especialidades;
        
        $this->render('medicos/editar');
    }
    
    public function actualizar() {
        $id = $this->getPost('id');
        $data = [
            'cedula_profesional' => $this->getPost('cedula_profesional'),
            'especialidad_id' => $this->getPost('especialidad_id'),
            'experiencia_anos' => $this->getPost('experiencia_anos', 0),
            'consultorio' => $this->getPost('consultorio'),
            'horario_inicio' => $this->getPost('horario_inicio', '08:00'),
            'horario_fin' => $this->getPost('horario_fin', '17:00'),
            'dias_atencion' => implode(',', $this->getPost('dias_atencion', [])),
            'costo_consulta' => $this->getPost('costo_consulta', 0),
            'observaciones' => $this->getPost('observaciones'),
            'is_active' => $this->getPost('is_active', 1)
        ];
        
        // Validaciones
        $errors = [];
        
        if (empty($data['cedula_profesional'])) {
            $errors[] = 'La cédula profesional es requerida';
        } elseif ($this->medicoModel->existsCedula($data['cedula_profesional'], $id)) {
            $errors[] = 'La cédula profesional ya está registrada';
        }
        
        if (empty($data['especialidad_id'])) {
            $errors[] = 'Debe seleccionar una especialidad';
        }
        
        if ($data['horario_inicio'] >= $data['horario_fin']) {
            $errors[] = 'La hora de inicio debe ser menor que la hora de fin';
        }
        
        if (empty($data['dias_atencion'])) {
            $errors[] = 'Debe seleccionar al menos un día de atención';
        }
        
        if (!empty($errors)) {
            foreach ($errors as $error) {
                Flash::error($error);
            }
            $this->redirect('medicos/editar?id=' . $id);
        }
        
        try {
            $result = $this->medicoModel->update($id, $data);
            if ($result) {
                Flash::success('Médico actualizado exitosamente');
                $this->redirect('medicos/ver?id=' . $id);
            } else {
                Flash::error('Error al actualizar el médico');
                $this->redirect('medicos/editar?id=' . $id);
            }
        } catch (Exception $e) {
            Flash::error('Error al actualizar el médico: ' . $e->getMessage());
            $this->redirect('medicos/editar?id=' . $id);
        }
    }
    
    public function eliminar() {
        $id = $this->getGet('id');
        if (!$id) {
            Flash::error('ID de médico no válido');
            $this->redirect('medicos');
        }
        
        try {
            // Verificar si tiene citas pendientes
            $citasPendientes = $this->medicoModel->getCitasProximas($id);
            if (!empty($citasPendientes)) {
                Flash::warning('No se puede desactivar el médico porque tiene citas programadas');
                $this->redirect('medicos');
            }
            
            // Desactivar médico
            $result = $this->medicoModel->update($id, ['is_active' => 0]);
            if ($result) {
                Flash::success('Médico desactivado exitosamente');
            } else {
                Flash::error('Error al desactivar el médico');
            }
        } catch (Exception $e) {
            Flash::error('Error al desactivar el médico: ' . $e->getMessage());
        }
        
        $this->redirect('medicos');
    }
    
    public function horarios() {
        $id = $this->getGet('id');
        if (!$id) {
            Flash::error('ID de médico no válido');
            $this->redirect('medicos');
        }
        
        $medico = $this->medicoModel->findWithInfo($id);
        if (!$medico) {
            Flash::error('Médico no encontrado');
            $this->redirect('medicos');
        }
        
        if ($this->isPost()) {
            return $this->actualizarHorarios();
        }
        
        $horarios = $this->medicoModel->getHorarioCompleto($id);
        
        $this->data['title'] = 'Horarios - Dr. ' . $medico['nombre'];
        $this->data['medico'] = $medico;
        $this->data['horarios'] = $horarios;
        
        $this->render('medicos/horarios');
    }
    
    private function actualizarHorarios() {
        $id = $this->getPost('medico_id');
        $data = [
            'horario_inicio' => $this->getPost('horario_inicio'),
            'horario_fin' => $this->getPost('horario_fin'),
            'dias_atencion' => implode(',', $this->getPost('dias_atencion', []))
        ];
        
        try {
            $result = $this->medicoModel->update($id, $data);
            if ($result) {
                Flash::success('Horarios actualizados exitosamente');
            } else {
                Flash::error('Error al actualizar horarios');
            }
        } catch (Exception $e) {
            Flash::error('Error: ' . $e->getMessage());
        }
        
        $this->redirect('medicos/horarios?id=' . $id);
    }
}
?>