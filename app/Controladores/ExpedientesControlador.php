<?php
require_once APP_PATH . '/Modelos/Expediente.php';
require_once APP_PATH . '/Modelos/Paciente.php';

class ExpedientesControlador extends BaseControlador {
    private $expedienteModel;
    private $pacienteModel;
    
    public function __construct() {
        parent::__construct();
        $this->requireAuth();
        
        $this->expedienteModel = new Expediente();
        $this->pacienteModel = new Paciente();
    }
    
    /**
     * Listar todos los expedientes
     */
    public function index() {
        $search = $this->getGet('search', '');
        
        if ($search) {
            $expedientes = $this->expedienteModel->search($search);
        } else {
            $expedientes = $this->expedienteModel->getAllWithPacientes();
        }
        
        // Obtener estadísticas
        $stats = $this->expedienteModel->getEstadisticas();
        
        $this->data['title'] = 'Gestión de Expedientes Médicos';
        $this->data['expedientes'] = $expedientes;
        $this->data['search'] = $search;
        $this->data['stats'] = $stats;
        
        $this->render('expedientes/index');
    }
    
    /**
     * Ver expediente completo
     */
    public function ver() {
        $id = $this->getGet('id');
        if (!$id) {
            Flash::error('ID de expediente no válido');
            $this->redirect('expedientes');
        }
        
        $expediente = $this->expedienteModel->findWithPaciente($id);
        if (!$expediente) {
            Flash::error('Expediente no encontrado');
            $this->redirect('expedientes');
        }
        
        // Calcular IMC si hay datos
        if ($expediente['peso_actual'] && $expediente['altura_actual']) {
            $expediente['imc_calculado'] = $this->expedienteModel->calcularIMC(
                $expediente['peso_actual'], 
                $expediente['altura_actual']
            );
            $expediente['clasificacion_imc'] = $this->expedienteModel->clasificarIMC($expediente['imc_calculado']);
        }
        
        // Obtener historial de consultas
        $historialConsultas = $this->expedienteModel->getHistorialConsultas($id);
        
        // Obtener prescripciones
        $prescripciones = $this->expedienteModel->getPrescripciones($id);
        
        // Obtener citas próximas
        $citasProximas = $this->expedienteModel->getCitasProximas($id);
        
        $this->data['title'] = 'Expediente: ' . $expediente['nombre'] . ' ' . $expediente['apellidos'];
        $this->data['expediente'] = $expediente;
        $this->data['historial_consultas'] = $historialConsultas;
        $this->data['prescripciones'] = $prescripciones;
        $this->data['citas_proximas'] = $citasProximas;
        
        $this->render('expedientes/ver');
    }
    
    /**
     * Editar expediente
     */
    public function editar() {
        $id = $this->getGet('id');
        if (!$id) {
            Flash::error('ID de expediente no válido');
            $this->redirect('expedientes');
        }
        
        if ($this->isPost()) {
            return $this->actualizar();
        }
        
        $expediente = $this->expedienteModel->findWithPaciente($id);
        if (!$expediente) {
            Flash::error('Expediente no encontrado');
            $this->redirect('expedientes');
        }
        
        $this->data['title'] = 'Editar Expediente';
        $this->data['expediente'] = $expediente;
        
        $this->render('expedientes/editar');
    }
    
    /**
     * Actualizar expediente
     */
    public function actualizar() {
        $id = $this->getPost('id');
        
        $data = [
            'antecedentes_familiares' => $this->getPost('antecedentes_familiares'),
            'antecedentes_personales' => $this->getPost('antecedentes_personales'),
            'antecedentes_quirurgicos' => $this->getPost('antecedentes_quirurgicos'),
            'antecedentes_alergicos' => $this->getPost('antecedentes_alergicos'),
            'vacunas' => $this->getPost('vacunas'),
            'grupo_sanguineo' => $this->getPost('grupo_sanguineo'),
            'factor_rh' => $this->getPost('factor_rh'),
            'peso_actual' => $this->getPost('peso_actual'),
            'altura_actual' => $this->getPost('altura_actual'),
            'estado_civil' => $this->getPost('estado_civil'),
            'ocupacion' => $this->getPost('ocupacion'),
            'escolaridad' => $this->getPost('escolaridad'),
            'observaciones_generales' => $this->getPost('observaciones_generales')
        ];
        
        // Validaciones
        $errors = [];
        
        if ($data['peso_actual'] && ($data['peso_actual'] < 0 || $data['peso_actual'] > 500)) {
            $errors[] = 'El peso debe estar entre 0 y 500 kg';
        }
        
        if ($data['altura_actual'] && ($data['altura_actual'] < 0 || $data['altura_actual'] > 300)) {
            $errors[] = 'La altura debe estar entre 0 y 300 cm';
        }
        
        if (!empty($errors)) {
            foreach ($errors as $error) {
                Flash::error($error);
            }
            $this->redirect('expedientes/editar?id=' . $id);
        }
        
        try {
            $result = $this->expedienteModel->update($id, $data);
            if ($result) {
                Flash::success('Expediente actualizado exitosamente');
                $this->redirect('expedientes/ver?id=' . $id);
            } else {
                Flash::error('Error al actualizar el expediente');
                $this->redirect('expedientes/editar?id=' . $id);
            }
        } catch (Exception $e) {
            Flash::error('Error al actualizar el expediente: ' . $e->getMessage());
            $this->redirect('expedientes/editar?id=' . $id);
        }
    }
    
    /**
     * Crear expediente para un paciente
     */
    public function crear() {
        $paciente_id = $this->getGet('paciente_id');
        
        if (!$paciente_id) {
            Flash::error('Debe especificar un paciente');
            $this->redirect('pacientes');
        }
        
        // Verificar si ya existe expediente
        if ($this->expedienteModel->existeParaPaciente($paciente_id)) {
            Flash::warning('Este paciente ya tiene un expediente');
            $expediente = $this->expedienteModel->findByPaciente($paciente_id);
            $this->redirect('expedientes/ver?id=' . $expediente['id']);
        }
        
        if ($this->isPost()) {
            return $this->guardar();
        }
        
        $paciente = $this->pacienteModel->find($paciente_id);
        if (!$paciente) {
            Flash::error('Paciente no encontrado');
            $this->redirect('pacientes');
        }
        
        $this->data['title'] = 'Crear Expediente Médico';
        $this->data['paciente'] = $paciente;
        
        $this->render('expedientes/crear');
    }
    
    /**
     * Guardar nuevo expediente
     */
    public function guardar() {
        $paciente_id = $this->getPost('paciente_id');
        
        $data = [
            'paciente_id' => $paciente_id,
            'antecedentes_familiares' => $this->getPost('antecedentes_familiares'),
            'antecedentes_personales' => $this->getPost('antecedentes_personales'),
            'antecedentes_quirurgicos' => $this->getPost('antecedentes_quirurgicos'),
            'antecedentes_alergicos' => $this->getPost('antecedentes_alergicos'),
            'vacunas' => $this->getPost('vacunas'),
            'grupo_sanguineo' => $this->getPost('grupo_sanguineo'),
            'factor_rh' => $this->getPost('factor_rh'),
            'peso_actual' => $this->getPost('peso_actual'),
            'altura_actual' => $this->getPost('altura_actual'),
            'estado_civil' => $this->getPost('estado_civil'),
            'ocupacion' => $this->getPost('ocupacion'),
            'escolaridad' => $this->getPost('escolaridad'),
            'observaciones_generales' => $this->getPost('observaciones_generales')
        ];
        
        try {
            $id = $this->expedienteModel->crearParaPaciente($paciente_id, $data);
            if ($id) {
                Flash::success('Expediente creado exitosamente');
                $this->redirect('expedientes/ver?id=' . $id);
            } else {
                Flash::error('Error al crear el expediente');
                $this->redirect('expedientes/crear?paciente_id=' . $paciente_id);
            }
        } catch (Exception $e) {
            Flash::error('Error al crear el expediente: ' . $e->getMessage());
            $this->redirect('expedientes/crear?paciente_id=' . $paciente_id);
        }
    }
    
    /**
     * Buscar expediente por paciente (AJAX)
     */
    public function buscarPorPaciente() {
        $paciente_id = $this->getGet('paciente_id');
        
        if (!$paciente_id) {
            echo json_encode(['success' => false, 'message' => 'ID de paciente requerido']);
            exit;
        }
        
        $expediente = $this->expedienteModel->findByPaciente($paciente_id);
        
        if ($expediente) {
            echo json_encode(['success' => true, 'expediente' => $expediente]);
        } else {
            echo json_encode(['success' => false, 'message' => 'No se encontró expediente']);
        }
        exit;
    }
    
    /**
     * Imprimir expediente (PDF)
     */
    public function imprimir() {
        $id = $this->getGet('id');
        if (!$id) {
            Flash::error('ID de expediente no válido');
            $this->redirect('expedientes');
        }
        
        $expediente = $this->expedienteModel->findWithPaciente($id);
        if (!$expediente) {
            Flash::error('Expediente no encontrado');
            $this->redirect('expedientes');
        }
        
        // Calcular IMC
        if ($expediente['peso_actual'] && $expediente['altura_actual']) {
            $expediente['imc_calculado'] = $this->expedienteModel->calcularIMC(
                $expediente['peso_actual'], 
                $expediente['altura_actual']
            );
            $expediente['clasificacion_imc'] = $this->expedienteModel->clasificarIMC($expediente['imc_calculado']);
        }
        
        // Obtener historial
        $historialConsultas = $this->expedienteModel->getHistorialConsultas($id);
        
        $this->data['expediente'] = $expediente;
        $this->data['historial_consultas'] = $historialConsultas;
        $this->data['show_print'] = true; // Flag para indicar que es vista de impresión
        
        // Renderizar vista normal (el CSS se encargará de la impresión)
        $this->render('expedientes/ver');
    }
}
?>