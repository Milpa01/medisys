<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="bi bi-folder2-open text-primary me-2"></i>Expedientes Médicos</h2>
</div>

<!-- Estadísticas rápidas -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card bg-primary text-white">
            <div class="card-body text-center">
                <h3><?= $stats['total'] ?? 0 ?></h3>
                <small><i class="bi bi-folder2-open me-1"></i>Expedientes Totales</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-success text-white">
            <div class="card-body text-center">
                <h3><?= $stats['actualizados_mes'] ?? 0 ?></h3>
                <small><i class="bi bi-calendar-check me-1"></i>Actualizados este Mes</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-info text-white">
            <div class="card-body text-center">
                <h3><?= $stats['con_consultas_recientes'] ?? 0 ?></h3>
                <small><i class="bi bi-clipboard2-pulse me-1"></i>Con Consultas Recientes</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-warning text-white">
            <div class="card-body text-center">
                <h3><?= count($stats['grupos_sanguineos'] ?? []) ?></h3>
                <small><i class="bi bi-droplet-fill me-1"></i>Tipos Sanguíneos</small>
            </div>
        </div>
    </div>
</div>

<!-- Filtros y Búsqueda -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="<?= Router::url('expedientes') ?>" class="row g-3">
            <div class="col-md-10">
                <div class="input-group">
                    <span class="input-group-text">
                        <i class="bi bi-search"></i>
                    </span>
                    <input type="text" class="form-control" name="search" 
                           placeholder="Buscar por nombre, código de paciente, número de expediente o DPI..." 
                           value="<?= htmlspecialchars($search ?? '') ?>">
                </div>
            </div>
            <div class="col-md-2">
                <div class="btn-group w-100">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-search me-1"></i>Buscar
                    </button>
                    <?php if ($search ?? ''): ?>
                    <a href="<?= Router::url('expedientes') ?>" class="btn btn-outline-secondary">
                        <i class="bi bi-x-circle"></i>
                    </a>
                    <?php endif; ?>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Lista de Expedientes -->
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">
            <i class="bi bi-list-ul me-2"></i>
            Lista de Expedientes (<?= count($expedientes ?? []) ?>)
        </h5>
    </div>
    <div class="card-body p-0">
        <?php if (empty($expedientes ?? [])): ?>
            <div class="text-center py-5">
                <i class="bi bi-folder2-open display-1 text-muted"></i>
                <h4 class="mt-3 text-muted">No se encontraron expedientes</h4>
                <p class="text-muted">
                    <?= ($search ?? '') ? 'Intenta con otros términos de búsqueda' : 'Los expedientes se crean automáticamente al registrar pacientes' ?>
                </p>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>N° Expediente</th>
                            <th>Paciente</th>
                            <th>Edad</th>
                            <th>Grupo Sanguíneo</th>
                            <th>Contacto</th>
                            <th>Última Actualización</th>
                            <th width="120">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($expedientes as $expediente): ?>
                            <tr>
                                <td>
                                    <strong class="text-primary">
                                        <?= htmlspecialchars($expediente['numero_expediente'] ?? 'N/A') ?>
                                    </strong>
                                    <br>
                                    <small class="text-muted">
                                        <?= htmlspecialchars($expediente['codigo_paciente'] ?? 'N/A') ?>
                                    </small>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <?php if (!empty($expediente['imagen'])): ?>
                                            <img src="<?= htmlspecialchars($expediente['imagen']) ?>" 
                                                 alt="Foto" class="rounded-circle me-2" 
                                                 style="width: 40px; height: 40px; object-fit: cover;">
                                        <?php else: ?>
                                            <div class="avatar-sm bg-info text-white rounded-circle d-flex align-items-center justify-content-center me-2">
                                                <i class="bi bi-person-fill"></i>
                                            </div>
                                        <?php endif; ?>
                                        <div>
                                            <strong><?= htmlspecialchars(($expediente['nombre'] ?? '') . ' ' . ($expediente['apellidos'] ?? '')) ?></strong>
                                            <br>
                                            <small class="text-muted">
                                                <i class="bi bi-gender-<?= strtolower($expediente['genero'] ?? 'ambiguous') ?>"></i>
                                                <?php 
                                                $generos = ['M' => 'Masculino', 'F' => 'Femenino', 'Otro' => 'Otro'];
                                                echo $generos[$expediente['genero'] ?? 'Otro'] ?? 'No especificado';
                                                ?>
                                            </small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <strong><?= $expediente['edad'] ?? 'N/A' ?> años</strong>
                                    <br>
                                    <small class="text-muted">
                                        <?= Util::formatDate($expediente['fecha_nacimiento'] ?? '') ?>
                                    </small>
                                </td>
                                <td>
                                    <?php if (!empty($expediente['grupo_sanguineo']) && !empty($expediente['factor_rh'])): ?>
                                        <span class="badge bg-danger fs-6">
                                            <i class="bi bi-droplet-fill me-1"></i>
                                            <?= htmlspecialchars($expediente['grupo_sanguineo'] . $expediente['factor_rh']) ?>
                                        </span>
                                    <?php else: ?>
                                        <span class="text-muted">No registrado</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if (!empty($expediente['telefono'])): ?>
                                        <small>
                                            <i class="bi bi-telephone me-1"></i>
                                            <?= htmlspecialchars($expediente['telefono']) ?>
                                        </small>
                                        <br>
                                    <?php endif; ?>
                                    <?php if (!empty($expediente['email'])): ?>
                                        <small class="text-muted">
                                            <i class="bi bi-envelope me-1"></i>
                                            <?= htmlspecialchars($expediente['email']) ?>
                                        </small>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <small>
                                        <i class="bi bi-clock me-1"></i>
                                        <?= Util::formatDateTime($expediente['updated_at'] ?? '') ?>
                                    </small>
                                    <br>
                                    <small class="text-muted">
                                        Creado: <?= Util::formatDate($expediente['created_at'] ?? '') ?>
                                    </small>
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <a href="<?= Router::url('expedientes/ver?id=' . ($expediente['id'] ?? '')) ?>" 
                                           class="btn btn-outline-info" title="Ver expediente completo">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="<?= Router::url('expedientes/editar?id=' . ($expediente['id'] ?? '')) ?>" 
                                           class="btn btn-outline-primary" title="Editar expediente">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <a href="<?= Router::url('expedientes/imprimir?id=' . ($expediente['id'] ?? '')) ?>" 
                                           class="btn btn-outline-secondary" title="Imprimir expediente" target="_blank">
                                            <i class="bi bi-printer"></i>
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

<!-- Distribución de Grupos Sanguíneos -->
<?php if (!empty($stats['grupos_sanguineos'])): ?>
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="bi bi-droplet-fill text-danger me-2"></i>
                        Distribución de Grupos Sanguíneos
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <?php foreach ($stats['grupos_sanguineos'] as $grupo): ?>
                            <div class="col-md-3 mb-3">
                                <div class="d-flex justify-content-between align-items-center p-3 bg-light rounded">
                                    <div>
                                        <strong class="text-danger">
                                            <i class="bi bi-droplet-fill me-1"></i>
                                            <?= htmlspecialchars($grupo['tipo']) ?>
                                        </strong>
                                    </div>
                                    <span class="badge bg-primary"><?= $grupo['cantidad'] ?> pacientes</span>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>

<style>
.avatar-sm {
    width: 40px;
    height: 40px;
    font-size: 1.2rem;
}
</style>