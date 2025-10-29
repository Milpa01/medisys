<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="bi bi-person-check text-primary me-2"></i>Mi Agenda Médica</h2>
    <div class="btn-group">
        <a href="<?= Router::url('citas/calendario') ?>" class="btn btn-outline-info">
            <i class="bi bi-calendar-week me-2"></i>Vista Calendario
        </a>
        <a href="<?= Router::url('citas') ?>" class="btn btn-outline-secondary">
            <i class="bi bi-list me-2"></i>Todas las Citas
        </a>
    </div>
</div>

<!-- Información del médico -->
<div class="card mb-4">
    <div class="card-body">
        <div class="row align-items-center">
            <div class="col-md-8">
                <h5 class="mb-0">
                    <i class="bi bi-person-badge text-success me-2"></i>
                    Dr. <?= htmlspecialchars(($medico['nombre'] ?? '') . ' ' . ($medico['apellidos'] ?? '')) ?>
                </h5>
                <p class="text-muted mb-0">
                    <?= htmlspecialchars($medico['especialidad_nombre'] ?? 'Sin especialidad') ?>
                    <?php if (!empty($medico['consultorio'])): ?>
                        - <?= htmlspecialchars($medico['consultorio']) ?>
                    <?php endif; ?>
                </p>
            </div>
            <div class="col-md-4 text-end">
                <div class="btn-group btn-group-sm">
                    <button class="btn btn-outline-primary" id="btn-hoy">Hoy</button>
                    <button class="btn btn-outline-secondary" id="btn-semana">Esta Semana</button>
                    <button class="btn btn-outline-info" id="btn-mes">Este Mes</button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Selector de fecha -->
<div class="card mb-4">
    <div class="card-body">
        <div class="row align-items-center">
            <div class="col-md-6">
                <div class="d-flex align-items-center gap-3">
                    <button class="btn btn-outline-primary" id="btn-anterior">
                        <i class="bi bi-chevron-left"></i>
                    </button>
                    
                    <div class="input-group" style="width: 200px;">
                        <input type="date" class="form-control" id="fecha-agenda" 
                               value="<?= htmlspecialchars($fecha ?? date('Y-m-d')) ?>">
                    </div>
                    
                    <button class="btn btn-outline-primary" id="btn-siguiente">
                        <i class="bi bi-chevron-right"></i>
                    </button>
                    
                    <span class="text-primary fw-bold" id="fecha-actual">
                        <?= Util::formatDate($fecha ?? date('Y-m-d')) ?>
                    </span>
                </div>
            </div>
            
            <div class="col-md-6 text-end">
                <span class="badge bg-primary me-2">
                    <i class="bi bi-calendar-check me-1"></i>
                    <?= count($citas ?? []) ?> citas programadas
                </span>
                
                <?php
                $citasConfirmadas = array_filter($citas ?? [], fn($c) => $c['estado'] == 'confirmada');
                $citasPendientes = array_filter($citas ?? [], fn($c) => $c['estado'] == 'programada');
                ?>
                
                <span class="badge bg-success me-2">
                    <?= count($citasConfirmadas) ?> confirmadas
                </span>
                
                <span class="badge bg-warning">
                    <?= count($citasPendientes) ?> pendientes
                </span>
            </div>
        </div>
    </div>
</div>

<!-- Agenda del día -->
<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="bi bi-clock text-primary me-2"></i>
                    Agenda del <?= Util::formatDate($fecha ?? date('Y-m-d')) ?>
                </h5>
                <div class="dropdown">
                    <button class="btn btn-sm btn-outline-secondary dropdown-toggle" 
                            data-bs-toggle="dropdown">
                        <i class="bi bi-funnel me-1"></i>Filtrar
                    </button>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item filtro-estado" href="#" data-estado="todos">Todas las citas</a></li>
                        <li><a class="dropdown-item filtro-estado" href="#" data-estado="programada">Programadas</a></li>
                        <li><a class="dropdown-item filtro-estado" href="#" data-estado="confirmada">Confirmadas</a></li>
                        <li><a class="dropdown-item filtro-estado" href="#" data-estado="completada">Completadas</a></li>
                    </ul>
                </div>
            </div>
            <div class="card-body p-0">
                <?php if (empty($citas)): ?>
                    <div class="text-center py-5">
                        <i class="bi bi-calendar-check display-1 text-muted"></i>
                        <h4 class="mt-3 text-muted">No hay citas programadas</h4>
                        <p class="text-muted">
                            Disfruta tu día libre o revisa otras fechas en tu agenda
                        </p>
                        <div class="mt-3">
                            <button class="btn btn-outline-primary me-2" onclick="cambiarFecha(-1)">
                                <i class="bi bi-chevron-left me-1"></i>Día Anterior
                            </button>
                            <button class="btn btn-outline-primary" onclick="cambiarFecha(1)">
                                Día Siguiente<i class="bi bi-chevron-right ms-1"></i>
                            </button>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="agenda-timeline">
                        <?php 
                        // Generar horarios desde las 8:00 AM hasta las 6:00 PM
                        $horaInicio = 8;
                        $horaFin = 18;
                        
                        for ($hora = $horaInicio; $hora <= $horaFin; $hora++):
                            $horaFormateada = sprintf('%02d:00:00', $hora);
                            $horaDisplay = date('g:i A', strtotime($horaFormateada));
                            
                            // Buscar citas en esta hora
                            $citasHora = array_filter($citas, function($cita) use ($hora) {
                                $horaCita = (int)date('H', strtotime($cita['hora_cita']));
                                return $horaCita == $hora;
                            });
                        ?>
                            <div class="timeline-slot" data-hora="<?= $hora ?>">
                                <div class="d-flex">
                                    <div class="time-marker">
                                        <span class="time-label"><?= $horaDisplay ?></span>
                                        <div class="time-line"></div>
                                    </div>
                                    
                                    <div class="appointments-container flex-grow-1">
                                        <?php if (empty($citasHora)): ?>
                                            <div class="appointment-slot empty">
                                                <span class="text-muted">Libre</span>
                                            </div>
                                        <?php else: ?>
                                            <?php foreach ($citasHora as $cita): ?>
                                                <div class="appointment-slot <?= getClaseEstadoCita($cita['estado']) ?>" 
                                                     data-estado="<?= $cita['estado'] ?>"
                                                     onclick="verDetalleCita(<?= $cita['id'] ?>)">
                                                    <div class="appointment-content">
                                                        <div class="d-flex justify-content-between align-items-start">
                                                            <div class="appointment-info">
                                                                <h6 class="mb-1">
                                                                    <i class="bi bi-clock me-1"></i>
                                                                    <?= Util::formatTime($cita['hora_cita']) ?>
                                                                    
                                                                    <span class="badge <?= getBadgeEstado($cita['estado']) ?> ms-2">
                                                                        <?= ucfirst(str_replace('_', ' ', $cita['estado'])) ?>
                                                                    </span>
                                                                </h6>
                                                                
                                                                <p class="mb-1">
                                                                    <strong><i class="bi bi-person me-1"></i>
                                                                    <?= htmlspecialchars($cita['paciente_nombre']) ?></strong>
                                                                </p>
                                                                
                                                                <p class="mb-1 text-muted small">
                                                                    <i class="bi bi-clipboard-data me-1"></i>
                                                                    <?= htmlspecialchars(Util::truncate($cita['motivo_consulta'], 60)) ?>
                                                                </p>
                                                                
                                                                <?php if (!empty($cita['paciente_telefono'])): ?>
                                                                    <p class="mb-0 text-muted small">
                                                                        <i class="bi bi-telephone me-1"></i>
                                                                        <?= htmlspecialchars($cita['paciente_telefono']) ?>
                                                                    </p>
                                                                <?php endif; ?>
                                                            </div>
                                                            
                                                            <div class="appointment-actions">
                                                                <?php if ($cita['estado'] == 'confirmada'): ?>
                                                                    <button class="btn btn-success btn-sm" 
                                                                            onclick="event.stopPropagation(); iniciarConsulta(<?= $cita['id'] ?>)"
                                                                            title="Iniciar consulta">
                                                                        <i class="bi bi-play-fill"></i>
                                                                    </button>
                                                                <?php elseif ($cita['estado'] == 'programada'): ?>
                                                                    <button class="btn btn-primary btn-sm" 
                                                                            onclick="event.stopPropagation(); confirmarCita(<?= $cita['id'] ?>)"
                                                                            title="Confirmar cita">
                                                                        <i class="bi bi-check2"></i>
                                                                    </button>
                                                                <?php endif; ?>
                                                                
                                                                <button class="btn btn-outline-info btn-sm ms-1" 
                                                                        onclick="event.stopPropagation(); verDetalleCita(<?= $cita['id'] ?>)"
                                                                        title="Ver detalles">
                                                                    <i class="bi bi-eye"></i>
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        <?php endfor; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <!-- Panel lateral -->
    <div class="col-lg-4">
        <!-- Resumen del día -->
        <div class="card mb-4">
            <div class="card-header">
                <h6 class="mb-0">
                    <i class="bi bi-bar-chart text-primary me-2"></i>
                    Resumen del Día
                </h6>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-6">
                        <h4 class="text-primary"><?= count($citas ?? []) ?></h4>
                        <small class="text-muted">Total Citas</small>
                    </div>
                    <div class="col-6">
                        <h4 class="text-success"><?= count($citasConfirmadas) ?></h4>
                        <small class="text-muted">Confirmadas</small>
                    </div>
                </div>
                
                <hr>
                
                <div class="mb-2">
                    <small class="text-muted">Progreso del día:</small>
                    <div class="progress mt-1" style="height: 8px;">
                        <?php 
                        $completadas = count(array_filter($citas ?? [], fn($c) => $c['estado'] == 'completada'));
                        $progreso = count($citas) > 0 ? ($completadas / count($citas)) * 100 : 0;
                        ?>
                        <div class="progress-bar bg-success" style="width: <?= $progreso ?>%"></div>
                    </div>
                    <small class="text-muted"><?= $completadas ?> de <?= count($citas ?? []) ?> completadas</small>
                </div>
            </div>
        </div>
        
        <!-- Próxima cita -->
        <?php 
        $proximaCita = null;
        $ahora = date('H:i:s');
        foreach ($citas ?? [] as $cita) {
            if ($cita['hora_cita'] >= $ahora && in_array($cita['estado'], ['programada', 'confirmada'])) {
                $proximaCita = $cita;
                break;
            }
        }
        ?>
        
        <div class="card mb-4">
            <div class="card-header">
                <h6 class="mb-0">
                    <i class="bi bi-clock-history text-warning me-2"></i>
                    Próxima Cita
                </h6>
            </div>
            <div class="card-body">
                <?php if ($proximaCita): ?>
                    <div class="text-center">
                        <h5 class="text-primary"><?= Util::formatTime($proximaCita['hora_cita']) ?></h5>
                        <p class="mb-2"><strong><?= htmlspecialchars($proximaCita['paciente_nombre']) ?></strong></p>
                        <p class="text-muted small mb-3">
                            <?= htmlspecialchars(Util::truncate($proximaCita['motivo_consulta'], 50)) ?>
                        </p>
                        
                        <?php if ($proximaCita['estado'] == 'confirmada'): ?>
                            <button class="btn btn-success btn-sm" 
                                    onclick="iniciarConsulta(<?= $proximaCita['id'] ?>)">
                                <i class="bi bi-play-fill me-1"></i>Iniciar Consulta
                            </button>
                        <?php else: ?>
                            <button class="btn btn-primary btn-sm" 
                                    onclick="confirmarCita(<?= $proximaCita['id'] ?>)">
                                <i class="bi bi-check2 me-1"></i>Confirmar Cita
                            </button>
                        <?php endif; ?>
                    </div>
                <?php else: ?>
                    <div class="text-center text-muted">
                        <i class="bi bi-check-circle display-4"></i>
                        <p class="mt-2">No hay más citas pendientes para hoy</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        
        <!-- Acciones rápidas -->
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0">
                    <i class="bi bi-lightning-charge text-primary me-2"></i>
                    Acciones Rápidas
                </h6>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="<?= Router::url('consultas') ?>" class="btn btn-outline-primary btn-sm">
                        <i class="bi bi-clipboard2-pulse me-2"></i>Mis Consultas
                    </a>
                    <a href="<?= Router::url('pacientes') ?>" class="btn btn-outline-info btn-sm">
                        <i class="bi bi-people me-2"></i>Buscar Pacientes
                    </a>
                    <button class="btn btn-outline-warning btn-sm" onclick="actualizarAgenda()">
                        <i class="bi bi-arrow-clockwise me-2"></i>Actualizar Agenda
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
function getClaseEstadoCita($estado) {
    return match($estado) {
        'programada' => 'appointment-programada',
        'confirmada' => 'appointment-confirmada',
        'en_curso' => 'appointment-en-curso',
        'completada' => 'appointment-completada',
        'cancelada' => 'appointment-cancelada',
        default => 'appointment-programada'
    };
}

function getBadgeEstado($estado) {
    return match($estado) {
        'programada' => 'bg-secondary',
        'confirmada' => 'bg-primary',
        'en_curso' => 'bg-warning',
        'completada' => 'bg-success',
        'cancelada' => 'bg-danger',
        default => 'bg-secondary'
    };
}
?>

<style>
.agenda-timeline {
    padding: 0;
}

.timeline-slot {
    display: flex;
    min-height: 80px;
    border-bottom: 1px solid #e9ecef;
}

.time-marker {
    width: 100px;
    position: relative;
    padding: 15px 10px;
    background-color: #f8f9fa;
    border-right: 2px solid #dee2e6;
    display: flex;
    flex-direction: column;
    align-items: center;
}

.time-label {
    font-size: 12px;
    font-weight: 600;
    color: #6c757d;
    background: #f8f9fa;
    padding: 2px 8px;
    border-radius: 12px;
    border: 1px solid #dee2e6;
}

.time-line {
    flex-grow: 1;
    width: 2px;
    background-color: #dee2e6;
    margin-top: 10px;
}

.appointments-container {
    padding: 10px;
    min-height: 60px;
}

.appointment-slot {
    border-radius: 8px;
    padding: 12px;
    margin-bottom: 8px;
    cursor: pointer;
    transition: all 0.2s ease;
    border-left: 4px solid;
}

.appointment-slot.empty {
    background-color: transparent;
    border: 1px dashed #dee2e6;
    text-align: center;
    cursor: default;
}

.appointment-slot:hover:not(.empty) {
    transform: translateY(-1px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}

.appointment-programada {
    background-color: #f8f9fa;
    border-left-color: #6c757d;
}

.appointment-confirmada {
    background-color: #e3f2fd;
    border-left-color: #2196f3;
}

.appointment-en-curso {
    background-color: #fff3cd;
    border-left-color: #ffc107;
}

.appointment-completada {
    background-color: #e8f5e8;
    border-left-color: #28a745;
}

.appointment-cancelada {
    background-color: #f8d7da;
    border-left-color: #dc3545;
    opacity: 0.7;
}

.appointment-content h6 {
    font-size: 14px;
    margin-bottom: 8px;
}

.appointment-actions {
    opacity: 0;
    transition: opacity 0.2s ease;
}

.appointment-slot:hover .appointment-actions {
    opacity: 1;
}

.appointment-info p {
    font-size: 13px;
    line-height: 1.3;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const fechaInput = document.getElementById('fecha-agenda');
    const fechaActual = document.getElementById('fecha-actual');
    
    // Navegación de fechas
    document.getElementById('btn-anterior').addEventListener('click', () => cambiarFecha(-1));
    document.getElementById('btn-siguiente').addEventListener('click', () => cambiarFecha(1));
    
    // Botones de período
    document.getElementById('btn-hoy').addEventListener('click', function() {
        const hoy = new Date().toISOString().split('T')[0];
        cargarFecha(hoy);
    });
    
    // Cambio de fecha manual
    fechaInput.addEventListener('change', function() {
        cargarFecha(this.value);
    });
    
    // Filtros de estado
    document.querySelectorAll('.filtro-estado').forEach(filtro => {
        filtro.addEventListener('click', function(e) {
            e.preventDefault();
            const estado = this.getAttribute('data-estado');
            filtrarCitasPorEstado(estado);
        });
    });
    
    // Actualizar hora actual
    actualizarHoraActual();
    setInterval(actualizarHoraActual, 60000); // Cada minuto
});

function cambiarFecha(dias) {
    const fechaActual = new Date(document.getElementById('fecha-agenda').value);
    fechaActual.setDate(fechaActual.getDate() + dias);
    const nuevaFecha = fechaActual.toISOString().split('T')[0];
    cargarFecha(nuevaFecha);
}

function cargarFecha(fecha) {
    const url = `<?= Router::url('citas/agenda') ?>?fecha=${fecha}`;
    window.location.href = url;
}

function filtrarCitasPorEstado(estado) {
    const citas = document.querySelectorAll('.appointment-slot[data-estado]');
    
    citas.forEach(cita => {
        if (estado === 'todos' || cita.getAttribute('data-estado') === estado) {
            cita.style.display = 'block';
        } else {
            cita.style.display = 'none';
        }
    });
}

function verDetalleCita(citaId) {
    window.location.href = `<?= Router::url('citas/ver') ?>?id=${citaId}`;
}

function iniciarConsulta(citaId) {
    if (confirm('¿Desea iniciar la consulta médica?')) {
        window.location.href = `<?= Router::url('consultas/nueva') ?>?cita_id=${citaId}`;
    }
}

function confirmarCita(citaId) {
    if (confirm('¿Confirmar esta cita?')) {
        window.location.href = `<?= Router::url('citas/cambiarEstado') ?>?id=${citaId}&estado=confirmada`;
    }
}

function actualizarAgenda() {
    location.reload();
}

function actualizarHoraActual() {
    const ahora = new Date();
    const horaActual = ahora.getHours();
    
    // Resaltar la hora actual
    document.querySelectorAll('.timeline-slot').forEach(slot => {
        const hora = parseInt(slot.getAttribute('data-hora'));
        if (hora === horaActual) {
            slot.style.backgroundColor = '#fff3cd';
            slot.querySelector('.time-marker').style.backgroundColor = '#ffc107';
            slot.querySelector('.time-label').style.backgroundColor = '#ffc107';
            slot.querySelector('.time-label').style.color = '#000';
        }
    });
}

// Scroll automático a la hora actual
document.addEventListener('DOMContentLoaded', function() {
    setTimeout(() => {
        const horaActual = new Date().getHours();
        const slotActual = document.querySelector(`[data-hora="${horaActual}"]`);
        if (slotActual) {
            slotActual.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }
    }, 500);
});
</script>