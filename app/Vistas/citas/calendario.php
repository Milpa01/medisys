<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="bi bi-calendar-week text-primary me-2"></i>Calendario de Citas</h2>
    <div class="btn-group">
        <?php if (Auth::hasRole('administrador') || Auth::hasRole('secretario')): ?>
            <a href="<?= Router::url('citas/crear') ?>" class="btn btn-primary">
                <i class="bi bi-calendar-plus me-2"></i>Nueva Cita
            </a>
        <?php endif; ?>
        <a href="<?= Router::url('citas') ?>" class="btn btn-outline-secondary">
            <i class="bi bi-list me-2"></i>Vista Lista
        </a>
    </div>
</div>

<!-- Controles de navegación del calendario -->
<div class="card mb-4">
    <div class="card-body">
        <div class="row align-items-center">
            <div class="col-md-6">
                <div class="d-flex align-items-center gap-3">
                    <a href="<?= Router::url('citas/calendario?mes=' . (($mes ?? date('m')) == 1 ? 12 : ($mes ?? date('m')) - 1) . '&ano=' . (($mes ?? date('m')) == 1 ? ($ano ?? date('Y')) - 1 : ($ano ?? date('Y')))) ?>" 
                       class="btn btn-outline-primary">
                        <i class="bi bi-chevron-left"></i>
                    </a>
                    
                    <h4 class="mb-0 text-primary">
                        <?php
                        $meses = [
                            1 => 'Enero', 2 => 'Febrero', 3 => 'Marzo', 4 => 'Abril',
                            5 => 'Mayo', 6 => 'Junio', 7 => 'Julio', 8 => 'Agosto',
                            9 => 'Septiembre', 10 => 'Octubre', 11 => 'Noviembre', 12 => 'Diciembre'
                        ];
                        $mesActual = (int)($mes ?? date('m'));
                        $anoActual = $ano ?? date('Y');
                        echo $meses[$mesActual] . ' ' . $anoActual;
                        ?>
                    </h4>
                    
                    <a href="<?= Router::url('citas/calendario?mes=' . (($mes ?? date('m')) == 12 ? 1 : ($mes ?? date('m')) + 1) . '&ano=' . (($mes ?? date('m')) == 12 ? ($ano ?? date('Y')) + 1 : ($ano ?? date('Y')))) ?>" 
                       class="btn btn-outline-primary">
                        <i class="bi bi-chevron-right"></i>
                    </a>
                    
                    <a href="<?= Router::url('citas/calendario') ?>" class="btn btn-outline-info btn-sm">
                        <i class="bi bi-house me-1"></i>Hoy
                    </a>
                </div>
            </div>
            
            <div class="col-md-6 text-end">
                <!-- Filtros rápidos -->
                <div class="btn-group btn-group-sm">
                    <button type="button" class="btn btn-outline-secondary" id="filtro-todos">Todos</button>
                    <button type="button" class="btn btn-outline-primary" id="filtro-programada">Programadas</button>
                    <button type="button" class="btn btn-outline-success" id="filtro-confirmada">Confirmadas</button>
                    <button type="button" class="btn btn-outline-warning" id="filtro-en-curso">En Curso</button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Leyenda de colores -->
<div class="card mb-4">
    <div class="card-body py-2">
        <div class="row align-items-center">
            <div class="col-md-8">
                <small class="text-muted"><strong>Leyenda:</strong></small>
                <span class="badge bg-secondary ms-2">Programada</span>
                <span class="badge bg-primary ms-1">Confirmada</span>
                <span class="badge bg-warning ms-1">En Curso</span>
                <span class="badge bg-success ms-1">Completada</span>
                <span class="badge bg-danger ms-1">Cancelada</span>
            </div>
            <div class="col-md-4 text-end">
                <small class="text-muted">
                    <i class="bi bi-info-circle me-1"></i>
                    Haz clic en una fecha para ver detalles
                </small>
            </div>
        </div>
    </div>
</div>

<!-- Calendario -->
<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-bordered mb-0" id="calendario">
                <thead class="bg-light">
                    <tr>
                        <th class="text-center py-3" style="width: 14.28%;">Domingo</th>
                        <th class="text-center py-3" style="width: 14.28%;">Lunes</th>
                        <th class="text-center py-3" style="width: 14.28%;">Martes</th>
                        <th class="text-center py-3" style="width: 14.28%;">Miércoles</th>
                        <th class="text-center py-3" style="width: 14.28%;">Jueves</th>
                        <th class="text-center py-3" style="width: 14.28%;">Viernes</th>
                        <th class="text-center py-3" style="width: 14.28%;">Sábado</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $mesActual = (int)($mes ?? date('m'));
                    $anoActual = (int)($ano ?? date('Y'));
                    $diaHoy = (int)date('j');
                    $mesHoy = (int)date('m');
                    $anoHoy = (int)date('Y');
                    
                    // Primer día del mes y último día
                    $primerDia = mktime(0, 0, 0, $mesActual, 1, $anoActual);
                    $ultimoDia = date('t', $primerDia);
                    $diaSemanaInicio = date('w', $primerDia); // 0 = domingo
                    
                    $diaActual = 1 - $diaSemanaInicio;
                    $semanas = ceil(($ultimoDia + $diaSemanaInicio) / 7);
                    
                    for ($semana = 0; $semana < $semanas; $semana++): ?>
                        <tr>
                            <?php for ($diaSemana = 0; $diaSemana < 7; $diaSemana++): 
                                $diaActual++;
                                $esDiaValido = $diaActual >= 1 && $diaActual <= $ultimoDia;
                                $esHoy = $esDiaValido && $diaActual == $diaHoy && $mesActual == $mesHoy && $anoActual == $anoHoy;
                                
                                // Obtener citas del día
                                $citasDelDia = [];
                                if ($esDiaValido && isset($citas_por_dia[$diaActual])) {
                                    $citasDelDia = $citas_por_dia[$diaActual];
                                }
                                ?>
                                <td class="calendario-dia <?= $esHoy ? 'hoy' : '' ?> <?= !$esDiaValido ? 'dia-inactivo' : '' ?>" 
                                    style="height: 120px; vertical-align: top; position: relative;"
                                    data-dia="<?= $diaActual ?>"
                                    data-fecha="<?= $anoActual . '-' . str_pad($mesActual, 2, '0', STR_PAD_LEFT) . '-' . str_pad($diaActual, 2, '0', STR_PAD_LEFT) ?>">
                                    
                                    <?php if ($esDiaValido): ?>
                                        <div class="d-flex justify-content-between align-items-start p-2">
                                            <span class="dia-numero <?= $esHoy ? 'text-primary fw-bold' : '' ?>">
                                                <?= $diaActual ?>
                                            </span>
                                            
                                            <?php if (count($citasDelDia) > 0): ?>
                                                <span class="badge bg-info">
                                                    <?= count($citasDelDia) ?>
                                                </span>
                                            <?php endif; ?>
                                        </div>
                                        
                                        <!-- Citas del día -->
                                        <div class="citas-container px-2">
                                            <?php foreach (array_slice($citasDelDia, 0, 3) as $cita): ?>
                                                <div class="cita-item mb-1" 
                                                     data-estado="<?= $cita['estado'] ?>"
                                                     data-bs-toggle="tooltip" 
                                                     title="<?= htmlspecialchars($cita['paciente_nombre']) ?> - Dr. <?= htmlspecialchars($cita['medico_nombre']) ?>"
                                                     style="cursor: pointer;"
                                                     onclick="verDetallesCita(<?= $cita['id'] ?>)">
                                                    <div class="cita-badge <?= getClaseEstado($cita['estado']) ?>" 
                                                         style="font-size: 10px; padding: 1px 4px; border-radius: 3px;">
                                                        <i class="bi bi-clock me-1"></i>
                                                        <?= date('H:i', strtotime($cita['hora_cita'])) ?>
                                                        <small class="d-block" style="font-size: 9px;">
                                                            <?= Util::truncate($cita['paciente_nombre'], 15) ?>
                                                        </small>
                                                    </div>
                                                </div>
                                            <?php endforeach; ?>
                                            
                                            <?php if (count($citasDelDia) > 3): ?>
                                                <div class="text-center">
                                                    <small class="text-muted">
                                                        +<?= count($citasDelDia) - 3 ?> más
                                                    </small>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                        
                                        <!-- Botón para agregar cita -->
                                        <?php if (Auth::hasRole('administrador') || Auth::hasRole('secretario')): ?>
                                            <div class="agregar-cita position-absolute bottom-0 end-0 p-1">
                                                <button class="btn btn-sm btn-outline-primary p-1" 
                                                        style="font-size: 10px; line-height: 1;"
                                                        onclick="nuevaCitaFecha('<?= $anoActual . '-' . str_pad($mesActual, 2, '0', STR_PAD_LEFT) . '-' . str_pad($diaActual, 2, '0', STR_PAD_LEFT) ?>')"
                                                        title="Nueva cita">
                                                    <i class="bi bi-plus"></i>
                                                </button>
                                            </div>
                                        <?php endif; ?>
                                        
                                    <?php else: ?>
                                        <div class="p-2">
                                            <span class="text-muted">
                                                <?= $diaActual <= 0 ? date('j', mktime(0, 0, 0, $mesActual, $diaActual, $anoActual)) : date('j', mktime(0, 0, 0, $mesActual + 1, $diaActual - $ultimoDia, $anoActual)) ?>
                                            </span>
                                        </div>
                                    <?php endif; ?>
                                </td>
                            <?php endfor; ?>
                        </tr>
                    <?php endfor; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal para detalles del día -->
<div class="modal fade" id="modalDetallesDia" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="bi bi-calendar-day me-2"></i>
                    Citas del <span id="fecha-modal"></span>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="contenido-modal">
                <!-- Se carga dinámicamente -->
            </div>
            <div class="modal-footer">
                <?php if (Auth::hasRole('administrador') || Auth::hasRole('secretario')): ?>
                    <button type="button" class="btn btn-primary" id="btn-nueva-cita-modal">
                        <i class="bi bi-calendar-plus me-2"></i>Nueva Cita
                    </button>
                <?php endif; ?>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<?php
// Función helper para obtener la clase CSS según el estado
function getClaseEstado($estado) {
    return match($estado) {
        'programada' => 'bg-secondary text-white',
        'confirmada' => 'bg-primary text-white',
        'en_curso' => 'bg-warning text-dark',
        'completada' => 'bg-success text-white',
        'cancelada' => 'bg-danger text-white',
        'no_asistio' => 'bg-dark text-white',
        default => 'bg-secondary text-white'
    };
}
?>

<style>
.calendario-dia {
    cursor: pointer;
    transition: background-color 0.2s ease;
}

.calendario-dia:hover {
    background-color: #f8f9fa;
}

.calendario-dia.hoy {
    background-color: #e3f2fd;
    border: 2px solid #2196f3;
}

.dia-inactivo {
    background-color: #f5f5f5;
    color: #ccc;
}

.cita-item {
    cursor: pointer;
    transition: transform 0.1s ease;
}

.cita-item:hover {
    transform: scale(1.02);
}

.cita-badge {
    display: block;
    text-align: center;
    border-radius: 4px;
    font-weight: 500;
    line-height: 1.2;
}

.agregar-cita {
    opacity: 0;
    transition: opacity 0.2s ease;
}

.calendario-dia:hover .agregar-cita {
    opacity: 1;
}

.dia-numero {
    font-size: 14px;
    font-weight: 600;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Inicializar tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
    
    // Filtros de estado
    document.getElementById('filtro-todos').addEventListener('click', function() {
        filtrarCitas('todos');
        actualizarBotonFiltro(this);
    });
    
    document.getElementById('filtro-programada').addEventListener('click', function() {
        filtrarCitas('programada');
        actualizarBotonFiltro(this);
    });
    
    document.getElementById('filtro-confirmada').addEventListener('click', function() {
        filtrarCitas('confirmada');
        actualizarBotonFiltro(this);
    });
    
    document.getElementById('filtro-en-curso').addEventListener('click', function() {
        filtrarCitas('en_curso');
        actualizarBotonFiltro(this);
    });
    
    // Hacer clic en día para ver detalles
    document.querySelectorAll('.calendario-dia').forEach(dia => {
        dia.addEventListener('click', function(e) {
            if (e.target.closest('.agregar-cita')) return;
            if (e.target.closest('.cita-item')) return;
            
            const fecha = this.getAttribute('data-fecha');
            const diaNumero = this.getAttribute('data-dia');
            
            if (diaNumero >= 1) {
                mostrarDetallesDia(fecha);
            }
        });
    });
});

function filtrarCitas(estado) {
    const citasItems = document.querySelectorAll('.cita-item');
    
    citasItems.forEach(item => {
        if (estado === 'todos' || item.getAttribute('data-estado') === estado) {
            item.style.display = 'block';
        } else {
            item.style.display = 'none';
        }
    });
}

function actualizarBotonFiltro(botonActivo) {
    document.querySelectorAll('.btn-group .btn').forEach(btn => {
        btn.classList.remove('active');
    });
    botonActivo.classList.add('active');
}

function nuevaCitaFecha(fecha) {
    window.location.href = `<?= Router::url('citas/crear') ?>?fecha=${fecha}`;
}

function verDetallesCita(citaId) {
    window.location.href = `<?= Router::url('citas/ver') ?>?id=${citaId}`;
}

function mostrarDetallesDia(fecha) {
    const modal = new bootstrap.Modal(document.getElementById('modalDetallesDia'));
    const fechaModal = document.getElementById('fecha-modal');
    const contenidoModal = document.getElementById('contenido-modal');
    const btnNuevaCita = document.getElementById('btn-nueva-cita-modal');
    
    // Formatear fecha para mostrar
    const fechaObj = new Date(fecha + 'T00:00:00');
    const opciones = { 
        weekday: 'long', 
        year: 'numeric', 
        month: 'long', 
        day: 'numeric' 
    };
    fechaModal.textContent = fechaObj.toLocaleDateString('es-ES', opciones);
    
    // Cargar citas del día
    contenidoModal.innerHTML = '<div class="text-center"><div class="spinner-border" role="status"></div></div>';
    
    // Simular carga de datos (en implementación real sería AJAX)
    setTimeout(() => {
        cargarCitasDia(fecha);
    }, 500);
    
    // Configurar botón nueva cita
    if (btnNuevaCita) {
        btnNuevaCita.onclick = () => nuevaCitaFecha(fecha);
    }
    
    modal.show();
}

function cargarCitasDia(fecha) {
    const contenido = document.getElementById('contenido-modal');
    
    // Buscar citas del día actual en el calendario
    const diaElemento = document.querySelector(`[data-fecha="${fecha}"]`);
    const citasDelDia = diaElemento ? diaElemento.querySelectorAll('.cita-item') : [];
    
    if (citasDelDia.length === 0) {
        contenido.innerHTML = `
            <div class="text-center py-4">
                <i class="bi bi-calendar-x display-4 text-muted"></i>
                <p class="mt-3 text-muted">No hay citas programadas para este día</p>
                ${(<?= json_encode(Auth::hasRole('administrador') || Auth::hasRole('secretario')) ?>) ? 
                    `<button class="btn btn-primary" onclick="nuevaCitaFecha('${fecha}')">
                        <i class="bi bi-calendar-plus me-2"></i>Programar Primera Cita
                    </button>` : ''
                }
            </div>
        `;
        return;
    }
    
    // Crear tabla de citas
    let html = `
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Hora</th>
                        <th>Paciente</th>
                        <th>Médico</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
    `;
    
    // Agregar filas de ejemplo (en implementación real vendría del servidor)
    for (let i = 0; i < citasDelDia.length; i++) {
        html += `
            <tr>
                <td><strong>08:${i}0 AM</strong></td>
                <td>Paciente ${i + 1}</td>
                <td>Dr. Médico ${i + 1}</td>
                <td><span class="badge bg-primary">Confirmada</span></td>
                <td>
                    <div class="btn-group btn-group-sm">
                        <button class="btn btn-outline-info" onclick="verDetallesCita(${i + 1})">
                            <i class="bi bi-eye"></i>
                        </button>
                        <button class="btn btn-outline-warning">
                            <i class="bi bi-pencil"></i>
                        </button>
                    </div>
                </td>
            </tr>
        `;
    }
    
    html += `
                </tbody>
            </table>
        </div>
    `;
    
    contenido.innerHTML = html;
}
</script>