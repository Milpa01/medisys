<div class="container-fluid">
    <!-- Encabezado -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-0">
                        <i class="bi bi-clipboard-pulse me-2"></i>
                        Consulta <?= htmlspecialchars($consulta['numero_consulta']) ?>
                    </h1>
                    <p class="text-muted mb-0">
                        <?= Util::formatDate($consulta['fecha_cita']) ?> a las <?= Util::formatTime($consulta['hora_cita']) ?>
                    </p>
                </div>
                <div>
                    <?php if ((Auth::hasRole('medico') && $consulta['medico_usuario_id'] == Auth::id()) || Auth::hasRole('administrador')): ?>
                        <a href="<?= Util::url('consultas/editar?id=' . $consulta['id']) ?>" class="btn btn-primary">
                            <i class="bi bi-pencil-square"></i> Editar
                        </a>
                    <?php endif; ?>
                    <a href="<?= Util::url('consultas') ?>" class="btn btn-secondary">
                        <i class="bi bi-arrow-left"></i> Volver
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Mensajes Flash -->
    <?= Flash::display() ?>

    <div class="row">
        <!-- Columna Principal -->
        <div class="col-xl-8 col-lg-7">
            
            <!-- Información de la Cita -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-calendar-check me-2"></i>
                        Información de la Cita
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <td class="fw-bold">Código de cita:</td>
                                    <td>
                                        <a href="<?= Util::url('citas/ver?id=' . $consulta['cita_id']) ?>">
                                            <?= htmlspecialchars($consulta['codigo_cita']) ?>
                                        </a>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Fecha y hora:</td>
                                    <td>
                                        <?= Util::formatDate($consulta['fecha_cita']) ?><br>
                                        <small class="text-muted"><?= Util::formatTime($consulta['hora_cita']) ?></small>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Motivo original:</td>
                                    <td><?= htmlspecialchars($consulta['motivo_consulta']) ?></td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Tipo de cita:</td>
                                    <td>
                                        <span class="badge bg-<?= 
                                            $consulta['tipo_cita'] === 'primera_vez' ? 'info' : 
                                            ($consulta['tipo_cita'] === 'control' ? 'success' : 
                                            ($consulta['tipo_cita'] === 'emergencia' ? 'danger' : 'warning')) 
                                        ?>">
                                            <?= ucfirst(str_replace('_', ' ', $consulta['tipo_cita'])) ?>
                                        </span>
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <td class="fw-bold">Estado de cita:</td>
                                    <td>
                                        <span class="badge bg-<?= $consulta['estado_cita'] === 'completada' ? 'success' : 'secondary' ?>">
                                            <?= ucfirst(str_replace('_', ' ', $consulta['estado_cita'])) ?>
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Costo consulta:</td>
                                    <td>
                                        <?php if ($consulta['costo_consulta'] > 0): ?>
                                            <?= Util::formatMoney($consulta['costo_consulta']) ?>
                                        <?php else: ?>
                                            <span class="text-muted">No especificado</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Registrada:</td>
                                    <td><?= Util::formatDateTime($consulta['created_at']) ?></td>
                                </tr>
                                <?php if ($consulta['updated_at'] != $consulta['created_at']): ?>
                                <tr>
                                    <td class="fw-bold">Última modificación:</td>
                                    <td><?= Util::formatDateTime($consulta['updated_at']) ?></td>
                                </tr>
                                <?php endif; ?>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Signos Vitales -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-heart-pulse me-2"></i>
                        Signos Vitales
                    </h5>
                </div>
                <div class="card-body">
                    <?php if ($consulta['peso'] || $consulta['altura'] || $consulta['temperatura'] || 
                              $consulta['presion_sistolica'] || $consulta['frecuencia_cardiaca']): ?>
                        <div class="row text-center">
                            <?php if ($consulta['peso']): ?>
                                <div class="col-md-2 col-6 mb-3">
                                    <div class="border rounded p-3">
                                        <i class="bi bi-speedometer2 text-primary display-6"></i>
                                        <div class="h4 mb-0"><?= number_format($consulta['peso'], 1) ?></div>
                                        <small class="text-muted">kg</small>
                                    </div>
                                </div>
                            <?php endif; ?>
                            
                            <?php if ($consulta['altura']): ?>
                                <div class="col-md-2 col-6 mb-3">
                                    <div class="border rounded p-3">
                                        <i class="bi bi-arrows-vertical text-info display-6"></i>
                                        <div class="h4 mb-0"><?= number_format($consulta['altura'], 1) ?></div>
                                        <small class="text-muted">cm</small>
                                    </div>
                                </div>
                            <?php endif; ?>
                            
                            <?php if ($consulta['temperatura']): ?>
                                <div class="col-md-2 col-6 mb-3">
                                    <div class="border rounded p-3">
                                        <i class="bi bi-thermometer-half text-danger display-6"></i>
                                        <div class="h4 mb-0"><?= number_format($consulta['temperatura'], 1) ?></div>
                                        <small class="text-muted">°C</small>
                                    </div>
                                </div>
                            <?php endif; ?>
                            
                            <?php if ($consulta['presion_sistolica'] && $consulta['presion_diastolica']): ?>
                                <div class="col-md-2 col-6 mb-3">
                                    <div class="border rounded p-3">
                                        <i class="bi bi-heart text-danger display-6"></i>
                                        <div class="h4 mb-0"><?= $consulta['presion_sistolica'] ?>/<?= $consulta['presion_diastolica'] ?></div>
                                        <small class="text-muted">mmHg</small>
                                    </div>
                                </div>
                            <?php endif; ?>
                            
                            <?php if ($consulta['frecuencia_cardiaca']): ?>
                                <div class="col-md-2 col-6 mb-3">
                                    <div class="border rounded p-3">
                                        <i class="bi bi-activity text-success display-6"></i>
                                        <div class="h4 mb-0"><?= $consulta['frecuencia_cardiaca'] ?></div>
                                        <small class="text-muted">ppm</small>
                                    </div>
                                </div>
                            <?php endif; ?>
                            
                            <?php if ($consulta['peso'] && $consulta['altura']): ?>
                                <?php 
                                $imc = $consulta['peso'] / (($consulta['altura'] / 100) * ($consulta['altura'] / 100));
                                $imcClase = $imc < 18.5 ? 'warning' : ($imc < 25 ? 'success' : ($imc < 30 ? 'warning' : 'danger'));
                                ?>
                                <div class="col-md-2 col-6 mb-3">
                                    <div class="border rounded p-3">
                                        <i class="bi bi-calculator text-<?= $imcClase ?> display-6"></i>
                                        <div class="h4 mb-0"><?= number_format($imc, 1) ?></div>
                                        <small class="text-muted">IMC</small>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-3">
                            <i class="bi bi-heart display-4 text-muted"></i>
                            <p class="text-muted mt-2">No se registraron signos vitales</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Evaluación Clínica -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-clipboard-data me-2"></i>
                        Evaluación Clínica
                    </h5>
                </div>
                <div class="card-body">
                    <div class="mb-4">
                        <h6 class="fw-bold text-primary">Síntomas</h6>
                        <p class="mb-0"><?= nl2br(htmlspecialchars($consulta['sintomas'])) ?></p>
                    </div>
                    
                    <div class="mb-0">
                        <h6 class="fw-bold text-primary">Exploración Física</h6>
                        <p class="mb-0"><?= nl2br(htmlspecialchars($consulta['exploracion_fisica'])) ?></p>
                    </div>
                </div>
            </div>

            <!-- Diagnóstico y Tratamiento -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-clipboard-check me-2"></i>
                        Diagnóstico y Tratamiento
                    </h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <h6 class="fw-bold text-success">Diagnóstico Principal</h6>
                        <p class="mb-0 fs-5"><?= htmlspecialchars($consulta['diagnostico_principal']) ?></p>
                    </div>
                    
                    <?php if ($consulta['diagnosticos_secundarios']): ?>
                        <div class="mb-3">
                            <h6 class="fw-bold text-info">Diagnósticos Secundarios</h6>
                            <p class="mb-0"><?= nl2br(htmlspecialchars($consulta['diagnosticos_secundarios'])) ?></p>
                        </div>
                    <?php endif; ?>
                    
                    <div class="mb-3">
                        <h6 class="fw-bold text-primary">Plan de Tratamiento</h6>
                        <p class="mb-0"><?= nl2br(htmlspecialchars($consulta['plan_tratamiento'])) ?></p>
                    </div>
                    
                    <?php if ($consulta['indicaciones']): ?>
                        <div class="mb-0">
                            <h6 class="fw-bold text-warning">Indicaciones al Paciente</h6>
                            <p class="mb-0"><?= nl2br(htmlspecialchars($consulta['indicaciones'])) ?></p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Prescripciones -->
            <?php if (!empty($prescripciones)): ?>
            <div class="card mb-4">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">
                            <i class="bi bi-prescription2 me-2"></i>
                            Prescripciones Médicas
                        </h5>
                        <a href="<?= Util::url('consultas/prescripciones?consulta_id=' . $consulta['id']) ?>" 
                           class="btn btn-sm btn-outline-primary">
                            <i class="bi bi-printer"></i> Imprimir Receta
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Medicamento</th>
                                    <th>Dosis</th>
                                    <th>Frecuencia</th>
                                    <th>Duración</th>
                                    <th>Vía</th>
                                    <th>Cantidad</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($prescripciones as $prescripcion): ?>
                                    <tr>
                                        <td>
                                            <div class="fw-bold"><?= htmlspecialchars($prescripcion['nombre_comercial']) ?></div>
                                            <?php if ($prescripcion['nombre_generico']): ?>
                                                <small class="text-muted"><?= htmlspecialchars($prescripcion['nombre_generico']) ?></small>
                                            <?php endif; ?>
                                            <?php if ($prescripcion['presentacion']): ?>
                                                <br><small class="text-info"><?= htmlspecialchars($prescripcion['presentacion']) ?></small>
                                            <?php endif; ?>
                                        </td>
                                        <td><?= htmlspecialchars($prescripcion['dosis']) ?></td>
                                        <td><?= htmlspecialchars($prescripcion['frecuencia']) ?></td>
                                        <td><?= htmlspecialchars($prescripcion['duracion'] ?: 'No especificada') ?></td>
                                        <td>
                                            <span class="badge bg-secondary">
                                                <?= ucfirst($prescripcion['via_administracion']) ?>
                                            </span>
                                        </td>
                                        <td><?= $prescripcion['cantidad_recetada'] ?: '-' ?></td>
                                    </tr>
                                    <?php if ($prescripcion['indicaciones_especiales']): ?>
                                    <tr>
                                        <td colspan="6" class="pt-0">
                                            <small class="text-muted">
                                                <i class="bi bi-info-circle me-1"></i>
                                                <?= htmlspecialchars($prescripcion['indicaciones_especiales']) ?>
                                            </small>
                                        </td>
                                    </tr>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <!-- Seguimiento y Observaciones -->
            <?php if ($consulta['proxima_cita'] || $consulta['observaciones']): ?>
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-calendar-plus me-2"></i>
                        Seguimiento y Observaciones
                    </h5>
                </div>
                <div class="card-body">
                    <?php if ($consulta['proxima_cita']): ?>
                        <div class="mb-3">
                            <h6 class="fw-bold text-primary">Próxima Cita Programada</h6>
                            <p class="mb-0">
                                <i class="bi bi-calendar-event me-2"></i>
                                <?= Util::formatDate($consulta['proxima_cita']) ?>
                                <a href="<?= Util::url('citas/crear?paciente_id=' . $consulta['paciente_id'] . '&fecha=' . $consulta['proxima_cita']) ?>" 
                                   class="btn btn-sm btn-outline-success ms-2">
                                    <i class="bi bi-plus-circle"></i> Programar Cita
                                </a>
                            </p>
                        </div>
                    <?php endif; ?>
                    
                    <?php if ($consulta['observaciones']): ?>
                        <div class="mb-0">
                            <h6 class="fw-bold text-secondary">Observaciones Generales</h6>
                            <p class="mb-0"><?= nl2br(htmlspecialchars($consulta['observaciones'])) ?></p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            <?php endif; ?>
        </div>

        <!-- Sidebar -->
        <div class="col-xl-4 col-lg-5">
            
            <!-- Información del Paciente -->
            <div class="card mb-4">
                <div class="card-header">
                    <h6 class="card-title mb-0">
                        <i class="bi bi-person me-2"></i>
                        Información del Paciente
                    </h6>
                </div>
                <div class="card-body">
                    <div class="text-center mb-3">
                        <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center mb-2" 
                             style="width: 60px; height: 60px;">
                            <i class="bi bi-person-fill display-6 text-muted"></i>
                        </div>
                        <h6 class="mb-1">
                            <a href="<?= Util::url('pacientes/ver?id=' . $consulta['paciente_id']) ?>" 
                               class="text-decoration-none">
                                <?= htmlspecialchars($consulta['paciente_nombre']) ?>
                            </a>
                        </h6>
                        <small class="text-muted"><?= htmlspecialchars($consulta['codigo_paciente']) ?></small>
                    </div>
                    
                    <?php if ($consulta['paciente_telefono']): ?>
                        <p class="mb-2">
                            <i class="bi bi-telephone me-2"></i>
                            <a href="tel:<?= htmlspecialchars($consulta['paciente_telefono']) ?>">
                                <?= htmlspecialchars($consulta['paciente_telefono']) ?>
                            </a>
                        </p>
                    <?php endif; ?>
                    
                    <?php if ($consulta['paciente_email']): ?>
                        <p class="mb-2">
                            <i class="bi bi-envelope me-2"></i>
                            <a href="mailto:<?= htmlspecialchars($consulta['paciente_email']) ?>">
                                <?= htmlspecialchars($consulta['paciente_email']) ?>
                            </a>
                        </p>
                    <?php endif; ?>
                    
                    <?php if ($consulta['fecha_nacimiento']): ?>
                        <p class="mb-2">
                            <i class="bi bi-calendar3 me-2"></i>
                            <?= Util::calculateAge($consulta['fecha_nacimiento']) ?> años
                            <small class="text-muted">(<?= Util::formatDate($consulta['fecha_nacimiento']) ?>)</small>
                        </p>
                    <?php endif; ?>
                    
                    <?php if ($consulta['tipo_sangre']): ?>
                        <p class="mb-3">
                            <i class="bi bi-droplet me-2"></i>
                            <span class="badge bg-danger"><?= htmlspecialchars($consulta['tipo_sangre']) ?></span>
                        </p>
                    <?php endif; ?>
                    
                    <div class="d-grid gap-2">
                        <a href="<?= Util::url('pacientes/ver?id=' . $consulta['paciente_id']) ?>" 
                           class="btn btn-outline-primary btn-sm">
                            <i class="bi bi-eye"></i> Ver Expediente Completo
                        </a>
                        <a href="<?= Util::url('citas/crear?paciente_id=' . $consulta['paciente_id']) ?>" 
                           class="btn btn-outline-success btn-sm">
                            <i class="bi bi-calendar-plus"></i> Nueva Cita
                        </a>
                    </div>
                </div>
            </div>

            <!-- Información del Médico -->
            <div class="card mb-4">
                <div class="card-header">
                    <h6 class="card-title mb-0">
                        <i class="bi bi-person-badge me-2"></i>
                        Médico Tratante
                    </h6>
                </div>
                <div class="card-body">
                    <div class="text-center mb-3">
                        <div class="bg-primary bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-2" 
                             style="width: 50px; height: 50px;">
                            <i class="bi bi-person-fill-check text-primary"></i>
                        </div>
                        <h6 class="mb-1">Dr. <?= htmlspecialchars($consulta['medico_nombre']) ?></h6>
                        <small class="text-muted"><?= htmlspecialchars($consulta['especialidad']) ?></small>
                    </div>
                    
                    <?php if ($consulta['cedula_profesional']): ?>
                        <p class="mb-2">
                            <i class="bi bi-award me-2"></i>
                            Cédula: <?= htmlspecialchars($consulta['cedula_profesional']) ?>
                        </p>
                    <?php endif; ?>
                    
                    <?php if ($consulta['consultorio']): ?>
                        <p class="mb-2">
                            <i class="bi bi-geo-alt me-2"></i>
                            <?= htmlspecialchars($consulta['consultorio']) ?>
                        </p>
                    <?php endif; ?>
                    
                    <?php if ($consulta['medico_telefono']): ?>
                        <p class="mb-2">
                            <i class="bi bi-telephone me-2"></i>
                            <a href="tel:<?= htmlspecialchars($consulta['medico_telefono']) ?>">
                                <?= htmlspecialchars($consulta['medico_telefono']) ?>
                            </a>
                        </p>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Historial del Paciente -->
            <?php if (!empty($historial)): ?>
            <div class="card mb-4">
                <div class="card-header">
                    <h6 class="card-title mb-0">
                        <i class="bi bi-clock-history me-2"></i>
                        Historial Reciente
                    </h6>
                </div>
                <div class="card-body">
                    <div class="timeline-sm">
                        <?php foreach (array_slice($historial, 0, 5) as $historia): ?>
                            <div class="timeline-item">
                                <div class="timeline-marker bg-secondary"></div>
                                <div class="timeline-content">
                                    <div class="small fw-bold"><?= Util::formatDate($historia['fecha_cita']) ?></div>
                                    <div class="small text-muted"><?= htmlspecialchars($historia['motivo_consulta']) ?></div>
                                    <?php if ($historia['diagnostico_principal']): ?>
                                        <div class="small text-primary">
                                            <?= Util::truncate($historia['diagnostico_principal'], 50) ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    
                    <?php if (count($historial) > 5): ?>
                        <div class="text-center mt-3">
                            <a href="<?= Util::url('pacientes/ver?id=' . $consulta['paciente_id']) ?>" 
                               class="btn btn-sm btn-outline-secondary">
                                Ver historial completo
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            <?php endif; ?>

            <!-- Acciones Rápidas -->
            <div class="card">
                <div class="card-header">
                    <h6 class="card-title mb-0">
                        <i class="bi bi-lightning me-2"></i>
                        Acciones Rápidas
                    </h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <button type="button" class="btn btn-outline-primary btn-sm" onclick="window.print()">
                            <i class="bi bi-printer"></i> Imprimir Consulta
                        </button>
                        
                        <?php if (!empty($prescripciones)): ?>
                            <a href="<?= Util::url('consultas/prescripciones?consulta_id=' . $consulta['id']) ?>" 
                               class="btn btn-outline-success btn-sm">
                                <i class="bi bi-prescription2"></i> Ver Receta Completa
                            </a>
                        <?php endif; ?>
                        
                        <a href="<?= Util::url('citas/ver?id=' . $consulta['cita_id']) ?>" 
                           class="btn btn-outline-info btn-sm">
                            <i class="bi bi-calendar-check"></i> Ver Cita Original
                        </a>
                        
                        <?php if ($consulta['proxima_cita']): ?>
                            <a href="<?= Util::url('citas/crear?paciente_id=' . $consulta['paciente_id'] . '&fecha=' . $consulta['proxima_cita']) ?>" 
                               class="btn btn-outline-warning btn-sm">
                                <i class="bi bi-calendar-plus"></i> Programar Seguimiento
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- CSS personalizado para timeline e impresión -->
<style>
.timeline-sm .timeline-item {
    display: flex;
    align-items-flex-start;
    margin-bottom: 15px;
}

.timeline-sm .timeline-marker {
    width: 8px;
    height: 8px;
    border-radius: 50%;
    margin-right: 10px;
    margin-top: 4px;
    flex-shrink: 0;
}

.timeline-sm .timeline-content {
    flex-grow: 1;
}

.timeline-sm .timeline-item:not(:last-child) {
    position: relative;
}

.timeline-sm .timeline-item:not(:last-child)::after {
    content: '';
    position: absolute;
    width: 2px;
    height: 20px;
    background-color: #dee2e6;
    left: 3px;
    top: 15px;
}

/* Estilos para impresión */
@media print {
    .btn, .card-header .btn, .navbar, .sidebar {
        display: none !important;
    }
    
    .card {
        border: 1px solid #000 !important;
        box-shadow: none !important;
        page-break-inside: avoid;
        margin-bottom: 20px !important;
    }
    
    .card-header {
        background-color: #f8f9fa !important;
        border-bottom: 1px solid #000 !important;
    }
    
    .container-fluid {
        padding: 0 !important;
    }
    
    .col-xl-8, .col-lg-7 {
        width: 100% !important;
    }
    
    .col-xl-4, .col-lg-5 {
        display: none !important;
    }
    
    .badge {
        border: 1px solid #000 !important;
    }
    
    .text-primary, .text-success, .text-info, .text-warning, .text-danger {
        color: #000 !important;
    }
    
    .bg-primary, .bg-success, .bg-info, .bg-warning, .bg-danger {
        background-color: #f8f9fa !important;
        color: #000 !important;
    }
    
    /* Header para impresión */
    .container-fluid:before {
        content: "CONSULTA MÉDICA - <?= htmlspecialchars($consulta['numero_consulta']) ?>";
        display: block;
        text-align: center;
        font-size: 18px;
        font-weight: bold;
        margin-bottom: 20px;
        padding-bottom: 10px;
        border-bottom: 2px solid #000;
    }
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .row.text-center .col-6 {
        margin-bottom: 1rem;
    }
    
    .table-responsive {
        font-size: 0.875rem;
    }
}

/* Hover effects */
.card:hover {
    box-shadow: 0 0.125rem 0.75rem rgba(0, 0, 0, 0.1);
    transition: box-shadow 0.15s ease-in-out;
}

.border.rounded.p-3:hover {
    background-color: #f8f9fa;
    transition: background-color 0.15s ease-in-out;
}
</style>

<!-- JavaScript para funcionalidades adicionales -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Función de impresión personalizada
    window.print = function() {
        const printContent = document.documentElement.outerHTML;
        const printWindow = window.open('', '_blank');
        
        printWindow.document.write(`
            <!DOCTYPE html>
            <html>
            <head>
                <title>Consulta ${document.querySelector('h1').textContent}</title>
                <link href="<?= Util::asset('bootstrap5/css/bootstrap.min.css') ?>" rel="stylesheet">
                <link href="<?= Util::asset('css/admin.css') ?>" rel="stylesheet">
                <style>
                    @media print {
                        body { font-size: 12px; }
                        .btn, .sidebar, .navbar { display: none !important; }
                        .card { border: 1px solid #000 !important; page-break-inside: avoid; }
                        .col-xl-4, .col-lg-5 { display: none !important; }
                        .col-xl-8, .col-lg-7 { width: 100% !important; }
                    }
                </style>
            </head>
            <body>
                ${printContent}
            </body>
            </html>
        `);
        
        printWindow.document.close();
        printWindow.focus();
        
        setTimeout(() => {
            printWindow.print();
            printWindow.close();
        }, 250);
    };
    
    // Tooltips para iconos
    const tooltipElements = document.querySelectorAll('[title]');
    tooltipElements.forEach(element => {
        new bootstrap.Tooltip(element);
    });
    
    // Atajos de teclado
    document.addEventListener('keydown', function(e) {
        if (e.ctrlKey) {
            switch(e.key) {
                case 'p':
                    e.preventDefault();
                    window.print();
                    break;
                case 'e':
                    e.preventDefault();
                    const editBtn = document.querySelector('a[href*="editar"]');
                    if (editBtn) editBtn.click();
                    break;
            }
        }
        
        if (e.key === 'Escape') {
            window.location.href = '<?= Util::url('consultas') ?>';
        }
    });
    
    // Animaciones suaves para los signos vitales
    const signosVitales = document.querySelectorAll('.border.rounded.p-3');
    signosVitales.forEach((elemento, index) => {
        elemento.style.opacity = '0';
        elemento.style.transform = 'translateY(20px)';
        
        setTimeout(() => {
            elemento.style.transition = 'all 0.3s ease-out';
            elemento.style.opacity = '1';
            elemento.style.transform = 'translateY(0)';
        }, index * 100);
    });
    
    // Destacar diagnóstico principal
    const diagnosticoPrincipal = document.querySelector('.fs-5');
    if (diagnosticoPrincipal) {
        diagnosticoPrincipal.addEventListener('click', function() {
            this.style.backgroundColor = '#fff3cd';
            setTimeout(() => {
                this.style.backgroundColor = '';
            }, 2000);
        });
    }
});

// Función para copiar información al portapapeles
function copiarDiagnostico() {
    const diagnostico = document.querySelector('.fs-5').textContent;
    navigator.clipboard.writeText(diagnostico).then(() => {
        // Mostrar mensaje de confirmación
        const alert = document.createElement('div');
        alert.className = 'alert alert-success alert-dismissible fade show position-fixed';
        alert.style.top = '20px';
        alert.style.right = '20px';
        alert.style.zIndex = '9999';
        alert.innerHTML = '<i class="bi bi-check-circle me-2"></i>Diagnóstico copiado al portapapeles.';
        document.body.appendChild(alert);
        
        setTimeout(() => alert.remove(), 3000);
    });
}

// Función para exportar a PDF (placeholder)
function exportarPDF() {
    // Implementar exportación a PDF
    console.log('Exportar consulta a PDF');
}
</script>