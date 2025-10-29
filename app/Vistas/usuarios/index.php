<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="bi bi-people-fill text-primary me-2"></i>Gestión de Usuarios</h2>
    <a href="<?= Router::url('usuarios/crear') ?>" class="btn btn-primary">
        <i class="bi bi-person-plus me-2"></i>Nuevo Usuario
    </a>
</div>

<!-- Filtros y Búsqueda -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="<?= Router::url('usuarios') ?>" class="row g-3">
            <div class="col-md-8">
                <div class="input-group">
                    <span class="input-group-text">
                        <i class="bi bi-search"></i>
                    </span>
                    <input type="text" class="form-control" name="search" 
                           placeholder="Buscar por nombre, usuario o email..." 
                           value="<?= htmlspecialchars($search) ?>">
                </div>
            </div>
            <div class="col-md-4">
                <div class="btn-group w-100">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-search me-1"></i>Buscar
                    </button>
                    <a href="<?= Router::url('usuarios') ?>" class="btn btn-outline-secondary">
                        <i class="bi bi-x-circle me-1"></i>Limpiar
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Lista de Usuarios -->
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">
            <i class="bi bi-list-ul me-2"></i>
            Lista de Usuarios (<?= count($usuarios) ?>)
        </h5>
    </div>
    <div class="card-body p-0">
        <?php if (empty($usuarios)): ?>
            <div class="text-center py-5">
                <i class="bi bi-people display-1 text-muted"></i>
                <h4 class="mt-3 text-muted">No se encontraron usuarios</h4>
                <p class="text-muted">
                    <?= $search ? 'Intenta con otros términos de búsqueda' : 'Comienza creando el primer usuario' ?>
                </p>
                <?php if (!$search): ?>
                    <a href="<?= Router::url('usuarios/crear') ?>" class="btn btn-primary">
                        <i class="bi bi-person-plus me-2"></i>Crear Usuario
                    </a>
                <?php endif; ?>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>Usuario</th>
                            <th>Nombre Completo</th>
                            <th>Email</th>
                            <th>Rol</th>
                            <th>Estado</th>
                            <th>Último Acceso</th>
                            <th width="120">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($usuarios as $usuario): ?>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-sm bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3">
                                            <i class="bi bi-person-fill"></i>
                                        </div>
                                        <div>
                                            <strong><?= htmlspecialchars($usuario['username']) ?></strong>
                                            <?php if ($usuario['id'] == Auth::id()): ?>
                                                <span class="badge bg-info ms-1">Tú</span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <?= htmlspecialchars($usuario['nombre'] . ' ' . $usuario['apellidos']) ?>
                                    <?php if ($usuario['telefono']): ?>
                                        <br><small class="text-muted">
                                            <i class="bi bi-telephone me-1"></i>
                                            <?= htmlspecialchars($usuario['telefono']) ?>
                                        </small>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <a href="mailto:<?= htmlspecialchars($usuario['email']) ?>" class="text-decoration-none">
                                        <?= htmlspecialchars($usuario['email']) ?>
                                    </a>
                                </td>
                                <td>
                                    <?php
                                    $roleClass = match($usuario['rol_nombre']) {
                                        'administrador' => 'bg-danger',
                                        'medico' => 'bg-success',
                                        'secretario' => 'bg-warning',
                                        default => 'bg-secondary'
                                    };
                                    ?>
                                    <span class="badge <?= $roleClass ?>">
                                        <?= ucfirst($usuario['rol_nombre']) ?>
                                    </span>
                                </td>
                                <td>
                                    <?php if ($usuario['is_active']): ?>
                                        <span class="badge bg-success">Activo</span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary">Inactivo</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if ($usuario['ultimo_acceso']): ?>
                                        <span class="text-muted small">
                                            <?= Util::formatDateTime($usuario['ultimo_acceso']) ?>
                                        </span>
                                    <?php else: ?>
                                        <span class="text-muted small">Nunca</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <a href="<?= Router::url('usuarios/ver?id=' . $usuario['id']) ?>" 
                                           class="btn btn-outline-info" title="Ver detalles">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="<?= Router::url('usuarios/editar?id=' . $usuario['id']) ?>" 
                                           class="btn btn-outline-primary" title="Editar">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <?php if ($usuario['id'] != Auth::id()): ?>
                                            <a href="<?= Router::url('usuarios/eliminar?id=' . $usuario['id']) ?>" 
                                               class="btn btn-outline-danger" title="Desactivar"
                                               onclick="return confirm('¿Estás seguro de desactivar este usuario?')">
                                                <i class="bi bi-trash"></i>
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

<!-- Estadísticas -->
<?php if (!empty($usuarios)): ?>
    <div class="row mt-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body text-center">
                    <h3><?= count(array_filter($usuarios, fn($u) => $u['is_active'])) ?></h3>
                    <small>Usuarios Activos</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body text-center">
                    <h3><?= count(array_filter($usuarios, fn($u) => $u['rol_nombre'] == 'medico')) ?></h3>
                    <small>Médicos</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-white">
                <div class="card-body text-center">
                    <h3><?= count(array_filter($usuarios, fn($u) => $u['rol_nombre'] == 'secretario')) ?></h3>
                    <small>Secretarios</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-danger text-white">
                <div class="card-body text-center">
                    <h3><?= count(array_filter($usuarios, fn($u) => $u['rol_nombre'] == 'administrador')) ?></h3>
                    <small>Administradores</small>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>