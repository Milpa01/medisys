<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="bi bi-people-fill text-primary me-2"></i>Gestión de Pacientes</h2>
    <a href="<?= Router::url('pacientes/crear') ?>" class="btn btn-primary">
        <i class="bi bi-person-plus me-2"></i>Nuevo Paciente
    </a>
</div>

<!-- Filtros y Búsqueda -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="<?= Router::url('pacientes') ?>" class="row g-3">
            <div class="col-md-8">
                <div class="input-group">
                    <span class="input-group-text">
                        <i class="bi bi-search"></i>
                    </span>
                    <input type="text" class="form-control" name="search" 
                           placeholder="Buscar por nombre, código o DPI..." 
                           value="<?= htmlspecialchars($search ?? '') ?>">
                </div>
            </div>
            <div class="col-md-4">
                <div class="btn-group w-100">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-search me-1"></i>Buscar
                    </button>
                    <a href="<?= Router::url('pacientes') ?>" class="btn btn-outline-secondary">
                        <i class="bi bi-x-circle me-1"></i>Limpiar
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Lista de Pacientes -->
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">
            <i class="bi bi-list-ul me-2"></i>
            Lista de Pacientes (<?= count($pacientes ?? []) ?>)
        </h5>
    </div>
    <div class="card-body p-0">
        <?php if (empty($pacientes ?? [])): ?>
            <div class="text-center py-5">
                <i class="bi bi-people display-1 text-muted"></i>
                <h4 class="mt-3 text-muted">No se encontraron pacientes</h4>
                <p class="text-muted">
                    <?= ($search ?? '') ? 'Intenta con otros términos de búsqueda' : 'Comienza registrando el primer paciente' ?>
                </p>
                <?php if (!($search ?? '')): ?>
                    <a href="<?= Router::url('pacientes/crear') ?>" class="btn btn-primary">
                        <i class="bi bi-person-plus me-2"></i>Registrar Paciente
                    </a>
                <?php endif; ?>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>Código</th>
                            <th>Nombre Completo</th>
                            <th>Fecha Nacimiento</th>
                            <th>Género</th>
                            <th>Teléfono</th>
                            <th>Estado</th>
                            <th width="120">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($pacientes as $paciente): ?>
                            <tr>
                                <td>
                                    <strong class="text-primary">
                                        <?= htmlspecialchars($paciente['codigo_paciente'] ?? 'N/A') ?>
                                    </strong>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-sm bg-info text-white rounded-circle d-flex align-items-center justify-content-center me-3">
                                            <i class="bi bi-person-fill"></i>
                                        </div>
                                        <div>
                                            <strong><?= htmlspecialchars(($paciente['nombre'] ?? '') . ' ' . ($paciente['apellidos'] ?? '')) ?></strong>
                                            <?php if (!empty($paciente['email'])): ?>
                                                <br><small class="text-muted">
                                                    <i class="bi bi-envelope me-1"></i>
                                                    <?= htmlspecialchars($paciente['email']) ?>
                                                </small>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <?= Util::formatDate($paciente['fecha_nacimiento'] ?? '') ?>
                                    <?php if (!empty($paciente['fecha_nacimiento'])): ?>
                                        <br><small class="text-muted">
                                            <?= Util::calculateAge($paciente['fecha_nacimiento']) ?> años
                                        </small>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php
                                    $generoIcon = match($paciente['genero'] ?? '') {
                                        'M' => 'bi-gender-male text-primary',
                                        'F' => 'bi-gender-female text-danger',
                                        default => 'bi-gender-ambiguous text-secondary'
                                    };
                                    $generoText = match($paciente['genero'] ?? '') {
                                        'M' => 'Masculino',
                                        'F' => 'Femenino',
                                        default => 'Otro'
                                    };
                                    ?>
                                    <i class="bi <?= $generoIcon ?> me-1"></i>
                                    <?= $generoText ?>
                                </td>
                                <td>
                                    <?php if (!empty($paciente['telefono']) || !empty($paciente['celular'])): ?>
                                        <?php if (!empty($paciente['telefono'])): ?>
                                            <div><i class="bi bi-telephone me-1"></i><?= htmlspecialchars($paciente['telefono']) ?></div>
                                        <?php endif; ?>
                                        <?php if (!empty($paciente['celular'])): ?>
                                            <div><i class="bi bi-phone me-1"></i><?= htmlspecialchars($paciente['celular']) ?></div>
                                        <?php endif; ?>
                                    <?php else: ?>
                                        <span class="text-muted">No registrado</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if (($paciente['is_active'] ?? 1)): ?>
                                        <span class="badge bg-success">Activo</span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary">Inactivo</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <a href="<?= Router::url('pacientes/ver?id=' . ($paciente['id'] ?? '')) ?>" 
                                           class="btn btn-outline-info" title="Ver expediente">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="<?= Router::url('pacientes/editar?id=' . ($paciente['id'] ?? '')) ?>" 
                                           class="btn btn-outline-primary" title="Editar">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <a href="<?= Router::url('citas/crear?paciente_id=' . ($paciente['id'] ?? '')) ?>" 
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

<!-- Estadísticas rápidas -->
<?php if (!empty($pacientes)): ?>
    <div class="row mt-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body text-center">
                    <h3><?= count(array_filter($pacientes, fn($p) => ($p['is_active'] ?? 1))) ?></h3>
                    <small>Pacientes Activos</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-info text-white">
                <div class="card-body text-center">
                    <h3><?= count(array_filter($pacientes, fn($p) => ($p['genero'] ?? '') == 'M')) ?></h3>
                    <small>Hombres</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-danger text-white">
                <div class="card-body text-center">
                    <h3><?= count(array_filter($pacientes, fn($p) => ($p['genero'] ?? '') == 'F')) ?></h3>
                    <small>Mujeres</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body text-center">
                    <h3><?= count(array_filter($pacientes, fn($p) => !empty($p['created_at']) && date('Y-m', strtotime($p['created_at'])) == date('Y-m'))) ?></h3>
                    <small>Nuevos este mes</small>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>