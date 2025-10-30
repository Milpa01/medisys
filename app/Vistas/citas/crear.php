<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="bi bi-calendar-plus text-primary me-2"></i>Nueva Cita Médica</h2>
    <a href="<?= Router::url('citas') ?>" class="btn btn-secondary">
        <i class="bi bi-arrow-left me-2"></i>Volver a Citas
    </a>
</div>

<div class="row">
    <div class="col-lg-10 mx-auto">
        <form action="<?= Router::url('citas/guardar') ?>" method="POST" id="formCita">
            <div class="row">
                <!-- Selección de Paciente -->
                <div class="col-md-6">
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="mb-0">
                                <i class="bi bi-person me-2"></i>Información del Paciente
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label for="paciente_id" class="form-label">Paciente <span class="text-danger">*</span></label>
                                <select class="form-select" id="paciente_id" name="paciente_id" required>
                                    <option value="">Seleccionar paciente...</option>
                                    <?php foreach ($pacientes ?? [] as $paciente): ?>
                                        <option value="<?= $paciente['id'] ?>" 
                                                <?= ($paciente_seleccionado ?? '') == $paciente['id'] ? 'selected' : '' ?>
                                                data-telefono="<?= htmlspecialchars($paciente['telefono'] ?? '') ?>"
                                                data-email="<?= htmlspecialchars($paciente['email'] ?? '') ?>">
                                            <?= htmlspecialchars($paciente['nombre'] . ' ' . $paciente['apellidos']) ?>
                                            (<?= htmlspecialchars($paciente['codigo_paciente'] ?? '') ?>)
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                <div class="form-text">
                                    <i class="bi bi-info-circle me-1"></i>
                                    Si no encuentra el paciente, 
                                    <a href="<?= Router::url('pacientes/crear') ?>" target="_blank">regístrelo aquí</a>
                                </div>
                            </div>
                            
                            <!-- Información del paciente seleccionado -->
                            <div id="info-paciente" style="display: none;">
                                <div class="alert alert-info">
                                    <strong>Información del Paciente:</strong>
                                    <div id="paciente-detalles"></div>
                                </div>
                            </div>
                            
                            <!-- Búsqueda rápida de paciente -->
                            <div class="mb-3">
                                <label class="form-label">Búsqueda Rápida</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" id="buscar-paciente" 
                                           placeholder="Buscar por nombre o código...">
                                    <button class="btn btn-outline-secondary" type="button" id="limpiar-busqueda">
                                        <i class="bi bi-x"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Selección de Médico -->
                <div class="col-md-6">
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="mb-0">
                                <i class="bi bi-person-badge me-2"></i>Información del Médico
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label for="medico_id" class="form-label">Médico <span class="text-danger">*</span></label>
                                <select class="form-select" id="medico_id" name="medico_id" required>
                                    <option value="">Seleccionar médico...</option>
                                    <?php foreach ($medicos ?? [] as $medico): ?>
                                        <option value="<?= $medico['id'] ?>" 
                                                <?= ($medico_seleccionado ?? '') == $medico['id'] ? 'selected' : '' ?>
                                                data-especialidad="<?= htmlspecialchars($medico['especialidad_nombre'] ?? '') ?>"
                                                data-consultorio="<?= htmlspecialchars($medico['consultorio'] ?? '') ?>"
                                                data-costo="<?= $medico['costo_consulta'] ?? 0 ?>">
                                            Dr. <?= htmlspecialchars($medico['nombre'] . ' ' . $medico['apellidos']) ?>
                                            - <?= htmlspecialchars($medico['especialidad_nombre'] ?? '') ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            
                            <!-- Información del médico seleccionado -->
                            <div id="info-medico" style="display: none;">
                                <div class="alert alert-success">
                                    <strong>Información del Médico:</strong>
                                    <div id="medico-detalles"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Programación de la Cita -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="bi bi-calendar-event me-2"></i>Programación de la Cita
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="fecha_cita" class="form-label">Fecha de la Cita <span class="text-danger">*</span></label>
                                <input type="date" class="form-control" id="fecha_cita" name="fecha_cita" 
                                       min="<?= date('Y-m-d') ?>" required>
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="hora_cita" class="form-label">Hora de la Cita <span class="text-danger">*</span></label>
                                <select class="form-select" id="hora_cita" name="hora_cita" required disabled>
                                    <option value="">Primero seleccione médico y fecha</option>
                                </select>
                                <div class="form-text">
                                    <i class="bi bi-clock me-1"></i>
                                    <span id="horarios-info">Seleccione médico y fecha para ver horarios disponibles</span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="costo" class="form-label">Costo de la Consulta</label>
                                <div class="input-group">
                                    <span class="input-group-text">Q</span>
                                    <input type="number" step="0.01" class="form-control" id="costo" name="costo" 
                                           value="0.00" readonly>
                                </div>
                                <div class="form-text">Se establecerá automáticamente según el médico</div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label for="motivo_consulta" class="form-label">Motivo de Consulta <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="motivo_consulta" name="motivo_consulta" 
                                       maxlength="255" required placeholder="Ejemplo: Consulta general, dolor de cabeza, chequeo rutinario...">
                                <div class="form-text">Describa brevemente el motivo de la visita</div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label for="notas" class="form-label">Notas Adicionales</label>
                                <textarea class="form-control" id="notas" name="notas" rows="3" 
                                          placeholder="Observaciones adicionales para esta cita..."></textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Resumen de la Cita -->
            <div class="card mb-4" id="resumen-cita" style="display: none;">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">
                        <i class="bi bi-check-circle me-2"></i>Resumen de la Cita
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong><i class="bi bi-person me-2"></i>Paciente:</strong> <span id="resumen-paciente">-</span></p>
                            <p><strong><i class="bi bi-person-badge me-2"></i>Médico:</strong> <span id="resumen-medico">-</span></p>
                            <p><strong><i class="bi bi-briefcase me-2"></i>Especialidad:</strong> <span id="resumen-especialidad">-</span></p>
                        </div>
                        <div class="col-md-6">
                            <p><strong><i class="bi bi-calendar me-2"></i>Fecha:</strong> <span id="resumen-fecha">-</span></p>
                            <p><strong><i class="bi bi-clock me-2"></i>Hora:</strong> <span id="resumen-hora">-</span></p>
                            <p><strong><i class="bi bi-currency-exchange me-2"></i>Costo:</strong> <span id="resumen-costo">-</span></p>
                        </div>
                    </div>
                    <p><strong><i class="bi bi-file-text me-2"></i>Motivo:</strong> <span id="resumen-motivo">-</span></p>
                </div>
            </div>
            
            <!-- Botones de Acción -->
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <button type="button" class="btn btn-outline-secondary" id="btn-limpiar">
                            <i class="bi bi-x-circle me-2"></i>Limpiar Formulario
                        </button>
                        <div>
                            <a href="<?= Router::url('citas') ?>" class="btn btn-secondary me-2">
                                <i class="bi bi-x-lg me-2"></i>Cancelar
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-lg me-2"></i>Programar Cita
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const formCita = document.getElementById('formCita');
    const pacienteSelect = document.getElementById('paciente_id');
    const medicoSelect = document.getElementById('medico_id');
    const fechaCita = document.getElementById('fecha_cita');
    const horaCita = document.getElementById('hora_cita');
    const buscarPaciente = document.getElementById('buscar-paciente');
    const costoInput = document.getElementById('costo');
    
    // Búsqueda de pacientes
    buscarPaciente.addEventListener('input', function() {
        const searchTerm = this.value.toLowerCase();
        const options = pacienteSelect.querySelectorAll('option');
        
        options.forEach((option, index) => {
            if (index === 0) return; // Skip first option
            const text = option.textContent.toLowerCase();
            if (text.includes(searchTerm)) {
                option.style.display = 'block';
            } else {
                option.style.display = 'none';
            }
        });
    });
    
    document.getElementById('limpiar-busqueda').addEventListener('click', function() {
        buscarPaciente.value = '';
        const options = pacienteSelect.querySelectorAll('option');
        options.forEach(option => option.style.display = 'block');
    });
    
    // Mostrar información del paciente seleccionado
    pacienteSelect.addEventListener('change', function() {
        const option = this.selectedOptions[0];
        const infoPaciente = document.getElementById('info-paciente');
        const detalles = document.getElementById('paciente-detalles');
        
        if (this.value) {
            const telefono = option.getAttribute('data-telefono');
            const email = option.getAttribute('data-email');
            
            let info = `<strong>${option.textContent}</strong><br>`;
            if (telefono) info += `<i class="bi bi-telephone me-1"></i> ${telefono}<br>`;
            if (email) info += `<i class="bi bi-envelope me-1"></i> ${email}`;
            
            detalles.innerHTML = info;
            infoPaciente.style.display = 'block';
        } else {
            infoPaciente.style.display = 'none';
        }
        
        actualizarResumen();
    });
    
    // Mostrar información del médico seleccionado
    medicoSelect.addEventListener('change', function() {
        const option = this.selectedOptions[0];
        const infoMedico = document.getElementById('info-medico');
        const detalles = document.getElementById('medico-detalles');
        
        if (this.value) {
            const especialidad = option.getAttribute('data-especialidad');
            const consultorio = option.getAttribute('data-consultorio');
            const costo = option.getAttribute('data-costo');
            
            let info = `<strong>${option.textContent}</strong><br>`;
            if (consultorio) info += `<i class="bi bi-hospital me-1"></i> ${consultorio}<br>`;
            if (costo > 0) info += `<i class="bi bi-currency-exchange me-1"></i> Q ${parseFloat(costo).toFixed(2)}`;
            
            detalles.innerHTML = info;
            infoMedico.style.display = 'block';
            costoInput.value = parseFloat(costo).toFixed(2);
            
            // Actualizar horarios disponibles
            actualizarHorarios();
        } else {
            infoMedico.style.display = 'none';
            costoInput.value = '';
            horaCita.disabled = true;
            horaCita.innerHTML = '<option value="">Primero seleccione médico y fecha</option>';
        }
        
        actualizarResumen();
    });
    
    // Actualizar horarios cuando cambie la fecha
    fechaCita.addEventListener('change', actualizarHorarios);
    
    // Función para actualizar horarios disponibles mediante AJAX
    function actualizarHorarios() {
        const medicoId = medicoSelect.value;
        const fecha = fechaCita.value;
        
        if (!medicoId || !fecha) {
            horaCita.disabled = true;
            horaCita.innerHTML = '<option value="">Primero seleccione médico y fecha</option>';
            document.getElementById('horarios-info').textContent = 'Seleccione médico y fecha para ver horarios disponibles';
            return;
        }
        
        horaCita.innerHTML = '<option value="">Cargando horarios...</option>';
        horaCita.disabled = true;
        document.getElementById('horarios-info').innerHTML = '<i class="bi bi-hourglass-split text-warning me-1"></i>Cargando horarios disponibles...';
        
        // Llamada AJAX para obtener horarios disponibles
        fetch(`<?= Router::url('citas/obtenerHorariosDisponibles') ?>?medico_id=${medicoId}&fecha=${fecha}`)
            .then(response => response.json())
            .then(data => {
                horaCita.innerHTML = '<option value="">Seleccionar hora...</option>';
                
                if (data.error) {
                    document.getElementById('horarios-info').innerHTML = '<i class="bi bi-exclamation-triangle text-danger me-1"></i>' + data.error;
                    horaCita.disabled = true;
                    return;
                }
                
                if (data.length === 0) {
                    horaCita.innerHTML = '<option value="">No hay horarios disponibles</option>';
                    document.getElementById('horarios-info').innerHTML = '<i class="bi bi-x-circle text-danger me-1"></i>No hay horarios disponibles para esta fecha';
                    horaCita.disabled = true;
                } else {
                    data.forEach(horario => {
                        const option = document.createElement('option');
                        option.value = horario.valor;
                        option.textContent = horario.texto;
                        horaCita.appendChild(option);
                    });
                    
                    horaCita.disabled = false;
                    document.getElementById('horarios-info').innerHTML = `<i class="bi bi-check-circle text-success me-1"></i>${data.length} horarios disponibles`;
                }
            })
            .catch(error => {
                console.error('Error al cargar horarios:', error);
                horaCita.innerHTML = '<option value="">Error al cargar horarios</option>';
                document.getElementById('horarios-info').innerHTML = '<i class="bi bi-x-circle text-danger me-1"></i>Error al cargar horarios';
                horaCita.disabled = true;
            });
    }
    
    // Actualizar resumen cuando cambien los campos
    document.querySelectorAll('#fecha_cita, #hora_cita, #motivo_consulta').forEach(input => {
        input.addEventListener('change', actualizarResumen);
        input.addEventListener('input', actualizarResumen);
    });
    
    // Función para actualizar el resumen
    function actualizarResumen() {
        const pacienteNombre = pacienteSelect.selectedOptions[0]?.textContent || '-';
        const medicoNombre = medicoSelect.selectedOptions[0]?.textContent || '-';
        const especialidad = medicoSelect.selectedOptions[0]?.getAttribute('data-especialidad') || '-';
        const fecha = fechaCita.value;
        const hora = horaCita.selectedOptions[0]?.textContent || '-';
        const costo = document.getElementById('costo').value;
        const motivo = document.getElementById('motivo_consulta').value || '-';
        
        document.getElementById('resumen-paciente').textContent = pacienteNombre;
        document.getElementById('resumen-medico').textContent = medicoNombre;
        document.getElementById('resumen-especialidad').textContent = especialidad;
        document.getElementById('resumen-fecha').textContent = fecha ? new Date(fecha + 'T00:00:00').toLocaleDateString('es-ES', { year: 'numeric', month: 'long', day: 'numeric' }) : '-';
        document.getElementById('resumen-hora').textContent = hora;
        document.getElementById('resumen-costo').textContent = costo ? `Q ${parseFloat(costo).toFixed(2)}` : '-';
        document.getElementById('resumen-motivo').textContent = motivo;
        
        // Mostrar resumen si hay datos principales
        if (pacienteSelect.value && medicoSelect.value && fecha && horaCita.value) {
            document.getElementById('resumen-cita').style.display = 'block';
        } else {
            document.getElementById('resumen-cita').style.display = 'none';
        }
    }
    
    // Validaciones del formulario
    formCita.addEventListener('submit', function(e) {
        const errors = [];
        
        if (!pacienteSelect.value) errors.push('Debe seleccionar un paciente');
        if (!medicoSelect.value) errors.push('Debe seleccionar un médico');
        if (!fechaCita.value) errors.push('Debe seleccionar una fecha');
        if (!horaCita.value) errors.push('Debe seleccionar una hora');
        if (!document.getElementById('motivo_consulta').value.trim()) {
            errors.push('Debe especificar el motivo de la consulta');
        }
        
        if (errors.length > 0) {
            e.preventDefault();
            alert('Por favor corrija los siguientes errores:\n\n' + errors.join('\n'));
            return false;
        }
        
        // Confirmar antes de enviar
        if (!confirm('¿Está seguro de programar esta cita médica?')) {
            e.preventDefault();
            return false;
        }
    });
    
    // Limpiar formulario
    document.getElementById('btn-limpiar').addEventListener('click', function() {
        if (confirm('¿Está seguro de limpiar todo el formulario?')) {
            formCita.reset();
            document.getElementById('info-paciente').style.display = 'none';
            document.getElementById('info-medico').style.display = 'none';
            document.getElementById('resumen-cita').style.display = 'none';
            horaCita.disabled = true;
            horaCita.innerHTML = '<option value="">Primero seleccione médico y fecha</option>';
            buscarPaciente.value = '';
            const options = pacienteSelect.querySelectorAll('option');
            options.forEach(option => option.style.display = 'block');
        }
    });
});
</script>