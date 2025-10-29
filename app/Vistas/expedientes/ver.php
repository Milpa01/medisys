<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>
        <i class="bi bi-folder2-open text-primary me-2"></i>
        Expediente Médico
    </h2>
    <div class="btn-group">
        <a href="<?= Router::url('expedientes') ?>" class="btn btn-secondary">
            <i class="bi bi-arrow-left me-2"></i>Volver
        </a>
        <a href="<?= Router::url('expedientes/editar?id=' . ($expediente['id'] ?? '')) ?>" 
           class="btn btn-primary">
            <i class="bi bi-pencil me-2"></i>Editar
        </a>
        <a href="<?= Router::url('expedientes/imprimir?id=' . ($expediente['id'] ?? '')) ?>" 
           class="btn btn-outline-secondary" target="_blank">
            <i class="bi bi-printer me-2"></i>Imprimir
        </a>
    </div>
</div>

<!-- Información del Paciente -->
<div class="row mb-4">
    <div class="col-md-4">
        <div class="card">
            <div class="card-body text-center">
                <?php if (!empty($expediente['imagen'])): ?>
                    <img src="<?= htmlspecialchars($expediente['imagen']) ?>" 
                         alt="Foto del paciente" 
                         class="rounded-circle mb-3" 
                         style="width: 150px; height: 150px; object-fit: cover;">
                <?php else: ?>
                    <div class="avatar-lg bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3">
                        <i class="bi bi-person-fill" style="font-size: 4rem;"></i>
                    </div>
                <?php endif; ?>
                
                <h4><?= htmlspecialchars(($expediente['nombre'] ?? '') . ' ' . ($expediente['apellidos'] ?? '')) ?></h4>
                <p class="text-muted mb-3">
                    <i class="bi bi-file-earmark-medical me-1"></i>
                    <?= htmlspecialchars($expediente['numero_expediente'] ?? 'N/A') ?>
                </p>
                
                <div class="d-grid gap-2">
                    <a href="<?= Router::url('citas/crear?paciente_id=' . ($expediente['paciente_id'] ?? '')) ?>" 
                       class="btn btn-success">
                        <i class="bi bi-calendar-plus me-2"></i>Nueva Cita
                    </a>
                    <a href="<?= Router::url('pacientes/ver?id=' . ($expediente['paciente_id'] ?? '')) ?>" 
                       class="btn btn-outline-info">
                        <i class="bi bi-person-vcard me-2"></i>Ver Perfil Completo
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-8">
        <!-- Datos Generales -->
        <div class="card mb-3">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">
                    <i class="bi bi-person-vcard me-2"></i>Datos Generales
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="text-muted small">Código Paciente</label>
                        <p class="mb-0"><strong><?= htmlspecialchars($expediente['codigo_paciente'] ?? 'N/A') ?></strong></p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="text-muted small">DPI</label>
                        <p class="mb-0"><strong><?= htmlspecialchars($expediente['dpi'] ?? 'No registrado') ?></strong></p>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="text-muted small">Fecha de Nacimiento</label>
                        <p class="mb-0"><?= Util::formatDate($expediente['fecha_nacimiento'] ?? '') ?></p>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="text-muted small">Edad</label>
                        <p class="mb-0"><strong><?= $expediente['edad'] ?? 'N/A' ?> años</strong></p>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="text-muted small">Género</label>
                        <p class="mb-0">
                            <?php 
                            $generos = ['M' => 'Masculino', 'F' => 'Femenino', 'Otro' => 'Otro'];
                            echo $generos[$expediente['genero'] ?? 'Otro'] ?? 'No especificado';
                            ?>
                        </p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="text-muted small">Teléfono</label>
                        <p class="mb-0">
                            <i class="bi bi-telephone me-1"></i>
                            <?= htmlspecialchars($expediente['telefono'] ?? 'No registrado') ?>
                        </p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="text-muted small">Email</label>
                        <p class="mb-0">
                            <i class="bi bi-envelope me-1"></i>
                            <?= htmlspecialchars($expediente['email'] ?? 'No registrado') ?>
                        </p>
                    </div>
                    <div class="col-12 mb-3">
                        <label class="text-muted small">Dirección</label>
                        <p class="mb-0"><?= htmlspecialchars($expediente['direccion'] ?? 'No registrada') ?></p>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Datos Clínicos -->
        <div class="card">
            <div class="card-header bg-danger text-white">
                <h5 class="mb-0">
                    <i class="bi bi-heart-pulse me-2"></i>Datos Clínicos
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <label class="text-muted small">Grupo Sanguíneo</label>
                        <?php if (!empty($expediente['grupo_sanguineo'])): ?>
                            <p class="mb-0">
                                <span class="badge bg-danger fs-6">
                                    <i class="bi bi-droplet-fill me-1"></i>
                                    <?= htmlspecialchars($expediente['grupo_sanguineo'] . ($expediente['factor_rh'] ?? '')) ?>
                                </span>
                            </p>
                        <?php else: ?>
                            <p class="mb-0 text-muted">No registrado</p>
                        <?php endif; ?>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="text-muted small">Peso Actual</label>
                        <p class="mb-0">
                            <?= !empty($expediente['peso_actual']) ? number_format($expediente['peso_actual'], 2) . ' kg' : 'No registrado' ?>
                        </p>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="text-muted small">Altura Actual</label>
                        <p class="mb-0">
                            <?= !empty($expediente['altura_actual']) ? number_format($expediente['altura_actual'], 2) . ' cm' : 'No registrada' ?>
                        </p>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="text-muted small">IMC</label>
                        <?php if (!empty($expediente['imc_calculado'])): ?>
                            <p class="mb-0">
                                <strong><?= number_format($expediente['imc_calculado'], 2) ?></strong>
                                <br>
                                <small class="badge bg-info"><?= $expediente['clasificacion_imc'] ?? '' ?></small>
                            </p>
                        <?php else: ?>
                            <p class="mb-0 text-muted">No calculado</p>
                        <?php endif; ?>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="text-muted small">Estado Civil</label>
                        <p class="mb-0">
                            <?php 
                            $estados = [
                                'soltero' => 'Soltero(a)',
                                'casado' => 'Casado(a)',
                                'divorciado' => 'Divorciado(a)',
                                'viudo' => 'Viudo(a)',
                                'union_libre' => 'Unión Libre'
                            ];
                            echo $estados[$expediente['estado_civil'] ?? ''] ?? 'No especificado';
                            ?>
                        </p>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="text-muted small">Ocupación</label>
                        <p class="mb-0"><?= htmlspecialchars($expediente['ocupacion'] ?? 'No registrada') ?></p>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="text-muted small">Escolaridad</label>
                        <p class="mb-0"><?= htmlspecialchars($expediente['escolaridad'] ?? 'No registrada') ?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Pestañas de Información -->
<ul class="nav nav-tabs mb-3" id="expedienteTabs" role="tablist">
    <li class="nav-item" role="presentation">
        <button class="nav-link active" id="antecedentes-tab" data-bs-toggle="tab" 
                data-bs-target="#antecedentes" type="button" role="tab">
            <i class="bi bi-clipboard-data me-2"></i>Antecedentes
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" id="historial-tab" data-bs-toggle="tab" 
                data-bs-target="#historial" type="button" role="tab">
            <i class="bi bi-clock-history me-2"></i>Historial de Consultas
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" id="prescripciones-tab" data-bs-toggle="tab" 
                data-bs-target="#prescripciones" type="button" role="tab">
            <i class="bi bi-prescription2 me-2"></i>Prescripciones
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" id="citas-tab" data-bs-toggle="tab" 
                data-bs-target="#citas" type="button" role="tab">
            <i class="bi bi-calendar-check me-2"></i>Citas Próximas
        </button>
    </li>
</ul>

<div class="tab-content" id="expedienteTabsContent">
    <!-- Antecedentes -->
    <div class="tab-pane fade show active" id="antecedentes" role="tabpanel">
        <div class="row">
            <div class="col-md-6 mb-3">
                <div class="card h-100">
                    <div class="card-header">
                        <h6 class="mb-0"><i class="bi bi-people me-2"></i>Antecedentes Familiares</h6>
                    </div>
                    <div class="card-body">
                        <p><?= !empty($expediente['antecedentes_familiares']) ? nl2br(htmlspecialchars($expediente['antecedentes_familiares'])) : '<em class="text-muted">No registrados</em>' ?></p>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6 mb-3">
                <div class="card h-100">
                    <div class="card-header">
                        <h6 class="mb-0"><i class="bi bi-person me-2"></i>Antecedentes Personales</h6>
                    </div>
                    <div class="card-body">
                        <p><?= !empty($expediente['antecedentes_personales']) ? nl2br(htmlspecialchars($expediente['antecedentes_personales'])) : '<em class="text-muted">No registrados</em>' ?></p>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6 mb-3">
                <div class="card h-100">
                    <div class="card-header">
                        <h6 class="mb-0"><i class="bi bi-bandaid me-2"></i>Antecedentes Quirúrgicos</h6>
                    </div>
                    <div class="card-body">
                        <p><?= !empty($expediente['antecedentes_quirurgicos']) ? nl2br(htmlspecialchars($expediente['antecedentes_quirurgicos'])) : '<em class="text-muted">No registrados</em>' ?></p>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6 mb-3">
                <div class="card h-100">
                    <div class="card-header">
                        <h6 class="mb-0"><i class="bi bi-exclamation-triangle me-2"></i>Antecedentes Alérgicos</h6>
                    </div>
                    <div class="card-body">
                        <?php if (!empty($expediente['antecedentes_alergicos']) || !empty($expediente['alergias'])): ?>
                            <div class="alert alert-warning mb-2">
                                <strong><i class="bi bi-exclamation-triangle me-1"></i>ALERGIAS:</strong>
                                <p class="mb-0">
                                    <?= nl2br(htmlspecialchars($expediente['antecedentes_alergicos'] ?? $expediente['alergias'] ?? '')) ?>
                                </p>
                            </div>
                        <?php else: ?>
                            <p class="text-muted"><em>No se registran alergias</em></p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            
            <div class="col-12 mb-3">
                <div class="card">
                    <div class="card-header">
                        <h6 class="mb-0"><i class="bi bi-shield-check me-2"></i>Vacunas</h6>
                    </div>
                    <div class="card-body">
                        <p><?= !empty($expediente['vacunas']) ? nl2br(htmlspecialchars($expediente['vacunas'])) : '<em class="text-muted">No registradas</em>' ?></p>
                    </div>
                </div>
            </div>
            
            <?php if (!empty($expediente['observaciones_generales'])): ?>
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h6 class="mb-0"><i class="bi bi-chat-left-text me-2"></i>Observaciones Generales</h6>
                    </div>
                    <div class="card-body">
                        <p><?= nl2br(htmlspecialchars($expediente['observaciones_generales'])) ?></p>
                    </div>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>
    
    <!-- Historial de Consultas -->
    <div class="tab-pane fade" id="historial" role="tabpanel">
        <?php if (empty($historial_consultas ?? [])): ?>
            <div class="card">
                <div class="card-body text-center py-5">
                    <i class="bi bi-clipboard2-pulse display-1 text-muted"></i>
                    <h4 class="mt-3 text-muted">No hay consultas registradas</h4>
                    <p class="text-muted">El historial de consultas aparecerá aquí</p>
                </div>
            </div>
        <?php else: ?>
            <div class="timeline">
                <?php foreach ($historial_consultas as $consulta): ?>
                    <div class="card mb-3">
                        <div class="card-header bg-light">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-0">
                                        <i class="bi bi-calendar3 me-2"></i>
                                        <?= Util::formatDate($consulta['fecha_cita'] ?? '') ?>
                                        <span class="text-muted">• <?= Util::formatTime($consulta['hora_cita'] ?? '') ?></span>
                                    </h6>
                                    <small class="text-muted">
                                        Consulta: <?= htmlspecialchars($consulta['numero_consulta'] ?? 'N/A') ?>
                                    </small>
                                </div>
                                <span class="badge bg-info">
                                    <?= htmlspecialchars($consulta['especialidad'] ?? 'Consulta General') ?>
                                </span>
                            </div>
                        </div>
                        <div class="card-body">
                            <p class="mb-2">
                                <strong>Médico:</strong> <?= htmlspecialchars($consulta['medico_nombre'] ?? 'N/A') ?>
                            </p>
                            
                            <?php if (!empty($consulta['motivo_consulta'])): ?>
                                <div class="mb-2">
                                    <strong><i class="bi bi-chat-left-text me-1"></i>Motivo de Consulta:</strong>
                                    <p class="mb-0"><?= nl2br(htmlspecialchars($consulta['motivo_consulta'])) ?></p>
                                </div>
                            <?php endif; ?>
                            
                            <?php if (!empty($consulta['sintomas'])): ?>
                                <div class="mb-2">
                                    <strong><i class="bi bi-thermometer me-1"></i>Síntomas:</strong>
                                    <p class="mb-0"><?= nl2br(htmlspecialchars($consulta['sintomas'])) ?></p>
                                </div>
                            <?php endif; ?>
                            
                            <?php if (!empty($consulta['diagnostico_principal'])): ?>
                                <div class="alert alert-info mb-2">
                                    <strong><i class="bi bi-stethoscope me-1"></i>Diagnóstico Principal:</strong>
                                    <p class="mb-0"><?= nl2br(htmlspecialchars($consulta['diagnostico_principal'])) ?></p>
                                </div>
                            <?php endif; ?>
                            
                            <?php if (!empty($consulta['diagnosticos_secundarios'])): ?>
                                <div class="mb-2">
                                    <strong><i class="bi bi-clipboard-check me-1"></i>Diagnósticos Secundarios:</strong>
                                    <p class="mb-0"><?= nl2br(htmlspecialchars($consulta['diagnosticos_secundarios'])) ?></p>
                                </div>
                            <?php endif; ?>
                            
                            <?php if (!empty($consulta['plan_tratamiento'])): ?>
                                <div class="mb-2">
                                    <strong><i class="bi bi-clipboard-plus me-1"></i>Plan de Tratamiento:</strong>
                                    <p class="mb-0"><?= nl2br(htmlspecialchars($consulta['plan_tratamiento'])) ?></p>
                                </div>
                            <?php endif; ?>
                            
                            <?php if (!empty($consulta['indicaciones'])): ?>
                                <div class="mb-2">
                                    <strong><i class="bi bi-card-checklist me-1"></i>Indicaciones:</strong>
                                    <p class="mb-0"><?= nl2br(htmlspecialchars($consulta['indicaciones'])) ?></p>
                                </div>
                            <?php endif; ?>
                            
                            <!-- Signos Vitales -->
                            <?php if (!empty($consulta['peso']) || !empty($consulta['presion_sistolica']) || !empty($consulta['temperatura'])): ?>
                                <div class="mt-3 p-2 bg-light rounded">
                                    <strong><i class="bi bi-heart-pulse me-1"></i>Signos Vitales:</strong>
                                    <div class="row mt-2">
                                        <?php if (!empty($consulta['peso'])): ?>
                                            <div class="col-md-3">
                                                <small class="text-muted">Peso:</small> <?= $consulta['peso'] ?> kg
                                            </div>
                                        <?php endif; ?>
                                        <?php if (!empty($consulta['altura'])): ?>
                                            <div class="col-md-3">
                                                <small class="text-muted">Altura:</small> <?= $consulta['altura'] ?> cm
                                            </div>
                                        <?php endif; ?>
                                        <?php if (!empty($consulta['temperatura'])): ?>
                                            <div class="col-md-3">
                                                <small class="text-muted">Temperatura:</small> <?= $consulta['temperatura'] ?>°C
                                            </div>
                                        <?php endif; ?>
                                        <?php if (!empty($consulta['presion_sistolica']) && !empty($consulta['presion_diastolica'])): ?>
                                            <div class="col-md-3">
                                                <small class="text-muted">Presión:</small> <?= $consulta['presion_sistolica'] ?>/<?= $consulta['presion_diastolica'] ?> mmHg
                                            </div>
                                        <?php endif; ?>
                                        <?php if (!empty($consulta['frecuencia_cardiaca'])): ?>
                                            <div class="col-md-3">
                                                <small class="text-muted">FC:</small> <?= $consulta['frecuencia_cardiaca'] ?> lpm
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
    
    <!-- Prescripciones -->
    <div class="tab-pane fade" id="prescripciones" role="tabpanel">
        <?php if (empty($prescripciones ?? [])): ?>
            <div class="card">
                <div class="card-body text-center py-5">
                    <i class="bi bi-prescription2 display-1 text-muted"></i>
                    <h4 class="mt-3 text-muted">No hay prescripciones registradas</h4>
                    <p class="text-muted">Las prescripciones médicas aparecerán aquí</p>
                </div>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Fecha</th>
                            <th>Medicamento</th>
                            <th>Dosis</th>
                            <th>Frecuencia</th>
                            <th>Duración</th>
                            <th>Médico</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($prescripciones as $prescripcion): ?>
                            <tr>
                                <td><?= Util::formatDate($prescripcion['fecha_consulta'] ?? '') ?></td>
                                <td>
                                    <strong><?= htmlspecialchars($prescripcion['nombre_comercial'] ?? 'N/A') ?></strong>
                                    <br>
                                    <small class="text-muted"><?= htmlspecialchars($prescripcion['nombre_generico'] ?? '') ?></small>
                                </td>
                                <td><?= htmlspecialchars($prescripcion['dosis'] ?? 'N/A') ?></td>
                                <td><?= htmlspecialchars($prescripcion['frecuencia'] ?? 'N/A') ?></td>
                                <td><?= htmlspecialchars($prescripcion['duracion'] ?? 'N/A') ?></td>
                                <td><?= htmlspecialchars($prescripcion['medico_nombre'] ?? 'N/A') ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
    
    <!-- Citas Próximas -->
    <div class="tab-pane fade" id="citas" role="tabpanel">
        <?php if (empty($citas_proximas ?? [])): ?>
            <div class="card">
                <div class="card-body text-center py-5">
                    <i class="bi bi-calendar-x display-1 text-muted"></i>
                    <h4 class="mt-3 text-muted">No hay citas programadas</h4>
                    <a href="<?= Router::url('citas/crear?paciente_id=' . ($expediente['paciente_id'] ?? '')) ?>" 
                       class="btn btn-primary mt-3">
                        <i class="bi bi-calendar-plus me-2"></i>Programar Nueva Cita
                    </a>
                </div>
            </div>
        <?php else: ?>
            <?php foreach ($citas_proximas as $cita): ?>
                <div class="card mb-3">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-md-3">
                                <h5 class="mb-0">
                                    <i class="bi bi-calendar3 me-2"></i>
                                    <?= Util::formatDate($cita['fecha_cita'] ?? '') ?>
                                </h5>
                                <p class="text-muted mb-0">
                                    <i class="bi bi-clock me-1"></i>
                                    <?= Util::formatTime($cita['hora_cita'] ?? '') ?>
                                </p>
                            </div>
                            <div class="col-md-6">
                                <p class="mb-1">
                                    <strong><?= htmlspecialchars($cita['medico_nombre'] ?? 'N/A') ?></strong>
                                </p>
                                <p class="text-muted mb-1">
                                    <i class="bi bi-hospital me-1"></i>
                                    <?= htmlspecialchars($cita['especialidad'] ?? 'N/A') ?>
                                    <?php if (!empty($cita['consultorio'])): ?>
                                        - <?= htmlspecialchars($cita['consultorio']) ?>
                                    <?php endif; ?>
                                </p>
                                <p class="mb-0">
                                    <small><?= htmlspecialchars($cita['motivo_consulta'] ?? '') ?></small>
                                </p>
                            </div>
                            <div class="col-md-3 text-end">
                                <span class="badge bg-success">
                                    <?= ucfirst($cita['estado'] ?? 'programada') ?>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

<style>
.avatar-lg {
    width: 150px;
    height: 150px;
}

/* Estilos para impresión */
@media print {
    /* Ocultar elementos de navegación */
    .btn, .btn-group, button, 
    nav, .sidebar, header, footer,
    .no-print {
        display: none !important;
    }
    
    /* Ajustar pestañas para impresión */
    .nav-tabs {
        display: none !important;
    }
    
    .tab-content .tab-pane {
        display: block !important;
        opacity: 1 !important;
        page-break-after: always;
    }
    
    /* Ajustar tamaños */
    body {
        font-size: 12pt;
    }
    
    .card {
        border: 1px solid #000;
        page-break-inside: avoid;
        margin-bottom: 10px;
    }
    
    /* Expandir todo el contenido */
    .container, .container-fluid {
        width: 100% !important;
        max-width: 100% !important;
        margin: 0 !important;
        padding: 10px !important;
    }
    
    .row {
        margin: 0 !important;
    }
    
    /* Ajustar badges para impresión */
    .badge {
        border: 1px solid #000;
        background-color: transparent !important;
        color: #000 !important;
    }
    
    /* Alertas en impresión */
    .alert {
        border: 2px solid #000;
        background-color: transparent !important;
    }
    
    /* Título del documento */
    h2:first-of-type::before {
        content: "EXPEDIENTE MÉDICO";
        display: block;
        text-align: center;
        font-size: 18pt;
        font-weight: bold;
        margin-bottom: 20px;
        border-bottom: 2px solid #000;
        padding-bottom: 10px;
    }
}

/* Botón de impresión flotante */
<?php if (isset($show_print) && $show_print): ?>
.print-btn-float {
    position: fixed;
    bottom: 30px;
    right: 30px;
    z-index: 1000;
}

@media print {
    .print-btn-float {
        display: none !important;
    }
}
<?php endif; ?>
</style>

<?php if (isset($show_print) && $show_print): ?>
<div class="print-btn-float no-print">
    <button onclick="window.print()" class="btn btn-lg btn-primary shadow">
        <i class="bi bi-printer-fill me-2"></i>Imprimir
    </button>
</div>

<script>
// Auto-abrir diálogo de impresión
window.addEventListener('load', function() {
    // Opcional: descomentar para abrir automáticamente
    // setTimeout(function() { window.print(); }, 500);
});
</script>
<?php endif; ?>