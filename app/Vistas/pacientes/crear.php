<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="bi bi-person-plus text-primary me-2"></i>Registrar Paciente</h2>
    <a href="<?= Router::url('pacientes') ?>" class="btn btn-secondary">
        <i class="bi bi-arrow-left me-2"></i>Volver
    </a>
</div>

<div class="row">
    <div class="col-lg-10 mx-auto">
        <form action="<?= Router::url('pacientes/guardar') ?>" method="POST">
            <div class="row">
                <!-- Información Personal -->
                <div class="col-md-6">
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="mb-0">
                                <i class="bi bi-person me-2"></i>Información Personal
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label for="nombre" class="form-label">Nombre <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="nombre" name="nombre" required>
                            </div>
                            
                            <div class="mb-3">
                                <label for="apellidos" class="form-label">Apellidos <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="apellidos" name="apellidos" required>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="fecha_nacimiento" class="form-label">Fecha de Nacimiento <span class="text-danger">*</span></label>
                                        <input type="date" class="form-control" id="fecha_nacimiento" name="fecha_nacimiento" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="genero" class="form-label">Género <span class="text-danger">*</span></label>
                                        <select class="form-select" id="genero" name="genero" required>
                                            <option value="">Seleccionar...</option>
                                            <option value="M">Masculino</option>
                                            <option value="F">Femenino</option>
                                            <option value="Otro">Otro</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="tipo_sangre" class="form-label">Tipo de Sangre</label>
                                <select class="form-select" id="tipo_sangre" name="tipo_sangre">
                                    <option value="">Seleccionar...</option>
                                    <option value="A+">A+</option>
                                    <option value="A-">A-</option>
                                    <option value="B+">B+</option>
                                    <option value="B-">B-</option>
                                    <option value="AB+">AB+</option>
                                    <option value="AB-">AB-</option>
                                    <option value="O+">O+</option>
                                    <option value="O-">O-</option>
                                </select>
                            </div>
                            
                            <div class="mb-3">
                                <label for="dpi" class="form-label">DPI</label>
                                <input type="text" class="form-control" id="dpi" name="dpi" 
                                       placeholder="1234567890123" maxlength="13">
                                <div class="form-text">13 dígitos sin espacios ni guiones</div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Información de Contacto -->
                <div class="col-md-6">
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="mb-0">
                                <i class="bi bi-telephone me-2"></i>Información de Contacto
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email">
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="telefono" class="form-label">Teléfono</label>
                                        <input type="tel" class="form-control" id="telefono" name="telefono">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="celular" class="form-label">Celular</label>
                                        <input type="tel" class="form-control" id="celular" name="celular">
                                    </div>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="direccion" class="form-label">Dirección</label>
                                <textarea class="form-control" id="direccion" name="direccion" rows="3"></textarea>
                            </div>
                            
                            <div class="mb-3">
                                <label for="ciudad" class="form-label">Ciudad</label>
                                <input type="text" class="form-control" id="ciudad" name="ciudad">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="row">
                <!-- Contacto de Emergencia -->
                <div class="col-md-6">
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="mb-0">
                                <i class="bi bi-exclamation-triangle me-2"></i>Contacto de Emergencia
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label for="contacto_emergencia" class="form-label">Nombre del Contacto</label>
                                <input type="text" class="form-control" id="contacto_emergencia" name="contacto_emergencia">
                            </div>
                            
                            <div class="mb-3">
                                <label for="telefono_emergencia" class="form-label">Teléfono de Emergencia</label>
                                <input type="tel" class="form-control" id="telefono_emergencia" name="telefono_emergencia">
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Seguro Médico -->
                <div class="col-md-6">
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="mb-0">
                                <i class="bi bi-shield-check me-2"></i>Seguro Médico
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label for="seguro_medico" class="form-label">Aseguradora</label>
                                <input type="text" class="form-control" id="seguro_medico" name="seguro_medico">
                            </div>
                            
                            <div class="mb-3">
                                <label for="numero_seguro" class="form-label">Número de Póliza</label>
                                <input type="text" class="form-control" id="numero_seguro" name="numero_seguro">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Información Médica -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="bi bi-heart-pulse me-2"></i>Información Médica
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="alergias" class="form-label">Alergias</label>
                                <textarea class="form-control" id="alergias" name="alergias" rows="3" 
                                          placeholder="Especificar alergias conocidas"></textarea>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="medicamentos_actuales" class="form-label">Medicamentos Actuales</label>
                                <textarea class="form-control" id="medicamentos_actuales" name="medicamentos_actuales" rows="3" 
                                          placeholder="Medicamentos que toma actualmente"></textarea>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="enfermedades_cronicas" class="form-label">Enfermedades Crónicas</label>
                                <textarea class="form-control" id="enfermedades_cronicas" name="enfermedades_cronicas" rows="3" 
                                          placeholder="Enfermedades crónicas o condiciones médicas"></textarea>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="observaciones" class="form-label">Observaciones Generales</label>
                        <textarea class="form-control" id="observaciones" name="observaciones" rows="3" 
                                  placeholder="Cualquier información adicional relevante"></textarea>
                    </div>
                </div>
            </div>
            
            <!-- Botones -->
            <div class="d-flex justify-content-end gap-2 mb-4">
                <a href="<?= Router::url('pacientes') ?>" class="btn btn-secondary">
                    <i class="bi bi-x-circle me-2"></i>Cancelar
                </a>
                <button type="reset" class="btn btn-outline-warning">
                    <i class="bi bi-arrow-clockwise me-2"></i>Limpiar
                </button>
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-save me-2"></i>Registrar Paciente
                </button>
            </div>
        </form>
    </div>
</div>

<script>
// Validación del DPI en tiempo real
document.getElementById('dpi').addEventListener('input', function() {
    this.value = this.value.replace(/[^0-9]/g, '');
    if (this.value.length > 13) {
        this.value = this.value.substring(0, 13);
    }
});

// Calcular y mostrar edad en tiempo real
document.getElementById('fecha_nacimiento').addEventListener('change', function() {
    if (this.value) {
        const birthDate = new Date(this.value);
        const today = new Date();
        let age = today.getFullYear() - birthDate.getFullYear();
        const monthDiff = today.getMonth() - birthDate.getMonth();
        
        if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birthDate.getDate())) {
            age--;
        }
        
        // Mostrar edad cerca del campo
        let ageLabel = document.getElementById('age-display');
        if (!ageLabel) {
            ageLabel = document.createElement('small');
            ageLabel.id = 'age-display';
            ageLabel.className = 'form-text text-primary';
            this.parentNode.appendChild(ageLabel);
        }
        ageLabel.textContent = `Edad: ${age} años`;
    }
});

// Formatear teléfonos automáticamente
document.querySelectorAll('input[type="tel"]').forEach(input => {
    input.addEventListener('input', function() {
        this.value = this.value.replace(/[^0-9]/g, '');
    });
});
</script>