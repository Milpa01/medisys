<?php if (Auth::hasRole('secretario')): ?>
<!-- Dashboard específico para Secretarios -->
<div class="row">
    <!-- Panel de Tareas del Día -->
    <div class="col-md-4 mb-4">
        <div class="card border-primary">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">
                    <i class="bi bi-list-check me-2"></i>Tareas del Día
                </h5>
            </div>
            <div class="card-body">
                <div class="list-group list-group-flush">
                    <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                        <span><i class="bi bi-calendar-check text-success me-2"></i>Confirmar citas</span>
                        <span class="badge bg-warning"><?= count(array_filter($citas_hoy ?? [], fn($c) => $c['estado'] == 'programada')) ?></span>
                    </div>
                    <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                        <span><i class="bi bi-telephone text-info me-2"></i>Llamadas pendientes</span>
                        <span class="badge bg-info">
                            <?= count(array_filter($citas_hoy ?? [], fn($c) => in_array($c['estado'], ['programada', 'confirmada']))) ?>
                        </span>
                    </div>
                </div>
                
                <div class="mt-3 d-grid gap-2">
                    <a href="<?= Router::url('pacientes/crear') ?>" class="btn btn-outline-primary btn-sm">
                        <i class="bi bi-person-plus me-1"></i>Registrar Paciente
                    </a>
                    <a href="<?= Router::url('citas/crear') ?>" class="btn btn-outline-success btn-sm">
                        <i class="bi bi-calendar-plus me-1"></i>Nueva Cita
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Citas del día para secretarios -->
    <div class="col-md-8 mb-4">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="bi bi-calendar-check text-primary me-2"></i>
                    Citas de Hoy - Control
                </h5>
                <span class="badge bg-primary"><?= count($citas_hoy ?? []) ?></span>
            </div>
            <div class="card-body">
                <?php if (empty($citas_hoy)): ?>
                    <div class="text-center py-3">
                        <i class="bi bi-calendar-x display-4 text-muted"></i>
                        <p class="mt-2 text-muted">No hay citas programadas para hoy</p>
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Hora</th>
                                    <th>Paciente</th>
                                    <th>Médico</th>
                                    <th>Estado</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($citas_hoy as $cita): ?>
                                    <tr>
                                        <td><strong><?= Util::formatTime($cita['hora_cita']) ?></strong></td>
                                        <td>
                                            <?= htmlspecialchars($cita['paciente_nombre']) ?>
                                            <?php if (!empty($cita['telefono_paciente'])): ?>
                                                <br><small class="text-muted">
                                                    <i class="bi bi-telephone me-1"></i><?= $cita['telefono_paciente'] ?>
                                                </small>
                                            <?php endif; ?>
                                        </td>
                                        <td>Dr. <?= htmlspecialchars($cita['medico_nombre']) ?></td>
                                        <td>
                                            <?php
                                            $badgeClass = match($cita['estado']) {
                                                'programada' => 'bg-secondary',
                                                'confirmada' => 'bg-success',
                                                'en_curso' => 'bg-primary',
                                                'completada' => 'bg-info',
                                                default => 'bg-secondary'
                                            };
                                            ?>
                                            <span class="badge <?= $badgeClass ?>">
                                                <?= ucfirst(str_replace('_', ' ', $cita['estado'])) ?>
                                            </span>
                                        </td>
                                        <td>
                                            <div class="btn-group btn-group-sm">
                                                <?php if ($cita['estado'] == 'programada'): ?>
                                                    <a href="<?= Router::url('citas/cambiarEstado?id=' . $cita['id'] . '&estado=confirmada') ?>" 
                                                       class="btn btn-success btn-sm" title="Confirmar"
                                                       onclick="return confirm('¿Marcar como confirmada?')">
                                                        <i class="bi bi-check"></i>
                                                    </a>
                                                <?php endif; ?>
                                                <a href="<?= Router::url('citas/ver?id=' . $cita['id']) ?>" 
                                                   class="btn btn-info btn-sm" title="Ver">
                                                    <i class="bi bi-eye"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php elseif (Auth::hasRole('medico')): ?>
<!-- Dashboard específico para Médicos -->
<div class="row">
    <!-- Mis citas de hoy -->
    <div class="col-md-8 mb-4">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="bi bi-calendar-check text-primary me-2"></i>
                    Mi Agenda de Hoy
                </h5>
                <a href="<?= Router::url('citas/agenda') ?>" class="btn btn-sm btn-primary">
                    <i class="bi bi-calendar-week me-1"></i>Ver Agenda Completa
                </a>
            </div>
            <div class="card-body">
                <?php 
                // Filtrar citas del médico actual
                $misCitas = [];
                if (!empty($citas_hoy)) {
                    // Aquí se filtrarían las citas del médico logueado
                    $misCitas = $citas_hoy; // Placeholder - se debería filtrar por médico
                }
                ?>
                
                <?php if (empty($misCitas)): ?>
                    <div class="text-center py-3">
                        <i class="bi bi-calendar-check display-4 text-success"></i>
                        <p class="mt-2 text-muted">No tienes citas programadas para hoy</p>
                        <p class="text-muted">¡Perfecto momento para ponerte al día!</p>
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Hora</th>
                                    <th>Paciente</th>
                                    <th>Motivo</th>
                                    <th>Estado</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($misCitas as $cita): ?>
                                    <tr>
                                        <td><strong><?= Util::formatTime($cita['hora_cita']) ?></strong></td>
                                        <td><?= htmlspecialchars($cita['paciente_nombre']) ?></td>
                                        <td><?= htmlspecialchars(Util::truncate($cita['motivo_consulta'], 50)) ?></td>
                                        <td>
                                            <span class="badge <?= match($cita['estado']) {
                                                'confirmada' => 'bg-success',
                                                'en_curso' => 'bg-primary',
                                                'completada' => 'bg-info',
                                                default => 'bg-secondary'
                                            } ?>">
                                                <?= ucfirst(str_replace('_', ' ', $cita['estado'])) ?>
                                            </span>
                                        </td>
                                        <td>
                                            <?php if ($cita['estado'] == 'confirmada'): ?>
                                                <a href="<?= Router::url('consultas/nueva?cita_id=' . $cita['id']) ?>" 
                                                   class="btn btn-success btn-sm">
                                                    <i class="bi bi-play-fill me-1"></i>Iniciar Consulta
                                                </a>
                                            <?php else: ?>
                                                <a href="<?= Router::url('citas/ver?id=' . $cita['id']) ?>" 
                                                   class="btn btn-info btn-sm">
                                                    <i class="bi bi-eye me-1"></i>Ver
                                                </a>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <!-- Panel de acciones para médicos -->
    <div class="col-md-4 mb-4">
        <div class="card border-success">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0">
                    <i class="bi bi-stethoscope me-2"></i>Panel Médico
                </h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="<?= Router::url('consultas') ?>" class="btn btn-outline-primary">
                        <i class="bi bi-clipboard2-pulse me-2"></i>Mis Consultas
                    </a>
                    <a href="<?= Router::url('pacientes') ?>" class="btn btn-outline-info">
                        <i class="bi bi-people me-2"></i>Buscar Pacientes
                    </a>
                    <a href="<?= Router::url('citas/agenda') ?>" class="btn btn-outline-warning">
                        <i class="bi bi-calendar-week me-2"></i>Mi Agenda
                    </a>
                </div>
                
                <hr>
                
                <div class="text-center">
                    <small class="text-muted">
                        <i class="bi bi-info-circle me-1"></i>
                        Herramientas médicas
                    </small>
                </div>
            </div>
        </div>
        
        <!-- Estadísticas del médico -->
        <div class="card mt-3">
            <div class="card-header">
                <h6 class="mb-0">
                    <i class="bi bi-graph-up me-2"></i>Mis Estadísticas
                </h6>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-6">
                        <h4 class="text-primary"><?= $stats['consultas_mes'] ?? 0 ?></h4>
                        <small class="text-muted">Consultas del mes</small>
                    </div>
                    <div class="col-6">
                        <h4 class="text-success"><?= count($misCitas ?? []) ?></h4>
                        <small class="text-muted">Citas hoy</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php else: ?>
<!-- Dashboard para Administradores (Vista original) -->
<div class="row">
    <!-- Estadísticas -->
    <div class="col-lg-3 col-md-6 mb-4">
        <div class="stat-card">
            <div class="stat-number"><?= number_format($stats['pacientes']) ?></div>
            <div class="stat-label">
                <i class="bi bi-people-fill me-2"></i>Pacientes Registrados
            </div>
        </div>
    </div>
    
    <div class="col-lg-3 col-md-6 mb-4">
        <div class="stat-card" style="background: linear-gradient(135deg, #10b981, #059669);">
            <div class="stat-number"><?= number_format($stats['citas_hoy']) ?></div>
            <div class="stat-label">
                <i class="bi bi-calendar-check-fill me-2"></i>Citas de Hoy
            </div>
        </div>
    </div>
    
    <div class="col-lg-3 col-md-6 mb-4">
        <div class="stat-card" style="background: linear-gradient(135deg, #f59e0b, #d97706);">
            <div class="stat-number"><?= number_format($stats['medicos']) ?></div>
            <div class="stat-label">
                <i class="bi bi-person-badge-fill me-2"></i>Médicos Activos
            </div>
        </div>
    </div>
    
    <div class="col-lg-3 col-md-6 mb-4">
        <div class="stat-card" style="background: linear-gradient(135deg, #8b5cf6, #7c3aed);">
            <div class="stat-number"><?= number_format($stats['consultas_mes']) ?></div>
            <div class="stat-label">
                <i class="bi bi-clipboard2-pulse-fill me-2"></i>Consultas del Mes
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Citas de Hoy -->
    <div class="col-lg-8 mb-4">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="bi bi-calendar-check text-primary me-2"></i>
                    Citas de Hoy
                </h5>
                <a href="<?= Router::url('citas') ?>" class="btn btn-sm btn-primary">
                    <i class="bi bi-plus-lg me-1"></i>Nueva Cita
                </a>
            </div>
            <div class="card-body">
                <?php if (empty($citas_hoy)): ?>
                    <div class="text-center text-muted py-4">
                        <i class="bi bi-calendar-x display-1 text-muted"></i>
                        <p class="mt-3">No hay citas programadas para hoy</p>
                        <a href="<?= Router::url('citas/crear') ?>" class="btn btn-primary">
                            Programar Primera Cita
                        </a>
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Hora</th>
                                    <th>Paciente</th>
                                    <th>Médico</th>
                                    <th>Especialidad</th>
                                    <th>Estado</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($citas_hoy as $cita): ?>
                                    <tr>
                                        <td>
                                            <strong><?= Util::formatTime($cita['hora_cita']) ?></strong>
                                        </td>
                                        <td><?= htmlspecialchars($cita['paciente_nombre']) ?></td>
                                        <td><?= htmlspecialchars($cita['medico_nombre']) ?></td>
                                        <td>
                                            <span class="badge bg-info">
                                                <?= htmlspecialchars($cita['especialidad']) ?>
                                            </span>
                                        </td>
                                        <td>
                                            <?php
                                            $badgeClass = match($cita['estado']) {
                                                'programada' => 'bg-secondary',
                                                'confirmada' => 'bg-primary',
                                                'en_curso' => 'bg-warning',
                                                'completada' => 'bg-success',
                                                'cancelada' => 'bg-danger',
                                                'no_asistio' => 'bg-dark',
                                                default => 'bg-secondary'
                                            };
                                            ?>
                                            <span class="badge <?= $badgeClass ?>">
                                                <?= ucfirst(str_replace('_', ' ', $cita['estado'])) ?>
                                            </span>
                                        </td>
                                        <td>
                                            <div class="btn-group btn-group-sm">
                                                <a href="<?= Router::url('citas/ver?id=' . $cita['id']) ?>" 
                                                   class="btn btn-outline-primary btn-sm" title="Ver detalles">
                                                    <i class="bi bi-eye"></i>
                                                </a>
                                                <?php if ($cita['estado'] == 'confirmada'): ?>
                                                    <a href="<?= Router::url('consultas/nueva?cita_id=' . $cita['id']) ?>" 
                                                       class="btn btn-outline-success btn-sm" title="Iniciar consulta">
                                                        <i class="bi bi-play-fill"></i>
                                                    </a>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <!-- Pacientes Recientes -->
    <div class="col-lg-4 mb-4">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="bi bi-people text-primary me-2"></i>
                    Pacientes Recientes
                </h5>
                <a href="<?= Router::url('pacientes') ?>" class="btn btn-sm btn-outline-primary">
                    Ver Todos
                </a>
            </div>
            <div class="card-body">
                <?php if (empty($pacientes_recientes)): ?>
                    <div class="text-center text-muted py-3">
                        <i class="bi bi-person-plus display-4 text-muted"></i>
                        <p class="mt-2">No hay pacientes registrados</p>
                        <a href="<?= Router::url('pacientes/crear') ?>" class="btn btn-sm btn-primary">
                            Registrar Paciente
                        </a>
                    </div>
                <?php else: ?>
                    <div class="list-group list-group-flush">
                        <?php foreach ($pacientes_recientes as $paciente): ?>
                            <div class="list-group-item border-0 px-0">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div class="flex-grow-1">
                                        <h6 class="mb-1">
                                            <?= htmlspecialchars($paciente['nombre'] . ' ' . $paciente['apellidos']) ?>
                                        </h6>
                                        <p class="mb-1 text-muted small">
                                            <i class="bi bi-credit-card-2-front me-1"></i>
                                            <?= htmlspecialchars($paciente['codigo_paciente']) ?>
                                        </p>
                                        <?php if ($paciente['telefono']): ?>
                                            <p class="mb-1 text-muted small">
                                                <i class="bi bi-telephone me-1"></i>
                                                <?= htmlspecialchars($paciente['telefono']) ?>
                                            </p>
                                        <?php endif; ?>
                                    </div>
                                    <small class="text-muted">
                                        <?= Util::formatDate($paciente['created_at']) ?>
                                    </small>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Accesos Rápidos -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="bi bi-lightning-charge text-primary me-2"></i>
                    Accesos Rápidos - Administrador
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <a href="<?= Router::url('pacientes/crear') ?>" class="btn btn-outline-primary w-100 h-100 d-flex flex-column align-items-center justify-content-center py-3">
                            <i class="bi bi-person-plus display-6 mb-2"></i>
                            <span>Registrar Paciente</span>
                        </a>
                    </div>
                    
                    <div class="col-md-3 mb-3">
                        <a href="<?= Router::url('citas/crear') ?>" class="btn btn-outline-success w-100 h-100 d-flex flex-column align-items-center justify-content-center py-3">
                            <i class="bi bi-calendar-plus display-6 mb-2"></i>
                            <span>Nueva Cita</span>
                        </a>
                    </div>
                    
                    <div class="col-md-3 mb-3">
                        <a href="<?= Router::url('medicos/crear') ?>" class="btn btn-outline-warning w-100 h-100 d-flex flex-column align-items-center justify-content-center py-3">
                            <i class="bi bi-person-badge display-6 mb-2"></i>
                            <span>Registrar Médico</span>
                        </a>
                    </div>
                    
                    <div class="col-md-3 mb-3">
                        <a href="<?= Router::url('usuarios') ?>" class="btn btn-outline-secondary w-100 h-100 d-flex flex-column align-items-center justify-content-center py-3">
                            <i class="bi bi-gear display-6 mb-2"></i>
                            <span>Gestión de Usuarios</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php endif; ?>