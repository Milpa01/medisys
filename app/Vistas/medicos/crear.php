<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="bi bi-person-plus text-primary me-2"></i>Registrar Médico</h2>
    <a href="<?= Router::url('medicos') ?>" class="btn btn-secondary">
        <i class="bi bi-arrow-left me-2"></i>Volver
    </a>
</div>

<div class="row">
    <div class="col-lg-10 mx-auto">
        <form action="<?= Router::url('medicos/guardar') ?>" method="POST">
            <div class="row">
                <!-- Información Básica -->
                <div class="col-md-6">
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="mb-0">
                                <i class="bi bi-person-badge me-2"></i>Información Básica
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label for="usuario_id" class="form-label">Usuario Médico <span class="text-danger">*</span></label>
                                <select class="form-select" id="usuario_id" name="usuario_id" required>
                                    <option value="">Seleccionar usuario...</option>
                                    <?php foreach ($usuarios_disponibles ?? [] as $usuario): ?>
                                        <option value="<?= $usuario['id'] ?>">
                                            Dr. <?= htmlspecialchars($usuario['nombre'] . ' ' . $usuario['apellidos']) ?>
                                            (<?= htmlspecialchars($usuario['username']) ?>)
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                <div class="form-text">
                                    Solo aparecen usuarios con rol "Médico" que no estén asignados
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="cedula_profesional" class="form-label">Cédula Profesional <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="cedula_profesional" name="cedula_profesional" required>
                                <div class="form-text">Número de cédula profesional del médico</div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="especialidad_id" class="form-label">Especialidad <span class="text-danger">*</span></label>
                                <select class="form-select" id="especialidad_id" name="especialidad_id" required>
                                    <option value="">Seleccionar especialidad...</option>
                                    <?php foreach ($especialidades ?? [] as $especialidad): ?>
                                        <option value="<?= $especialidad['id'] ?>">
                                            <?= htmlspecialchars($especialidad['nombre']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            
                            <div class="mb-3">
                                <label for="experiencia_anos" class="form-label">Años de Experiencia</label>
                                <input type="number" class="form-control" id="experiencia_anos" name="experiencia_anos" min="0" max="50" value="0">
                            </div>
                            
                            <div class="mb-3">
                                <label for="consultorio" class="form-label">Consultorio</label>
                                <input type="text" class="form-control" id="consultorio" name="consultorio" 
                                       placeholder="Ej: Consultorio 1, Sala A, etc.">
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Horarios y Configuración -->
                <div class="col-md-6">
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="mb-0">
                                <i class="bi bi-clock me-2"></i>Horarios de Atención
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="horario_inicio" class="form-label">Hora de Inicio</label>
                                        <input type="time" class="form-control" id="horario_inicio" name="horario_inicio" value="08:00">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="horario_fin" class="form-label">Hora de Fin</label>
                                        <input type="time" class="form-control" id="horario_fin" name="horario_fin" value="17:00">
                                    </div>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Días de Atención <span class="text-danger">*</span></label>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="lunes" name="dias_atencion[]" value="lunes" checked>
                                            <label class="form-check-label" for="lunes">Lunes</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="martes" name="dias_atencion[]" value="martes" checked>
                                            <label class="form-check-label" for="martes">Martes</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="miercoles" name="dias_atencion[]" value="miercoles" checked>
                                            <label class="form-check-label" for="miercoles">Miércoles</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="jueves" name="dias_atencion[]" value="jueves" checked>
                                            <label class="form-check-label" for="jueves">Jueves</label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="viernes" name="dias_atencion[]" value="viernes" checked>
                                            <label class="form-check-label" for="viernes">Viernes</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="sabado" name="dias_atencion[]" value="sabado">
                                            <label class="form-check-label" for="sabado">Sábado</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="domingo" name="dias_atencion[]" value="domingo">
                                            <label class="form-check-label" for="domingo">Domingo</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="costo_consulta" class="form-label">Costo de Consulta (Q)</label>
                                <div class="input-group">
                                    <span class="input-group-text">Q</span>
                                    <input type="number" class="form-control" id="costo_consulta" name="costo_consulta" 
                                           min="0" step="0.01" placeholder="0.00">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Observaciones -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="bi bi-clipboard-data me-2"></i>Observaciones Adicionales
                    </h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label for="observaciones" class="form-label">Observaciones</label>
                        <textarea class="form-control" id="observaciones" name="observaciones" rows="4" 
                                  placeholder="Información adicional sobre el médico, certificaciones, estudios especializados, etc."></textarea>
                    </div>
                </div>
            </div>
            
            <!-- Botones -->
            <div class="d-flex justify-content-end gap-2 mb-4">
                <a href="<?= Router::url('medicos') ?>" class="btn btn-secondary">
                    <i class="bi bi-x-circle me-2"></i>Cancelar
                </a>
                <button type="reset" class="btn btn-outline-warning">
                    <i class="bi bi-arrow-clockwise me-2"></i>Limpiar
                </button>
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-save me-2"></i>Registrar Médico
                </button>
            </div>
        </form>
    </div>
</div>

<script>
// Validación de horarios
document.getElementById('horario_inicio').addEventListener('change', validarHorarios);
document.getElementById('horario_fin').addEventListener('change', validarHorarios);

function validarHorarios() {
    const inicio = document.getElementById('horario_inicio').value;
    const fin = document.getElementById('horario_fin').value;
    
    if (inicio && fin && inicio >= fin) {
        alert('La hora de inicio debe ser menor que la hora de fin');
        document.getElementById('horario_fin').focus();
    }
}

// Formatear costo en tiempo real
document.getElementById('costo_consulta').addEventListener('input', function() {
    let value = this.value;
    if (value && !isNaN(value)) {
        this.value = parseFloat(value).toFixed(2);
    }
});

// Validar que al menos un día esté seleccionado
document.querySelector('form').addEventListener('submit', function(e) {
    const diasSeleccionados = document.querySelectorAll('input[name="dias_atencion[]"]:checked');
    if (diasSeleccionados.length === 0) {
        e.preventDefault();
        alert('Debe seleccionar al menos un día de atención');
        return false;
    }
});
</script>