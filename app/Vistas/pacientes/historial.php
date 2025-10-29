<?php

if (!defined('APP_PATH')) exit('No direct script access allowed');
?>

<!-- Encabezado del Paciente -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card shadow-sm">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <div class="d-flex align-items-center">
                            <?php if ($paciente['imagen']): ?>
                                <img src="<?= Util::asset('uploads/pacientes/' . $paciente['imagen']) ?>" 
                                     class="rounded-circle me-3" 
                                     alt="<?= htmlspecialchars($paciente['nombre_completo']) ?>"
                                     style="width: 80px; height: 80px; object-fit: cover;">
                            <?php else: ?>
                                <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center me-3" 
                                     style="width: 80px; height: 80px; font-size: 2rem;">
                                    <?= strtoupper(substr($paciente['nombre'], 0, 1) . substr($paciente['apellidos'], 0, 1)) ?>
                                </div>
                            <?php endif; ?>
                            
                            <div>
                                <h4 class="mb-1">
                                    <?= htmlspecialchars($paciente['nombre_completo']) ?>
                                </h4>
                                <div class="text-muted">
                                    <span class="badge bg-info me-2">
                                        <i class="bi bi-clipboard-pulse me-1"></i>
                                        <?= htmlspecialchars($paciente['codigo_paciente']) ?>
                                    </span>
                                    <span class="me-3">
                                        <i class="bi bi-person me-1"></i>
                                        <?= $paciente['edad'] ?> años
                                    </span>
                                    <span class="me-3">
                                        <i class="bi bi-gender-<?= $paciente['genero'] === 'masculino' ? 'male' : 'female' ?> me-1"></i>
                                        <?= ucfirst($paciente['genero']) ?>
                                    </span>
                                    <?php if ($paciente['telefono']): ?>
                                        <span>
                                            <i class="bi bi-telephone me-1"></i>
                                            <?= htmlspecialchars($paciente['telefono']) ?>
                                        </span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 text-end">
                        <a href="<?= Util::url('pacientes/ver?id=' . $paciente['id']) ?>" 
                           class="btn btn-outline-primary btn-sm me-2">
                            <i class="bi bi-eye me-1"></i>
                            Ver Expediente
                        </a>
                        <a href="<?= Util::url('pacientes/editar?id=' . $paciente['id']) ?>" 
                           class="btn btn-outline-secondary btn-sm">
                            <i class="bi bi-pencil me-1"></i>
                            Editar
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Estadísticas Rápidas -->
<div class="row mb-4">
    <div class="col-md-3 col-sm-6 mb-3">
        <div class="card text-center shadow-sm">
            <div class="card-body">
                <div class="text-primary" style="font-size: 2rem;">
                    <i class="bi bi-file-medical"></i>
                </div>
                <h3 class="mt-2 mb-0"><?= $estadisticas['total_consultas'] ?? 0 ?></h3>
                <p class="text-muted mb-0 small">Consultas Totales</p>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6 mb-3">
        <div class="card text-center shadow-sm">
            <div class="card-body">
                <div class="text-success" style="font-size: 2rem;">
                    <i class="bi bi-capsule"></i>
                </div>
                <h3 class="mt-2 mb-0"><?= $estadisticas['total_prescripciones'] ?? 0 ?></h3>
                <p class="text-muted mb-0 small">Medicamentos Prescritos</p>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6 mb-3">
        <div class="card text-center shadow-sm">
            <div class="card-body">
                <div class="text-info" style="font-size: 2rem;">
                    <i class="bi bi-calendar-check"></i>
                </div>
                <h3 class="mt-2 mb-0"><?= $estadisticas['ultima_visita'] ?? 'N/A' ?></h3>
                <p class="text-muted mb-0 small">Última Visita</p>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6 mb-3">
        <div class="card text-center shadow-sm">
            <div class="card-body">
                <div class="text-warning" style="font-size: 2rem;">
                    <i class="bi bi-calendar-event"></i>
                </div>
                <h3 class="mt-2 mb-0"><?= $estadisticas['citas_proximas'] ?? 0 ?></h3>
                <p class="text-muted mb-0 small">Citas Próximas</p>
            </div>
        </div>
    </div>
</div>

<!-- Filtros y Búsqueda -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="<?= Util::url('pacientes/historial') ?>" id="formFiltros">
            <input type="hidden" name="id" value="<?= $paciente['id'] ?>">
            <div class="row g-3">
                <div class="col-md-3">
                    <label for="fecha_desde" class="form-label">Desde</label>
                    <input type="date" 
                           class="form-control" 
                           id="fecha_desde" 
                           name="fecha_desde"
                           value="<?= $_GET['fecha_desde'] ?? '' ?>">
                </div>
                <div class="col-md-3">
                    <label for="fecha_hasta" class="form-label">Hasta</label>
                    <input type="date" 
                           class="form-control" 
                           id="fecha_hasta" 
                           name="fecha_hasta"
                           value="<?= $_GET['fecha_hasta'] ?? '' ?>">
                </div>
                <div class="col-md-3">
                    <label for="medico" class="form-label">Médico</label>
                    <select class="form-select" id="medico" name="medico_id">
                        <option value="">Todos los médicos</option>
                        <?php if (!empty($medicos)): ?>
                            <?php foreach ($medicos as $medico): ?>
                                <option value="<?= $medico['id'] ?>" 
                                        <?= (isset($_GET['medico_id']) && $_GET['medico_id'] == $medico['id']) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($medico['nombre_completo']) ?>
                                </option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="buscar" class="form-label">Buscar</label>
                    <input type="text" 
                           class="form-control" 
                           id="buscar" 
                           name="buscar"
                           placeholder="Diagnóstico, síntomas..."
                           value="<?= $_GET['buscar'] ?? '' ?>">
                </div>
                <div class="col-12">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-search me-1"></i>
                        Filtrar
                    </button>
                    <a href="<?= Util::url('pacientes/historial?id=' . $paciente['id']) ?>" 
                       class="btn btn-secondary">
                        <i class="bi bi-arrow-clockwise me-1"></i>
                        Limpiar
                    </a>
                    <button type="button" class="btn btn-success" onclick="exportarPDF()">
                        <i class="bi bi-file-pdf me-1"></i>
                        Exportar PDF
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Historial Médico -->
<div class="card">
    <div class="card-header">
        <h5 class="card-title mb-0">
            <i class="bi bi-clock-history me-2"></i>
            Historial Médico Completo
        </h5>
    </div>
    <div class="card-body p-0">
        <?php if (!empty($historial)): ?>
            <div class="timeline">
                <?php foreach ($historial as $consulta): ?>
                    <div class="timeline-item">
                        <div class="timeline-marker bg-primary">
                            <i class="bi bi-file-medical-fill text-white"></i>
                        </div>
                        <div class="timeline-content">
                            <div class="card mb-3 shadow-sm">
                                <div class="card-header bg-light">
                                    <div class="row align-items-center">
                                        <div class="col-md-8">
                                            <h6 class="mb-1">
                                                <i class="bi bi-calendar3 me-2"></i>
                                                <?= Util::formatDate($consulta['fecha_cita']) ?>
                                                <span class="text-muted">a las</span>
                                                <?= Util::formatTime($consulta['hora_cita']) ?>
                                            </h6>
                                            <div class="small text-muted">
                                                <i class="bi bi-person-badge me-1"></i>
                                                Dr(a). <?= htmlspecialchars($consulta['medico_nombre']) ?>
                                                <?php if ($consulta['especialidad']): ?>
                                                    <span class="badge bg-secondary ms-2">
                                                        <?= htmlspecialchars($consulta['especialidad']) ?>
                                                    </span>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                        <div class="col-md-4 text-end">
                                            <span class="badge bg-info">
                                                <?= htmlspecialchars($consulta['numero_consulta'] ?? 'N/A') ?>
                                            </span>
                                            <span class="badge bg-<?= 
                                                $consulta['tipo_cita'] === 'primera_vez' ? 'primary' : 
                                                ($consulta['tipo_cita'] === 'control' ? 'success' : 
                                                ($consulta['tipo_cita'] === 'emergencia' ? 'danger' : 'warning')) 
                                            ?>">
                                                <?= ucfirst(str_replace('_', ' ', $consulta['tipo_cita'])) ?>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <!-- Motivo de Consulta -->
                                    <div class="mb-3">
                                        <h6 class="text-primary mb-2">
                                            <i class="bi bi-chat-square-text me-2"></i>
                                            Motivo de Consulta
                                        </h6>
                                        <p class="mb-0">
                                            <?= htmlspecialchars($consulta['motivo_consulta']) ?>
                                        </p>
                                    </div>

                                    <!-- Signos Vitales -->
                                    <?php if ($consulta['peso'] || $consulta['temperatura'] || $consulta['presion_sistolica']): ?>
                                        <div class="mb-3">
                                            <h6 class="text-primary mb-2">
                                                <i class="bi bi-heart-pulse me-2"></i>
                                                Signos Vitales
                                            </h6>
                                            <div class="row g-2">
                                                <?php if ($consulta['peso']): ?>
                                                    <div class="col-auto">
                                                        <span class="badge bg-light text-dark">
                                                            <i class="bi bi-speedometer2 me-1"></i>
                                                            Peso: <?= number_format($consulta['peso'], 1) ?> kg
                                                        </span>
                                                    </div>
                                                <?php endif; ?>
                                                <?php if ($consulta['altura']): ?>
                                                    <div class="col-auto">
                                                        <span class="badge bg-light text-dark">
                                                            <i class="bi bi-arrows-expand me-1"></i>
                                                            Altura: <?= number_format($consulta['altura'], 2) ?> m
                                                        </span>
                                                    </div>
                                                <?php endif; ?>
                                                <?php if ($consulta['temperatura']): ?>
                                                    <div class="col-auto">
                                                        <span class="badge bg-light text-dark">
                                                            <i class="bi bi-thermometer-half me-1"></i>
                                                            Temp: <?= number_format($consulta['temperatura'], 1) ?>°C
                                                        </span>
                                                    </div>
                                                <?php endif; ?>
                                                <?php if ($consulta['presion_sistolica'] && $consulta['presion_diastolica']): ?>
                                                    <div class="col-auto">
                                                        <span class="badge bg-light text-dark">
                                                            <i class="bi bi-activity me-1"></i>
                                                            PA: <?= $consulta['presion_sistolica'] ?>/<?= $consulta['presion_diastolica'] ?> mmHg
                                                        </span>
                                                    </div>
                                                <?php endif; ?>
                                                <?php if ($consulta['frecuencia_cardiaca']): ?>
                                                    <div class="col-auto">
                                                        <span class="badge bg-light text-dark">
                                                            <i class="bi bi-heart me-1"></i>
                                                            FC: <?= $consulta['frecuencia_cardiaca'] ?> lpm
                                                        </span>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    <?php endif; ?>

                                    <!-- Síntomas -->
                                    <?php if ($consulta['sintomas']): ?>
                                        <div class="mb-3">
                                            <h6 class="text-primary mb-2">
                                                <i class="bi bi-clipboard2-pulse me-2"></i>
                                                Síntomas
                                            </h6>
                                            <p class="mb-0">
                                                <?= nl2br(htmlspecialchars($consulta['sintomas'])) ?>
                                            </p>
                                        </div>
                                    <?php endif; ?>

                                    <!-- Exploración Física -->
                                    <?php if ($consulta['exploracion_fisica']): ?>
                                        <div class="mb-3">
                                            <h6 class="text-primary mb-2">
                                                <i class="bi bi-stethoscope me-2"></i>
                                                Exploración Física
                                            </h6>
                                            <p class="mb-0">
                                                <?= nl2br(htmlspecialchars($consulta['exploracion_fisica'])) ?>
                                            </p>
                                        </div>
                                    <?php endif; ?>

                                    <!-- Diagnóstico -->
                                    <div class="mb-3">
                                        <h6 class="text-primary mb-2">
                                            <i class="bi bi-clipboard-check me-2"></i>
                                            Diagnóstico
                                        </h6>
                                        <div class="alert alert-info mb-2" role="alert">
                                            <strong>Principal:</strong> 
                                            <?= htmlspecialchars($consulta['diagnostico_principal']) ?>
                                        </div>
                                        <?php if ($consulta['diagnosticos_secundarios']): ?>
                                            <div class="alert alert-secondary mb-0" role="alert">
                                                <strong>Secundarios:</strong>
                                                <?= nl2br(htmlspecialchars($consulta['diagnosticos_secundarios'])) ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>

                                    <!-- Plan de Tratamiento -->
                                    <?php if ($consulta['plan_tratamiento']): ?>
                                        <div class="mb-3">
                                            <h6 class="text-primary mb-2">
                                                <i class="bi bi-prescription2 me-2"></i>
                                                Plan de Tratamiento
                                            </h6>
                                            <p class="mb-0">
                                                <?= nl2br(htmlspecialchars($consulta['plan_tratamiento'])) ?>
                                            </p>
                                        </div>
                                    <?php endif; ?>

                                    <!-- Prescripciones -->
                                    <?php if (!empty($consulta['prescripciones'])): ?>
                                        <div class="mb-3">
                                            <h6 class="text-primary mb-2">
                                                <i class="bi bi-capsule me-2"></i>
                                                Medicamentos Prescritos
                                            </h6>
                                            <div class="table-responsive">
                                                <table class="table table-sm table-bordered">
                                                    <thead class="table-light">
                                                        <tr>
                                                            <th>Medicamento</th>
                                                            <th>Dosis</th>
                                                            <th>Frecuencia</th>
                                                            <th>Duración</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php foreach ($consulta['prescripciones'] as $prescripcion): ?>
                                                            <tr>
                                                                <td>
                                                                    <strong><?= htmlspecialchars($prescripcion['medicamento']) ?></strong>
                                                                    <?php if ($prescripcion['nombre_generico']): ?>
                                                                        <br><small class="text-muted">
                                                                            <?= htmlspecialchars($prescripcion['nombre_generico']) ?>
                                                                        </small>
                                                                    <?php endif; ?>
                                                                </td>
                                                                <td><?= htmlspecialchars($prescripcion['dosis']) ?></td>
                                                                <td><?= htmlspecialchars($prescripcion['frecuencia']) ?></td>
                                                                <td><?= htmlspecialchars($prescripcion['duracion']) ?></td>
                                                            </tr>
                                                        <?php endforeach; ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    <?php endif; ?>

                                    <!-- Indicaciones -->
                                    <?php if ($consulta['indicaciones']): ?>
                                        <div class="mb-3">
                                            <h6 class="text-primary mb-2">
                                                <i class="bi bi-info-circle me-2"></i>
                                                Indicaciones
                                            </h6>
                                            <p class="mb-0">
                                                <?= nl2br(htmlspecialchars($consulta['indicaciones'])) ?>
                                            </p>
                                        </div>
                                    <?php endif; ?>

                                    <!-- Observaciones -->
                                    <?php if ($consulta['observaciones']): ?>
                                        <div class="mb-3">
                                            <h6 class="text-primary mb-2">
                                                <i class="bi bi-journal-text me-2"></i>
                                                Observaciones
                                            </h6>
                                            <p class="mb-0 text-muted">
                                                <?= nl2br(htmlspecialchars($consulta['observaciones'])) ?>
                                            </p>
                                        </div>
                                    <?php endif; ?>

                                    <!-- Acciones -->
                                    <div class="text-end mt-3 pt-3 border-top">
                                        <a href="<?= Util::url('consultas/ver?id=' . $consulta['consulta_id']) ?>" 
                                           class="btn btn-sm btn-outline-primary">
                                            <i class="bi bi-eye me-1"></i>
                                            Ver Detalle
                                        </a>
                                        <?php if (Auth::hasRole('medico') || Auth::hasRole('administrador')): ?>
                                            <a href="<?= Util::url('consultas/editar?id=' . $consulta['consulta_id']) ?>" 
                                               class="btn btn-sm btn-outline-secondary">
                                                <i class="bi bi-pencil me-1"></i>
                                                Editar
                                            </a>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <!-- Paginación -->
            <?php if (isset($pagination) && $pagination['total_pages'] > 1): ?>
                <div class="card-footer">
                    <nav aria-label="Navegación de historial">
                        <ul class="pagination justify-content-center mb-0">
                            <?php if ($pagination['current_page'] > 1): ?>
                                <li class="page-item">
                                    <a class="page-link" 
                                       href="<?= Util::url('pacientes/historial?id=' . $paciente['id'] . '&page=' . ($pagination['current_page'] - 1) . (isset($_GET['fecha_desde']) ? '&fecha_desde=' . $_GET['fecha_desde'] : '') . (isset($_GET['fecha_hasta']) ? '&fecha_hasta=' . $_GET['fecha_hasta'] : '') . (isset($_GET['medico_id']) ? '&medico_id=' . $_GET['medico_id'] : '') . (isset($_GET['buscar']) ? '&buscar=' . $_GET['buscar'] : '')) ?>">
                                        Anterior
                                    </a>
                                </li>
                            <?php endif; ?>

                            <?php for ($i = 1; $i <= $pagination['total_pages']; $i++): ?>
                                <li class="page-item <?= $i === $pagination['current_page'] ? 'active' : '' ?>">
                                    <a class="page-link" 
                                       href="<?= Util::url('pacientes/historial?id=' . $paciente['id'] . '&page=' . $i . (isset($_GET['fecha_desde']) ? '&fecha_desde=' . $_GET['fecha_desde'] : '') . (isset($_GET['fecha_hasta']) ? '&fecha_hasta=' . $_GET['fecha_hasta'] : '') . (isset($_GET['medico_id']) ? '&medico_id=' . $_GET['medico_id'] : '') . (isset($_GET['buscar']) ? '&buscar=' . $_GET['buscar'] : '')) ?>">
                                        <?= $i ?>
                                    </a>
                                </li>
                            <?php endfor; ?>

                            <?php if ($pagination['current_page'] < $pagination['total_pages']): ?>
                                <li class="page-item">
                                    <a class="page-link" 
                                       href="<?= Util::url('pacientes/historial?id=' . $paciente['id'] . '&page=' . ($pagination['current_page'] + 1) . (isset($_GET['fecha_desde']) ? '&fecha_desde=' . $_GET['fecha_desde'] : '') . (isset($_GET['fecha_hasta']) ? '&fecha_hasta=' . $_GET['fecha_hasta'] : '') . (isset($_GET['medico_id']) ? '&medico_id=' . $_GET['medico_id'] : '') . (isset($_GET['buscar']) ? '&buscar=' . $_GET['buscar'] : '')) ?>">
                                        Siguiente
                                    </a>
                                </li>
                            <?php endif; ?>
                        </ul>
                    </nav>
                </div>
            <?php endif; ?>

        <?php else: ?>
            <div class="card-body text-center py-5">
                <i class="bi bi-clipboard-x text-muted" style="font-size: 4rem;"></i>
                <h5 class="mt-3 text-muted">No hay consultas registradas</h5>
                <p class="text-muted">
                    Este paciente aún no tiene historial médico.
                </p>
                <?php if (Auth::hasRole('medico') || Auth::hasRole('secretario')): ?>
                    <a href="<?= Util::url('citas/crear?paciente_id=' . $paciente['id']) ?>" 
                       class="btn btn-primary mt-3">
                        <i class="bi bi-calendar-plus me-1"></i>
                        Agendar Primera Cita
                    </a>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Estilos CSS personalizados -->
<style>
.timeline {
    position: relative;
    padding: 20px 0;
}

.timeline::before {
    content: '';
    position: absolute;
    left: 30px;
    top: 0;
    bottom: 0;
    width: 2px;
    background: #dee2e6;
}

.timeline-item {
    position: relative;
    padding-left: 70px;
    padding-bottom: 30px;
}

.timeline-marker {
    position: absolute;
    left: 15px;
    top: 0;
    width: 32px;
    height: 32px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 1;
    box-shadow: 0 0 0 4px #fff;
}

.timeline-content {
    flex: 1;
}

.badge {
    font-weight: 500;
}

@media print {
    .card-header .btn,
    .timeline-item .btn,
    .card-footer,
    nav {
        display: none !important;
    }
}
</style>

<!-- JavaScript -->
<script>
function exportarPDF() {
    const pacienteId = <?= $paciente['id'] ?>;
    const params = new URLSearchParams(window.location.search);
    params.set('export', 'pdf');
    
    window.open(
        '<?= Util::url('pacientes/historial') ?>?' + params.toString(),
        '_blank'
    );
}

// Función para imprimir
function imprimirHistorial() {
    window.print();
}

// Atajos de teclado
document.addEventListener('keydown', function(e) {
    if (e.ctrlKey) {
        switch(e.key) {
            case 'p':
                e.preventDefault();
                imprimirHistorial();
                break;
            case 'e':
                e.preventDefault();
                exportarPDF();
                break;
        }
    }
});
</script>