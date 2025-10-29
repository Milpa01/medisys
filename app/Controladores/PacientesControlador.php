<?php
require_once APP_PATH . '/Modelos/Paciente.php';

class PacientesControlador extends BaseControlador {
    private $pacienteModel;
    
    public function __construct() {
        parent::__construct();
        $this->requireAuth();
        $this->pacienteModel = new Paciente();
    }
    
    public function index() {
        $page = (int)$this->getGet('page', 1);
        $search = $this->getGet('search', '');
        
        if ($search) {
            $pacientes = $this->pacienteModel->search($search);
            $pagination = [
                'data' => $pacientes,
                'total' => count($pacientes),
                'current_page' => 1,
                'last_page' => 1
            ];
        } else {
            $pacientes = $this->pacienteModel->getWithAge();
            $pagination = [
                'data' => $pacientes,
                'total' => count($pacientes),
                'current_page' => 1,
                'last_page' => 1
            ];
        }
        
        $this->data['title'] = 'Gestión de Pacientes';
        $this->data['pacientes'] = $pagination['data'];
        $this->data['pagination'] = $pagination;
        $this->data['search'] = $search;
        
        $this->render('pacientes/index');
    }
    
    public function crear() {
        if ($this->isPost()) {
            return $this->guardar();
        }
        
        $this->data['title'] = 'Registrar Paciente';
        $this->data['paciente'] = null;
        
        $this->render('pacientes/crear');
    }
    
    public function guardar() {
        $data = [
            'nombre' => $this->getPost('nombre'),
            'apellidos' => $this->getPost('apellidos'),
            'fecha_nacimiento' => $this->getPost('fecha_nacimiento'),
            'genero' => $this->getPost('genero'),
            'tipo_sangre' => $this->getPost('tipo_sangre'),
            'email' => $this->getPost('email'),
            'telefono' => $this->getPost('telefono'),
            'celular' => $this->getPost('celular'),
            'direccion' => $this->getPost('direccion'),
            'ciudad' => $this->getPost('ciudad'),
            'dpi' => $this->getPost('dpi'),
            'contacto_emergencia' => $this->getPost('contacto_emergencia'),
            'telefono_emergencia' => $this->getPost('telefono_emergencia'),
            'seguro_medico' => $this->getPost('seguro_medico'),
            'numero_seguro' => $this->getPost('numero_seguro'),
            'alergias' => $this->getPost('alergias'),
            'medicamentos_actuales' => $this->getPost('medicamentos_actuales'),
            'enfermedades_cronicas' => $this->getPost('enfermedades_cronicas'),
            'observaciones' => $this->getPost('observaciones'),
            'is_active' => 1
        ];
        
        // Validaciones básicas
        $errors = [];
        
        if (empty($data['nombre'])) {
            $errors[] = 'El nombre es requerido';
        }
        
        if (empty($data['apellidos'])) {
            $errors[] = 'Los apellidos son requeridos';
        }
        
        if (empty($data['fecha_nacimiento'])) {
            $errors[] = 'La fecha de nacimiento es requerida';
        } elseif ($data['fecha_nacimiento'] > date('Y-m-d')) {
            $errors[] = 'La fecha de nacimiento no puede ser futura';
        }
        
        if (empty($data['genero'])) {
            $errors[] = 'El género es requerido';
        }
        
        if ($data['email'] && !Util::validateEmail($data['email'])) {
            $errors[] = 'El email no es válido';
        } elseif ($data['email'] && $this->pacienteModel->existsEmail($data['email'])) {
            $errors[] = 'El email ya está registrado';
        }
        
        if ($data['dpi'] && !Util::validateDPI($data['dpi'])) {
            $errors[] = 'El DPI debe tener 13 dígitos';
        } elseif ($data['dpi'] && $this->pacienteModel->existsDPI($data['dpi'])) {
            $errors[] = 'El DPI ya está registrado';
        }
        
        if (!empty($errors)) {
            foreach ($errors as $error) {
                Flash::error($error);
            }
            $this->redirect('pacientes/crear');
        }
        
        try {
            $id = $this->pacienteModel->create($data);
            if ($id) {
                // Crear expediente médico
                $this->pacienteModel->createExpediente($id);
                
                Flash::success('Paciente registrado exitosamente');
                $this->redirect('pacientes/ver?id=' . $id);
            } else {
                Flash::error('Error al registrar el paciente');
                $this->redirect('pacientes/crear');
            }
        } catch (Exception $e) {
            Flash::error('Error al registrar el paciente: ' . $e->getMessage());
            $this->redirect('pacientes/crear');
        }
    }
    
    public function ver() {
        $id = $this->getGet('id');
        if (!$id) {
            Flash::error('ID de paciente no válido');
            $this->redirect('pacientes');
        }
        
        $paciente = $this->pacienteModel->find($id);
        if (!$paciente) {
            Flash::error('Paciente no encontrado');
            $this->redirect('pacientes');
        }
        
        // Obtener información adicional
        $historial = $this->pacienteModel->getHistorialMedico($id);
        $citasPendientes = $this->pacienteModel->getCitasPendientes($id);
        $expediente = $this->pacienteModel->getExpediente($id);
        
        // Calcular edad
        $paciente['edad'] = Util::calculateAge($paciente['fecha_nacimiento']);
        
        $this->data['title'] = 'Expediente de ' . $paciente['nombre'] . ' ' . $paciente['apellidos'];
        $this->data['paciente'] = $paciente;
        $this->data['historial'] = $historial;
        $this->data['citas_pendientes'] = $citasPendientes;
        $this->data['expediente'] = $expediente;
        
        $this->render('pacientes/ver');
    }
    
    public function editar() {
        $id = $this->getGet('id');
        if (!$id) {
            Flash::error('ID de paciente no válido');
            $this->redirect('pacientes');
        }
        
        if ($this->isPost()) {
            return $this->actualizar();
        }
        
        $paciente = $this->pacienteModel->find($id);
        if (!$paciente) {
            Flash::error('Paciente no encontrado');
            $this->redirect('pacientes');
        }
        
        $this->data['title'] = 'Editar Paciente';
        $this->data['paciente'] = $paciente;
        
        $this->render('pacientes/editar');
    }
    
    public function actualizar() {
        $id = $this->getPost('id');
        $data = [
            'nombre' => $this->getPost('nombre'),
            'apellidos' => $this->getPost('apellidos'),
            'fecha_nacimiento' => $this->getPost('fecha_nacimiento'),
            'genero' => $this->getPost('genero'),
            'tipo_sangre' => $this->getPost('tipo_sangre'),
            'email' => $this->getPost('email'),
            'telefono' => $this->getPost('telefono'),
            'celular' => $this->getPost('celular'),
            'direccion' => $this->getPost('direccion'),
            'ciudad' => $this->getPost('ciudad'),
            'dpi' => $this->getPost('dpi'),
            'contacto_emergencia' => $this->getPost('contacto_emergencia'),
            'telefono_emergencia' => $this->getPost('telefono_emergencia'),
            'seguro_medico' => $this->getPost('seguro_medico'),
            'numero_seguro' => $this->getPost('numero_seguro'),
            'alergias' => $this->getPost('alergias'),
            'medicamentos_actuales' => $this->getPost('medicamentos_actuales'),
            'enfermedades_cronicas' => $this->getPost('enfermedades_cronicas'),
            'observaciones' => $this->getPost('observaciones'),
            'is_active' => $this->getPost('is_active', 1)
        ];
        
        // Validaciones
        $errors = [];
        
        if (empty($data['nombre'])) {
            $errors[] = 'El nombre es requerido';
        }
        
        if (empty($data['apellidos'])) {
            $errors[] = 'Los apellidos son requeridos';
        }
        
        if ($data['email'] && !Util::validateEmail($data['email'])) {
            $errors[] = 'El email no es válido';
        } elseif ($data['email'] && $this->pacienteModel->existsEmail($data['email'], $id)) {
            $errors[] = 'El email ya está registrado';
        }
        
        if ($data['dpi'] && !Util::validateDPI($data['dpi'])) {
            $errors[] = 'El DPI debe tener 13 dígitos';
        } elseif ($data['dpi'] && $this->pacienteModel->existsDPI($data['dpi'], $id)) {
            $errors[] = 'El DPI ya está registrado';
        }
        
        if (!empty($errors)) {
            foreach ($errors as $error) {
                Flash::error($error);
            }
            $this->redirect('pacientes/editar?id=' . $id);
        }
        
        try {
            $result = $this->pacienteModel->update($id, $data);
            if ($result) {
                Flash::success('Paciente actualizado exitosamente');
                $this->redirect('pacientes/ver?id=' . $id);
            } else {
                Flash::error('Error al actualizar el paciente');
                $this->redirect('pacientes/editar?id=' . $id);
            }
        } catch (Exception $e) {
            Flash::error('Error al actualizar el paciente: ' . $e->getMessage());
            $this->redirect('pacientes/editar?id=' . $id);
        }
    }
    
    public function eliminar() {
        $id = $this->getGet('id');
        if (!$id) {
            Flash::error('ID de paciente no válido');
            $this->redirect('pacientes');
        }
        
        try {
            // Usar soft delete
            $result = $this->pacienteModel->softDelete($id);
            if ($result) {
                Flash::success('Paciente desactivado exitosamente');
            } else {
                Flash::error('Error al desactivar el paciente');
            }
        } catch (Exception $e) {
            Flash::error('Error al desactivar el paciente: ' . $e->getMessage());
        }
        
        $this->redirect('pacientes');
    }
}
?>