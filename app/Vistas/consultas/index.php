<div class="container-fluid">
    <!-- Encabezado -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-0">
                        <i class="bi bi-clipboard-pulse me-2"></i>
                        Gestión de Consultas
                    </h1>
                    <p class="text-muted mb-0">
                        Historial y seguimiento de consultas médicas
                    </p>
                </div>
                <div>
                    <?php if (Auth::hasRole('medico')): ?>
                        <a href="<?= Util::url('citas/agenda') ?>" class="btn btn-primary">
                            <i class="bi bi-calendar-week"></i> Mi Agenda
                        </a>
                    <?php endif; ?>
                    <a href="<?= Util::url('citas') ?>" class="btn btn-outline-secondary">
                        <i class="bi bi-calendar-check"></i> Ver Citas
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Mensajes Flash -->
    <?= Flash::display() ?>

    <!-- Filtros y Búsqueda -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="<?= Util::url('consultas') ?>" class="row g-3">
                <div class="col-md-3">
                    <label for="search" class="form-label">Buscar</label>
                    <input type="text" class="form-control" id="search" name="search" 
                           value="<?= htmlspecialchars($search) ?>" 
                           placeholder="Paciente, médico, diagnóstico...">
                </div>
                
                <div class="col-md-2">
                    <label for="fecha_inicio" class="form-label">Fecha desde</label>
                    <input type="date" class="form-control" id="fecha_inicio" name="fecha_inicio" 
                           value="<?= htmlspecialchars($fecha_inicio) ?>">
                </div>
                
                <div class="col-md-2">
                    <label for="fecha_fin" class="form-label">Fecha hasta</label>
                    <input type="date" class="form-control" id="fecha_fin" name="fecha_fin" 
                           value="<?= htmlspecialchars($fecha_fin) ?>">
                </div>
                
                <?php if (!Auth::hasRole('medico')): ?>
                <div class="col-md-3">
                    <label for="medico_id" class="form-label">Médico</label>
                    <select class="form-select" id="medico_id" name="medico_id">
                        <option value="">Todos los médicos</option>
                        <?php foreach ($medicos as $medico): ?>
                            <option value="<?= $medico['id'] ?>" 
                                    <?= $medico_seleccionado == $medico['id'] ? 'selected' : '' ?>>
                                Dr. <?= htmlspecialchars($medico['nombre'] . ' ' . $medico['apellidos']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <?php endif; ?>
                
                <div class="col-md-2">
                    <label for="especialidad_id" class="form-label">Especialidad</label>
                    <select class="form-select" id="especialidad_id" name="especialidad_id">
                        <option value="">Todas</option>
                        <?php foreach ($especialidades as $especialidad): ?>
                            <option value="<?= $especialidad['id'] ?>" 
                                    <?= $especialidad_seleccionada == $especialidad['id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($especialidad['nombre']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="col-12">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-search"></i> Buscar
                    </button>
                    <a href="<?= Util::url('consultas') ?>" class="btn btn-outline-secondary">
                        <i class="bi bi-x-circle"></i> Limpiar
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Estadísticas Rápidas -->
    <?php if (isset($stats)): ?>
    <div class="row mb-4">
        <div class="col-lg-3 col-md-6">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h5 class="card-title mb-1"><?= number_format($stats['total_consultas']) ?></h5>
                            <p class="card-text mb-0">Total Consultas</p>
                        </div>
                        <div class="flex-shrink-0">
                            <i class="bi bi-clipboard-pulse display-6"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-3 col-md-6">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h5 class="card-title mb-1"><?= number_format($stats['consultas_hoy']) ?></h5>
                            <p class="card-text mb-0">Consultas Hoy</p>
                        </div>
                        <div class="flex-shrink-0">
                            <i class="bi bi-calendar-check display-6"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-3 col-md-6">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h5 class="card-title mb-1"><?= number_format($stats['consultas_semana']) ?></h5>
                            <p class="card-text mb-0">Esta Semana</p>
                        </div>
                        <div class="flex-shrink-0">
                            <i class="bi bi-calendar-week display-6"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-3 col-md-6">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h5 class="card-title mb-1"><?= number_format($stats['consultas_mes']) ?></h5>
                            <p class="card-text mb-0">Este Mes</p>
                        </div>
                        <div class="flex-shrink-0">
                            <i class="bi bi-calendar-month display-6"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <!-- Lista de Consultas -->
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">
                    <i class="bi bi-list-ul me-2"></i>
                    Lista de Consultas
                    <?php if (!empty($search) || !empty($fecha_inicio) || !empty($fecha_fin) || !empty($medico_seleccionado) || !empty($especialidad_seleccionada)): ?>
                        <small class="text-muted">(Filtrada)</small>
                    <?php endif; ?>
                </h5>
                <span class="badge bg-secondary"><?= count($consultas) ?> resultados</span>
            </div>
        </div>
        <div class="card-body p-0">
            <?php if (!empty($consultas)): ?>
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>No. Consulta</th>
                                <th>Fecha</th>
                                <th>Paciente</th>
                                <th>Médico</th>
                                <th>Especialidad</th>
                                <th>Diagnóstico Principal</th>
                                <th>Signos Vitales</th>
                                <th class="text-end">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($consultas as $consulta): ?>
                                <tr>
                                    <td>
                                        <span class="fw-bold text-primary">
                                            <?= htmlspecialchars($consulta['numero_consulta']) ?>
                                        </span>
                                    </td>
                                    <td>
                                        <div>
                                            <?= Util::formatDate($consulta['fecha_cita']) ?>
                                        </div>
                                        <small class="text-muted">
                                            <?= Util::formatTime($consulta['hora_cita']) ?>
                                        </small>
                                    </td>
                                    <td>
                                        <div>
                                            <a href="<?= Util::url('pacientes/ver?id=' . $consulta['paciente_id']) ?>" 
                                               class="text-decoration-none fw-bold">
                                                <?= htmlspecialchars($consulta['paciente_nombre']) ?>
                                            </a>
                                        </div>
                                        <small class="text-muted">
                                            <?= htmlspecialchars($consulta['codigo_paciente']) ?>
                                        </small>
                                    </td>
                                    <td>
                                        <div>
                                            Dr. <?= htmlspecialchars($consulta['medico_nombre']) ?>
                                        </div>
                                        <?php if ($consulta['consultorio']): ?>
                                            <small class="text-muted">
                                                Consultorio: <?= htmlspecialchars($consulta['consultorio']) ?>
                                            </small>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <span class="badge bg-info">
                                            <?= htmlspecialchars($consulta['especialidad']) ?>
                                        </span>
                                    </td>
                                    <td>
                                        <?php if ($consulta['diagnostico_principal']): ?>
                                            <div class="text-truncate" style="max-width: 200px;" 
                                                 title="<?= htmlspecialchars($consulta['diagnostico_principal']) ?>">
                                                <?= htmlspecialchars($consulta['diagnostico_principal']) ?>
                                            </div>
                                        <?php else: ?>
                                            <span class="text-muted fst-italic">Sin diagnóstico</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <div class="small">
                                            <?php if ($consulta['temperatura']): ?>
                                                <div>
                                                    <i class="bi bi-thermometer-half text-danger"></i>
                                                    <?= number_format($consulta['temperatura'], 1) ?>°C
                                                </div>
                                            <?php endif; ?>
                                            
                                            <?php if ($consulta['presion_sistolica'] && $consulta['presion_diastolica']): ?>
                                                <div>
                                                    <i class="bi bi-heart text-danger"></i>
                                                    <?= $consulta['presion_sistolica'] ?>/<?= $consulta['presion_diastolica'] ?>
                                                </div>
                                            <?php endif; ?>
                                            
                                            <?php if ($consulta['peso']): ?>
                                                <div>
                                                    <i class="bi bi-speedometer2 text-primary"></i>
                                                    <?= number_format($consulta['peso'], 1) ?> kg
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                    <td class="text-end">
                                        <div class="btn-group btn-group-sm" role="group">
                                            <a href="<?= Util::url('consultas/ver?id=' . $consulta['id']) ?>" 
                                               class="btn btn-outline-primary" title="Ver consulta">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            
                                            <?php if (Auth::hasRole('medico') && $consulta['medico_usuario_id'] == Auth::id()): ?>
                                                <a href="<?= Util::url('consultas/editar?id=' . $consulta['id']) ?>" 
                                                   class="btn btn-outline-warning" title="Editar consulta">
                                                    <i class="bi bi-pencil"></i>
                                                </a>
                                            <?php endif; ?>
                                            
                                            <?php if (Auth::hasRole('administrador')): ?>
                                                <a href="<?= Util::url('consultas/editar?id=' . $consulta['id']) ?>" 
                                                   class="btn btn-outline-warning" title="Editar consulta">
                                                    <i class="bi bi-pencil"></i>
                                                </a>
                                            <?php endif; ?>
                                            
                                            <a href="<?= Util::url('consultas/prescripciones?consulta_id=' . $consulta['id']) ?>" 
                                               class="btn btn-outline-success" title="Ver prescripciones">
                                                <i class="bi bi-prescription2"></i>
                                            </a>
                                            
                                            <div class="btn-group" role="group">
                                                <button type="button" class="btn btn-outline-secondary dropdown-toggle" 
                                                        data-bs-toggle="dropdown" title="Más opciones">
                                                    <i class="bi bi-three-dots"></i>
                                                </button>
                                                <ul class="dropdown-menu dropdown-menu-end">
                                                    <li>
                                                        <a class="dropdown-item" 
                                                           href="<?= Util::url('citas/ver?id=' . $consulta['cita_id']) ?>">
                                                            <i class="bi bi-calendar-check me-2"></i>Ver Cita
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a class="dropdown-item" 
                                                           href="<?= Util::url('pacientes/ver?id=' . $consulta['paciente_id']) ?>">
                                                            <i class="bi bi-person me-2"></i>Ver Expediente
                                                        </a>
                                                    </li>
                                                    <li><hr class="dropdown-divider"></li>
                                                    <li>
                                                        <a class="dropdown-item" 
                                                           href="<?= Util::url('citas/crear?paciente_id=' . $consulta['paciente_id']) ?>">
                                                            <i class="bi bi-calendar-plus me-2"></i>Nueva Cita
                                                        </a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="text-center py-5">
                    <i class="bi bi-clipboard-x display-1 text-muted"></i>
                    <h5 class="mt-3 text-muted">No se encontraron consultas</h5>
                    <?php if (!empty($search) || !empty($fecha_inicio) || !empty($fecha_fin)): ?>
                        <p class="text-muted">
                            Intenta ajustar los filtros de búsqueda o 
                            <a href="<?= Util::url('consultas') ?>">ver todas las consultas</a>
                        </p>
                    <?php else: ?>
                        <p class="text-muted">
                            Las consultas aparecerán aquí cuando se completen las citas médicas.
                        </p>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>
        
        <!-- Paginación -->
        <?php if (isset($pagination) && $pagination['last_page'] > 1): ?>
        <div class="card-footer">
            <nav aria-label="Navegación de consultas">
                <ul class="pagination pagination-sm justify-content-center mb-0">
                    <li class="page-item <?= $pagination['current_page'] <= 1 ? 'disabled' : '' ?>">
                        <a class="page-link" href="<?= Util::url('consultas?page=' . ($pagination['current_page'] - 1) . '&' . http_build_query($_GET)) ?>">
                            <i class="bi bi-chevron-left"></i>
                        </a>
                    </li>
                    
                    <?php for ($i = 1; $i <= $pagination['last_page']; $i++): ?>
                        <?php if ($i == $pagination['current_page']): ?>
                            <li class="page-item active">
                                <span class="page-link"><?= $i ?></span>
                            </li>
                        <?php elseif ($i <= 3 || $i > $pagination['last_page'] - 3 || abs($i - $pagination['current_page']) <= 2): ?>
                            <li class="page-item">
                                <a class="page-link" href="<?= Util::url('consultas?page=' . $i . '&' . http_build_query($_GET)) ?>">
                                    <?= $i ?>
                                </a>
                            </li>
                        <?php elseif ($i == 4 || $i == $pagination['last_page'] - 3): ?>
                            <li class="page-item disabled">
                                <span class="page-link">...</span>
                            </li>
                        <?php endif; ?>
                    <?php endfor; ?>
                    
                    <li class="page-item <?= $pagination['current_page'] >= $pagination['last_page'] ? 'disabled' : '' ?>">
                        <a class="page-link" href="<?= Util::url('consultas?page=' . ($pagination['current_page'] + 1) . '&' . http_build_query($_GET)) ?>">
                            <i class="bi bi-chevron-right"></i>
                        </a>
                    </li>
                </ul>
            </nav>
            
            <div class="text-center mt-2">
                <small class="text-muted">
                    Mostrando <?= $pagination['from'] ?? 1 ?> a <?= $pagination['to'] ?? count($consultas) ?> 
                    de <?= $pagination['total'] ?? count($consultas) ?> consultas
                </small>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>

<!-- JavaScript para funcionalidades adicionales -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Configurar fechas por defecto
    const fechaInicio = document.getElementById('fecha_inicio');
    const fechaFin = document.getElementById('fecha_fin');
    
    // Si no hay fechas seleccionadas, establecer última semana como defecto
    if (!fechaInicio.value && !fechaFin.value) {
        const hoy = new Date();
        const semanaAtras = new Date();
        semanaAtras.setDate(hoy.getDate() - 7);
        
        fechaInicio.value = semanaAtras.toISOString().split('T')[0];
        fechaFin.value = hoy.toISOString().split('T')[0];
    }
    
    // Validar que fecha fin no sea menor que fecha inicio
    fechaInicio.addEventListener('change', function() {
        if (fechaFin.value && this.value > fechaFin.value) {
            fechaFin.value = this.value;
        }
    });
    
    fechaFin.addEventListener('change', function() {
        if (fechaInicio.value && this.value < fechaInicio.value) {
            fechaInicio.value = this.value;
        }
    });
    
    // Auto-submit en cambio de filtros principales
    const autoSubmitElements = ['medico_id', 'especialidad_id'];
    autoSubmitElements.forEach(function(elementId) {
        const element = document.getElementById(elementId);
        if (element) {
            element.addEventListener('change', function() {
                // Pequeño delay para mejor UX
                setTimeout(() => {
                    this.form.submit();
                }, 100);
            });
        }
    });
    
    // Atajos de teclado
    document.addEventListener('keydown', function(e) {
        // Ctrl + F para enfocar búsqueda
        if (e.ctrlKey && e.key === 'f') {
            e.preventDefault();
            document.getElementById('search').focus();
        }
        
        // Escape para limpiar filtros
        if (e.key === 'Escape') {
            const form = document.querySelector('form');
            if (form) {
                const inputs = form.querySelectorAll('input[type="text"], input[type="date"], select');
                inputs.forEach(input => input.value = '');
            }
        }
    });
    
    // Tooltips para signos vitales
    const tooltips = document.querySelectorAll('[title]');
    tooltips.forEach(function(element) {
        new bootstrap.Tooltip(element);
    });
});

// Función para exportar resultados (para implementar después)
function exportarConsultas() {
    // Implementar exportación a Excel/PDF
    console.log('Exportar consultas');
}

// Función para filtros rápidos
function filtroRapido(tipo) {
    const fechaInicio = document.getElementById('fecha_inicio');
    const fechaFin = document.getElementById('fecha_fin');
    const hoy = new Date();
    
    switch(tipo) {
        case 'hoy':
            fechaInicio.value = hoy.toISOString().split('T')[0];
            fechaFin.value = hoy.toISOString().split('T')[0];
            break;
        case 'semana':
            const inicioSemana = new Date();
            inicioSemana.setDate(hoy.getDate() - hoy.getDay());
            fechaInicio.value = inicioSemana.toISOString().split('T')[0];
            fechaFin.value = hoy.toISOString().split('T')[0];
            break;
        case 'mes':
            const inicioMes = new Date(hoy.getFullYear(), hoy.getMonth(), 1);
            fechaInicio.value = inicioMes.toISOString().split('T')[0];
            fechaFin.value = hoy.toISOString().split('T')[0];
            break;
    }
    
    // Auto-submit del formulario
    document.querySelector('form').submit();
}
</script>

<style>
/* Estilos adicionales para mejorar la presentación */
.table th {
    font-weight: 600;
    font-size: 0.875rem;
    border-bottom: 2px solid #dee2e6;
}

.table td {
    vertical-align: middle;
    font-size: 0.875rem;
}

.text-truncate {
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.btn-group-sm > .btn {
    padding: 0.25rem 0.5rem;
    font-size: 0.75rem;
}

.badge {
    font-size: 0.75rem;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .table-responsive {
        font-size: 0.8rem;
    }
    
    .btn-group-sm > .btn {
        padding: 0.2rem 0.4rem;
        font-size: 0.7rem;
    }
}

/* Loading state */
.table tbody tr:hover {
    background-color: rgba(0, 123, 255, 0.05);
}

/* Print styles */
@media print {
    .btn, .card-header, .pagination {
        display: none !important;
    }
    
    .card {
        border: none !important;
        box-shadow: none !important;
    }
}
</style>