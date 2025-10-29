<!-- Vista: usuarios/ver.php -->
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <!-- Encabezado -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="mb-1">
                        <i class="bi bi-person-circle me-2"></i>
                        <?= htmlspecialchars($usuario['nombre'] . ' ' . $usuario['apellidos']) ?>
                    </h2>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item"><a href="<?= Router::url('dashboard') ?>">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="<?= Router::url('usuarios') ?>">Usuarios</a></li>
                            <li class="breadcrumb-item active"><?= htmlspecialchars($usuario['username']) ?></li>
                        </ol>
                    </nav>
                </div>
                <div>
                    <?php if (Auth::id() != $usuario['id']): ?>
                        <a href="<?= Router::url('usuarios/editar?id=' . $usuario['id']) ?>" 
                           class="btn btn-primary">
                            <i class="bi bi-pencil-square"></i> Editar
                        </a>
                        <?php if ($usuario['is_active']): ?>
                            <a href="<?= Router::url('usuarios/eliminar?id=' . $usuario['id']) ?>" 
                               class="btn btn-danger"
                               onclick="return confirm('¿Está seguro de desactivar este usuario?')">
                                <i class="bi bi-trash"></i> Desactivar
                            </a>
                        <?php endif; ?>
                    <?php else: ?>
                        <a href="<?= Router::url('usuarios/editar?id=' . $usuario['id']) ?>" 
                           class="btn btn-primary">
                            <i class="bi bi-pencil-square"></i> Editar mi perfil
                        </a>
                    <?php endif; ?>
                    <a href="<?= Router::url('usuarios') ?>" class="btn btn-secondary">
                        <i class="bi bi-arrow-left"></i> Volver
                    </a>
                </div>
            </div>

            <!-- Mensajes Flash -->
            <?= Flash::display() ?>

            <div class="row">
                <!-- Información Personal -->
                <div class="col-md-4 mb-4">
                    <div class="card shadow-sm">
                        <div class="card-body text-center">
                            <?php if ($usuario['imagen']): ?>
                                <img src="<?= Util::asset('img/avatars/' . $usuario['imagen']) ?>" 
                                     alt="Foto de perfil" 
                                     class="rounded-circle mb-3"
                                     style="width: 150px; height: 150px; object-fit: cover;">
                            <?php else: ?>
                                <div class="rounded-circle bg-primary text-white d-inline-flex align-items-center justify-content-center mb-3"
                                     style="width: 150px; height: 150px; font-size: 3rem;">
                                    <?= strtoupper(substr($usuario['nombre'], 0, 1) . substr($usuario['apellidos'], 0, 1)) ?>
                                </div>
                            <?php endif; ?>
                            
                            <h4 class="mb-1"><?= htmlspecialchars($usuario['nombre'] . ' ' . $usuario['apellidos']) ?></h4>
                            <p class="text-muted mb-2">@<?= htmlspecialchars($usuario['username']) ?></p>
                            
                            <span class="badge bg-<?= $usuario['is_active'] ? 'success' : 'danger' ?> mb-3">
                                <?= $usuario['is_active'] ? 'Activo' : 'Inactivo' ?>
                            </span>
                            
                            <div class="mt-3">
                                <span class="badge bg-primary fs-6">
                                    <i class="bi bi-shield-check"></i>
                                    <?= htmlspecialchars(ucfirst($usuario['rol_nombre'])) ?>
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Actividad Reciente -->
                    <div class="card shadow-sm mt-4">
                        <div class="card-header bg-light">
                            <h5 class="card-title mb-0">
                                <i class="bi bi-clock-history me-2"></i>
                                Actividad
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <small class="text-muted d-block">Fecha de registro</small>
                                <strong><?= Util::formatDateTime($usuario['created_at']) ?></strong>
                            </div>
                            
                            <?php if ($usuario['ultimo_acceso']): ?>
                            <div class="mb-3">
                                <small class="text-muted d-block">Último acceso</small>
                                <strong><?= Util::formatDateTime($usuario['ultimo_acceso']) ?></strong>
                            </div>
                            <?php endif; ?>
                            
                            <?php if ($usuario['updated_at'] && $usuario['updated_at'] != $usuario['created_at']): ?>
                            <div>
                                <small class="text-muted d-block">Última actualización</small>
                                <strong><?= Util::formatDateTime($usuario['updated_at']) ?></strong>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Detalles del Usuario -->
                <div class="col-md-8 mb-4">
                    <div class="card shadow-sm">
                        <div class="card-header bg-light">
                            <h5 class="card-title mb-0">
                                <i class="bi bi-info-circle me-2"></i>
                                Información de Contacto
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="text-muted small">Nombre de Usuario</label>
                                    <div class="fw-bold">
                                        <i class="bi bi-person-badge text-primary me-2"></i>
                                        <?= htmlspecialchars($usuario['username']) ?>
                                    </div>
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label class="text-muted small">Correo Electrónico</label>
                                    <div class="fw-bold">
                                        <i class="bi bi-envelope text-primary me-2"></i>
                                        <a href="mailto:<?= htmlspecialchars($usuario['email']) ?>">
                                            <?= htmlspecialchars($usuario['email']) ?>
                                        </a>
                                    </div>
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label class="text-muted small">Teléfono</label>
                                    <div class="fw-bold">
                                        <i class="bi bi-telephone text-primary me-2"></i>
                                        <?= $usuario['telefono'] ? htmlspecialchars($usuario['telefono']) : '<span class="text-muted">No registrado</span>' ?>
                                    </div>
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label class="text-muted small">Rol del Sistema</label>
                                    <div class="fw-bold">
                                        <i class="bi bi-shield-check text-primary me-2"></i>
                                        <?= htmlspecialchars(ucfirst($usuario['rol_nombre'])) ?>
                                    </div>
                                </div>
                                
                                <div class="col-12 mb-3">
                                    <label class="text-muted small">Dirección</label>
                                    <div class="fw-bold">
                                        <i class="bi bi-geo-alt text-primary me-2"></i>
                                        <?= $usuario['direccion'] ? htmlspecialchars($usuario['direccion']) : '<span class="text-muted">No registrada</span>' ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Información del Rol -->
                    <div class="card shadow-sm mt-4">
                        <div class="card-header bg-light">
                            <h5 class="card-title mb-0">
                                <i class="bi bi-shield-lock me-2"></i>
                                Permisos y Accesos
                            </h5>
                        </div>
                        <div class="card-body">
                            <?php if ($usuario['rol_nombre'] === 'administrador'): ?>
                                <div class="alert alert-info mb-0">
                                    <h6 class="alert-heading">
                                        <i class="bi bi-star-fill me-2"></i>
                                        Administrador del Sistema
                                    </h6>
                                    <p class="mb-0">
                                        Este usuario tiene acceso completo a todas las funcionalidades del sistema, 
                                        incluyendo gestión de usuarios, configuraciones y reportes.
                                    </p>
                                    <hr>
                                    <ul class="mb-0">
                                        <li>Gestión de usuarios y roles</li>
                                        <li>Configuración del sistema</li>
                                        <li>Acceso a todos los módulos</li>
                                        <li>Gestión de médicos y personal</li>
                                        <li>Reportes y estadísticas completas</li>
                                    </ul>
                                </div>
                            <?php elseif ($usuario['rol_nombre'] === 'medico'): ?>
                                <div class="alert alert-success mb-0">
                                    <h6 class="alert-heading">
                                        <i class="bi bi-heart-pulse-fill me-2"></i>
                                        Médico
                                    </h6>
                                    <p class="mb-0">
                                        Este usuario tiene acceso a funcionalidades médicas y gestión de consultas.
                                    </p>
                                    <hr>
                                    <ul class="mb-0">
                                        <li>Gestión de consultas médicas</li>
                                        <li>Acceso a expedientes de pacientes</li>
                                        <li>Prescripción de medicamentos</li>
                                        <li>Agenda de citas personales</li>
                                        <li>Historial médico de pacientes</li>
                                    </ul>
                                </div>
                            <?php elseif ($usuario['rol_nombre'] === 'secretario'): ?>
                                <div class="alert alert-warning mb-0">
                                    <h6 class="alert-heading">
                                        <i class="bi bi-calendar-check-fill me-2"></i>
                                        Secretario/a
                                    </h6>
                                    <p class="mb-0">
                                        Este usuario tiene acceso a funcionalidades administrativas y de recepción.
                                    </p>
                                    <hr>
                                    <ul class="mb-0">
                                        <li>Gestión de citas médicas</li>
                                        <li>Registro de pacientes</li>
                                        <li>Consulta de información de pacientes</li>
                                        <li>Administración de la agenda</li>
                                        <li>Reportes básicos</li>
                                    </ul>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Estado del Usuario -->
                    <div class="card shadow-sm mt-4">
                        <div class="card-header bg-light">
                            <h5 class="card-title mb-0">
                                <i class="bi bi-activity me-2"></i>
                                Estado del Usuario
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-md-8">
                                    <p class="mb-2">
                                        <strong>Estado actual:</strong>
                                        <span class="badge bg-<?= $usuario['is_active'] ? 'success' : 'danger' ?>">
                                            <?= $usuario['is_active'] ? 'Usuario Activo' : 'Usuario Inactivo' ?>
                                        </span>
                                    </p>
                                    <p class="text-muted small mb-0">
                                        <?php if ($usuario['is_active']): ?>
                                            Este usuario puede acceder al sistema y realizar sus funciones normalmente.
                                        <?php else: ?>
                                            Este usuario no puede acceder al sistema. Debe ser reactivado por un administrador.
                                        <?php endif; ?>
                                    </p>
                                </div>
                                <div class="col-md-4 text-end">
                                    <?php if (Auth::id() != $usuario['id']): ?>
                                        <?php if ($usuario['is_active']): ?>
                                            <a href="<?= Router::url('usuarios/eliminar?id=' . $usuario['id']) ?>" 
                                               class="btn btn-outline-danger btn-sm"
                                               onclick="return confirm('¿Está seguro de desactivar este usuario?')">
                                                <i class="bi bi-x-circle"></i> Desactivar
                                            </a>
                                        <?php else: ?>
                                            <a href="<?= Router::url('usuarios/activar?id=' . $usuario['id']) ?>" 
                                               class="btn btn-outline-success btn-sm"
                                               onclick="return confirm('¿Está seguro de reactivar este usuario?')">
                                                <i class="bi bi-check-circle"></i> Reactivar
                                            </a>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.badge {
    font-weight: 500;
}

.card {
    border: none;
    transition: transform 0.2s;
}

.card:hover {
    transform: translateY(-2px);
}

.alert ul {
    padding-left: 1.2rem;
}

.alert li {
    margin-bottom: 0.3rem;
}
</style>