<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="bi bi-person-plus text-primary me-2"></i>Crear Usuario</h2>
    <a href="<?= Router::url('usuarios') ?>" class="btn btn-secondary">
        <i class="bi bi-arrow-left me-2"></i>Volver
    </a>
</div>

<div class="row">
    <div class="col-lg-8 mx-auto">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="bi bi-person-fill-add me-2"></i>
                    Información del Usuario
                </h5>
            </div>
            <div class="card-body">
                <form action="<?= Router::url('usuarios/guardar') ?>" method="POST">
                    <div class="row">
                        <!-- Información Personal -->
                        <div class="col-md-6">
                            <h6 class="text-primary mb-3">
                                <i class="bi bi-person me-2"></i>Datos Personales
                            </h6>
                            
                            <div class="mb-3">
                                <label for="nombre" class="form-label">Nombre <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="nombre" name="nombre" required>
                            </div>
                            
                            <div class="mb-3">
                                <label for="apellidos" class="form-label">Apellidos <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="apellidos" name="apellidos" required>
                            </div>
                            
                            <div class="mb-3">
                                <label for="telefono" class="form-label">Teléfono</label>
                                <input type="tel" class="form-control" id="telefono" name="telefono">
                            </div>
                            
                            <div class="mb-3">
                                <label for="direccion" class="form-label">Dirección</label>
                                <textarea class="form-control" id="direccion" name="direccion" rows="3"></textarea>
                            </div>
                        </div>
                        
                        <!-- Información de Acceso -->
                        <div class="col-md-6">
                            <h6 class="text-primary mb-3">
                                <i class="bi bi-key me-2"></i>Datos de Acceso
                            </h6>
                            
                            <div class="mb-3">
                                <label for="username" class="form-label">Usuario <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="username" name="username" required>
                                <div class="form-text">Solo letras, números y guiones bajos</div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                                <input type="email" class="form-control" id="email" name="email" required>
                            </div>
                            
                            <div class="mb-3">
                                <label for="password" class="form-label">Contraseña <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="password" class="form-control" id="password" name="password" required>
                                    <button class="btn btn-outline-secondary" type="button" onclick="togglePassword()">
                                        <i class="bi bi-eye" id="toggleIcon"></i>
                                    </button>
                                </div>
                                <div class="form-text">Mínimo 6 caracteres</div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="rol_id" class="form-label">Rol <span class="text-danger">*</span></label>
                                <select class="form-select" id="rol_id" name="rol_id" required>
                                    <option value="">Seleccionar rol...</option>
                                    <?php foreach ($roles as $rol): ?>
                                        <option value="<?= $rol['id'] ?>">
                                            <?= ucfirst($rol['nombre']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                <div class="form-text">
                                    <small>
                                        <strong>Administrador:</strong> Control total del sistema<br>
                                        <strong>Médico:</strong> Acceso a consultas y expedientes<br>
                                        <strong>Secretario:</strong> Gestión de citas y pacientes
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <hr>
                    
                    <div class="d-flex justify-content-end gap-2">
                        <a href="<?= Router::url('usuarios') ?>" class="btn btn-secondary">
                            <i class="bi bi-x-circle me-2"></i>Cancelar
                        </a>
                        <button type="reset" class="btn btn-outline-warning">
                            <i class="bi bi-arrow-clockwise me-2"></i>Limpiar
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save me-2"></i>Guardar Usuario
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function togglePassword() {
    const passwordInput = document.getElementById('password');
    const toggleIcon = document.getElementById('toggleIcon');
    
    if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        toggleIcon.className = 'bi bi-eye-slash';
    } else {
        passwordInput.type = 'password';
        toggleIcon.className = 'bi bi-eye';
    }
}

// Validación en tiempo real del username
document.getElementById('username').addEventListener('input', function() {
    this.value = this.value.replace(/[^a-zA-Z0-9_]/g, '');
});

// Generar username automático basado en nombre y apellidos
document.getElementById('nombre').addEventListener('blur', generateUsername);
document.getElementById('apellidos').addEventListener('blur', generateUsername);

function generateUsername() {
    const nombre = document.getElementById('nombre').value;
    const apellidos = document.getElementById('apellidos').value;
    const usernameField = document.getElementById('username');
    
    if (nombre && apellidos && !usernameField.value) {
        const username = (nombre.charAt(0) + apellidos.split(' ')[0]).toLowerCase();
        usernameField.value = username.replace(/[^a-zA-Z0-9_]/g, '');
    }
}
</script>