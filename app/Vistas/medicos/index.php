<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="bi bi-person-badge-fill text-primary me-2"></i>Gestión de Médicos</h2>
    <a href="<?= Router::url('medicos/crear') ?>" class="btn btn-primary">
        <i class="bi bi-person-plus me-2"></i>Registrar Médico
    </a>
</div>

<!-- Estadísticas rápidas -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card bg-primary text-white">
            <div class="card-body text-center">
                <h3><?= $stats['total'] ?? 0 ?></h3>
                <small><i class="bi bi-person-badge me-1"></i>Médicos Activos</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-success text-white">
            <div class="card-body text-center">
                <h3><?= $stats['citas_programadas'] ?? 0 ?></h3>
                <small><i class="bi bi-calendar-check me-1"></i>Citas Programadas</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-info text-white">
            <div class="card-body text-center">
                <h3><?= $stats['consultas_mes'] ?? 0 ?></h3>
                <small><i class="bi bi-clipboard2-pulse me-1"></i>Consultas del Mes</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-warning text-white">
            <div class="card-body text-center">
                <h3><?= count($stats['especialidades'] ?? []) ?></h3>
                <small><i class="bi bi-heart-pulse me-1"></i>Especialidades</small>
            </div>
        </div>
    </div>
</div>

<!-- Filtros y Búsqueda -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="<?= Router::url('medicos') ?>" class="row g-3">
            <div class="col-md-8">
                <div class="input-group">
                    <span class="input-group-text">
                        <i class="bi bi-search"></i>
                    </span>
                    <input type="text" class="form-control" name="search" 
                           placeholder="Buscar por nombre, cédula o especialidad..." 
                           value="<?= htmlspecialchars($search ?? '') ?>">
                </div>
            </div>
            <div class="col-md-4">
                <div class="btn-group w-100">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-search me-1"></i>Buscar
                    </button>
                    <a href="<?= Router::url('medicos') ?>" class="btn btn-outline-secondary">
                        <i class="bi bi-x-circle me-1"></i>Limpiar
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Lista de Médicos -->
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">
            <i class="bi bi-list-ul me-2"></i>
            Lista de Médicos (<?= count($medicos ?? []) ?>)
        </h5>
    </div>
    <div class="card-body p-0">
        <?php if (empty($medicos ?? [])): ?>
            <div class="text-center py-5">
                <i class="bi bi-person-badge display-1 text-muted"></i>
                <h4 class="mt-3 text-muted">No se encontraron médicos</h4>
                <p class="text-muted">
                    <?= ($search ?? '') ? 'Intenta con otros términos de búsqueda' : 'Comienza registrando el primer médico' ?>
                </p>
                <?php if (!($search ?? '')): ?>
                    <a href="<?= Router::url('medicos/crear') ?>" class="btn btn-primary">
                        <i class="bi bi-person-plus me-2"></i>Registrar Médico
                    </a>
                <?php endif; ?>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>Médico</th>
                            <th>Especialidad</th>
                            <th>Cédula Profesional</th>
                            <th>Consultorio</th>
                            <th>Horario</th>
                            <th>Estado</th>
                            <th width="150">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($medicos as $medico): ?>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-sm bg-success text-white rounded-circle d-flex align-items-center justify-content-center me-3">
                                            <i class="bi bi-person-fill"></i>
                                        </div>
                                        <div>
                                            <strong>Dr. <?= htmlspecialchars(($medico['nombre'] ?? '') . ' ' . ($medico['apellidos'] ?? '')) ?></strong>
                                            <?php if (!empty($medico['email'])): ?>
                                                <br><small class="text-muted">
                                                    <i class="bi bi-envelope me-1"></i>
                                                    <?= htmlspecialchars($medico['email']) ?>
                                                </small>
                                            <?php endif; ?>
                                            <?php if (!empty($medico['telefono'])): ?>
                                                <br><small class="text-muted">
                                                    <i class="bi bi-telephone me-1"></i>
                                                    <?= htmlspecialchars($medico['telefono']) ?>
                                                </small>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-info fs-6">
                                        <?= htmlspecialchars($medico['especialidad_nombre'] ?? 'Sin especialidad') ?>
                                    </span>
                                    <?php if (!empty($medico['experiencia_anos']) && $medico['experiencia_anos'] > 0): ?>
                                        <br><small class="text-muted">
                                            <i class="bi bi-award me-1"></i>
                                            <?= $medico['experiencia_anos'] ?> años de experiencia
                                        </small>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <strong class="text-primary">
                                        <?= htmlspecialchars($medico['cedula_profesional'] ?? 'N/A') ?>
                                    </strong>
                                </td>
                                <td>
                                    <?php if (!empty($medico['consultorio'])): ?>
                                        <i class="bi bi-hospital me-1"></i>
                                        <?= htmlspecialchars($medico['consultorio']) ?>
                                    <?php else: ?>
                                        <span class="text-muted">No asignado</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <small>
                                        <i class="bi bi-clock me-1"></i>
                                        <?= Util::formatTime($medico['horario_inicio'] ?? '08:00') ?> - 
                                        <?= Util::formatTime($medico['horario_fin'] ?? '17:00') ?>
                                        <br>
                                        <?php 
                                        $dias = explode(',', $medico['dias_atencion'] ?? '');
                                        $diasCortos = array_map(function($dia) {
                                            return substr(ucfirst(trim($dia)), 0, 3);
                                        }, $dias);
                                        echo implode(', ', $diasCortos);
                                        ?>
                                    </small>
                                </td>
                                <td>
                                    <?php if (($medico['usuario_activo'] ?? 1) && ($medico['is_active'] ?? 1)): ?>
                                        <span class="badge bg-success">Activo</span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary">Inactivo</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <a href="<?= Router::url('medicos/ver?id=' . ($medico['id'] ?? '')) ?>" 
                                           class="btn btn-outline-info" title="Ver perfil">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="<?= Router::url('medicos/editar?id=' . ($medico['id'] ?? '')) ?>" 
                                           class="btn btn-outline-primary" title="Editar">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <a href="<?= Router::url('medicos/horarios?id=' . ($medico['id'] ?? '')) ?>" 
                                           class="btn btn-outline-warning" title="Horarios">
                                            <i class="bi bi-calendar3"></i>
                                        </a>
                                        <a href="<?= Router::url('citas/crear?medico_id=' . ($medico['id'] ?? '')) ?>" 
                                           class="btn btn-outline-success" title="Nueva cita">
                                            <i class="bi bi-calendar-plus"></i>
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

<!-- Especialidades -->
<?php if (!empty($stats['especialidades'])): ?>
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="bi bi-heart-pulse text-primary me-2"></i>
                        Médicos por Especialidad
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <?php foreach ($stats['especialidades'] as $esp): ?>
                            <div class="col-md-4 mb-3">
                                <div class="d-flex justify-content-between align-items-center p-3 bg-light rounded">
                                    <div>
                                        <strong><?= htmlspecialchars($esp['nombre']) ?></strong>
                                    </div>
                                    <span class="badge bg-primary"><?= $esp['cantidad'] ?></span>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>