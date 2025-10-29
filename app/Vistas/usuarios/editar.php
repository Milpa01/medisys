<!-- Vista: usuarios/editar.php -->
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <!-- Encabezado -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="mb-1">
                        <i class="bi bi-pencil-square me-2"></i>
                        Editar Usuario
                    </h2>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item"><a href="<?= Router::url('dashboard') ?>">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="<?= Router::url('usuarios') ?>">Usuarios</a></li>
                            <li class="breadcrumb-item"><a href="<?= Router::url('usuarios/ver?id=' . $usuario['id']) ?>">
                                <?= htmlspecialchars($usuario['username']) ?>
                            </a></li>
                            <li class="breadcrumb-item active">Editar</li>
                        </ol>
                    </nav>
                </div>
                <div>
                    <a href="<?= Router::url('usuarios/ver?id=' . $usuario['id']) ?>" class="btn btn-secondary">
                        <i class="bi bi-x-lg"></i> Cancelar
                    </a>
                </div>
            </div>

            <!-- Mensajes Flash -->
            <?= Flash::display() ?>

            <!-- Formulario de Edición -->
            <div class="row">
                <div class="col-lg-8 mx-auto">
                    <div class="card shadow-sm">
                        <div class="card-header bg-primary text-white">
                            <h5 class="card-title mb-0">
                                <i class="bi bi-person-gear me-2"></i>
                                Información del Usuario
                            </h5>
                        </div>
                        <div class="card-body">
                            <form action="<?= Router::url('usuarios/actualizar') ?>" method="POST" id="formEditarUsuario">
                                <input type="hidden" name="id" value="<?= $usuario['id'] ?>">

                                <!-- Sección: Datos de Acceso -->
                                <div class="border-bottom pb-3 mb-4">
                                    <h6 class="text-primary mb-3">
                                        <i class="bi bi-key-fill me-2"></i>
                                        Datos de Acceso
                                    </h6>

                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="username" class="form-label">
                                                Nombre de Usuario <span class="text-danger">*</span>
                                            </label>
                                            <div class="input-group">
                                                <span class="input-group-text">
                                                    <i class="bi bi-person-badge"></i>
                                                </span>
                                                <input type="text" 
                                                       class="form-control" 
                                                       id="username" 
                                                       name="username" 
                                                       value="<?= htmlspecialchars($usuario['username']) ?>"
                                                       required>
                                            </div>
                                            <small class="form-text text-muted">
                                                Nombre único para iniciar sesión
                                            </small>
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label for="email" class="form-label">
                                                Correo Electrónico <span class="text-danger">*</span>
                                            </label>
                                            <div class="input-group">
                                                <span class="input-group-text">
                                                    <i class="bi bi-envelope"></i>
                                                </span>
                                                <input type="email" 
                                                       class="form-control" 
                                                       id="email" 
                                                       name="email" 
                                                       value="<?= htmlspecialchars($usuario['email']) ?>"
                                                       required>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-12 mb-3">
                                            <label for="password" class="form-label">
                                                Nueva Contraseña
                                            </label>
                                            <div class="input-group">
                                                <span class="input-group-text">
                                                    <i class="bi bi-lock"></i>
                                                </span>
                                                <input type="password" 
                                                       class="form-control" 
                                                       id="password" 
                                                       name="password"
                                                       placeholder="Dejar en blanco para mantener la actual">
                                                <button class="btn btn-outline-secondary" 
                                                        type="button" 
                                                        id="togglePassword">
                                                    <i class="bi bi-eye"></i>
                                                </button>
                                            </div>
                                            <small class="form-text text-muted">
                                                Mínimo 6 caracteres. Solo completar si desea cambiar la contraseña.
                                            </small>
                                        </div>
                                    </div>
                                </div>

                                <!-- Sección: Información Personal -->
                                <div class="border-bottom pb-3 mb-4">
                                    <h6 class="text-primary mb-3">
                                        <i class="bi bi-person-fill me-2"></i>
                                        Información Personal
                                    </h6>

                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="nombre" class="form-label">
                                                Nombre <span class="text-danger">*</span>
                                            </label>
                                            <input type="text" 
                                                   class="form-control" 
                                                   id="nombre" 
                                                   name="nombre" 
                                                   value="<?= htmlspecialchars($usuario['nombre']) ?>"
                                                   required>
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label for="apellidos" class="form-label">
                                                Apellidos <span class="text-danger">*</span>
                                            </label>
                                            <input type="text" 
                                                   class="form-control" 
                                                   id="apellidos" 
                                                   name="apellidos" 
                                                   value="<?= htmlspecialchars($usuario['apellidos']) ?>"
                                                   required>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="telefono" class="form-label">
                                                Teléfono
                                            </label>
                                            <div class="input-group">
                                                <span class="input-group-text">
                                                    <i class="bi bi-telephone"></i>
                                                </span>
                                                <input type="tel" 
                                                       class="form-control" 
                                                       id="telefono" 
                                                       name="telefono" 
                                                       value="<?= htmlspecialchars($usuario['telefono'] ?? '') ?>"
                                                       placeholder="Ej: 2345-6789">
                                            </div>
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label for="rol_id" class="form-label">
                                                Rol del Sistema <span class="text-danger">*</span>
                                            </label>
                                            <select class="form-select" id="rol_id" name="rol_id" required>
                                                <option value="">Seleccionar rol...</option>
                                                <?php foreach ($roles as $rol): ?>
                                                    <option value="<?= $rol['id'] ?>" 
                                                            <?= $rol['id'] == $usuario['rol_id'] ? 'selected' : '' ?>>
                                                        <?= htmlspecialchars(ucfirst($rol['nombre'])) ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                            <small class="form-text text-muted">
                                                Define los permisos de acceso del usuario
                                            </small>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-12 mb-3">
                                            <label for="direccion" class="form-label">
                                                Dirección
                                            </label>
                                            <textarea class="form-control" 
                                                      id="direccion" 
                                                      name="direccion" 
                                                      rows="2" 
                                                      placeholder="Dirección completa"><?= htmlspecialchars($usuario['direccion'] ?? '') ?></textarea>
                                        </div>
                                    </div>
                                </div>

                                <!-- Sección: Estado -->
                                <div class="mb-4">
                                    <h6 class="text-primary mb-3">
                                        <i class="bi bi-toggle-on me-2"></i>
                                        Estado del Usuario
                                    </h6>

                                    <div class="form-check form-switch">
                                        <input class="form-check-input" 
                                               type="checkbox" 
                                               id="is_active" 
                                               name="is_active" 
                                               value="1"
                                               <?= $usuario['is_active'] ? 'checked' : '' ?>
                                               <?= Auth::id() == $usuario['id'] ? 'disabled' : '' ?>>
                                        <label class="form-check-label" for="is_active">
                                            Usuario Activo
                                        </label>
                                        <small class="form-text text-muted d-block mt-1">
                                            <?php if (Auth::id() == $usuario['id']): ?>
                                                No puedes desactivar tu propia cuenta
                                            <?php else: ?>
                                                Desmarcar para impedir que el usuario acceda al sistema
                                            <?php endif; ?>
                                        </small>
                                    </div>
                                </div>

                                <!-- Información Adicional -->
                                <div class="alert alert-info">
                                    <i class="bi bi-info-circle me-2"></i>
                                    <strong>Nota:</strong> Los campos marcados con <span class="text-danger">*</span> son obligatorios.
                                </div>

                                <!-- Botones de Acción -->
                                <div class="d-flex justify-content-between pt-3 border-top">
                                    <a href="<?= Router::url('usuarios/ver?id=' . $usuario['id']) ?>" 
                                       class="btn btn-secondary">
                                        <i class="bi bi-x-lg me-1"></i>
                                        Cancelar
                                    </a>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="bi bi-save me-1"></i>
                                        Guardar Cambios
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Información del Rol Seleccionado -->
                    <div class="card shadow-sm mt-4" id="infoRol">
                        <div class="card-header bg-light">
                            <h6 class="mb-0">
                                <i class="bi bi-shield-check me-2"></i>
                                Permisos del Rol
                            </h6>
                        </div>
                        <div class="card-body">
                            <div id="permisos-administrador" class="rol-info" style="display: none;">
                                <h6 class="text-primary">
                                    <i class="bi bi-star-fill me-2"></i>
                                    Administrador
                                </h6>
                                <ul class="mb-0">
                                    <li>Acceso completo al sistema</li>
                                    <li>Gestión de usuarios y roles</li>
                                    <li>Configuración del sistema</li>
                                    <li>Gestión de médicos y personal</li>
                                    <li>Acceso a todos los reportes</li>
                                </ul>
                            </div>

                            <div id="permisos-medico" class="rol-info" style="display: none;">
                                <h6 class="text-success">
                                    <i class="bi bi-heart-pulse-fill me-2"></i>
                                    Médico
                                </h6>
                                <ul class="mb-0">
                                    <li>Gestión de consultas médicas</li>
                                    <li>Acceso a expedientes de pacientes</li>
                                    <li>Prescripción de medicamentos</li>
                                    <li>Agenda de citas personales</li>
                                </ul>
                            </div>

                            <div id="permisos-secretario" class="rol-info" style="display: none;">
                                <h6 class="text-warning">
                                    <i class="bi bi-calendar-check-fill me-2"></i>
                                    Secretario/a
                                </h6>
                                <ul class="mb-0">
                                    <li>Gestión de citas médicas</li>
                                    <li>Registro de pacientes</li>
                                    <li>Consulta de información</li>
                                    <li>Administración de agenda</li>
                                </ul>
                            </div>

                            <p class="text-muted mb-0" id="seleccionar-rol">
                                Selecciona un rol para ver sus permisos
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.form-label {
    font-weight: 500;
    color: #495057;
}

.input-group-text {
    background-color: #f8f9fa;
}

.card {
    border: none;
}

.border-bottom {
    border-color: #e9ecef !important;
}

.form-switch .form-check-input {
    width: 3rem;
    height: 1.5rem;
}

.form-switch .form-check-input:checked {
    background-color: #198754;
    border-color: #198754;
}

.alert-info {
    background-color: #cfe2ff;
    border-color: #b6d4fe;
    color: #084298;
}

.rol-info ul {
    padding-left: 1.2rem;
}

.rol-info li {
    margin-bottom: 0.3rem;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Toggle de contraseña
    const togglePassword = document.getElementById('togglePassword');
    const passwordInput = document.getElementById('password');
    
    if (togglePassword) {
        togglePassword.addEventListener('click', function() {
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            
            const icon = this.querySelector('i');
            icon.classList.toggle('bi-eye');
            icon.classList.toggle('bi-eye-slash');
        });
    }

    // Mostrar información del rol seleccionado
    const rolSelect = document.getElementById('rol_id');
    const rolesInfo = {
        '1': 'administrador',
        '2': 'medico',
        '3': 'secretario'
    };

    function mostrarInfoRol() {
        const rolId = rolSelect.value;
        
        // Ocultar todos los permisos
        document.querySelectorAll('.rol-info').forEach(el => el.style.display = 'none');
        document.getElementById('seleccionar-rol').style.display = 'none';
        
        // Mostrar el seleccionado
        if (rolId && rolesInfo[rolId]) {
            const infoElement = document.getElementById('permisos-' + rolesInfo[rolId]);
            if (infoElement) {
                infoElement.style.display = 'block';
            }
        } else {
            document.getElementById('seleccionar-rol').style.display = 'block';
        }
    }

    // Mostrar información inicial
    mostrarInfoRol();

    // Actualizar al cambiar el rol
    rolSelect.addEventListener('change', mostrarInfoRol);

    // Validación del formulario
    const form = document.getElementById('formEditarUsuario');
    form.addEventListener('submit', function(e) {
        const password = passwordInput.value;
        
        // Validar contraseña solo si se ingresó una
        if (password && password.length < 6) {
            e.preventDefault();
            alert('La contraseña debe tener al menos 6 caracteres');
            passwordInput.focus();
            return false;
        }

        // Validar email
        const email = document.getElementById('email').value;
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(email)) {
            e.preventDefault();
            alert('Por favor ingrese un email válido');
            document.getElementById('email').focus();
            return false;
        }

        // Confirmación antes de enviar
        return confirm('¿Está seguro de actualizar la información de este usuario?');
    });
});
</script>