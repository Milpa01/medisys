<?php
if (!defined('APP_PATH')) exit('No direct script access allowed');
?>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="bi bi-pencil-square me-2"></i>
                    Editar Médico
                </h5>
            </div>
            <div class="card-body">
                <form action="<?= Util::url('medicos/actualizar') ?>" method="POST" id="formEditarMedico" class="needs-validation" novalidate>
                    <input type="hidden" name="id" value="<?= $medico['id'] ?>">
                    
                    <!-- Información Personal -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card mb-4">
                                <div class="card-header">
                                    <h6 class="mb-0">
                                        <i class="bi bi-person-badge me-2"></i>Información Personal
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <!-- Información del Usuario (Solo lectura) -->
                                    <div class="mb-3">
                                        <label class="form-label">Usuario Asociado</label>
                                        <input type="text" class="form-control bg-light" 
                                               value="<?= htmlspecialchars($medico['nombre'] . ' ' . $medico['apellidos']) ?>" 
                                               readonly>
                                        <small class="text-muted">
                                            <i class="bi bi-info-circle me-1"></i>
                                            Para cambiar nombre o email, edítalo desde el módulo de Usuarios
                                        </small>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label class="form-label">Email</label>
                                        <input type="text" class="form-control bg-light" 
                                               value="<?= htmlspecialchars($medico['email']) ?>" 
                                               readonly>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label class="form-label">Teléfono</label>
                                        <input type="text" class="form-control bg-light" 
                                               value="<?= htmlspecialchars($medico['telefono'] ?? 'No especificado') ?>" 
                                               readonly>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="card mb-4">
                                <div class="card-header">
                                    <h6 class="mb-0">
                                        <i class="bi bi-hospital me-2"></i>Información Profesional
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <label for="cedula_profesional" class="form-label">
                                            Cédula Profesional <span class="text-danger">*</span>
                                        </label>
                                        <input type="text" 
                                               class="form-control" 
                                               id="cedula_profesional" 
                                               name="cedula_profesional" 
                                               value="<?= htmlspecialchars($medico['cedula_profesional']) ?>"
                                               required>
                                        <div class="invalid-feedback">
                                            La cédula profesional es requerida
                                        </div>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="especialidad_id" class="form-label">
                                            Especialidad <span class="text-danger">*</span>
                                        </label>
                                        <select class="form-select" id="especialidad_id" name="especialidad_id" required>
                                            <option value="">Seleccionar especialidad</option>
                                            <?php foreach ($especialidades ?? [] as $especialidad): ?>
                                                <option value="<?= $especialidad['id'] ?>" 
                                                        <?= $medico['especialidad_id'] == $especialidad['id'] ? 'selected' : '' ?>>
                                                    <?= htmlspecialchars($especialidad['nombre']) ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                        <div class="invalid-feedback">
                                            Debe seleccionar una especialidad
                                        </div>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="experiencia_anos" class="form-label">Años de Experiencia</label>
                                        <input type="number" 
                                               class="form-control" 
                                               id="experiencia_anos" 
                                               name="experiencia_anos" 
                                               min="0" 
                                               max="50" 
                                               value="<?= $medico['experiencia_anos'] ?>">
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="consultorio" class="form-label">Consultorio</label>
                                        <input type="text" 
                                               class="form-control" 
                                               id="consultorio" 
                                               name="consultorio" 
                                               value="<?= htmlspecialchars($medico['consultorio'] ?? '') ?>"
                                               placeholder="Ej: Consultorio 1, Sala A, etc.">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Horarios y Configuración -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card mb-4">
                                <div class="card-header">
                                    <h6 class="mb-0">
                                        <i class="bi bi-clock me-2"></i>Horarios de Atención
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="horario_inicio" class="form-label">
                                                    Hora de Inicio <span class="text-danger">*</span>
                                                </label>
                                                <input type="time" 
                                                       class="form-control" 
                                                       id="horario_inicio" 
                                                       name="horario_inicio" 
                                                       value="<?= $medico['horario_inicio'] ?>"
                                                       required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="horario_fin" class="form-label">
                                                    Hora de Fin <span class="text-danger">*</span>
                                                </label>
                                                <input type="time" 
                                                       class="form-control" 
                                                       id="horario_fin" 
                                                       name="horario_fin" 
                                                       value="<?= $medico['horario_fin'] ?>"
                                                       required>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label class="form-label">
                                            Días de Atención <span class="text-danger">*</span>
                                        </label>
                                        <div class="row g-2">
                                            <?php 
                                            $dias = [
                                                'lunes' => 'Lunes',
                                                'martes' => 'Martes',
                                                'miercoles' => 'Miércoles',
                                                'jueves' => 'Jueves',
                                                'viernes' => 'Viernes',
                                                'sabado' => 'Sábado',
                                                'domingo' => 'Domingo'
                                            ];
                                            
                                            foreach ($dias as $valor => $etiqueta):
                                            ?>
                                                <div class="col-6">
                                                    <div class="form-check">
                                                        <input class="form-check-input" 
                                                               type="checkbox" 
                                                               name="dias_atencion[]" 
                                                               value="<?= $valor ?>" 
                                                               id="dia_<?= $valor ?>"
                                                               <?= in_array($valor, $medico['dias_atencion_array']) ? 'checked' : '' ?>>
                                                        <label class="form-check-label" for="dia_<?= $valor ?>">
                                                            <?= $etiqueta ?>
                                                        </label>
                                                    </div>
                                                </div>
                                            <?php endforeach; ?>
                                        </div>
                                        <div class="invalid-feedback" id="dias-error" style="display: none;">
                                            Debe seleccionar al menos un día de atención
                                        </div>
                                    </div>
                                    
                                    <div class="alert alert-info small mb-0">
                                        <i class="bi bi-info-circle me-1"></i>
                                        Para configurar horarios especiales o bloqueos, use la sección 
                                        <a href="<?= Util::url('medicos/horarios?id=' . $medico['id']) ?>">Gestionar Horarios</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="card mb-4">
                                <div class="card-header">
                                    <h6 class="mb-0">
                                        <i class="bi bi-cash-coin me-2"></i>Información Adicional
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <label for="costo_consulta" class="form-label">Costo de Consulta (Q)</label>
                                        <div class="input-group">
                                            <span class="input-group-text">Q</span>
                                            <input type="number" 
                                                   class="form-control" 
                                                   id="costo_consulta" 
                                                   name="costo_consulta" 
                                                   step="0.01" 
                                                   min="0" 
                                                   value="<?= number_format($medico['costo_consulta'], 2, '.', '') ?>"
                                                   placeholder="0.00">
                                        </div>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="observaciones" class="form-label">Observaciones</label>
                                        <textarea class="form-control" 
                                                  id="observaciones" 
                                                  name="observaciones" 
                                                  rows="5" 
                                                  placeholder="Información adicional sobre el médico..."><?= htmlspecialchars($medico['observaciones'] ?? '') ?></textarea>
                                    </div>
                                    
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" 
                                               type="checkbox" 
                                               id="is_active" 
                                               name="is_active" 
                                               value="1"
                                               <?= $medico['is_active'] ? 'checked' : '' ?>>
                                        <label class="form-check-label" for="is_active">
                                            <strong>Médico Activo</strong>
                                            <small class="d-block text-muted">
                                                Si está inactivo, no podrá recibir nuevas citas
                                            </small>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Botones de Acción -->
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <a href="<?= Util::url('medicos/ver?id=' . $medico['id']) ?>" 
                                   class="btn btn-secondary">
                                    <i class="bi bi-arrow-left me-1"></i>
                                    Cancelar
                                </a>
                                <div>
                                    <button type="button" class="btn btn-outline-primary me-2" onclick="resetForm()">
                                        <i class="bi bi-arrow-clockwise me-1"></i>
                                        Restablecer
                                    </button>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="bi bi-save me-1"></i>
                                        Guardar Cambios
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript -->
<script>
// Validación del formulario
(function() {
    'use strict';
    
    const form = document.getElementById('formEditarMedico');
    
    form.addEventListener('submit', function(event) {
        // Validar días de atención
        const diasSeleccionados = document.querySelectorAll('input[name="dias_atencion[]"]:checked');
        const diasError = document.getElementById('dias-error');
        
        if (diasSeleccionados.length === 0) {
            event.preventDefault();
            event.stopPropagation();
            diasError.style.display = 'block';
            
            // Scroll hacia el campo de días
            document.querySelector('input[name="dias_atencion[]"]').scrollIntoView({ 
                behavior: 'smooth', 
                block: 'center' 
            });
            
            return false;
        } else {
            diasError.style.display = 'none';
        }
        
        // Validar horarios
        const horaInicio = document.getElementById('horario_inicio').value;
        const horaFin = document.getElementById('horario_fin').value;
        
        if (horaInicio && horaFin && horaInicio >= horaFin) {
            event.preventDefault();
            event.stopPropagation();
            alert('La hora de inicio debe ser menor que la hora de fin');
            return false;
        }
        
        if (!form.checkValidity()) {
            event.preventDefault();
            event.stopPropagation();
        }
        
        form.classList.add('was-validated');
    }, false);
})();

// Función para restablecer el formulario
function resetForm() {
    if (confirm('¿Está seguro de que desea restablecer todos los cambios?')) {
        document.getElementById('formEditarMedico').reset();
        document.getElementById('formEditarMedico').classList.remove('was-validated');
        document.getElementById('dias-error').style.display = 'none';
    }
}

// Actualizar días de atención cuando cambian
document.querySelectorAll('input[name="dias_atencion[]"]').forEach(checkbox => {
    checkbox.addEventListener('change', function() {
        const diasSeleccionados = document.querySelectorAll('input[name="dias_atencion[]"]:checked');
        const diasError = document.getElementById('dias-error');
        
        if (diasSeleccionados.length > 0) {
            diasError.style.display = 'none';
        }
    });
});

// Confirmar antes de salir si hay cambios sin guardar
let formChanged = false;
const formInputs = document.querySelectorAll('#formEditarMedico input, #formEditarMedico select, #formEditarMedico textarea');

formInputs.forEach(input => {
    input.addEventListener('change', function() {
        formChanged = true;
    });
});

window.addEventListener('beforeunload', function(e) {
    if (formChanged) {
        e.preventDefault();
        e.returnValue = '';
    }
});

document.getElementById('formEditarMedico').addEventListener('submit', function() {
    formChanged = false;
});

// Atajos de teclado
document.addEventListener('keydown', function(e) {
    if (e.ctrlKey && e.key === 's') {
        e.preventDefault();
        document.getElementById('formEditarMedico').submit();
    } else if (e.key === 'Escape') {
        window.location.href = '<?= Util::url('medicos/ver?id=' . $medico['id']) ?>';
    }
});
</script>

<style>
.form-check-input:checked {
    background-color: #0d6efd;
    border-color: #0d6efd;
}

.card-header h6 {
    font-weight: 600;
}

.invalid-feedback {
    font-size: 0.875rem;
}
</style>