<?php
// app/Vistas/citas/editar.php

// Función auxiliar para manejar valores NULL
function safeHtml($value, $default = '') {
    return htmlspecialchars($value ?? $default);
}
?>

<div class="container-fluid">
    <!-- Encabezado -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-0">
                        <i class="bi bi-calendar-event me-2"></i>
                        Editar Cita <?= htmlspecialchars($cita['codigo_cita']) ?>
                    </h1>
                    <p class="text-muted mb-0">
                        Paciente: <?= safeHtml($cita['paciente_nombre']) ?>
                    </p>
                </div>
                <div>
                    <a href="<?= Util::url('citas/ver?id=' . $cita['id']) ?>" class="btn btn-secondary">
                        <i class="bi bi-arrow-left"></i> Cancelar
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Mensajes Flash -->
    <?= Flash::display() ?>

    <!-- Alerta de estado -->
    <?php if (in_array($cita['estado'], ['completada', 'cancelada'])): ?>
    <div class="row mb-4">
        <div class="col-12">
            <div class="alert alert-warning">
                <i class="bi bi-exclamation-triangle me-2"></i>
                <strong>Advertencia:</strong> Esta cita está en estado "<?= ucfirst($cita['estado']) ?>". 
                Algunos cambios pueden no ser apropiados según el estado actual.
            </div>
        </div>
    </div>
    <?php endif; ?>

    <!-- Formulario de Edición -->
    <form method="POST" action="<?= Util::url('citas/editar') ?>" novalidate id="form-editar-cita">
        <input type="hidden" name="id" value="<?= (int)$cita['id'] ?>">
        
        <div class="row">
            <!-- Columna Principal -->
            <div class="col-xl-8 col-lg-7">
                
                <!-- Información de la Cita -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="bi bi-calendar-check me-2"></i>
                            Información de la Cita
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Código de Cita</label>
                                    <input type="text" class="form-control" value="<?= safeHtml($cita['codigo_cita']) ?>" readonly>
                                    <div class="form-text">El código se genera automáticamente</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="estado" class="form-label">Estado de la Cita</label>
                                    <select class="form-select" id="estado" name="estado" required>
                                        <option value="programada" <?= $cita['estado'] === 'programada' ? 'selected' : '' ?>>Programada</option>
                                        <option value="confirmada" <?= $cita['estado'] === 'confirmada' ? 'selected' : '' ?>>Confirmada</option>
                                        <option value="en_curso" <?= $cita['estado'] === 'en_curso' ? 'selected' : '' ?>>En Curso</option>
                                        <option value="completada" <?= $cita['estado'] === 'completada' ? 'selected' : '' ?>>Completada</option>
                                        <option value="cancelada" <?= $cita['estado'] === 'cancelada' ? 'selected' : '' ?>>Cancelada</option>
                                        <option value="no_asistio" <?= $cita['estado'] === 'no_asistio' ? 'selected' : '' ?>>No Asistió</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="fecha_cita" class="form-label">
                                        Fecha de la Cita <span class="text-danger">*</span>
                                    </label>
                                    <input type="date" class="form-control" id="fecha_cita" name="fecha_cita" 
                                           value="<?= $cita['fecha_cita'] ?>" required>
                                    <div class="invalid-feedback">
                                        Por favor seleccione una fecha válida.
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="hora_cita" class="form-label">
                                        Hora de la Cita <span class="text-danger">*</span>
                                    </label>
                                    <select class="form-select" id="hora_cita" name="hora_cita" required>
                                        <option value="">Seleccionar hora...</option>
                                        <!-- Las opciones se cargarán dinámicamente -->
                                    </select>
                                    <div class="invalid-feedback">
                                        Por favor seleccione una hora.
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="duracion_minutos" class="form-label">Duración (minutos)</label>
                                    <select class="form-select" id="duracion_minutos" name="duracion_minutos">
                                        <option value="15" <?= $cita['duracion_minutos'] == 15 ? 'selected' : '' ?>>15 minutos</option>
                                        <option value="30" <?= $cita['duracion_minutos'] == 30 ? 'selected' : '' ?>>30 minutos</option>
                                        <option value="45" <?= $cita['duracion_minutos'] == 45 ? 'selected' : '' ?>>45 minutos</option>
                                        <option value="60" <?= $cita['duracion_minutos'] == 60 ? 'selected' : '' ?>>60 minutos</option>
                                        <option value="90" <?= $cita['duracion_minutos'] == 90 ? 'selected' : '' ?>>90 minutos</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="tipo_cita" class="form-label">Tipo de Cita</label>
                                    <select class="form-select" id="tipo_cita" name="tipo_cita">
                                        <option value="primera_vez" <?= $cita['tipo_cita'] === 'primera_vez' ? 'selected' : '' ?>>Primera Vez</option>
                                        <option value="control" <?= $cita['tipo_cita'] === 'control' ? 'selected' : '' ?>>Control</option>
                                        <option value="emergencia" <?= $cita['tipo_cita'] === 'emergencia' ? 'selected' : '' ?>>Emergencia</option>
                                        <option value="especializada" <?= $cita['tipo_cita'] === 'especializada' ? 'selected' : '' ?>>Especializada</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="costo" class="form-label">Costo de la Consulta</label>
                            <div class="input-group">
                                <span class="input-group-text">Q</span>
                                <input type="number" class="form-control" id="costo" name="costo" 
                                       step="0.01" min="0" value="<?= $cita['costo'] ?? '0.00' ?>" placeholder="0.00">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Información del Paciente y Médico -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="bi bi-people me-2"></i>
                            Paciente y Médico
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Paciente Asignado</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" 
                                               value="<?= htmlspecialchars($cita['paciente_nombre']) ?> (<?= htmlspecialchars($cita['codigo_paciente']) ?>)" 
                                               readonly>
                                        <a href="<?= Util::url('pacientes/ver?id=' . $cita['paciente_id']) ?>" 
                                           class="btn btn-outline-primary" target="_blank">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                    </div>
                                    <div class="form-text">El paciente no se puede cambiar una vez creada la cita</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Médico Asignado</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" 
                                               value="Dr. <?= htmlspecialchars($cita['medico_nombre']) ?> - <?= htmlspecialchars($cita['especialidad']) ?>" 
                                               readonly>
                                        <a href="<?= Util::url('medicos/ver?id=' . $cita['medico_id']) ?>" 
                                           class="btn btn-outline-primary" target="_blank">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                    </div>
                                    <div class="form-text">El médico no se puede cambiar una vez creada la cita</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Motivo y Observaciones -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="bi bi-chat-text me-2"></i>
                            Motivo y Observaciones
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="motivo_consulta" class="form-label">
                                Motivo de Consulta <span class="text-danger">*</span>
                            </label>
                            <textarea class="form-control" id="motivo_consulta" name="motivo_consulta" 
                                      rows="3" required><?= htmlspecialchars($cita['motivo_consulta'] ?? '') ?></textarea>
                            <div class="invalid-feedback">
                                El motivo de consulta es requerido.
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="notas" class="form-label">Notas Internas</label>
                            <textarea class="form-control" id="notas" name="notas" rows="2" 
                                      placeholder="Notas para uso interno del personal médico..."><?= htmlspecialchars($cita['notas'] ?? '') ?></textarea>
                        </div>

                        <div class="mb-3">
                            <label for="observaciones" class="form-label">Observaciones</label>
                            <textarea class="form-control" id="observaciones" name="observaciones" rows="2" 
                                      placeholder="Observaciones adicionales sobre la cita..."><?= htmlspecialchars($cita['observaciones'] ?? '') ?></textarea>
                        </div>
                    </div>
                </div>

                <!-- Botones de Acción -->
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-end gap-2">
                            <a href="<?= Util::url('citas/ver?id=' . $cita['id']) ?>" class="btn btn-secondary">
                                <i class="bi bi-x-circle"></i> Cancelar
                            </a>
                            <button type="button" class="btn btn-outline-primary" id="guardar-borrador">
                                <i class="bi bi-save"></i> Guardar Borrador
                            </button>
                            <button type="submit" class="btn btn-success">
                                <i class="bi bi-check-circle"></i> Guardar Cambios
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="col-xl-4 col-lg-5">
                
                <!-- Información Actual de la Cita -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h6 class="card-title mb-0">
                            <i class="bi bi-info-circle me-2"></i>
                            Información Actual
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="small">
                            <div class="mb-2">
                                <strong>Código:</strong> <?= htmlspecialchars($cita['codigo_cita']) ?>
                            </div>
                            <div class="mb-2">
                                <strong>Estado actual:</strong> 
                                <span class="badge bg-<?= 
                                    $cita['estado'] === 'programada' ? 'warning' : 
                                    ($cita['estado'] === 'confirmada' ? 'info' : 
                                    ($cita['estado'] === 'en_curso' ? 'primary' : 
                                    ($cita['estado'] === 'completada' ? 'success' : 
                                    ($cita['estado'] === 'cancelada' ? 'danger' : 'secondary')))) 
                                ?>">
                                    <?= ucfirst(str_replace('_', ' ', $cita['estado'])) ?>
                                </span>
                            </div>
                            <div class="mb-2">
                                <strong>Fecha original:</strong> <?= Util::formatDate($cita['fecha_cita']) ?>
                            </div>
                            <div class="mb-2">
                                <strong>Hora original:</strong> <?= Util::formatTime($cita['hora_cita']) ?>
                            </div>
                            <div class="mb-2">
                                <strong>Creada:</strong> <?= Util::formatDateTime($cita['created_at']) ?>
                            </div>
                            <div class="mb-2">
                                <strong>Registrada por:</strong> <?= htmlspecialchars($cita['registrado_por']) ?>
                            </div>
                            <?php if ($cita['updated_at'] != $cita['created_at']): ?>
                            <div class="mb-0">
                                <strong>Última modificación:</strong> <?= Util::formatDateTime($cita['updated_at']) ?>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Disponibilidad del Médico -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h6 class="card-title mb-0">
                            <i class="bi bi-clock me-2"></i>
                            Disponibilidad del Médico
                        </h6>
                    </div>
                    <div class="card-body">
                        <div id="disponibilidad-info" class="small">
                            <div class="text-muted">
                                <i class="bi bi-info-circle me-1"></i>
                                Selecciona una fecha para ver la disponibilidad
                            </div>
                        </div>
                        
                        <div id="horarios-ocupados" class="mt-3" style="display: none;">
                            <div class="small text-muted mb-2">Horarios ocupados:</div>
                            <div id="lista-ocupados"></div>
                        </div>
                    </div>
                </div>

                <!-- Validaciones y Reglas -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h6 class="card-title mb-0">
                            <i class="bi bi-shield-check me-2"></i>
                            Validaciones
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="small">
                            <div class="alert alert-info py-2 mb-2">
                                <i class="bi bi-info-circle me-1"></i>
                                Al cambiar fecha/hora se verificará automáticamente la disponibilidad del médico
                            </div>
                            
                            <ul class="list-unstyled mb-0">
                                <li class="mb-1">
                                    <i class="bi bi-check-circle text-success me-1"></i>
                                    No se permiten citas en fechas pasadas
                                </li>
                                <li class="mb-1">
                                    <i class="bi bi-check-circle text-success me-1"></i>
                                    Se valida el horario de atención del médico
                                </li>
                                <li class="mb-1">
                                    <i class="bi bi-check-circle text-success me-1"></i>
                                    Se verifica que no haya conflictos de horario
                                </li>
                                <li class="mb-0">
                                    <i class="bi bi-check-circle text-success me-1"></i>
                                    Los campos obligatorios deben completarse
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Historial de Cambios -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h6 class="card-title mb-0">
                            <i class="bi bi-clock-history me-2"></i>
                            Control de Cambios
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="small">
                            <div class="mb-2">
                                <strong>Editor actual:</strong><br>
                                <?= htmlspecialchars(Auth::user()['nombre'] . ' ' . Auth::user()['apellidos']) ?>
                            </div>
                            <div class="mb-2">
                                <strong>Fecha de edición:</strong><br>
                                <?= Util::formatDateTime(date('Y-m-d H:i:s')) ?>
                            </div>
                            <div class="mb-0">
                                <strong>Motivo de cambio:</strong><br>
                                <textarea class="form-control form-control-sm" name="motivo_cambio" 
                                          placeholder="Opcional: describir por qué se modifica la cita" rows="2"></textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Acciones Rápidas -->
                <div class="card">
                    <div class="card-header">
                        <h6 class="card-title mb-0">
                            <i class="bi bi-lightning me-2"></i>
                            Acciones Rápidas
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <button type="button" class="btn btn-outline-success btn-sm" id="confirmar-cita">
                                <i class="bi bi-check-circle"></i> Confirmar Cita
                            </button>
                            
                            <button type="button" class="btn btn-outline-warning btn-sm" id="reprogramar-cita">
                                <i class="bi bi-calendar-event"></i> Reprogramar
                            </button>
                            
                            <button type="button" class="btn btn-outline-danger btn-sm" id="cancelar-cita">
                                <i class="bi bi-x-circle"></i> Cancelar Cita
                            </button>
                            
                            <div class="dropdown">
                                <button class="btn btn-outline-secondary btn-sm dropdown-toggle w-100" type="button" 
                                        data-bs-toggle="dropdown">
                                    <i class="bi bi-three-dots"></i> Más opciones
                                </button>
                                <ul class="dropdown-menu w-100">
                                    <li>
                                        <a class="dropdown-item" href="<?= Util::url('pacientes/ver?id=' . $cita['paciente_id']) ?>">
                                            <i class="bi bi-person me-2"></i>Ver Expediente del Paciente
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="<?= Util::url('citas/crear?paciente_id=' . $cita['paciente_id']) ?>">
                                            <i class="bi bi-calendar-plus me-2"></i>Nueva Cita para este Paciente
                                        </a>
                                    </li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        <a class="dropdown-item" href="#" onclick="duplicarCita()">
                                            <i class="bi bi-files me-2"></i>Duplicar Cita
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<!-- JavaScript -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Variables globales
    const form = document.getElementById('form-editar-cita');
    const fechaCitaInput = document.getElementById('fecha_cita');
    const horaCitaSelect = document.getElementById('hora_cita');
    const estadoSelect = document.getElementById('estado');
    const medicoId = <?= $cita['medico_id'] ?>;
    const citaId = <?= $cita['id'] ?>;
    
    // Cargar hora actual
    horaCitaSelect.innerHTML = '<option value="<?= $cita['hora_cita'] ?>" selected><?= Util::formatTime($cita['hora_cita']) ?></option>';
    
    // Obtener horarios disponibles cuando cambia la fecha
    fechaCitaInput.addEventListener('change', function() {
        const fecha = this.value;
        if (fecha) {
            obtenerHorariosDisponibles(fecha, medicoId);
            mostrarDisponibilidadMedico(fecha, medicoId);
        }
    });
    
    // Función para obtener horarios disponibles
    function obtenerHorariosDisponibles(fecha, medicoId) {
        const url = `<?= Util::url('citas/obtenerHorariosDisponibles') ?>?medico_id=${medicoId}&fecha=${fecha}&exclude_id=${citaId}`;
        
        fetch(url)
            .then(response => response.json())
            .then(data => {
                horaCitaSelect.innerHTML = '<option value="">Seleccionar hora...</option>';
                
                if (data.error) {
                    console.error('Error:', data.error);
                    return;
                }
                
                // Agregar hora actual como opción
                const horaActual = '<?= $cita['hora_cita'] ?>';
                horaCitaSelect.innerHTML += `<option value="${horaActual}" selected>${formatTime(horaActual)} (Actual)</option>`;
                
                // Agregar horarios disponibles
                data.forEach(horario => {
                    if (horario.valor !== horaActual) {
                        horaCitaSelect.innerHTML += `<option value="${horario.valor}">${horario.texto}</option>`;
                    }
                });
            })
            .catch(error => {
                console.error('Error al obtener horarios:', error);
                horaCitaSelect.innerHTML = '<option value="">Error al cargar horarios</option>';
            });
    }
    
    // Función para mostrar disponibilidad del médico
    function mostrarDisponibilidadMedico(fecha, medicoId) {
        const disponibilidadDiv = document.getElementById('disponibilidad-info');
        const horariosOcupados = document.getElementById('horarios-ocupados');
        
        disponibilidadDiv.innerHTML = '<div class="text-muted"><i class="bi bi-clock me-1"></i>Verificando disponibilidad...</div>';
        
        // Obtener citas del médico para esa fecha
        fetch(`<?= Util::url('citas/obtenerHorariosDisponibles') ?>?medico_id=${medicoId}&fecha=${fecha}`)
            .then(response => response.json())
            .then(data => {
                if (data.length === 0) {
                    disponibilidadDiv.innerHTML = '<div class="text-danger"><i class="bi bi-x-circle me-1"></i>Sin horarios disponibles</div>';
                    horariosOcupados.style.display = 'none';
                } else {
                    disponibilidadDiv.innerHTML = `<div class="text-success"><i class="bi bi-check-circle me-1"></i>${data.length} horarios disponibles</div>`;
                    
                    // Mostrar horarios ocupados (simplificado)
                    const ocupadosInfo = document.getElementById('lista-ocupados');
                    ocupadosInfo.innerHTML = '<div class="small text-muted">Consulte el sistema para ver horarios ocupados específicos</div>';
                    horariosOcupados.style.display = 'block';
                }
            })
            .catch(error => {
                disponibilidadDiv.innerHTML = '<div class="text-warning"><i class="bi bi-exclamation-triangle me-1"></i>Error al verificar</div>';
            });
    }
    
    // Función auxiliar para formatear tiempo
    function formatTime(time) {
        const [hours, minutes] = time.split(':');
        return `${hours}:${minutes}`;
    }
    
    // Validación del formulario
    form.addEventListener('submit', function(e) {
        if (!form.checkValidity()) {
            e.preventDefault();
            e.stopPropagation();
        }
        
        // Validación adicional de fecha
        const fecha = fechaCitaInput.value;
        if (fecha && fecha < new Date().toISOString().split('T')[0]) {
            e.preventDefault();
            alert('No se pueden programar citas en fechas pasadas');
            return;
        }
        
        // Confirmar cambios importantes
        const estadoOriginal = '<?= $cita['estado'] ?>';
        const estadoNuevo = estadoSelect.value;
        
        if (estadoOriginal !== estadoNuevo && estadoNuevo === 'cancelada') {
            if (!confirm('¿Está seguro que desea cancelar esta cita?')) {
                e.preventDefault();
                return;
            }
        }
        
        form.classList.add('was-validated');
    });
    
    // Acciones rápidas
    document.getElementById('confirmar-cita').addEventListener('click', function() {
        if (confirm('¿Está seguro que desea confirmar esta cita?')) {
            estadoSelect.value = 'confirmada';
            estadoSelect.dispatchEvent(new Event('change'));
            
            // Desactivar beforeunload antes de enviar
            formModificado = false;
            form.submit();
        }
    });
    
    document.getElementById('cancelar-cita').addEventListener('click', function() {
        if (confirm('¿Está seguro que desea cancelar esta cita?')) {
            estadoSelect.value = 'cancelada';
            
            // Desactivar beforeunload antes de enviar
            formModificado = false;
            form.submit();
        }
    });
    
    document.getElementById('reprogramar-cita').addEventListener('click', function() {
        // Enfocar en fecha para facilitar la reprogramación
        fechaCitaInput.focus();
        fechaCitaInput.scrollIntoView({ behavior: 'smooth' });
    });
    
    // Guardar borrador
    document.getElementById('guardar-borrador').addEventListener('click', function() {
        const formData = new FormData(form);
        const data = Object.fromEntries(formData.entries());
        localStorage.setItem('cita_edicion_<?= $cita['id'] ?>', JSON.stringify(data));
        
        // Mostrar mensaje
        mostrarMensaje('Borrador guardado exitosamente', 'success');
    });
    
    // Cargar borrador si existe
    const borrador = localStorage.getItem('cita_edicion_<?= $cita['id'] ?>');
    if (borrador) {
        try {
            const data = JSON.parse(borrador);
            const confirmar = confirm('Se encontró un borrador guardado. ¿Desea cargarlo?');
            
            if (confirmar) {
                Object.keys(data).forEach(key => {
                    const input = document.querySelector(`[name="${key}"]`);
                    if (input && input.name !== 'id') {
                        input.value = data[key];
                    }
                });
                mostrarMensaje('Borrador cargado', 'info');
            }
        } catch (e) {
            console.log('Error cargando borrador:', e);
        }
    }
    
    // Detectar cambios en el formulario
    let formModificado = false;
    const inputs = form.querySelectorAll('input, textarea, select');
    
    inputs.forEach(input => {
        input.addEventListener('change', function() {
            formModificado = true;
            this.classList.add('border-warning');
        });
    });
    
    // Advertencia al salir sin guardar
    window.addEventListener('beforeunload', function(e) {
        if (formModificado) {
            e.preventDefault();
            e.returnValue = '¿Está seguro que desea salir? Los cambios no guardados se perderán.';
        }
    });
    
    // Limpiar borrador al guardar exitosamente
    form.addEventListener('submit', function() {
        if (form.checkValidity()) {
            localStorage.removeItem('cita_edicion_<?= $cita['id'] ?>');
        }
    });
    
    // Atajos de teclado
    document.addEventListener('keydown', function(e) {
        if (e.ctrlKey) {
            switch(e.key) {
                case 's':
                    e.preventDefault();
                    document.getElementById('guardar-borrador').click();
                    break;
                case 'Enter':
                    e.preventDefault();
                    if (confirm('¿Está seguro que desea guardar los cambios?')) {
                        form.submit();
                    }
                    break;
            }
        }
        
        if (e.key === 'Escape') {
            if (confirm('¿Está seguro que desea cancelar? Los cambios no guardados se perderán.')) {
                window.location.href = '<?= Util::url('citas/ver?id=' . $cita['id']) ?>';
            }
        }
    });
    
    // Función para mostrar mensajes
    function mostrarMensaje(mensaje, tipo = 'info') {
        const alert = document.createElement('div');
        alert.className = `alert alert-${tipo} alert-dismissible fade show position-fixed`;
        alert.style.top = '20px';
        alert.style.right = '20px';
        alert.style.zIndex = '9999';
        
        const iconos = {
            success: 'bi-check-circle',
            error: 'bi-exclamation-triangle',
            warning: 'bi-exclamation-triangle',
            info: 'bi-info-circle'
        };
        
        alert.innerHTML = `
            <i class="bi ${iconos[tipo]} me-2"></i>${mensaje}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;
        
        document.body.appendChild(alert);
        setTimeout(() => alert.remove(), 5000);
    }
    
    // Función de depuración para verificar datos del formulario
    function depurarFormulario() {
        const formData = new FormData(form);
        console.log('Datos del formulario:');
        for (let [key, value] of formData.entries()) {
            console.log(`${key}: ${value}`);
        }
        
        const idValue = formData.get('id');
        if (!idValue) {
            mostrarMensaje('Error: ID de cita no encontrado', 'error');
            return false;
        }
        
        return true;
    }
    
    // Validaciones en tiempo real
    fechaCitaInput.addEventListener('change', function() {
        const fecha = this.value;
        const hoy = new Date().toISOString().split('T')[0];
        
        if (fecha < hoy) {
            this.setCustomValidity('No se pueden programar citas en fechas pasadas');
            this.classList.add('is-invalid');
            mostrarMensaje('No se pueden programar citas en fechas pasadas', 'error');
        } else {
            this.setCustomValidity('');
            this.classList.remove('is-invalid');
        }
    });
    
    // Validar cambios de estado
    estadoSelect.addEventListener('change', function() {
        const estadoOriginal = '<?= $cita['estado'] ?? 'programada' ?>';
        const estadoNuevo = this.value;
        
        // Validaciones de transición de estados
        const transicionesInvalidas = {
            'completada': ['programada', 'confirmada'],
            'cancelada': ['completada'],
            'en_curso': ['completada', 'cancelada']
        };
        
        if (transicionesInvalidas[estadoOriginal] && transicionesInvalidas[estadoOriginal].includes(estadoNuevo)) {
            mostrarMensaje(`No se puede cambiar de ${estadoOriginal} a ${estadoNuevo}`, 'warning');
            this.value = estadoOriginal;
        }
    });
});

// Función para duplicar cita
function duplicarCita() {
    if (confirm('¿Desea crear una nueva cita con los mismos datos para este paciente?')) {
        const url = `<?= Util::url('citas/crear') ?>?paciente_id=<?= $cita['paciente_id'] ?>&medico_id=<?= $cita['medico_id'] ?>&motivo=<?= urlencode($cita['motivo_consulta']) ?>`;
        window.open(url, '_blank');
    }
}

// Función para enviar notificación al paciente
function enviarNotificacion() {
    // Implementar envío de notificación por SMS/Email
    alert('Funcionalidad de notificaciones en desarrollo');
}

// Función para ver historial de cambios
function verHistorialCambios() {
    // Implementar vista de historial de cambios
    alert('Funcionalidad de historial en desarrollo');
}
</script>

<style>
/* Estilos para el formulario de edición */
.border-warning {
    border-color: #ffc107 !important;
    box-shadow: 0 0 0 0.2rem rgba(255, 193, 7, 0.25);
}

.was-validated .form-control:valid {
    border-color: #198754;
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 8 8'%3e%3cpath fill='%23198754' d='m2.3 6.73.4.43 3.36-3.36-.43-.43-2.93 2.93-1.47-1.47-.43.43 1.9 1.9z'/%3e%3c/svg%3e");
    background-repeat: no-repeat;
    background-position: right calc(0.375em + 0.1875rem) center;
    background-size: calc(0.75em + 0.375rem) calc(0.75em + 0.375rem);
}

.was-validated .form-control:invalid {
    border-color: #dc3545;
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 12 12' width='12' height='12' fill='none' stroke='%23dc3545'%3e%3ccircle cx='6' cy='6' r='4.5'/%3e%3cpath d='m5.8 3.6h.4L6 6.5z'/%3e%3ccircle cx='6' cy='8.2' r='.6' fill='%23dc3545' stroke='none'/%3e%3c/svg%3e");
    background-repeat: no-repeat;
    background-position: right calc(0.375em + 0.1875rem) center;
    background-size: calc(0.75em + 0.375rem) calc(0.75em + 0.375rem);
}

/* Indicadores de estado */
.badge {
    font-size: 0.75rem;
}

/* Animaciones */
.alert {
    animation: slideInRight 0.3s ease-out;
}

@keyframes slideInRight {
    from {
        transform: translateX(100%);
        opacity: 0;
    }
    to {
        transform: translateX(0);
        opacity: 1;
    }
}

/* Hover effects para botones */
.btn:hover {
    transform: translateY(-1px);
    transition: transform 0.2s ease-in-out;
}

/* Estilos para campos modificados */
.form-control.border-warning,
.form-select.border-warning,
textarea.border-warning {
    background-color: #fff3cd;
    transition: background-color 0.3s ease-in-out;
}

/* Mejorar contraste en campos readonly */
.form-control[readonly] {
    background-color: #f8f9fa;
    border-color: #dee2e6;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .col-md-6 {
        margin-bottom: 1rem;
    }
    
    .btn-group-sm > .btn {
        padding: 0.25rem 0.5rem;
        font-size: 0.875rem;
    }
}

/* Print styles */
@media print {
    .d-print-none {
        display: none !important;
    }
    
    .card {
        border: 1px solid #000 !important;
        box-shadow: none !important;
    }
    
    .btn {
        display: none !important;
    }
}

/* Estados específicos */
.estado-programada { color: #ff6b35; }
.estado-confirmada { color: #17a2b8; }
.estado-en-curso { color: #007bff; }
.estado-completada { color: #28a745; }
.estado-cancelada { color: #dc3545; }
.estado-no-asistio { color: #6c757d; }

/* Mejoras visuales para disponibilidad */
#disponibilidad-info .text-success {
    font-weight: 600;
}

#disponibilidad-info .text-danger {
    font-weight: 600;
}

#disponibilidad-info .text-warning {
    font-weight: 600;
}

/* Destacar campos obligatorios */
.form-label .text-danger {
    font-weight: bold;
}

/* Mejorar el aspecto de los dropdowns */
.dropdown-menu {
    border: 1px solid #dee2e6;
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
}

.dropdown-item:hover {
    background-color: #f8f9fa;
}

/* Estilo para el contador de caracteres (si se implementa) */
.char-counter {
    font-size: 0.75rem;
    color: #6c757d;
    text-align: right;
}

.char-counter.warning {
    color: #ffc107;
}

.char-counter.danger {
    color: #dc3545;
}
</style>