<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="bi bi-calendar-check-fill text-primary me-2"></i>Gestión de Citas</h2>
    <div class="btn-group">
        <?php if (Auth::hasRole('administrador') || Auth::hasRole('secretario')): ?>
            <a href="<?= Router::url('citas/crear') ?>" class="btn btn-primary">
                <i class="bi bi-calendar-plus me-2"></i>Nueva Cita
            </a>
        <?php endif; ?>
        <a href="<?= Router::url('citas/calendario') ?>" class="btn btn-outline-info">
            <i class="bi bi-calendar-week me-2"></i>Calendario
        </a>
        <?php if (Auth::hasRole('medico')): ?>
            <a href="<?= Router::url('citas/agenda') ?>" class="btn btn-outline-success">
                <i class="bi bi-person-check me-2"></i>Mi Agenda
            </a>
        <?php endif; ?>
    </div>
</div>

<!-- Estadísticas rápidas -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card bg-primary text-white">
            <div class="card-body text-center">
                <h3><?= $stats['hoy'] ?? 0 ?></h3>
                <small><i class="bi bi-calendar-day me-1"></i>Citas de Hoy</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-success text-white">
            <div class="card-body text-center">
                <h3><?= $stats['semana'] ?? 0 ?></h3>
                <small><i class="bi bi-calendar-week me-1"></i>Esta Semana</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-info text-white">
            <div class="card-body text-center">
                <h3><?= $stats['mes'] ?? 0 ?></h3>
                <small><i class="bi bi-calendar-month me-1"></i>Este Mes</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-warning text-white">
            <div class="card-body text-center">
                <h3><?= ($stats['por_estado']['programada'] ?? 0) + ($stats['por_estado']['confirmada'] ?? 0) ?></h3>
                <small><i class="bi bi-clock-history me-1"></i>Pendientes</small>
            </div>
        </div>
    </div>
</div>

<!-- Filtros y Búsqueda -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="<?= Router::url('citas') ?>" class="row g-3">
            <div class="col-md-4">
                <div class="input-group">
                    <span class="input-group-text">
                        <i class="bi bi-search"></i>
                    </span>
                    <input type="text" class="form-control" name="search" 
                           placeholder="Buscar por paciente, código..." 
                           value="<?= htmlspecialchars($search ?? '') ?>">
                </div>
            </div>
            
            <div class="col-md-3">
                <input type="date" class="form-control" name="fecha" 
                       value="<?= htmlspecialchars($fecha ?? '') ?>" placeholder="Fecha">
            </div>
            
            <div class="col-md-3">
                <select class="form-select" name="estado">
                    <option value="">Todos los estados</option>
                    <option value="programada" <?= ($estado ?? '') == 'programada' ? 'selected' : '' ?>>Programada</option>
                    <option value="confirmada" <?= ($estado ?? '') == 'confirmada' ? 'selected' : '' ?>>Confirmada</option>
                    <option value="en_curso" <?= ($estado ?? '') == 'en_curso' ? 'selected' : '' ?>>En Curso</option>
                    <option value="completada" <?= ($estado ?? '') == 'completada' ? 'selected' : '' ?>>Completada</option>
                    <option value="cancelada" <?= ($estado ?? '') == 'cancelada' ? 'selected' : '' ?>>Cancelada</option>
                    <option value="no_asistio" <?= ($estado ?? '') == 'no_asistio' ? 'selected' : '' ?>>No Asistió</option>
                </select>
            </div>
            
            <div class="col-md-2">
                <div class="btn-group w-100">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-funnel"></i>
                    </button>
                    <a href="<?= Router::url('citas') ?>" class="btn btn-outline-secondary">
                        <i class="bi bi-x-circle"></i>
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Lista de Citas -->
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">
            <i class="bi bi-list-ul me-2"></i>
            Lista de Citas (<?= count($citas ?? []) ?>)
        </h5>
    </div>
    <div class="card-body p-0">
        <?php if (empty($citas ?? [])): ?>
            <div class="text-center py-5">
                <i class="bi bi-calendar-x display-1 text-muted"></i>
                <h4 class="mt-3 text-muted">No se encontraron citas</h4>
                <p class="text-muted">
                    <?php if ($search || $fecha || $estado): ?>
                        Intenta ajustar los filtros de búsqueda
                    <?php else: ?>
                        No hay citas registradas en el sistema
                    <?php endif; ?>
                </p>
                <?php if (Auth::hasRole('administrador') || Auth::hasRole('secretario')): ?>
                    <a href="<?= Router::url('citas/crear') ?>" class="btn btn-primary">
                        <i class="bi bi-calendar-plus me-2"></i>Programar Primera Cita
                    </a>
                <?php endif; ?>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>Código</th>
                            <th>Paciente</th>
                            <th>Médico</th>
                            <th>Fecha y Hora</th>
                            <th>Motivo</th>
                            <th>Estado</th>
                            <th width="150">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($citas as $cita): ?>
                            <tr>
                                <td>
                                    <strong class="text-primary">
                                        <?= htmlspecialchars($cita['codigo_cita'] ?? 'N/A') ?>
                                    </strong>
                                    <br>
                                    <small class="text-muted">
                                        <?= ucfirst($cita['tipo_cita'] ?? 'primera_vez') ?>
                                    </small>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-sm bg-info text-white rounded-circle d-flex align-items-center justify-content-center me-2">
                                            <i class="bi bi-person-fill"></i>
                                        </div>
                                        <div>
                                            <strong><?= htmlspecialchars($cita['paciente_nombre'] ?? 'N/A') ?></strong>
                                            <?php if (!empty($cita['codigo_paciente'])): ?>
                                                <br><small class="text-muted">
                                                    <?= htmlspecialchars($cita['codigo_paciente']) ?>
                                                </small>
                                            <?php endif; ?>
                                            <?php if (!empty($cita['paciente_telefono'])): ?>
                                                <br><small class="text-muted">
                                                    <i class="bi bi-telephone me-1"></i>
                                                    <?= htmlspecialchars($cita['paciente_telefono']) ?>
                                                </small>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <strong>Dr. <?= htmlspecialchars($cita['medico_nombre'] ?? 'N/A') ?></strong>
                                    <?php if (!empty($cita['especialidad'])): ?>
                                        <br><span class="badge bg-success">
                                            <?= htmlspecialchars($cita['especialidad']) ?>
                                        </span>
                                    <?php endif; ?>
                                    <?php if (!empty($cita['consultorio'])): ?>
                                        <br><small class="text-muted">
                                            <i class="bi bi-hospital me-1"></i>
                                            <?= htmlspecialchars($cita['consultorio']) ?>
                                        </small>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <strong><?= Util::formatDate($cita['fecha_cita'] ?? '') ?></strong>
                                    <br>
                                    <span class="text-primary">
                                        <i class="bi bi-clock me-1"></i>
                                        <?= Util::formatTime($cita['hora_cita'] ?? '') ?>
                                    </span>
                                    <?php if (!empty($cita['duracion_minutos'])): ?>
                                        <br><small class="text-muted">
                                            (<?= $cita['duracion_minutos'] ?> min)
                                        </small>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <span class="text-truncate d-inline-block" style="max-width: 150px;" 
                                          title="<?= htmlspecialchars($cita['motivo_consulta'] ?? '') ?>">
                                        <?= htmlspecialchars($cita['motivo_consulta'] ?? 'Sin motivo') ?>
                                    </span>
                                </td>
                                <td>
                                    <?php
                                    $badgeClass = match($cita['estado'] ?? 'programada') {
                                        'programada' => 'bg-secondary',
                                        'confirmada' => 'bg-primary',
                                        'en_curso' => 'bg-warning text-dark',
                                        'completada' => 'bg-success',
                                        'cancelada' => 'bg-danger',
                                        'no_asistio' => 'bg-dark',
                                        default => 'bg-secondary'
                                    };
                                    ?>
                                    <span class="badge <?= $badgeClass ?>">
                                        <?= ucfirst(str_replace('_', ' ', $cita['estado'] ?? 'programada')) ?>
                                    </span>
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <a href="<?= Router::url('citas/ver?id=' . ($cita['id'] ?? '')) ?>" 
                                           class="btn btn-outline-info" title="Ver detalles">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        
                                        <?php if (Auth::hasRole('administrador') || Auth::hasRole('secretario')): ?>
                                            <?php if (in_array($cita['estado'] ?? '', ['programada', 'confirmada'])): ?>
                                                <a href="<?= Router::url('citas/editar?id=' . ($cita['id'] ?? '')) ?>" 
                                                   class="btn btn-outline-primary" title="Editar">
                                                    <i class="bi bi-pencil"></i>
                                                </a>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                        
                                        <?php if (Auth::hasRole('medico') && ($cita['estado'] ?? '') == 'confirmada'): ?>
                                            <a href="<?= Router::url('consultas/nueva?cita_id=' . ($cita['id'] ?? '')) ?>" 
                                               class="btn btn-outline-success" title="Iniciar consulta">
                                                <i class="bi bi-play-fill"></i>
                                            </a>
                                        <?php endif; ?>
                                        
                                        <?php if (($cita['estado'] ?? '') == 'programada'): ?>
                                            <a href="<?= Router::url('citas/cambiarEstado?id=' . ($cita['id'] ?? '') . '&estado=confirmada') ?>" 
                                               class="btn btn-outline-success" title="Confirmar"
                                               onclick="return confirm('¿Confirmar esta cita?')">
                                                <i class="bi bi-check2"></i>
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