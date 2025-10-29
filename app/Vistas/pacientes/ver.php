<div class="container-fluid">
    <!-- Encabezado -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-0">
                        <i class="bi bi-person-circle me-2"></i>
                        Expediente de <?= htmlspecialchars($paciente['nombre'] . ' ' . $paciente['apellidos']) ?>
                    </h1>
                    <p class="text-muted mb-0">
                        Código: <?= htmlspecialchars($paciente['codigo_paciente']) ?> | 
                        Edad: <?= $paciente['edad'] ?> años
                    </p>
                </div>
                <div>
                    <?php if (Auth::hasRole('administrador') || Auth::hasRole('secretario')): ?>
                        <a href="<?= Util::url('pacientes/editar?id=' . $paciente['id']) ?>" class="btn btn-primary">
                            <i class="bi bi-pencil-square"></i> Editar
                        </a>
                    <?php endif; ?>
                    <a href="<?= Util::url('citas/crear?paciente_id=' . $paciente['id']) ?>" class="btn btn-success">
                        <i class="bi bi-calendar-plus"></i> Nueva Cita
                    </a>
                    <a href="<?= Util::url('pacientes') ?>" class="btn btn-secondary">
                        <i class="bi bi-arrow-left"></i> Volver
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Mensajes Flash -->
    <?= Flash::display() ?>

    <div class="row">
        <!-- Información Personal -->
        <div class="col-xl-8 col-lg-7">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-person-fill me-2"></i>
                        Información Personal
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <td class="fw-bold">Nombre completo:</td>
                                    <td><?= htmlspecialchars($paciente['nombre'] . ' ' . $paciente['apellidos']) ?></td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Fecha de nacimiento:</td>
                                    <td><?= Util::formatDate($paciente['fecha_nacimiento']) ?></td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Género:</td>
                                    <td>
                                        <?php
                                        $generos = ['M' => 'Masculino', 'F' => 'Femenino', 'Otro' => 'Otro'];
                                        echo $generos[$paciente['genero']] ?? $paciente['genero'];
                                        ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Tipo de sangre:</td>
                                    <td>
                                        <?php if ($paciente['tipo_sangre']): ?>
                                            <span class="badge bg-danger"><?= htmlspecialchars($paciente['tipo_sangre']) ?></span>
                                        <?php else: ?>
                                            <span class="text-muted">No especificado</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">DPI:</td>
                                    <td><?= htmlspecialchars($paciente['dpi'] ?: 'No registrado') ?></td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <td class="fw-bold">Email:</td>
                                    <td>
                                        <?php if ($paciente['email']): ?>
                                            <a href="mailto:<?= htmlspecialchars($paciente['email']) ?>">
                                                <?= htmlspecialchars($paciente['email']) ?>
                                            </a>
                                        <?php else: ?>
                                            <span class="text-muted">No registrado</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Teléfono:</td>
                                    <td>
                                        <?php if ($paciente['telefono']): ?>
                                            <a href="tel:<?= htmlspecialchars($paciente['telefono']) ?>">
                                                <?= htmlspecialchars($paciente['telefono']) ?>
                                            </a>
                                        <?php else: ?>
                                            <span class="text-muted">No registrado</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Celular:</td>
                                    <td>
                                        <?php if ($paciente['celular']): ?>
                                            <a href="tel:<?= htmlspecialchars($paciente['celular']) ?>">
                                                <?= htmlspecialchars($paciente['celular']) ?>
                                            </a>
                                        <?php else: ?>
                                            <span class="text-muted">No registrado</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Ciudad:</td>
                                    <td><?= htmlspecialchars($paciente['ciudad'] ?: 'No especificada') ?></td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Dirección:</td>
                                    <td><?= htmlspecialchars($paciente['direccion'] ?: 'No registrada') ?></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Información Médica -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-heart-pulse me-2"></i>
                        Información Médica
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="fw-bold">Alergias:</label>
                                <p class="mb-0">
                                    <?= $paciente['alergias'] ? nl2br(htmlspecialchars($paciente['alergias'])) : '<span class="text-muted">Ninguna registrada</span>' ?>
                                </p>
                            </div>
                            <div class="mb-3">
                                <label class="fw-bold">Medicamentos actuales:</label>
                                <p class="mb-0">
                                    <?= $paciente['medicamentos_actuales'] ? nl2br(htmlspecialchars($paciente['medicamentos_actuales'])) : '<span class="text-muted">Ninguno registrado</span>' ?>
                                </p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="fw-bold">Enfermedades crónicas:</label>
                                <p class="mb-0">
                                    <?= $paciente['enfermedades_cronicas'] ? nl2br(htmlspecialchars($paciente['enfermedades_cronicas'])) : '<span class="text-muted">Ninguna registrada</span>' ?>
                                </p>
                            </div>
                            <div class="mb-3">
                                <label class="fw-bold">Seguro médico:</label>
                                <p class="mb-0">
                                    <?= htmlspecialchars($paciente['seguro_medico'] ?: 'No especificado') ?>
                                    <?php if ($paciente['numero_seguro']): ?>
                                        <br><small class="text-muted">No. <?= htmlspecialchars($paciente['numero_seguro']) ?></small>
                                    <?php endif; ?>
                                </p>
                            </div>
                        </div>
                    </div>
                    
                    <?php if ($paciente['observaciones']): ?>
                        <div class="mt-3">
                            <label class="fw-bold">Observaciones generales:</label>
                            <p class="mb-0"><?= nl2br(htmlspecialchars($paciente['observaciones'])) ?></p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Historial Médico -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-clock-history me-2"></i>
                        Historial Médico
                    </h5>
                </div>
                <div class="card-body">
                    <?php if (!empty($historial)): ?>
                        <div class="timeline">
                            <?php foreach ($historial as $consulta): ?>
                                <div class="timeline-item mb-3">
                                    <div class="d-flex">
                                        <div class="flex-shrink-0">
                                            <div class="timeline-marker bg-primary"></div>
                                        </div>
                                        <div class="flex-grow-1 ms-3">
                                            <div class="card">
                                                <div class="card-body">
                                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                                        <h6 class="card-title mb-1">
                                                            <?= Util::formatDate($consulta['fecha_cita']) ?>
                                                        </h6>
                                                        <span class="badge bg-secondary"><?= htmlspecialchars($consulta['especialidad']) ?></span>
                                                    </div>
                                                    <p class="text-muted mb-2">
                                                        <strong>Dr. <?= htmlspecialchars($consulta['medico_nombre']) ?></strong>
                                                    </p>
                                                    <p class="mb-2">
                                                        <strong>Motivo:</strong> <?= htmlspecialchars($consulta['motivo_consulta']) ?>
                                                    </p>
                                                    <?php if ($consulta['diagnostico_principal']): ?>
                                                        <p class="mb-2">
                                                            <strong>Diagnóstico:</strong> <?= htmlspecialchars($consulta['diagnostico_principal']) ?>
                                                        </p>
                                                    <?php endif; ?>
                                                    <?php if ($consulta['plan_tratamiento']): ?>
                                                        <p class="mb-0">
                                                            <strong>Tratamiento:</strong> <?= nl2br(htmlspecialchars($consulta['plan_tratamiento'])) ?>
                                                        </p>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-4">
                            <i class="bi bi-journal-medical display-1 text-muted"></i>
                            <p class="text-muted mt-2">No hay historial médico registrado</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-xl-4 col-lg-5">
            <!-- Foto del paciente -->
            <div class="card mb-4">
                <div class="card-body text-center">
                    <?php if ($paciente['imagen']): ?>
                        <img src="<?= Util::asset('uploads/pacientes/' . $paciente['imagen']) ?>" 
                             alt="Foto del paciente" class="rounded-circle mb-3" width="120" height="120">
                    <?php else: ?>
                        <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center mb-3" 
                             style="width: 120px; height: 120px;">
                            <i class="bi bi-person-fill display-4 text-muted"></i>
                        </div>
                    <?php endif; ?>
                    
                    <h5 class="mb-1"><?= htmlspecialchars($paciente['nombre'] . ' ' . $paciente['apellidos']) ?></h5>
                    <p class="text-muted mb-0"><?= $paciente['edad'] ?> años</p>
                    
                    <div class="mt-3">
                        <span class="badge <?= $paciente['is_active'] ? 'bg-success' : 'bg-danger' ?>">
                            <?= $paciente['is_active'] ? 'Activo' : 'Inactivo' ?>
                        </span>
                    </div>
                </div>
            </div>

            <!-- Contacto de Emergencia -->
            <?php if ($paciente['contacto_emergencia'] || $paciente['telefono_emergencia']): ?>
                <div class="card mb-4">
                    <div class="card-header">
                        <h6 class="card-title mb-0">
                            <i class="bi bi-exclamation-triangle me-2"></i>
                            Contacto de Emergencia
                        </h6>
                    </div>
                    <div class="card-body">
                        <?php if ($paciente['contacto_emergencia']): ?>
                            <p class="mb-1">
                                <strong><?= htmlspecialchars($paciente['contacto_emergencia']) ?></strong>
                            </p>
                        <?php endif; ?>
                        <?php if ($paciente['telefono_emergencia']): ?>
                            <p class="mb-0">
                                <i class="bi bi-telephone me-1"></i>
                                <a href="tel:<?= htmlspecialchars($paciente['telefono_emergencia']) ?>">
                                    <?= htmlspecialchars($paciente['telefono_emergencia']) ?>
                                </a>
                            </p>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Citas Pendientes -->
            <div class="card mb-4">
                <div class="card-header">
                    <h6 class="card-title mb-0">
                        <i class="bi bi-calendar-check me-2"></i>
                        Citas Pendientes
                    </h6>
                </div>
                <div class="card-body">
                    <?php if (!empty($citas_pendientes)): ?>
                        <?php foreach ($citas_pendientes as $cita): ?>
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div>
                                    <div class="fw-bold"><?= Util::formatDate($cita['fecha_cita']) ?></div>
                                    <small class="text-muted">
                                        <?= Util::formatTime($cita['hora_cita']) ?> - 
                                        Dr. <?= htmlspecialchars($cita['medico_nombre']) ?>
                                    </small>
                                    <div class="small"><?= htmlspecialchars($cita['especialidad']) ?></div>
                                </div>
                                <span class="badge bg-<?= $cita['estado'] === 'confirmada' ? 'success' : 'warning' ?>">
                                    <?= ucfirst($cita['estado']) ?>
                                </span>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="text-center py-3">
                            <i class="bi bi-calendar-x display-6 text-muted"></i>
                            <p class="text-muted mt-2 mb-0">No hay citas pendientes</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Expediente Médico -->
            <?php if ($expediente): ?>
                <div class="card mb-4">
                    <div class="card-header">
                        <h6 class="card-title mb-0">
                            <i class="bi bi-folder-medical me-2"></i>
                            Expediente Médico
                        </h6>
                    </div>
                    <div class="card-body">
                        <p class="mb-2">
                            <strong>No. Expediente:</strong> <?= htmlspecialchars($expediente['numero_expediente']) ?>
                        </p>
                        <p class="mb-2">
                            <strong>Creado:</strong> <?= Util::formatDate($expediente['created_at']) ?>
                        </p>
                        
                        <?php if ($expediente['grupo_sanguineo']): ?>
                            <p class="mb-2">
                                <strong>Grupo sanguíneo:</strong> 
                                <span class="badge bg-danger">
                                    <?= htmlspecialchars($expediente['grupo_sanguineo'] . $expediente['factor_rh']) ?>
                                </span>
                            </p>
                        <?php endif; ?>
                        
                        <?php if ($expediente['peso_actual'] || $expediente['altura_actual']): ?>
                            <div class="row text-center">
                                <?php if ($expediente['peso_actual']): ?>
                                    <div class="col-6">
                                        <div class="border rounded p-2">
                                            <div class="h5 mb-0"><?= number_format($expediente['peso_actual'], 1) ?></div>
                                            <small class="text-muted">kg</small>
                                        </div>
                                    </div>
                                <?php endif; ?>
                                <?php if ($expediente['altura_actual']): ?>
                                    <div class="col-6">
                                        <div class="border rounded p-2">
                                            <div class="h5 mb-0"><?= number_format($expediente['altura_actual'], 2) ?></div>
                                            <small class="text-muted">cm</small>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </div>
                            
                            <?php if ($expediente['imc']): ?>
                                <div class="text-center mt-2">
                                    <span class="badge bg-info">IMC: <?= number_format($expediente['imc'], 1) ?></span>
                                </div>
                            <?php endif; ?>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Información Adicional -->
            <div class="card">
                <div class="card-header">
                    <h6 class="card-title mb-0">
                        <i class="bi bi-info-circle me-2"></i>
                        Información del Registro
                    </h6>
                </div>
                <div class="card-body">
                    <p class="mb-2">
                        <strong>Registrado:</strong> <?= Util::formatDateTime($paciente['created_at']) ?>
                    </p>
                    <p class="mb-0">
                        <strong>Última actualización:</strong> <?= Util::formatDateTime($paciente['updated_at']) ?>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- CSS personalizado para la timeline -->
<style>
.timeline-marker {
    width: 12px;
    height: 12px;
    border-radius: 50%;
    margin-top: 6px;
}

.timeline-item:not(:last-child) .timeline-marker::after {
    content: '';
    position: absolute;
    width: 2px;
    height: 30px;
    background-color: #dee2e6;
    left: 5px;
    top: 12px;
}

.timeline-marker {
    position: relative;
}
</style>