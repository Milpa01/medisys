<div class="container-fluid">
    <!-- Encabezado -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-0">
                        <i class="bi bi-person-fill-gear me-2"></i>
                        Editar Paciente
                    </h1>
                    <p class="text-muted mb-0">
                        Actualizar información de <?= htmlspecialchars($paciente['nombre'] . ' ' . $paciente['apellidos']) ?>
                    </p>
                </div>
                <div>
                    <a href="<?= Util::url('pacientes/ver?id=' . $paciente['id']) ?>" class="btn btn-secondary">
                        <i class="bi bi-arrow-left"></i> Cancelar
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Mensajes Flash -->
    <?= Flash::display() ?>

    <!-- Formulario -->
    <form method="POST" action="<?= Util::url('pacientes/editar') ?>" enctype="multipart/form-data" novalidate>
        <input type="hidden" name="id" value="<?= $paciente['id'] ?>">
        
        <div class="row">
            <!-- Columna Principal -->
            <div class="col-xl-8 col-lg-7">
                
                <!-- Información Personal -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="bi bi-person me-2"></i>
                            Información Personal
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="nombre" class="form-label">
                                        Nombre <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" class="form-control" id="nombre" name="nombre" 
                                           value="<?= htmlspecialchars($paciente['nombre']) ?>" required>
                                    <div class="invalid-feedback">
                                        Por favor ingrese el nombre.
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="apellidos" class="form-label">
                                        Apellidos <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" class="form-control" id="apellidos" name="apellidos" 
                                           value="<?= htmlspecialchars($paciente['apellidos']) ?>" required>
                                    <div class="invalid-feedback">
                                        Por favor ingrese los apellidos.
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="fecha_nacimiento" class="form-label">
                                        Fecha de Nacimiento <span class="text-danger">*</span>
                                    </label>
                                    <input type="date" class="form-control" id="fecha_nacimiento" name="fecha_nacimiento" 
                                           value="<?= $paciente['fecha_nacimiento'] ?>" required>
                                    <div class="invalid-feedback">
                                        Por favor seleccione la fecha de nacimiento.
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="genero" class="form-label">
                                        Género <span class="text-danger">*</span>
                                    </label>
                                    <select class="form-select" id="genero" name="genero" required>
                                        <option value="">Seleccionar...</option>
                                        <option value="M" <?= $paciente['genero'] === 'M' ? 'selected' : '' ?>>Masculino</option>
                                        <option value="F" <?= $paciente['genero'] === 'F' ? 'selected' : '' ?>>Femenino</option>
                                        <option value="Otro" <?= $paciente['genero'] === 'Otro' ? 'selected' : '' ?>>Otro</option>
                                    </select>
                                    <div class="invalid-feedback">
                                        Por favor seleccione el género.
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="tipo_sangre" class="form-label">Tipo de Sangre</label>
                                    <select class="form-select" id="tipo_sangre" name="tipo_sangre">
                                        <option value="">Seleccionar...</option>
                                        <?php 
                                        $tipos_sangre = ['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'];
                                        foreach ($tipos_sangre as $tipo): 
                                        ?>
                                            <option value="<?= $tipo ?>" <?= $paciente['tipo_sangre'] === $tipo ? 'selected' : '' ?>>
                                                <?= $tipo ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="dpi" class="form-label">DPI</label>
                                    <input type="text" class="form-control" id="dpi" name="dpi" 
                                           value="<?= htmlspecialchars($paciente['dpi']) ?>" 
                                           placeholder="1234567891234" maxlength="13" pattern="[0-9]{13}">
                                    <div class="form-text">13 dígitos numéricos</div>
                                    <div class="invalid-feedback">
                                        El DPI debe tener exactamente 13 dígitos.
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="estado" class="form-label">Estado</label>
                                    <select class="form-select" id="estado" name="is_active">
                                        <option value="1" <?= $paciente['is_active'] == 1 ? 'selected' : '' ?>>Activo</option>
                                        <option value="0" <?= $paciente['is_active'] == 0 ? 'selected' : '' ?>>Inactivo</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Información de Contacto -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="bi bi-telephone me-2"></i>
                            Información de Contacto
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" class="form-control" id="email" name="email" 
                                           value="<?= htmlspecialchars($paciente['email']) ?>" 
                                           placeholder="ejemplo@correo.com">
                                    <div class="invalid-feedback">
                                        Por favor ingrese un email válido.
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="telefono" class="form-label">Teléfono</label>
                                    <input type="tel" class="form-control" id="telefono" name="telefono" 
                                           value="<?= htmlspecialchars($paciente['telefono']) ?>" 
                                           placeholder="2234-5678">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="celular" class="form-label">Celular</label>
                                    <input type="tel" class="form-control" id="celular" name="celular" 
                                           value="<?= htmlspecialchars($paciente['celular']) ?>" 
                                           placeholder="5123-4567">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-8">
                                <div class="mb-3">
                                    <label for="direccion" class="form-label">Dirección</label>
                                    <textarea class="form-control" id="direccion" name="direccion" rows="2" 
                                              placeholder="Dirección completa"><?= htmlspecialchars($paciente['direccion']) ?></textarea>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="ciudad" class="form-label">Ciudad</label>
                                    <input type="text" class="form-control" id="ciudad" name="ciudad" 
                                           value="<?= htmlspecialchars($paciente['ciudad']) ?>" 
                                           placeholder="Ciudad de residencia">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Contacto de Emergencia -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="bi bi-exclamation-triangle me-2"></i>
                            Contacto de Emergencia
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="contacto_emergencia" class="form-label">Nombre del Contacto</label>
                                    <input type="text" class="form-control" id="contacto_emergencia" name="contacto_emergencia" 
                                           value="<?= htmlspecialchars($paciente['contacto_emergencia']) ?>" 
                                           placeholder="Nombre completo del contacto">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="telefono_emergencia" class="form-label">Teléfono de Emergencia</label>
                                    <input type="tel" class="form-control" id="telefono_emergencia" name="telefono_emergencia" 
                                           value="<?= htmlspecialchars($paciente['telefono_emergencia']) ?>" 
                                           placeholder="Teléfono del contacto">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Información Médica -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="bi bi-heart-pulse me-2"></i>
                            Información Médica
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="seguro_medico" class="form-label">Seguro Médico</label>
                                    <input type="text" class="form-control" id="seguro_medico" name="seguro_medico" 
                                           value="<?= htmlspecialchars($paciente['seguro_medico']) ?>" 
                                           placeholder="Nombre del seguro">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="numero_seguro" class="form-label">Número de Seguro</label>
                                    <input type="text" class="form-control" id="numero_seguro" name="numero_seguro" 
                                           value="<?= htmlspecialchars($paciente['numero_seguro']) ?>" 
                                           placeholder="Número de póliza/afiliación">
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="alergias" class="form-label">Alergias Conocidas</label>
                            <textarea class="form-control" id="alergias" name="alergias" rows="3" 
                                      placeholder="Describa las alergias conocidas del paciente..."><?= htmlspecialchars($paciente['alergias']) ?></textarea>
                        </div>

                        <div class="mb-3">
                            <label for="medicamentos_actuales" class="form-label">Medicamentos Actuales</label>
                            <textarea class="form-control" id="medicamentos_actuales" name="medicamentos_actuales" rows="3" 
                                      placeholder="Liste los medicamentos que toma actualmente..."><?= htmlspecialchars($paciente['medicamentos_actuales']) ?></textarea>
                        </div>

                        <div class="mb-3">
                            <label for="enfermedades_cronicas" class="form-label">Enfermedades Crónicas</label>
                            <textarea class="form-control" id="enfermedades_cronicas" name="enfermedades_cronicas" rows="3" 
                                      placeholder="Describa las enfermedades crónicas del paciente..."><?= htmlspecialchars($paciente['enfermedades_cronicas']) ?></textarea>
                        </div>

                        <div class="mb-3">
                            <label for="observaciones" class="form-label">Observaciones Generales</label>
                            <textarea class="form-control" id="observaciones" name="observaciones" rows="3" 
                                      placeholder="Observaciones adicionales sobre el paciente..."><?= htmlspecialchars($paciente['observaciones']) ?></textarea>
                        </div>
                    </div>
                </div>

                <!-- Botones de Acción -->
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-end gap-2">
                            <a href="<?= Util::url('pacientes/ver?id=' . $paciente['id']) ?>" class="btn btn-secondary">
                                <i class="bi bi-x-circle"></i> Cancelar
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-circle"></i> Guardar Cambios
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="col-xl-4 col-lg-5">
                
                <!-- Información del Paciente -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h6 class="card-title mb-0">
                            <i class="bi bi-info-circle me-2"></i>
                            Información del Paciente
                        </h6>
                    </div>
                    <div class="card-body text-center">
                        <?php if ($paciente['imagen']): ?>
                            <img src="<?= Util::asset('uploads/pacientes/' . $paciente['imagen']) ?>" 
                                 alt="Foto del paciente" class="rounded-circle mb-3" width="100" height="100">
                        <?php else: ?>
                            <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center mb-3" 
                                 style="width: 100px; height: 100px;">
                                <i class="bi bi-person-fill display-5 text-muted"></i>
                            </div>
                        <?php endif; ?>
                        
                        <h6 class="mb-1"><?= htmlspecialchars($paciente['nombre'] . ' ' . $paciente['apellidos']) ?></h6>
                        <p class="text-muted mb-2">
                            Código: <?= htmlspecialchars($paciente['codigo_paciente']) ?>
                        </p>
                        <p class="text-muted mb-0">
                            Edad: <?= Util::calculateAge($paciente['fecha_nacimiento']) ?> años
                        </p>
                        
                        <!-- Subir nueva imagen -->
                        <div class="mt-3">
                            <label for="imagen" class="form-label">Cambiar Foto</label>
                            <input type="file" class="form-control form-control-sm" id="imagen" name="imagen" 
                                   accept="image/*">
                            <div class="form-text">JPG, PNG o GIF. Máximo 2MB.</div>
                        </div>
                    </div>
                </div>

                <!-- Ayuda -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h6 class="card-title mb-0">
                            <i class="bi bi-question-circle me-2"></i>
                            Ayuda
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="small">
                            <p class="mb-2">
                                <strong>Campos obligatorios:</strong> Los campos marcados con 
                                <span class="text-danger">*</span> son requeridos.
                            </p>
                            <p class="mb-2">
                                <strong>DPI:</strong> Debe contener exactamente 13 dígitos numéricos.
                            </p>
                            <p class="mb-2">
                                <strong>Email:</strong> Debe ser una dirección de correo válida.
                            </p>
                            <p class="mb-0">
                                <strong>Imagen:</strong> Formatos permitidos: JPG, PNG, GIF. Tamaño máximo: 2MB.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Información de Registro -->
                <div class="card">
                    <div class="card-header">
                        <h6 class="card-title mb-0">
                            <i class="bi bi-clock me-2"></i>
                            Información de Registro
                        </h6>
                    </div>
                    <div class="card-body">
                        <p class="mb-2">
                            <strong>Registrado:</strong><br>
                            <small class="text-muted"><?= Util::formatDateTime($paciente['created_at']) ?></small>
                        </p>
                        <p class="mb-0">
                            <strong>Última actualización:</strong><br>
                            <small class="text-muted"><?= Util::formatDateTime($paciente['updated_at']) ?></small>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<!-- JavaScript para validación -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Validación del formulario
    const form = document.querySelector('form');
    const dpiInput = document.getElementById('dpi');
    const emailInput = document.getElementById('email');
    const fechaNacInput = document.getElementById('fecha_nacimiento');
    
    // Validación en tiempo real del DPI
    dpiInput.addEventListener('input', function() {
        this.value = this.value.replace(/\D/g, ''); // Solo números
        if (this.value.length > 13) {
            this.value = this.value.slice(0, 13);
        }
        
        if (this.value.length === 13) {
            this.classList.remove('is-invalid');
            this.classList.add('is-valid');
        } else if (this.value.length > 0) {
            this.classList.remove('is-valid');
            this.classList.add('is-invalid');
        } else {
            this.classList.remove('is-valid', 'is-invalid');
        }
    });
    
    // Validación de email
    emailInput.addEventListener('blur', function() {
        if (this.value) {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (emailRegex.test(this.value)) {
                this.classList.remove('is-invalid');
                this.classList.add('is-valid');
            } else {
                this.classList.remove('is-valid');
                this.classList.add('is-invalid');
            }
        } else {
            this.classList.remove('is-valid', 'is-invalid');
        }
    });
    
    // Validación de fecha de nacimiento
    fechaNacInput.addEventListener('change', function() {
        const fechaNac = new Date(this.value);
        const hoy = new Date();
        
        if (fechaNac > hoy) {
            this.classList.remove('is-valid');
            this.classList.add('is-invalid');
            this.setCustomValidity('La fecha de nacimiento no puede ser futura');
        } else {
            this.classList.remove('is-invalid');
            this.classList.add('is-valid');
            this.setCustomValidity('');
        }
    });
    
    // Validación del formulario al enviar
    form.addEventListener('submit', function(e) {
        if (!form.checkValidity()) {
            e.preventDefault();
            e.stopPropagation();
        }
        
        form.classList.add('was-validated');
    });
    
    // Previsualización de imagen
    const imagenInput = document.getElementById('imagen');
    imagenInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            // Validar tamaño (2MB max)
            if (file.size > 2 * 1024 * 1024) {
                alert('La imagen no puede ser mayor a 2MB');
                this.value = '';
                return;
            }
            
            // Validar tipo
            if (!file.type.match('image.*')) {
                alert('Por favor seleccione una imagen válida');
                this.value = '';
                return;
            }
            
            // Mostrar previsualización
            const reader = new FileReader();
            reader.onload = function(e) {
                const img = document.querySelector('.rounded-circle, .bg-light');
                if (img.tagName === 'IMG') {
                    img.src = e.target.result;
                } else {
                    // Reemplazar el placeholder con la imagen
                    const newImg = document.createElement('img');
                    newImg.src = e.target.result;
                    newImg.className = 'rounded-circle mb-3';
                    newImg.width = 100;
                    newImg.height = 100;
                    img.parentNode.replaceChild(newImg, img);
                }
            };
            reader.readAsDataURL(file);
        }
    });
});
</script>

<style>
.was-validated .form-control:valid {
    border-color: #198754;
    padding-right: calc(1.5em + 0.75rem);
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 8 8'%3e%3cpath fill='%23198754' d='m2.3 6.73.4.43 3.36-3.36-.43-.43-2.93 2.93-1.47-1.47-.43.43 1.9 1.9z'/%3e%3c/svg%3e");
    background-repeat: no-repeat;
    background-position: right calc(0.375em + 0.1875rem) center;
    background-size: calc(0.75em + 0.375rem) calc(0.75em + 0.375rem);
}

.was-validated .form-control:invalid {
    border-color: #dc3545;
    padding-right: calc(1.5em + 0.75rem);
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 12 12' width='12' height='12' fill='none' stroke='%23dc3545'%3e%3ccircle cx='6' cy='6' r='4.5'/%3e%3cpath d='m5.8 3.6h.4L6 6.5z'/%3e%3ccircle cx='6' cy='8.2' r='.6' fill='%23dc3545' stroke='none'/%3e%3c/svg%3e");
    background-repeat: no-repeat;
    background-position: right calc(0.375em + 0.1875rem) center;
    background-size: calc(0.75em + 0.375rem) calc(0.75em + 0.375rem);
}
</style>