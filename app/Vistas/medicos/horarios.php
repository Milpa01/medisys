<?php
if (!defined('APP_PATH')) exit('No direct script access allowed');
?>

<!-- Encabezado del Médico -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card shadow-sm">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h4 class="mb-1">
                            <i class="bi bi-calendar-week me-2"></i>
                            Gestión de Horarios
                        </h4>
                        <div class="text-muted">
                            <strong>Dr(a). <?= htmlspecialchars($medico['nombre'] . ' ' . $medico['apellidos']) ?></strong>
                            <span class="mx-2">|</span>
                            <span><?= htmlspecialchars($medico['especialidad']) ?></span>
                            <?php if ($medico['consultorio']): ?>
                                <span class="mx-2">|</span>
                                <i class="bi bi-door-open me-1"></i>
                                <?= htmlspecialchars($medico['consultorio']) ?>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="col-md-4 text-end">
                        <a href="<?= Util::url('medicos/ver?id=' . $medico['id']) ?>" 
                           class="btn btn-outline-primary btn-sm">
                            <i class="bi bi-arrow-left me-1"></i>
                            Volver al Perfil
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Pestañas de Navegación -->
<ul class="nav nav-tabs mb-4" id="horariosTab" role="tablist">
    <li class="nav-item" role="presentation">
        <button class="nav-link active" 
                id="horario-regular-tab" 
                data-bs-toggle="tab" 
                data-bs-target="#horario-regular" 
                type="button" 
                role="tab">
            <i class="bi bi-clock me-1"></i>
            Horario Regular
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" 
                id="calendario-tab" 
                data-bs-toggle="tab" 
                data-bs-target="#calendario" 
                type="button" 
                role="tab">
            <i class="bi bi-calendar3 me-1"></i>
            Calendario de Disponibilidad
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" 
                id="bloqueos-tab" 
                data-bs-toggle="tab" 
                data-bs-target="#bloqueos" 
                type="button" 
                role="tab">
            <i class="bi bi-x-circle me-1"></i>
            Bloqueos y Vacaciones
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" 
                id="horarios-especiales-tab" 
                data-bs-toggle="tab" 
                data-bs-target="#horarios-especiales" 
                type="button" 
                role="tab">
            <i class="bi bi-star me-1"></i>
            Horarios Especiales
        </button>
    </li>
</ul>

<!-- Contenido de las Pestañas -->
<div class="tab-content" id="horariosTabContent">
    
    <!-- Horario Regular -->
    <div class="tab-pane fade show active" id="horario-regular" role="tabpanel">
        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="bi bi-clock-history me-2"></i>
                            Horario de Atención Regular
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-info">
                            <i class="bi bi-info-circle me-2"></i>
                            Este es el horario habitual de atención. Se aplicará a todos los días seleccionados a menos que haya un horario especial o bloqueo.
                        </div>
                        
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="d-flex align-items-center">
                                    <div class="flex-shrink-0">
                                        <div class="bg-primary rounded-circle p-3">
                                            <i class="bi bi-sunrise text-white" style="font-size: 1.5rem;"></i>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <h6 class="mb-0">Hora de Inicio</h6>
                                        <h3 class="mb-0 text-primary">
                                            <?= Util::formatTime($medico['horario_inicio']) ?>
                                        </h3>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="d-flex align-items-center">
                                    <div class="flex-shrink-0">
                                        <div class="bg-warning rounded-circle p-3">
                                            <i class="bi bi-sunset text-white" style="font-size: 1.5rem;"></i>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <h6 class="mb-0">Hora de Fin</h6>
                                        <h3 class="mb-0 text-warning">
                                            <?= Util::formatTime($medico['horario_fin']) ?>
                                        </h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <h6 class="mb-3">Días de Atención:</h6>
                        <div class="d-flex flex-wrap gap-2 mb-4">
                            <?php 
                            $diasSemana = [
                                'lunes' => 'L',
                                'martes' => 'M',
                                'miercoles' => 'X',
                                'jueves' => 'J',
                                'viernes' => 'V',
                                'sabado' => 'S',
                                'domingo' => 'D'
                            ];
                            
                            $diasCompletos = [
                                'lunes' => 'Lunes',
                                'martes' => 'Martes',
                                'miercoles' => 'Miércoles',
                                'jueves' => 'Jueves',
                                'viernes' => 'Viernes',
                                'sabado' => 'Sábado',
                                'domingo' => 'Domingo'
                            ];
                            
                            $diasAtencion = explode(',', $medico['dias_atencion']);
                            
                            foreach ($diasSemana as $valor => $inicial):
                                $activo = in_array($valor, $diasAtencion);
                            ?>
                                <div class="text-center">
                                    <div class="badge <?= $activo ? 'bg-success' : 'bg-secondary' ?> p-3" 
                                         style="width: 50px; height: 50px; display: flex; align-items: center; justify-content: center; font-size: 1.2rem;"
                                         data-bs-toggle="tooltip" 
                                         title="<?= $diasCompletos[$valor] ?>">
                                        <?= $inicial ?>
                                    </div>
                                    <small class="d-block mt-1 <?= $activo ? 'text-success' : 'text-muted' ?>">
                                        <?= $activo ? 'Activo' : 'Inactivo' ?>
                                    </small>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        
                        <div class="text-end">
                            <a href="<?= Util::url('medicos/editar?id=' . $medico['id']) ?>" 
                               class="btn btn-primary">
                                <i class="bi bi-pencil me-1"></i>
                                Modificar Horario Regular
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="card bg-light">
                    <div class="card-body">
                        <h6 class="mb-3">
                            <i class="bi bi-calculator me-2"></i>
                            Información Calculada
                        </h6>
                        
                        <div class="mb-3">
                            <small class="text-muted">Horas de trabajo diarias</small>
                            <h5 class="mb-0">
                                <?php
                                $inicio = strtotime($medico['horario_inicio']);
                                $fin = strtotime($medico['horario_fin']);
                                $horas = ($fin - $inicio) / 3600;
                                echo number_format($horas, 1);
                                ?> horas
                            </h5>
                        </div>
                        
                        <div class="mb-3">
                            <small class="text-muted">Días laborales por semana</small>
                            <h5 class="mb-0">
                                <?= count($diasAtencion) ?> días
                            </h5>
                        </div>
                        
                        <div class="mb-3">
                            <small class="text-muted">Horas semanales totales</small>
                            <h5 class="mb-0">
                                <?= number_format($horas * count($diasAtencion), 1) ?> horas
                            </h5>
                        </div>
                        
                        <div class="mb-0">
                            <small class="text-muted">Costo por consulta</small>
                            <h5 class="mb-0 text-success">
                                Q <?= number_format($medico['costo_consulta'], 2) ?>
                            </h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Calendario de Disponibilidad -->
    <div class="tab-pane fade" id="calendario" role="tabpanel">
        <div class="card">
            <div class="card-header">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <h5 class="card-title mb-0">
                            <i class="bi bi-calendar3 me-2"></i>
                            Calendario de Disponibilidad
                        </h5>
                    </div>
                    <div class="col-md-6 text-end">
                        <button type="button" class="btn btn-sm btn-primary" id="btnHoy">
                            <i class="bi bi-calendar-check me-1"></i>
                            Hoy
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-primary" id="btnMesAnterior">
                            <i class="bi bi-chevron-left"></i>
                        </button>
                        <span id="mesActual" class="mx-3 fw-bold"></span>
                        <button type="button" class="btn btn-sm btn-outline-primary" id="btnMesSiguiente">
                            <i class="bi bi-chevron-right"></i>
                        </button>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div id="calendario-disponibilidad" class="table-responsive">
                    <!-- El calendario se generará dinámicamente con JavaScript -->
                </div>
                
                <div class="mt-4">
                    <h6>Leyenda:</h6>
                    <div class="d-flex flex-wrap gap-3">
                        <div class="d-flex align-items-center">
                            <div class="bg-success" style="width: 20px; height: 20px; border-radius: 3px;"></div>
                            <span class="ms-2 small">Disponible</span>
                        </div>
                        <div class="d-flex align-items-center">
                            <div class="bg-warning" style="width: 20px; height: 20px; border-radius: 3px;"></div>
                            <span class="ms-2 small">Parcialmente ocupado</span>
                        </div>
                        <div class="d-flex align-items-center">
                            <div class="bg-danger" style="width: 20px; height: 20px; border-radius: 3px;"></div>
                            <span class="ms-2 small">Completamente ocupado</span>
                        </div>
                        <div class="d-flex align-items-center">
                            <div class="bg-secondary" style="width: 20px; height: 20px; border-radius: 3px;"></div>
                            <span class="ms-2 small">Día no laborable</span>
                        </div>
                        <div class="d-flex align-items-center">
                            <div class="bg-dark" style="width: 20px; height: 20px; border-radius: 3px;"></div>
                            <span class="ms-2 small">Bloqueado</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Bloqueos y Vacaciones -->
    <div class="tab-pane fade" id="bloqueos" role="tabpanel">
        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="card-title mb-0">
                                <i class="bi bi-x-circle me-2"></i>
                                Bloqueos Registrados
                            </h5>
                            <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalNuevoBloqueo">
                                <i class="bi bi-plus-lg me-1"></i>
                                Nuevo Bloqueo
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Fecha Inicio</th>
                                        <th>Fecha Fin</th>
                                        <th>Motivo</th>
                                        <th>Estado</th>
                                        <th class="text-end">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody id="tabla-bloqueos">
                                    <!-- Los bloqueos se cargarán dinámicamente -->
                                    <tr>
                                        <td colspan="5" class="text-center text-muted py-4">
                                            <i class="bi bi-inbox" style="font-size: 2rem;"></i>
                                            <p class="mb-0 mt-2">No hay bloqueos registrados</p>
                                            <small>Haga clic en "Nuevo Bloqueo" para agregar uno</small>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="card bg-info text-white">
                    <div class="card-body">
                        <h6 class="card-title">
                            <i class="bi bi-info-circle me-2"></i>
                            ¿Qué son los bloqueos?
                        </h6>
                        <p class="small mb-0">
                            Los bloqueos permiten indicar períodos en los que el médico no estará disponible para atender pacientes, como:
                        </p>
                        <ul class="small mt-2 mb-0">
                            <li>Vacaciones</li>
                            <li>Congresos médicos</li>
                            <li>Días personales</li>
                            <li>Emergencias</li>
                            <li>Otros compromisos</li>
                        </ul>
                    </div>
                </div>
                
                <div class="card mt-3">
                    <div class="card-body">
                        <h6 class="card-title">
                            <i class="bi bi-exclamation-triangle me-2"></i>
                            Importante
                        </h6>
                        <p class="small text-muted mb-0">
                            Si hay citas programadas durante el período de bloqueo, deberá reprogramarlas o cancelarlas antes de crear el bloqueo.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Horarios Especiales -->
    <div class="tab-pane fade" id="horarios-especiales" role="tabpanel">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-star me-2"></i>
                        Horarios Especiales
                    </h5>
                    <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalNuevoEspecial">
                        <i class="bi bi-plus-lg me-1"></i>
                        Nuevo Horario Especial
                    </button>
                </div>
            </div>
            <div class="card-body">
                <div class="alert alert-info">
                    <i class="bi bi-info-circle me-2"></i>
                    Los horarios especiales permiten definir horarios diferentes para fechas específicas, por ejemplo, atención los sábados en horario reducido.
                </div>
                
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Fecha</th>
                                <th>Hora Inicio</th>
                                <th>Hora Fin</th>
                                <th>Descripción</th>
                                <th class="text-end">Acciones</th>
                            </tr>
                        </thead>
                        <tbody id="tabla-especiales">
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">
                                    <i class="bi bi-inbox" style="font-size: 2rem;"></i>
                                    <p class="mb-0 mt-2">No hay horarios especiales registrados</p>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Nuevo Bloqueo -->
<div class="modal fade" id="modalNuevoBloqueo" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="bi bi-x-circle me-2"></i>
                    Registrar Bloqueo
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="formNuevoBloqueo">
                    <input type="hidden" name="medico_id" value="<?= $medico['id'] ?>">
                    
                    <div class="mb-3">
                        <label for="fecha_inicio_bloqueo" class="form-label">
                            Fecha de Inicio <span class="text-danger">*</span>
                        </label>
                        <input type="date" class="form-control" id="fecha_inicio_bloqueo" name="fecha_inicio" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="fecha_fin_bloqueo" class="form-label">
                            Fecha de Fin <span class="text-danger">*</span>
                        </label>
                        <input type="date" class="form-control" id="fecha_fin_bloqueo" name="fecha_fin" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="motivo_bloqueo" class="form-label">
                            Motivo <span class="text-danger">*</span>
                        </label>
                        <select class="form-select" id="motivo_bloqueo" name="motivo" required>
                            <option value="">Seleccionar motivo...</option>
                            <option value="vacaciones">Vacaciones</option>
                            <option value="congreso">Congreso/Capacitación</option>
                            <option value="personal">Asunto Personal</option>
                            <option value="emergencia">Emergencia</option>
                            <option value="otro">Otro</option>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label for="descripcion_bloqueo" class="form-label">Descripción</label>
                        <textarea class="form-control" id="descripcion_bloqueo" name="descripcion" rows="3" placeholder="Detalles adicionales sobre el bloqueo..."></textarea>
                    </div>
                    
                    <div class="alert alert-warning small">
                        <i class="bi bi-exclamation-triangle me-1"></i>
                        Se verificarán las citas existentes en este período.
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" onclick="guardarBloqueo()">
                    <i class="bi bi-save me-1"></i>
                    Guardar Bloqueo
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Nuevo Horario Especial -->
<div class="modal fade" id="modalNuevoEspecial" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="bi bi-star me-2"></i>
                    Registrar Horario Especial
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="formNuevoEspecial">
                    <input type="hidden" name="medico_id" value="<?= $medico['id'] ?>">
                    
                    <div class="mb-3">
                        <label for="fecha_especial" class="form-label">
                            Fecha <span class="text-danger">*</span>
                        </label>
                        <input type="date" class="form-control" id="fecha_especial" name="fecha" required>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="hora_inicio_especial" class="form-label">
                                    Hora Inicio <span class="text-danger">*</span>
                                </label>
                                <input type="time" class="form-control" id="hora_inicio_especial" name="hora_inicio" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="hora_fin_especial" class="form-label">
                                    Hora Fin <span class="text-danger">*</span>
                                </label>
                                <input type="time" class="form-control" id="hora_fin_especial" name="hora_fin" required>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="descripcion_especial" class="form-label">
                            Descripción <span class="text-danger">*</span>
                        </label>
                        <textarea class="form-control" id="descripcion_especial" name="descripcion" rows="3" required placeholder="Ej: Atención especial día festivo"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" onclick="guardarHorarioEspecial()">
                    <i class="bi bi-save me-1"></i>
                    Guardar Horario
                </button>
            </div>
        </div>
    </div>
</div>

<!-- CSS personalizado -->
<style>
.calendario-dia {
    min-height: 80px;
    border: 1px solid #dee2e6;
    padding: 8px;
    cursor: pointer;
    transition: all 0.3s ease;
}

.calendario-dia:hover {
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    transform: translateY(-2px);
}

.calendario-dia-numero {
    font-weight: 600;
    font-size: 1.1rem;
}

.calendario-dia-citas {
    font-size: 0.75rem;
    margin-top: 5px;
}

.bg-disponible {
    background-color: #d1e7dd;
}

.bg-parcial {
    background-color: #fff3cd;
}

.bg-ocupado {
    background-color: #f8d7da;
}

.bg-no-laborable {
    background-color: #e9ecef;
}

.bg-bloqueado {
    background-color: #343a40;
    color: white;
}
</style>

<!-- JavaScript -->
<script>
// Datos del médico
const medicoId = <?= $medico['id'] ?>;
const diasAtencion = <?= json_encode(explode(',', $medico['dias_atencion'])) ?>;

// Calendario
let mesActual = new Date();

function generarCalendario(mes, anio) {
    const primerDia = new Date(anio, mes, 1);
    const ultimoDia = new Date(anio, mes + 1, 0);
    const diasEnMes = ultimoDia.getDate();
    const primerDiaSemana = primerDia.getDay();
    
    let html = '<table class="table table-bordered text-center">';
    html += '<thead><tr>';
    html += '<th>Dom</th><th>Lun</th><th>Mar</th><th>Mié</th><th>Jue</th><th>Vie</th><th>Sáb</th>';
    html += '</tr></thead><tbody><tr>';
    
    // Días vacíos al inicio
    for (let i = 0; i < primerDiaSemana; i++) {
        html += '<td class="bg-light"></td>';
    }
    
    // Días del mes
    for (let dia = 1; dia <= diasEnMes; dia++) {
        const fecha = new Date(anio, mes, dia);
        const diaSemana = ['domingo', 'lunes', 'martes', 'miercoles', 'jueves', 'viernes', 'sabado'][fecha.getDay()];
        const esLaborable = diasAtencion.includes(diaSemana);
        
        let clase = esLaborable ? 'bg-disponible' : 'bg-no-laborable';
        
        html += `<td class="calendario-dia ${clase}" onclick="verDetalleDia('${anio}-${(mes+1).toString().padStart(2, '0')}-${dia.toString().padStart(2, '0')}')">`;
        html += `<div class="calendario-dia-numero">${dia}</div>`;
        html += `<div class="calendario-dia-citas small">${esLaborable ? 'Disponible' : 'No laborable'}</div>`;
        html += '</td>';
        
        if (fecha.getDay() === 6 && dia < diasEnMes) {
            html += '</tr><tr>';
        }
    }
    
    // Completar última fila
    const ultimoDiaSemana = ultimoDia.getDay();
    if (ultimoDiaSemana < 6) {
        for (let i = ultimoDiaSemana + 1; i <= 6; i++) {
            html += '<td class="bg-light"></td>';
        }
    }
    
    html += '</tr></tbody></table>';
    
    document.getElementById('calendario-disponibilidad').innerHTML = html;
    
    // Actualizar título del mes
    const meses = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 
                   'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];
    document.getElementById('mesActual').textContent = `${meses[mes]} ${anio}`;
}

function verDetalleDia(fecha) {
    console.log('Ver detalle del día:', fecha);
    // Aquí se puede implementar la lógica para mostrar las citas del día
    // alert('Detalle del día: ' + fecha);
}

// Navegación del calendario
document.getElementById('btnMesAnterior').addEventListener('click', function() {
    mesActual.setMonth(mesActual.getMonth() - 1);
    generarCalendario(mesActual.getMonth(), mesActual.getFullYear());
});

document.getElementById('btnMesSiguiente').addEventListener('click', function() {
    mesActual.setMonth(mesActual.getMonth() + 1);
    generarCalendario(mesActual.getMonth(), mesActual.getFullYear());
});

document.getElementById('btnHoy').addEventListener('click', function() {
    mesActual = new Date();
    generarCalendario(mesActual.getMonth(), mesActual.getFullYear());
});

// Inicializar calendario
generarCalendario(mesActual.getMonth(), mesActual.getFullYear());

// Funciones para guardar bloqueos y horarios especiales
function guardarBloqueo() {
    const form = document.getElementById('formNuevoBloqueo');
    if (!form.checkValidity()) {
        form.reportValidity();
        return;
    }
    
    const formData = new FormData(form);
    
    // Aquí se enviaría la petición al servidor
    console.log('Guardar bloqueo:', Object.fromEntries(formData));
    alert('Función de guardar bloqueo en desarrollo');
    
    // Cerrar modal
    bootstrap.Modal.getInstance(document.getElementById('modalNuevoBloqueo')).hide();
}

function guardarHorarioEspecial() {
    const form = document.getElementById('formNuevoEspecial');
    if (!form.checkValidity()) {
        form.reportValidity();
        return;
    }
    
    const formData = new FormData(form);
    
    // Aquí se enviaría la petición al servidor
    console.log('Guardar horario especial:', Object.fromEntries(formData));
    alert('Función de guardar horario especial en desarrollo');
    
    // Cerrar modal
    bootstrap.Modal.getInstance(document.getElementById('modalNuevoEspecial')).hide();
}

// Inicializar tooltips
const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
tooltipTriggerList.map(function (tooltipTriggerEl) {
    return new bootstrap.Tooltip(tooltipTriggerEl);
});

// Validación de fechas en bloqueos
document.getElementById('fecha_fin_bloqueo').addEventListener('change', function() {
    const fechaInicio = document.getElementById('fecha_inicio_bloqueo').value;
    const fechaFin = this.value;
    
    if (fechaInicio && fechaFin && fechaFin < fechaInicio) {
        alert('La fecha de fin debe ser posterior a la fecha de inicio');
        this.value = '';
    }
});

// Validación de horarios especiales
document.getElementById('hora_fin_especial').addEventListener('change', function() {
    const horaInicio = document.getElementById('hora_inicio_especial').value;
    const horaFin = this.value;
    
    if (horaInicio && horaFin && horaFin <= horaInicio) {
        alert('La hora de fin debe ser posterior a la hora de inicio');
        this.value = '';
    }
});
</script>