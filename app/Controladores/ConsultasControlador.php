<?php
require_once APP_PATH . '/Modelos/Consulta.php';
require_once APP_PATH . '/Modelos/Cita.php';
require_once APP_PATH . '/Modelos/Paciente.php';
require_once APP_PATH . '/Modelos/Medico.php';

class ConsultasControlador extends BaseControlador {
    private $consultaModel;
    private $citaModel;
    private $pacienteModel;
    private $medicoModel;
    
    public function __construct() {
        parent::__construct();
        $this->requireAuth();
        
        $this->consultaModel = new Consulta();
        $this->citaModel = new Cita();
        $this->pacienteModel = new Paciente();
        $this->medicoModel = new Medico();
    }
    
    public function index() {
        $search = $this->getGet('search', '');
        $fecha_inicio = $this->getGet('fecha_inicio', '');
        $fecha_fin = $this->getGet('fecha_fin', '');
        $medico_seleccionado = $this->getGet('medico_id', '');
        $especialidad_seleccionada = $this->getGet('especialidad_id', '');
        $page = (int)$this->getGet('page', 1);
        $perPage = 20;
        
        // Si no hay fechas, establecer última semana por defecto
        if (empty($fecha_inicio) && empty($fecha_fin)) {
            $fecha_fin = date('Y-m-d');
            $fecha_inicio = date('Y-m-d', strtotime('-7 days'));
        }
        
        // Construir consulta base
        $sql = "SELECT con.*, 
                       c.fecha_cita, c.hora_cita, c.codigo_cita,
                       CONCAT(p.nombre, ' ', p.apellidos) as paciente_nombre,
                       p.codigo_paciente, p.id as paciente_id,
                       CONCAT(u.nombre, ' ', u.apellidos) as medico_nombre,
                       u.id as medico_usuario_id,
                       e.nombre as especialidad,
                       m.consultorio, m.id as medico_id,
                       c.id as cita_id
                FROM consultas con
                INNER JOIN citas c ON con.cita_id = c.id
                INNER JOIN pacientes p ON c.paciente_id = p.id
                INNER JOIN medicos m ON c.medico_id = m.id
                INNER JOIN usuarios u ON m.usuario_id = u.id
                INNER JOIN especialidades e ON m.especialidad_id = e.id";
        
        $whereConditions = [];
        $params = [];
        
        // Filtros de fecha
        if ($fecha_inicio) {
            $whereConditions[] = "c.fecha_cita >= ?";
            $params[] = $fecha_inicio;
        }
        
        if ($fecha_fin) {
            $whereConditions[] = "c.fecha_cita <= ?";
            $params[] = $fecha_fin;
        }
        
        // Filtro de búsqueda
        if ($search) {
            $whereConditions[] = "(p.nombre LIKE ? OR p.apellidos LIKE ? OR 
                                  u.nombre LIKE ? OR u.apellidos LIKE ? OR
                                  con.diagnostico_principal LIKE ? OR 
                                  con.numero_consulta LIKE ? OR
                                  p.codigo_paciente LIKE ?)";
            $searchTerm = "%{$search}%";
            $params = array_merge($params, [$searchTerm, $searchTerm, $searchTerm, $searchTerm, $searchTerm, $searchTerm, $searchTerm]);
        }
        
        // Filtro por médico (solo para no médicos)
        if (Auth::hasRole('medico')) {
            $medicoInfo = $this->medicoModel->findByUsuario(Auth::id());
            if ($medicoInfo) {
                $whereConditions[] = "c.medico_id = ?";
                $params[] = $medicoInfo['id'];
            }
        } elseif ($medico_seleccionado) {
            $whereConditions[] = "c.medico_id = ?";
            $params[] = $medico_seleccionado;
        }
        
        // Filtro por especialidad
        if ($especialidad_seleccionada) {
            $whereConditions[] = "m.especialidad_id = ?";
            $params[] = $especialidad_seleccionada;
        }
        
        // Agregar WHERE clause si hay condiciones
        if (!empty($whereConditions)) {
            $sql .= " WHERE " . implode(' AND ', $whereConditions);
        }
        
        $sql .= " ORDER BY c.fecha_cita DESC, c.hora_cita DESC";
        
        // Ejecutar consulta principal
        $consultas = $this->consultaModel->query($sql, $params);
        
        // Obtener estadísticas
        $stats = $this->getEstadisticas($fecha_inicio, $fecha_fin);
        
        // Obtener listas para filtros
        $medicos = [];
        $especialidades = [];
        
        if (!Auth::hasRole('medico')) {
            $medicos = $this->medicoModel->getAllWithInfo();
        }
        
        $db = Database::getInstance();
        $especialidades = $db->fetchAll("SELECT * FROM especialidades WHERE is_active = 1 ORDER BY nombre");
        
        // Preparar datos para la vista
        $this->data['title'] = 'Gestión de Consultas';
        $this->data['consultas'] = $consultas;
        $this->data['search'] = $search;
        $this->data['fecha_inicio'] = $fecha_inicio;
        $this->data['fecha_fin'] = $fecha_fin;
        $this->data['medico_seleccionado'] = $medico_seleccionado;
        $this->data['especialidad_seleccionada'] = $especialidad_seleccionada;
        $this->data['medicos'] = $medicos;
        $this->data['especialidades'] = $especialidades;
        $this->data['stats'] = $stats;
        
        $this->render('consultas/index');
    }
    
    public function nueva() {
        // Solo médicos pueden crear consultas
        if (!Auth::hasRole('medico') && !Auth::hasRole('administrador')) {
            Flash::error('No tienes permisos para crear consultas');
            $this->redirect('consultas');
        }
        
        $citaId = $this->getGet('cita_id');
        if (!$citaId) {
            Flash::error('ID de cita requerido para crear consulta');
            $this->redirect('citas');
        }
        
        if ($this->isPost()) {
            return $this->guardar();
        }
        
        // Verificar que la cita existe y está disponible
        $cita = $this->citaModel->findWithInfo($citaId);
        if (!$cita) {
            Flash::error('Cita no encontrada');
            $this->redirect('citas');
        }
        
        // Verificar que no existe ya una consulta para esta cita
        $consultaExistente = $this->consultaModel->findBy('cita_id', $citaId);
        if ($consultaExistente) {
            Flash::info('Ya existe una consulta para esta cita');
            $this->redirect('consultas/ver?id=' . $consultaExistente['id']);
        }
        
        // Verificar permisos del médico
        if (Auth::hasRole('medico')) {
            $medicoInfo = $this->medicoModel->findByUsuario(Auth::id());
            if (!$medicoInfo || $cita['medico_id'] != $medicoInfo['id']) {
                Flash::error('No tienes permisos para crear consulta para esta cita');
                $this->redirect('citas');
            }
        }
        
        // Cambiar estado de la cita a "en_curso"
        $this->citaModel->cambiarEstado($citaId, 'en_curso');
        
        // Obtener medicamentos para prescripciones
        $db = Database::getInstance();
        $medicamentos = $db->fetchAll("SELECT * FROM medicamentos WHERE is_active = 1 ORDER BY nombre_comercial");
        
        // Obtener datos del paciente para el expediente
        $paciente = $this->pacienteModel->find($cita['paciente_id']);
        
        $this->data['title'] = 'Nueva Consulta';
        $this->data['cita'] = $cita;
        $this->data['paciente'] = $paciente;
        $this->data['medicamentos'] = $medicamentos;
        
        $this->render('consultas/nueva');
    }
    
    public function guardar() {
        $citaId = $this->getPost('cita_id');
        
        $data = [
            'cita_id' => $citaId,
            'peso' => $this->getPost('peso') ?: null,
            'altura' => $this->getPost('altura') ?: null,
            'temperatura' => $this->getPost('temperatura') ?: null,
            'presion_sistolica' => $this->getPost('presion_sistolica') ?: null,
            'presion_diastolica' => $this->getPost('presion_diastolica') ?: null,
            'frecuencia_cardiaca' => $this->getPost('frecuencia_cardiaca') ?: null,
            'sintomas' => $this->getPost('sintomas'),
            'exploracion_fisica' => $this->getPost('exploracion_fisica'),
            'diagnostico_principal' => $this->getPost('diagnostico_principal'),
            'diagnosticos_secundarios' => $this->getPost('diagnosticos_secundarios'),
            'plan_tratamiento' => $this->getPost('plan_tratamiento'),
            'indicaciones' => $this->getPost('indicaciones'),
            'proxima_cita' => $this->getPost('proxima_cita') ?: null,
            'observaciones' => $this->getPost('observaciones')
        ];
        
        // Validaciones
        $errors = [];
        
        if (empty($data['sintomas'])) {
            $errors[] = 'Los síntomas son requeridos';
        }
        
        if (empty($data['exploracion_fisica'])) {
            $errors[] = 'La exploración física es requerida';
        }
        
        if (empty($data['diagnostico_principal'])) {
            $errors[] = 'El diagnóstico principal es requerido';
        }
        
        if (!empty($errors)) {
            foreach ($errors as $error) {
                Flash::error($error);
            }
            $this->redirect('consultas/nueva?cita_id=' . $citaId);
        }
        
        try {
            // Crear la consulta
            $consultaId = $this->consultaModel->create($data);
            
            if ($consultaId) {
                // *** ACTUALIZAR EXPEDIENTE DEL PACIENTE ***
                $this->actualizarExpediente($citaId, $data);
                
                // Procesar prescripciones si existen
                $medicamentos = $this->getPost('medicamentos', []);
                if (!empty($medicamentos)) {
                    foreach ($medicamentos as $med) {
                        if (!empty($med['medicamento_id'])) {
                            $prescripcionData = [
                                'consulta_id' => $consultaId,
                                'medicamento_id' => $med['medicamento_id'],
                                'dosis' => $med['dosis'] ?? '',
                                'frecuencia' => $med['frecuencia'] ?? '',
                                'duracion' => $med['duracion'] ?? '',
                                'via_administracion' => $med['via_administracion'] ?? 'oral',
                                'cantidad_recetada' => $med['cantidad_recetada'] ?? null,
                                'indicaciones_especiales' => $med['indicaciones_especiales'] ?? ''
                            ];
                            $this->crearPrescripcion($prescripcionData);
                        }
                    }
                }
                
                Flash::success('Consulta registrada exitosamente');
                $this->redirect('consultas/ver?id=' . $consultaId);
            } else {
                Flash::error('Error al registrar la consulta');
                $this->redirect('consultas/nueva?cita_id=' . $citaId);
            }
        } catch (Exception $e) {
            Flash::error('Error al registrar la consulta: ' . $e->getMessage());
            $this->redirect('consultas/nueva?cita_id=' . $citaId);
        }
    }
    
    public function ver() {
        $id = $this->getGet('id');
        if (!$id) {
            Flash::error('ID de consulta no válido');
            $this->redirect('consultas');
        }
        
        $consulta = $this->consultaModel->findWithInfo($id);
        if (!$consulta) {
            Flash::error('Consulta no encontrada');
            $this->redirect('consultas');
        }
        
        // Verificar permisos
        if (Auth::hasRole('medico')) {
            $medicoInfo = $this->medicoModel->findByUsuario(Auth::id());
            if (!$medicoInfo || $consulta['medico_id'] != $medicoInfo['id']) {
                Flash::error('No tienes permisos para ver esta consulta');
                $this->redirect('consultas');
            }
        }
        
        // Obtener prescripciones
        $prescripciones = $this->consultaModel->getPrescripciones($id);
        
        $this->data['title'] = 'Consulta ' . $consulta['numero_consulta'];
        $this->data['consulta'] = $consulta;
        $this->data['prescripciones'] = $prescripciones;
        
        $this->render('consultas/ver');
    }
    
    public function editar() {
        $id = $this->getGet('id');
        if (!$id) {
            Flash::error('ID de consulta no válido');
            $this->redirect('consultas');
        }
        
        if ($this->isPost()) {
            return $this->actualizar();
        }
        
        $consulta = $this->consultaModel->findWithInfo($id);
        if (!$consulta) {
            Flash::error('Consulta no encontrada');
            $this->redirect('consultas');
        }
        
        // Verificar permisos
        if (Auth::hasRole('medico')) {
            $medicoInfo = $this->medicoModel->findByUsuario(Auth::id());
            if (!$medicoInfo || $consulta['medico_id'] != $medicoInfo['id']) {
                Flash::error('No tienes permisos para editar esta consulta');
                $this->redirect('consultas');
            }
        } elseif (!Auth::hasRole('administrador')) {
            Flash::error('No tienes permisos para editar consultas');
            $this->redirect('consultas');
        }
        
        // Obtener medicamentos para prescripciones
        $db = Database::getInstance();
        $medicamentos = $db->fetchAll("SELECT * FROM medicamentos WHERE is_active = 1 ORDER BY nombre_comercial");
        
        // Obtener prescripciones existentes
        $prescripciones = $this->consultaModel->getPrescripciones($id);
        
        $this->data['title'] = 'Editar Consulta';
        $this->data['consulta'] = $consulta;
        $this->data['medicamentos'] = $medicamentos;
        $this->data['prescripciones'] = $prescripciones;
        
        $this->render('consultas/editar');
    }
    
    public function actualizar() {
        $id = $this->getPost('id');
        
        $data = [
            'peso' => $this->getPost('peso') ?: null,
            'altura' => $this->getPost('altura') ?: null,
            'temperatura' => $this->getPost('temperatura') ?: null,
            'presion_sistolica' => $this->getPost('presion_sistolica') ?: null,
            'presion_diastolica' => $this->getPost('presion_diastolica') ?: null,
            'frecuencia_cardiaca' => $this->getPost('frecuencia_cardiaca') ?: null,
            'sintomas' => $this->getPost('sintomas'),
            'exploracion_fisica' => $this->getPost('exploracion_fisica'),
            'diagnostico_principal' => $this->getPost('diagnostico_principal'),
            'diagnosticos_secundarios' => $this->getPost('diagnosticos_secundarios'),
            'plan_tratamiento' => $this->getPost('plan_tratamiento'),
            'indicaciones' => $this->getPost('indicaciones'),
            'proxima_cita' => $this->getPost('proxima_cita') ?: null,
            'observaciones' => $this->getPost('observaciones')
        ];
        
        // Validaciones (mismas que en guardar)
        $errors = [];
        
        if (empty($data['sintomas'])) {
            $errors[] = 'Los síntomas son requeridos';
        }
        
        if (empty($data['exploracion_fisica'])) {
            $errors[] = 'La exploración física es requerida';
        }
        
        if (empty($data['diagnostico_principal'])) {
            $errors[] = 'El diagnóstico principal es requerido';
        }
        
        if (!empty($errors)) {
            foreach ($errors as $error) {
                Flash::error($error);
            }
            $this->redirect('consultas/editar?id=' . $id);
        }
        
        try {
            $result = $this->consultaModel->update($id, $data);
            if ($result) {
                // *** ACTUALIZAR EXPEDIENTE DEL PACIENTE ***
                $consulta = $this->consultaModel->find($id);
                if ($consulta && !empty($consulta['cita_id'])) {
                    $this->actualizarExpediente($consulta['cita_id'], $data);
                }
                
                Flash::success('Consulta actualizada exitosamente');
                $this->redirect('consultas/ver?id=' . $id);
            } else {
                Flash::error('Error al actualizar la consulta');
                $this->redirect('consultas/editar?id=' . $id);
            }
        } catch (Exception $e) {
            Flash::error('Error al actualizar la consulta: ' . $e->getMessage());
            $this->redirect('consultas/editar?id=' . $id);
        }
    }
    
    /**
     * *** NUEVO MÉTODO: Actualizar expediente del paciente ***
     * Este método actualiza el expediente médico del paciente con información relevante de la consulta
     */
    private function actualizarExpediente($citaId, $datosConsulta) {
        try {
            // Obtener información de la cita y paciente
            $cita = $this->citaModel->find($citaId);
            if (!$cita) {
                return false;
            }
            
            $pacienteId = $cita['paciente_id'];
            $db = Database::getInstance();
            
            // Verificar si existe el expediente
            $expediente = $db->fetch("SELECT * FROM expedientes WHERE paciente_id = ?", [$pacienteId]);
            
            if (!$expediente) {
                // Crear expediente si no existe
                $this->pacienteModel->createExpediente($pacienteId);
                $expediente = $db->fetch("SELECT * FROM expedientes WHERE paciente_id = ?", [$pacienteId]);
            }
            
            // Preparar datos para actualizar el expediente
            $expedienteData = [];
            
            // Actualizar alergias si se especificaron en la consulta
            if (!empty($datosConsulta['alergias_nuevas'])) {
                $alergiasActuales = $expediente['alergias'] ?? '';
                $expedienteData['alergias'] = $alergiasActuales ? 
                    $alergiasActuales . "\n" . $datosConsulta['alergias_nuevas'] : 
                    $datosConsulta['alergias_nuevas'];
            }
            
            // Actualizar enfermedades crónicas si hay nuevos diagnósticos importantes
            if (!empty($datosConsulta['diagnostico_principal']) && 
                (stripos($datosConsulta['diagnostico_principal'], 'crónico') !== false ||
                 stripos($datosConsulta['diagnostico_principal'], 'permanente') !== false)) {
                $enfermedadesActuales = $expediente['enfermedades_cronicas'] ?? '';
                $expedienteData['enfermedades_cronicas'] = $enfermedadesActuales ? 
                    $enfermedadesActuales . "\n- " . $datosConsulta['diagnostico_principal'] : 
                    "- " . $datosConsulta['diagnostico_principal'];
            }
            
            // Actualizar última consulta
            $expedienteData['ultima_consulta'] = date('Y-m-d');
            $expedienteData['ultima_actualizacion_medica'] = date('Y-m-d H:i:s');
            
            // Si hay datos para actualizar, ejecutar el UPDATE
            if (!empty($expedienteData)) {
                $setClauses = [];
                $params = [];
                
                foreach ($expedienteData as $campo => $valor) {
                    $setClauses[] = "$campo = ?";
                    $params[] = $valor;
                }
                
                $params[] = $expediente['id'];
                
                $sql = "UPDATE expedientes SET " . implode(', ', $setClauses) . " WHERE id = ?";
                $db->execute($sql, $params);
                
                return true;
            }
            
        } catch (Exception $e) {
            // Log error pero no interrumpir el flujo
            error_log("Error actualizando expediente: " . $e->getMessage());
            return false;
        }
    }
    
    public function prescripciones() {
        $consultaId = $this->getGet('consulta_id');
        if (!$consultaId) {
            Flash::error('ID de consulta requerido');
            $this->redirect('consultas');
        }
        
        $consulta = $this->consultaModel->findWithInfo($consultaId);
        if (!$consulta) {
            Flash::error('Consulta no encontrada');
            $this->redirect('consultas');
        }
        
        $prescripciones = $this->consultaModel->getPrescripciones($consultaId);
        
        $this->data['title'] = 'Prescripciones - Consulta ' . $consulta['numero_consulta'];
        $this->data['consulta'] = $consulta;
        $this->data['prescripciones'] = $prescripciones;
        
        $this->render('consultas/prescripciones');
    }
    
    private function crearPrescripcion($data) {
        $db = Database::getInstance();
        
        $sql = "INSERT INTO prescripciones (consulta_id, medicamento_id, dosis, frecuencia, duracion, 
                via_administracion, cantidad_recetada, indicaciones_especiales) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        
        return $db->execute($sql, [
            $data['consulta_id'],
            $data['medicamento_id'],
            $data['dosis'],
            $data['frecuencia'],
            $data['duracion'],
            $data['via_administracion'],
            $data['cantidad_recetada'],
            $data['indicaciones_especiales']
        ]);
    }
    
    private function getEstadisticas($fechaInicio = null, $fechaFin = null) {
        $db = Database::getInstance();
        $whereClause = '';
        $params = [];
        
        // Filtrar por médico si es necesario
        if (Auth::hasRole('medico')) {
            $medicoInfo = $this->medicoModel->findByUsuario(Auth::id());
            if ($medicoInfo) {
                $whereClause = " AND c.medico_id = ?";
                $params[] = $medicoInfo['id'];
            }
        }
        
        $stats = [];
        
        // Total de consultas
        $sql = "SELECT COUNT(*) as total FROM consultas con 
                INNER JOIN citas c ON con.cita_id = c.id 
                WHERE 1=1" . $whereClause;
        $stats['total_consultas'] = $db->fetch($sql, $params)['total'];
        
        // Consultas hoy
        $sql = "SELECT COUNT(*) as total FROM consultas con 
                INNER JOIN citas c ON con.cita_id = c.id 
                WHERE c.fecha_cita = CURDATE()" . $whereClause;
        $stats['consultas_hoy'] = $db->fetch($sql, $params)['total'];
        
        // Consultas esta semana
        $inicioSemana = date('Y-m-d', strtotime('monday this week'));
        $finSemana = date('Y-m-d', strtotime('sunday this week'));
        $sql = "SELECT COUNT(*) as total FROM consultas con 
                INNER JOIN citas c ON con.cita_id = c.id 
                WHERE c.fecha_cita BETWEEN ? AND ?" . $whereClause;
        $paramsTemp = array_merge([$inicioSemana, $finSemana], $params);
        $stats['consultas_semana'] = $db->fetch($sql, $paramsTemp)['total'];
        
        // Consultas este mes
        $sql = "SELECT COUNT(*) as total FROM consultas con 
                INNER JOIN citas c ON con.cita_id = c.id 
                WHERE MONTH(c.fecha_cita) = MONTH(CURDATE()) 
                AND YEAR(c.fecha_cita) = YEAR(CURDATE())" . $whereClause;
        $stats['consultas_mes'] = $db->fetch($sql, $params)['total'];
        
        return $stats;
    }
    
    // Método para exportar consultas (para implementar después)
    public function exportar() {
        // Implementar exportación a Excel/PDF
        Flash::info('Funcionalidad de exportación en desarrollo');
        $this->redirect('consultas');
    }
}
?>