<div class="container-fluid">
    <!-- Encabezado -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-0">
                        <i class="bi bi-clipboard-plus me-2"></i>
                        Nueva Consulta Médica
                    </h1>
                    <p class="text-muted mb-0">
                        Cita: <?= htmlspecialchars($cita['codigo_cita']) ?> - 
                        <?= Util::formatDate($cita['fecha_cita']) ?> <?= Util::formatTime($cita['hora_cita']) ?>
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

    <!-- Información de la Cita -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="alert alert-info">
                <div class="row">
                    <div class="col-md-6">
                        <strong>Paciente:</strong> <?= htmlspecialchars($cita['paciente_nombre']) ?><br>
                        <strong>Motivo:</strong> <?= htmlspecialchars($cita['motivo_consulta']) ?>
                    </div>
                    <div class="col-md-6">
                        <strong>Médico:</strong> Dr. <?= htmlspecialchars($cita['medico_nombre']) ?><br>
                        <strong>Especialidad:</strong> <?= htmlspecialchars($cita['especialidad']) ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Formulario de Consulta -->
    <form method="POST" action="<?= Util::url('consultas/nueva') ?>" novalidate>
        <input type="hidden" name="cita_id" value="<?= $cita['id'] ?>">
        
        <div class="row">
            <!-- Columna Principal -->
            <div class="col-xl-8 col-lg-7">
                
                <!-- Signos Vitales -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="bi bi-heart-pulse me-2"></i>
                            Signos Vitales
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="peso" class="form-label">Peso (kg)</label>
                                    <input type="number" class="form-control" id="peso" name="peso" 
                                           step="0.1" min="1" max="500" placeholder="70.5">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="altura" class="form-label">Altura (cm)</label>
                                    <input type="number" class="form-control" id="altura" name="altura" 
                                           step="0.1" min="30" max="250" placeholder="175">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="temperatura" class="form-label">Temperatura (°C)</label>
                                    <input type="number" class="form-control" id="temperatura" name="temperatura" 
                                           step="0.1" min="30" max="45" placeholder="36.5">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="frecuencia_cardiaca" class="form-label">FC (ppm)</label>
                                    <input type="number" class="form-control" id="frecuencia_cardiaca" name="frecuencia_cardiaca" 
                                           min="30" max="250" placeholder="72">
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="presion_sistolica" class="form-label">Presión Sistólica</label>
                                    <input type="number" class="form-control" id="presion_sistolica" name="presion_sistolica" 
                                           min="70" max="250" placeholder="120">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="presion_diastolica" class="form-label">Presión Diastólica</label>
                                    <input type="number" class="form-control" id="presion_diastolica" name="presion_diastolica" 
                                           min="40" max="150" placeholder="80">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label class="form-label">IMC</label>
                                    <input type="text" class="form-control" id="imc_display" readonly 
                                           placeholder="Se calcula automáticamente">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label class="form-label">Clasificación</label>
                                    <input type="text" class="form-control" id="imc_clasificacion" readonly>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Evaluación Clínica -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="bi bi-clipboard-data me-2"></i>
                            Evaluación Clínica
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="sintomas" class="form-label">
                                Síntomas <span class="text-danger">*</span>
                            </label>
                            <textarea class="form-control" id="sintomas" name="sintomas" rows="4" 
                                      placeholder="Descripción detallada de los síntomas presentados por el paciente..." required></textarea>
                            <div class="invalid-feedback">
                                Por favor describa los síntomas del paciente.
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="exploracion_fisica" class="form-label">
                                Exploración Física <span class="text-danger">*</span>
                            </label>
                            <textarea class="form-control" id="exploracion_fisica" name="exploracion_fisica" rows="4" 
                                      placeholder="Hallazgos del examen físico..." required></textarea>
                            <div class="invalid-feedback">
                                Por favor registre los hallazgos del examen físico.
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Diagnóstico y Tratamiento -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="bi bi-clipboard-check me-2"></i>
                            Diagnóstico y Tratamiento
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="diagnostico_principal" class="form-label">
                                Diagnóstico Principal <span class="text-danger">*</span>
                            </label>
                            <input type="text" class="form-control" id="diagnostico_principal" name="diagnostico_principal" 
                                   placeholder="Diagnóstico principal según CIE-10" required>
                            <div class="invalid-feedback">
                                El diagnóstico principal es requerido.
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="diagnosticos_secundarios" class="form-label">Diagnósticos Secundarios</label>
                            <textarea class="form-control" id="diagnosticos_secundarios" name="diagnosticos_secundarios" rows="2" 
                                      placeholder="Diagnósticos adicionales o diferenciales..."></textarea>
                        </div>
                        
                        <div class="mb-3">
                            <label for="plan_tratamiento" class="form-label">
                                Plan de Tratamiento <span class="text-danger">*</span>
                            </label>
                            <textarea class="form-control" id="plan_tratamiento" name="plan_tratamiento" rows="4" 
                                      placeholder="Plan terapéutico detallado..." required></textarea>
                            <div class="invalid-feedback">
                                El plan de tratamiento es requerido.
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="indicaciones" class="form-label">Indicaciones al Paciente</label>
                            <textarea class="form-control" id="indicaciones" name="indicaciones" rows="3" 
                                      placeholder="Instrucciones específicas para el paciente..."></textarea>
                        </div>
                    </div>
                </div>

                <!-- Prescripciones -->
                <div class="card mb-4">
                    <div class="card-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="card-title mb-0">
                                <i class="bi bi-prescription2 me-2"></i>
                                Prescripciones
                            </h5>
                            <button type="button" class="btn btn-sm btn-outline-primary" id="agregar-medicamento">
                                <i class="bi bi-plus-circle"></i> Agregar Medicamento
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div id="prescripciones-container">
                            <!-- Las prescripciones se agregarán dinámicamente aquí -->
                        </div>
                        <div class="text-muted small" id="sin-prescripciones">
                            <i class="bi bi-info-circle me-1"></i>
                            No hay medicamentos prescritos. Use el botón "Agregar Medicamento" para añadir prescripciones.
                        </div>
                    </div>
                </div>

                <!-- Seguimiento -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="bi bi-calendar-plus me-2"></i>
                            Seguimiento
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="proxima_cita" class="form-label">Próxima Cita</label>
                                    <input type="date" class="form-control" id="proxima_cita" name="proxima_cita" 
                                           min="<?= date('Y-m-d', strtotime('+1 day')) ?>">
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="observaciones" class="form-label">Observaciones Generales</label>
                            <textarea class="form-control" id="observaciones" name="observaciones" rows="3" 
                                      placeholder="Observaciones adicionales sobre la consulta..."></textarea>
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
                                <i class="bi bi-check-circle"></i> Completar Consulta
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
                            <i class="bi bi-person me-2"></i>
                            Información del Paciente
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="text-center mb-3">
                            <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center mb-2" 
                                 style="width: 60px; height: 60px;">
                                <i class="bi bi-person-fill display-6 text-muted"></i>
                            </div>
                            <h6 class="mb-1"><?= htmlspecialchars($cita['paciente_nombre']) ?></h6>
                            <small class="text-muted"><?= htmlspecialchars($cita['codigo_paciente']) ?></small>
                        </div>
                        
                        <div class="d-grid">
                            <a href="<?= Util::url('pacientes/ver?id=' . $cita['paciente_id']) ?>" 
                               class="btn btn-outline-primary btn-sm mb-2">
                                <i class="bi bi-eye"></i> Ver Expediente
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Plantillas Rápidas -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h6 class="card-title mb-0">
                            <i class="bi bi-lightning me-2"></i>
                            Plantillas Rápidas
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <button type="button" class="btn btn-outline-secondary btn-sm plantilla-btn" 
                                    data-tipo="gripe">
                                <i class="bi bi-thermometer"></i> Gripe/Resfriado
                            </button>
                            <button type="button" class="btn btn-outline-secondary btn-sm plantilla-btn" 
                                    data-tipo="hipertension">
                                <i class="bi bi-heart"></i> Hipertensión
                            </button>
                            <button type="button" class="btn btn-outline-secondary btn-sm plantilla-btn" 
                                    data-tipo="diabetes">
                                <i class="bi bi-droplet"></i> Diabetes
                            </button>
                            <button type="button" class="btn btn-outline-secondary btn-sm plantilla-btn" 
                                    data-tipo="control">
                                <i class="bi bi-check-circle"></i> Control Rutinario
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Rangos de Referencia -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h6 class="card-title mb-0">
                            <i class="bi bi-info-circle me-2"></i>
                            Rangos de Referencia
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="small">
                            <div class="mb-2">
                                <strong>Temperatura:</strong> 36.1°C - 37.2°C
                            </div>
                            <div class="mb-2">
                                <strong>Presión Arterial:</strong><br>
                                Normal: < 120/80 mmHg<br>
                                Elevada: 120-129/<80 mmHg<br>
                                Hipertensión: ≥ 130/80 mmHg
                            </div>
                            <div class="mb-2">
                                <strong>Frecuencia Cardíaca:</strong><br>
                                Adultos: 60-100 ppm<br>
                                Deportistas: 40-60 ppm
                            </div>
                            <div class="mb-0">
                                <strong>IMC:</strong><br>
                                Bajo peso: < 18.5<br>
                                Normal: 18.5-24.9<br>
                                Sobrepeso: 25-29.9<br>
                                Obesidad: ≥ 30
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Atajos de Teclado -->
                <div class="card">
                    <div class="card-header">
                        <h6 class="card-title mb-0">
                            <i class="bi bi-keyboard me-2"></i>
                            Atajos de Teclado
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="small">
                            <div class="mb-1"><kbd>Ctrl</kbd> + <kbd>S</kbd> - Guardar borrador</div>
                            <div class="mb-1"><kbd>Ctrl</kbd> + <kbd>Enter</kbd> - Completar consulta</div>
                            <div class="mb-1"><kbd>Ctrl</kbd> + <kbd>M</kbd> - Agregar medicamento</div>
                            <div class="mb-0"><kbd>Esc</kbd> - Cancelar</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<!-- Template para prescripción -->
<template id="prescripcion-template">
    <div class="row prescripcion-item border rounded p-3 mb-3">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <h6 class="mb-0">Medicamento</h6>
                <button type="button" class="btn btn-sm btn-outline-danger eliminar-medicamento">
                    <i class="bi bi-trash"></i>
                </button>
            </div>
        </div>
        <div class="col-md-6">
            <div class="mb-2">
                <label class="form-label">Medicamento <span class="text-danger">*</span></label>
                <select class="form-select medicamento-select" name="medicamentos[]" required>
                    <option value="">Seleccionar medicamento...</option>
                    <?php foreach ($medicamentos as $medicamento): ?>
                        <option value="<?= $medicamento['id'] ?>">
                            <?= htmlspecialchars($medicamento['nombre_comercial']) ?>
                            <?php if ($medicamento['nombre_generico']): ?>
                                (<?= htmlspecialchars($medicamento['nombre_generico']) ?>)
                            <?php endif; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
        <div class="col-md-6">
            <div class="mb-2">
                <label class="form-label">Dosis <span class="text-danger">*</span></label>
                <input type="text" class="form-control" name="dosis[]" placeholder="500mg" required>
            </div>
        </div>
        <div class="col-md-6">
            <div class="mb-2">
                <label class="form-label">Frecuencia <span class="text-danger">*</span></label>
                <select class="form-select" name="frecuencias[]" required>
                    <option value="">Seleccionar...</option>
                    <option value="Cada 8 horas">Cada 8 horas</option>
                    <option value="Cada 12 horas">Cada 12 horas</option>
                    <option value="Cada 24 horas">Cada 24 horas</option>
                    <option value="2 veces al día">2 veces al día</option>
                    <option value="3 veces al día">3 veces al día</option>
                    <option value="Según necesidad">Según necesidad</option>
                </select>
            </div>
        </div>
        <div class="col-md-6">
            <div class="mb-2">
                <label class="form-label">Duración</label>
                <input type="text" class="form-control" name="duraciones[]" placeholder="7 días">
            </div>
        </div>
        <div class="col-md-6">
            <div class="mb-2">
                <label class="form-label">Vía</label>
                <select class="form-select" name="vias[]">
                    <option value="oral">Oral</option>
                    <option value="intravenosa">Intravenosa</option>
                    <option value="intramuscular">Intramuscular</option>
                    <option value="topica">Tópica</option>
                    <option value="sublingual">Sublingual</option>
                    <option value="otra">Otra</option>
                </select>
            </div>
        </div>
        <div class="col-md-6">
            <div class="mb-2">
                <label class="form-label">Cantidad</label>
                <input type="number" class="form-control" name="cantidades[]" placeholder="30">
            </div>
        </div>
        <div class="col-12">
            <div class="mb-0">
                <label class="form-label">Indicaciones Especiales</label>
                <textarea class="form-control" name="indicaciones_especiales[]" rows="2" 
                          placeholder="Tomar con alimentos, evitar alcohol, etc."></textarea>
            </div>
        </div>
    </div>
</template>

<!-- JavaScript -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Variables globales
    let prescripcionCounter = 0;
    
    // Calcular IMC automáticamente
    const pesoInput = document.getElementById('peso');
    const alturaInput = document.getElementById('altura');
    const imcDisplay = document.getElementById('imc_display');
    const imcClasificacion = document.getElementById('imc_clasificacion');
    
    function calcularIMC() {
        const peso = parseFloat(pesoInput.value);
        const altura = parseFloat(alturaInput.value);
        
        if (peso && altura && altura > 0) {
            const alturaMetros = altura / 100;
            const imc = peso / (alturaMetros * alturaMetros);
            imcDisplay.value = imc.toFixed(1);
            
            // Clasificación
            let clasificacion = '';
            if (imc < 18.5) {
                clasificacion = 'Bajo peso';
            } else if (imc < 25) {
                clasificacion = 'Normal';
            } else if (imc < 30) {
                clasificacion = 'Sobrepeso';
            } else {
                clasificacion = 'Obesidad';
            }
            imcClasificacion.value = clasificacion;
        } else {
            imcDisplay.value = '';
            imcClasificacion.value = '';
        }
    }
    
    pesoInput.addEventListener('input', calcularIMC);
    alturaInput.addEventListener('input', calcularIMC);
    
    // Agregar medicamento
    document.getElementById('agregar-medicamento').addEventListener('click', function() {
        const template = document.getElementById('prescripcion-template');
        const container = document.getElementById('prescripciones-container');
        const sinPrescripciones = document.getElementById('sin-prescripciones');
        
        const clone = template.content.cloneNode(true);
        container.appendChild(clone);
        
        prescripcionCounter++;
        sinPrescripciones.style.display = 'none';
        
        // Agregar event listener al botón eliminar
        const eliminarBtn = container.lastElementChild.querySelector('.eliminar-medicamento');
        eliminarBtn.addEventListener('click', function() {
            this.closest('.prescripcion-item').remove();
            prescripcionCounter--;
            
            if (prescripcionCounter === 0) {
                sinPrescripciones.style.display = 'block';
            }
        });
    });
    
    // Plantillas rápidas
    document.querySelectorAll('.plantilla-btn').forEach(function(btn) {
        btn.addEventListener('click', function() {
            const tipo = this.dataset.tipo;
            aplicarPlantilla(tipo);
        });
    });
    
    function aplicarPlantilla(tipo) {
        const plantillas = {
            gripe: {
                sintomas: 'Fiebre, congestión nasal, tos, malestar general, cefalea.',
                exploracion: 'Paciente febril, congestión nasal, faringe eritematosa, sin adenopatías palpables.',
                diagnostico: 'Rinofaringitis viral aguda (J00)',
                tratamiento: 'Manejo sintomático con analgésicos, descongestionantes, reposo e hidratación.'
            },
            hipertension: {
                sintomas: 'Cefalea ocasional, control de presión arterial.',
                exploracion: 'TA elevada, resto del examen físico normal.',
                diagnostico: 'Hipertensión arterial esencial (I10)',
                tratamiento: 'Modificaciones en el estilo de vida, medicación antihipertensiva según protocolo.'
            },
            diabetes: {
                sintomas: 'Control glucémico, seguimiento de diabetes mellitus.',
                exploracion: 'Paciente estable, sin complicaciones agudas evidentes.',
                diagnostico: 'Diabetes mellitus tipo 2 (E11)',
                tratamiento: 'Continuar con plan nutricional, ejercicio y medicación actual.'
            },
            control: {
                sintomas: 'Consulta de control, sin síntomas agudos.',
                exploracion: 'Paciente estable, signos vitales dentro de parámetros normales.',
                diagnostico: 'Control médico rutinario (Z00.0)',
                tratamiento: 'Continuar con medidas preventivas y seguimiento programado.'
            }
        };
        
        if (plantillas[tipo]) {
            const plantilla = plantillas[tipo];
            document.getElementById('sintomas').value = plantilla.sintomas;
            document.getElementById('exploracion_fisica').value = plantilla.exploracion;
            document.getElementById('diagnostico_principal').value = plantilla.diagnostico;
            document.getElementById('plan_tratamiento').value = plantilla.tratamiento;
        }
    }
    
    // Validación del formulario
    const form = document.querySelector('form');
    form.addEventListener('submit', function(e) {
        if (!form.checkValidity()) {
            e.preventDefault();
            e.stopPropagation();
        }
        form.classList.add('was-validated');
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
                    form.submit();
                    break;
                case 'm':
                    e.preventDefault();
                    document.getElementById('agregar-medicamento').click();
                    break;
            }
        }
        
        if (e.key === 'Escape') {
            window.location.href = '<?= Util::url('citas/ver?id=' . $cita['id']) ?>';
        }
    });
    
    // Guardar borrador (localStorage para recuperación)
    document.getElementById('guardar-borrador').addEventListener('click', function() {
        const formData = new FormData(form);
        const data = Object.fromEntries(formData.entries());
        localStorage.setItem('consulta_borrador_<?= $cita['id'] ?>', JSON.stringify(data));
        
        // Mostrar mensaje
        const alert = document.createElement('div');
        alert.className = 'alert alert-success alert-dismissible fade show';
        alert.innerHTML = '<i class="bi bi-check-circle me-2"></i>Borrador guardado exitosamente.';
        document.querySelector('.container-fluid').insertBefore(alert, document.querySelector('.row'));
        
        setTimeout(() => alert.remove(), 3000);
    });
    
    // Cargar borrador si existe
    const borrador = localStorage.getItem('consulta_borrador_<?= $cita['id'] ?>');
    if (borrador) {
        try {
            const data = JSON.parse(borrador);
            Object.keys(data).forEach(key => {
                const input = document.querySelector(`[name="${key}"]`);
                if (input) {
                    input.value = data[key];
                }
            });
            calcularIMC(); // Recalcular IMC si hay datos
        } catch (e) {
            console.log('Error cargando borrador:', e);
        }
    }
});
</script>

<style>
.prescripcion-item {
    background-color: #f8f9fa;
    border: 1px solid #dee2e6 !important;
}

.prescripcion-item:hover {
    background-color: #e9ecef;
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

kbd {
    padding: 0.2rem 0.4rem;
    font-size: 0.7rem;
    color: #fff;
    background-color: #212529;
    border-radius: 0.2rem;
}

.plantilla-btn {
    text-align: left;
}

.alert {
    animation: slideInDown 0.3s ease-out;
}

@keyframes slideInDown {
    from {
        transform: translateY(-100%);
        opacity: 0;
    }
    to {
        transform: translateY(0);
        opacity: 1;
    }
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .prescripcion-item .row {
        margin: 0 -5px;
    }
    
    .prescripcion-item .col-md-6 {
        padding: 0 5px;
    }
}
</style>