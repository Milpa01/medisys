<div class="container-fluid">
    <!-- Encabezado -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-0">
                        <i class="bi bi-calendar-check me-2"></i>
                        Cita <?= htmlspecialchars($cita['codigo_cita']) ?>
                    </h1>
                    <p class="text-muted mb-0">
                        <?= Util::formatDate($cita['fecha_cita']) ?> a las <?= Util::formatTime($cita['hora_cita']) ?>
                    </p>
                </div>
                <div>
                    <!-- Estado de la cita -->
                    <span class="badge bg-<?= 
                        $cita['estado'] === 'programada' ? 'warning' : 
                        ($cita['estado'] === 'confirmada' ? 'info' : 
                        ($cita['estado'] === 'en_curso' ? 'primary' : 
                        ($cita['estado'] === 'completada' ? 'success' : 
                        ($cita['estado'] === 'cancelada' ? 'danger' : 'secondary')))) 
                    ?> fs-6 me-2">
                        <?= ucfirst(str_replace('_', ' ', $cita['estado'])) ?>
                    </span>
                    
                    <!-- Botones de acción según rol y estado -->
                    <?php if (Auth::hasRole('administrador') || Auth::hasRole('secretario')): ?>
                        <?php if (!in_array($cita['estado'], ['completada', 'cancelada'])): ?>
                            <a href="<?= Util::url('citas/editar?id=' . $cita['id']) ?>" class="btn btn-primary btn-sm">
                                <i class="bi bi-pencil-square"></i> Editar
                            </a>
                        <?php endif; ?>
                    <?php endif; ?>
                    
                    <?php if (Auth::hasRole('medico') && $cita['estado'] === 'confirmada'): ?>
                        <a href="<?= Util::url('consultas/nueva?cita_id=' . $cita['id']) ?>" class="btn btn-success btn-sm">
                            <i class="bi bi-clipboard-plus"></i> Iniciar Consulta
                        </a>
                    <?php endif; ?>
                    
                    <a href="<?= Util::url('citas') ?>" class="btn btn-secondary btn-sm">
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
                        <i class="bi bi-calendar-event me-2"></i>
                        Detalles de la Cita
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <td class="fw-bold">Código de cita:</td>
                                    <td><?= htmlspecialchars($cita['codigo_cita']) ?></td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Fecha y hora:</td>
                                    <td>
                                        <?= Util::formatDate($cita['fecha_cita']) ?><br>
                                        <small class="text-muted"><?= Util::formatTime($cita['hora_cita']) ?></small>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Duración:</td>
                                    <td><?= $cita['duracion_minutos'] ?> minutos</td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Tipo de cita:</td>
                                    <td>
                                        <span class="badge bg-<?= 
                                            $cita['tipo_cita'] === 'primera_vez' ? 'info' : 
                                            ($cita['tipo_cita'] === 'control' ? 'success' : 
                                            ($cita['tipo_cita'] === 'emergencia' ? 'danger' : 'warning')) 
                                        ?>">
                                            <?= ucfirst(str_replace('_', ' ', $cita['tipo_cita'])) ?>
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Estado:</td>
                                    <td>
                                        <span class="badge bg-<?= 
                                            $cita['estado'] === 'programada' ? 'warning' : 
                                            ($cita['estado'] === 'confirmada' ? 'info' : 
                                            ($cita['estado'] === 'en_curso' ? 'primary' : 
                                            ($cita['estado'] === 'completada' ? 'success' : 
                                            ($cita['estado'] === 'cancelada' ? 'danger' : 'secondary')))) 
                                        ?>">
                                            <?= ucfirst(str_replace('_', ' ', $cita['estado'])) ?>
                                        </span>
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <td class="fw-bold">Costo:</td>
                                    <td>
                                        <?php if ($cita['costo'] > 0): ?>
                                            <?= Util::formatMoney($cita['costo']) ?>
                                        <?php else: ?>
                                            <span class="text-muted">No especificado</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Registrado por:</td>
                                    <td><?= htmlspecialchars($cita['registrado_por']) ?></td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Fecha de registro:</td>
                                    <td><?= Util::formatDateTime($cita['created_at']) ?></td>
                                </tr>
                                <?php if ($cita['updated_at'] != $cita['created_at']): ?>
                                <tr>
                                    <td class="fw-bold">Última modificación:</td>
                                    <td><?= Util::formatDateTime($cita['updated_at']) ?></td>
                                </tr>
                                <?php endif; ?>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Motivo de Consulta -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-chat-square-text me-2"></i>
                        Motivo de Consulta
                    </h5>
                </div>
                <div class="card-body">
                    <p class="mb-0"><?= nl2br(htmlspecialchars($cita['motivo_consulta'])) ?></p>
                </div>
            </div>

            <!-- Notas y Observaciones -->
            <?php if ($cita['notas'] || $cita['observaciones']): ?>
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-sticky me-2"></i>
                        Notas y Observaciones
                    </h5>
                </div>
                <div class="card-body">
                    <?php if ($cita['notas']): ?>
                        <div class="mb-3">
                            <label class="fw-bold">Notas:</label>
                            <p class="mb-0"><?= nl2br(htmlspecialchars($cita['notas'])) ?></p>
                        </div>
                    <?php endif; ?>
                    
                    <?php if ($cita['observaciones']): ?>
                        <div class="mb-0">
                            <label class="fw-bold">Observaciones:</label>
                            <p class="mb-0"><?= nl2br(htmlspecialchars($cita['observaciones'])) ?></p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            <?php endif; ?>

            <!-- Acciones Rápidas según Estado -->
            <?php if (!in_array($cita['estado'], ['completada', 'cancelada'])): ?>
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-lightning me-2"></i>
                        Acciones Rápidas
                    </h5>
                </div>
                <div class="card-body">
                    <div class="d-flex flex-wrap gap-2">
                        <?php if ($cita['estado'] === 'programada'): ?>
                            <a href="<?= Util::url('citas/cambiarEstado?id=' . $cita['id'] . '&estado=confirmada') ?>" 
                               class="btn btn-info btn-sm">
                                <i class="bi bi-check-circle"></i> Confirmar Cita
                            </a>
                        <?php endif; ?>
                        
                        <?php if ($cita['estado'] === 'confirmada' && Auth::hasRole('medico')): ?>
                            <a href="<?= Util::url('citas/cambiarEstado?id=' . $cita['id'] . '&estado=en_curso') ?>" 
                               class="btn btn-primary btn-sm">
                                <i class="bi bi-play-circle"></i> Iniciar Consulta
                            </a>
                        <?php endif; ?>
                        
                        <?php if (in_array($cita['estado'], ['programada', 'confirmada'])): ?>
                            <a href="<?= Util::url('citas/cambiarEstado?id=' . $cita['id'] . '&estado=no_asistio') ?>" 
                               class="btn btn-warning btn-sm" 
                               onclick="return confirm('¿Está seguro que el paciente no asistió?')">
                                <i class="bi bi-person-x"></i> No Asistió
                            </a>
                            <a href="<?= Util::url('citas/cambiarEstado?id=' . $cita['id'] . '&estado=cancelada') ?>" 
                               class="btn btn-danger btn-sm" 
                               onclick="return confirm('¿Está seguro que desea cancelar esta cita?')">
                                <i class="bi bi-x-circle"></i> Cancelar Cita
                            </a>
                        <?php endif; ?>
                    </div>
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
                    <div class="d-flex align-items-center mb-3">
                        <div class="bg-light rounded-circle d-flex align-items-center justify-content-center me-3" 
                             style="width: 50px; height: 50px;">
                            <i class="bi bi-person-fill text-muted"></i>
                        </div>
                        <div>
                            <h6 class="mb-1">
                                <a href="<?= Util::url('pacientes/ver?id=' . $cita['paciente_id']) ?>" 
                                   class="text-decoration-none">
                                    <?= htmlspecialchars($cita['paciente_nombre']) ?>
                                </a>
                            </h6>
                            <small class="text-muted"><?= htmlspecialchars($cita['codigo_paciente']) ?></small>
                        </div>
                    </div>
                    
                    <?php if ($cita['paciente_telefono']): ?>
                        <p class="mb-2">
                            <i class="bi bi-telephone me-2"></i>
                            <a href="tel:<?= htmlspecialchars($cita['paciente_telefono']) ?>">
                                <?= htmlspecialchars($cita['paciente_telefono']) ?>
                            </a>
                        </p>
                    <?php endif; ?>
                    
                    <?php if ($cita['paciente_email']): ?>
                        <p class="mb-2">
                            <i class="bi bi-envelope me-2"></i>
                            <a href="mailto:<?= htmlspecialchars($cita['paciente_email']) ?>">
                                <?= htmlspecialchars($cita['paciente_email']) ?>
                            </a>
                        </p>
                    <?php endif; ?>
                    
                    <div class="d-grid gap-2">
                        <a href="<?= Util::url('pacientes/ver?id=' . $cita['paciente_id']) ?>" 
                           class="btn btn-outline-primary btn-sm">
                            <i class="bi bi-eye"></i> Ver Expediente
                        </a>
                        <a href="<?= Util::url('citas/crear?paciente_id=' . $cita['paciente_id']) ?>" 
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
                        Información del Médico
                    </h6>
                </div>
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="bg-primary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center me-3" 
                             style="width: 50px; height: 50px;">
                            <i class="bi bi-person-fill-check text-primary"></i>
                        </div>
                        <div>
                            <h6 class="mb-1">Dr. <?= htmlspecialchars($cita['medico_nombre']) ?></h6>
                            <small class="text-muted"><?= htmlspecialchars($cita['especialidad']) ?></small>
                        </div>
                    </div>
                    
                    <?php if ($cita['consultorio']): ?>
                        <p class="mb-2">
                            <i class="bi bi-geo-alt me-2"></i>
                            Consultorio: <?= htmlspecialchars($cita['consultorio']) ?>
                        </p>
                    <?php endif; ?>
                    
                    <?php if ($cita['costo_consulta'] > 0): ?>
                        <p class="mb-2">
                            <i class="bi bi-currency-dollar me-2"></i>
                            Costo: <?= Util::formatMoney($cita['costo_consulta']) ?>
                        </p>
                    <?php endif; ?>
                    
                    <div class="d-grid">
                        <a href="<?= Util::url('medicos/ver?id=' . $cita['medico_id']) ?>" 
                           class="btn btn-outline-primary btn-sm">
                            <i class="bi bi-eye"></i> Ver Perfil Médico
                        </a>
                    </div>
                </div>
            </div>

            <!-- Timeline de Estados -->
            <div class="card mb-4">
                <div class="card-header">
                    <h6 class="card-title mb-0">
                        <i class="bi bi-clock-history me-2"></i>
                        Historial de Estados
                    </h6>
                </div>
                <div class="card-body">
                    <div class="timeline-sm">
                        <div class="timeline-item">
                            <div class="timeline-marker bg-secondary"></div>
                            <div class="timeline-content">
                                <small class="text-muted">Programada</small><br>
                                <small><?= Util::formatDateTime($cita['created_at']) ?></small>
                            </div>
                        </div>
                        
                        <?php if ($cita['estado'] !== 'programada'): ?>
                        <div class="timeline-item">
                            <div class="timeline-marker bg-<?= 
                                $cita['estado'] === 'confirmada' ? 'info' : 
                                ($cita['estado'] === 'en_curso' ? 'primary' : 
                                ($cita['estado'] === 'completada' ? 'success' : 
                                ($cita['estado'] === 'cancelada' ? 'danger' : 'warning'))) 
                            ?>"></div>
                            <div class="timeline-content">
                                <small class="text-muted"><?= ucfirst(str_replace('_', ' ', $cita['estado'])) ?></small><br>
                                <small><?= Util::formatDateTime($cita['updated_at']) ?></small>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Recordatorios -->
            <?php if (in_array($cita['estado'], ['programada', 'confirmada'])): ?>
            <div class="card">
                <div class="card-header">
                    <h6 class="card-title mb-0">
                        <i class="bi bi-bell me-2"></i>
                        Recordatorios
                    </h6>
                </div>
                <div class="card-body">
                    <?php 
                    $fechaCita = strtotime($cita['fecha_cita'] . ' ' . $cita['hora_cita']);
                    $ahora = time();
                    $diferencia = $fechaCita - $ahora;
                    $dias = floor($diferencia / 86400);
                    $horas = floor(($diferencia % 86400) / 3600);
                    ?>
                    
                    <?php if ($diferencia > 0): ?>
                        <div class="alert alert-info py-2 mb-3">
                            <small>
                                <i class="bi bi-clock me-1"></i>
                                <?php if ($dias > 0): ?>
                                    Faltan <?= $dias ?> día<?= $dias != 1 ? 's' : '' ?>
                                    <?php if ($horas > 0): ?>
                                        y <?= $horas ?> hora<?= $horas != 1 ? 's' : '' ?>
                                    <?php endif; ?>
                                <?php elseif ($horas > 0): ?>
                                    Faltan <?= $horas ?> hora<?= $horas != 1 ? 's' : '' ?>
                                <?php else: ?>
                                    Menos de 1 hora
                                <?php endif; ?>
                            </small>
                        </div>
                    <?php elseif ($diferencia > -3600): ?>
                        <div class="alert alert-warning py-2 mb-3">
                            <small>
                                <i class="bi bi-exclamation-triangle me-1"></i>
                                ¡La cita es ahora!
                            </small>
                        </div>
                    <?php else: ?>
                        <div class="alert alert-danger py-2 mb-3">
                            <small>
                                <i class="bi bi-clock-history me-1"></i>
                                Cita vencida
                            </small>
                        </div>
                    <?php endif; ?>
                    
                    <div class="small text-muted">
                        <p class="mb-1">• Confirmar asistencia del paciente</p>
                        <p class="mb-1">• Preparar expediente médico</p>
                        <p class="mb-0">• Verificar disponibilidad de consultorio</p>
                    </div>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- CSS personalizado para timeline pequeña -->
<style>
.timeline-sm .timeline-item {
    display: flex;
    align-items-center;
    margin-bottom: 15px;
}

.timeline-sm .timeline-marker {
    width: 10px;
    height: 10px;
    border-radius: 50%;
    margin-right: 10px;
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
    left: 4px;
    top: 15px;
}
</style>