<?php
if (!defined('APP_PATH'))
    exit('No direct script access allowed');
?>

<!-- Encabezado del Médico -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card shadow-sm">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <div class="d-flex align-items-center">
                            <?php if (!empty($medico['imagen'])): ?>
                                <img src="<?= Util::asset('uploads/usuarios/' . $medico['imagen']) ?>"
                                    class="rounded-circle me-3"
                                    alt="<?= htmlspecialchars($medico['nombre'] . ' ' . $medico['apellidos']) ?>"
                                    style="width: 100px; height: 100px; object-fit: cover;">
                            <?php else: ?>
                                <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center me-3"
                                    style="width: 100px; height: 100px; font-size: 2.5rem;">
                                    <i class="bi bi-person-badge"></i>
                                </div>
                            <?php endif; ?>

                            <div>
                                <h3 class="mb-1">
                                    Dr(a). <?= htmlspecialchars($medico['nombre'] . ' ' . $medico['apellidos']) ?>
                                    <?php if ($medico['is_active']): ?>
                                        <span class="badge bg-success">Activo</span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary">Inactivo</span>
                                    <?php endif; ?>
                                </h3>
                                <div class="text-muted mb-2">
                                    <?php if (!empty($medico['especialidad_nombre'])): ?>
                                        <span class="badge bg-primary me-2">
                                            <i class="bi bi-heart-pulse me-1"></i>
                                            <?= htmlspecialchars($medico['especialidad_nombre']) ?>
                                        </span>
                                    <?php endif; ?>
                                    <?php if (!empty($medico['cedula_profesional'])): ?>
                                        <span class="me-3">
                                            <i class="bi bi-shield-check me-1"></i>
                                            Cédula: <?= htmlspecialchars($medico['cedula_profesional']) ?>
                                        </span>
                                    <?php endif; ?>
                                    <?php if (!empty($medico['experiencia_anos'])): ?>
                                        <span>
                                            <i class="bi bi-award me-1"></i>
                                            <?= $medico['experiencia_anos'] ?> años de experiencia
                                        </span>
                                    <?php endif; ?>
                                </div>
                                <div class="text-muted">
                                    <?php if (!empty($medico['email'])): ?>
                                        <span class="me-3">
                                            <i class="bi bi-envelope me-1"></i>
                                            <a href="mailto:<?= htmlspecialchars($medico['email']) ?>">
                                                <?= htmlspecialchars($medico['email']) ?>
                                            </a>
                                        </span>
                                    <?php endif; ?>
                                    <?php if (!empty($medico['telefono'])): ?>
                                        <span>
                                            <i class="bi bi-telephone me-1"></i>
                                            <a href="tel:<?= htmlspecialchars($medico['telefono']) ?>">
                                                <?= Util::formatPhone($medico['telefono']) ?>
                                            </a>
                                        </span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 text-end">
                        <a href="<?= Util::url('medicos') ?>" class="btn btn-outline-secondary btn-sm me-2">
                            <i class="bi bi-arrow-left me-1"></i>
                            Volver
                        </a>
                        <?php if (Auth::hasRole('administrador')): ?>
                            <a href="<?= Util::url('medicos/editar?id=' . $medico['id']) ?>" class="btn btn-primary btn-sm">
                                <i class="bi bi-pencil me-1"></i>
                                Editar
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Estadísticas Rápidas -->
<div class="row mb-4">
    <div class="col-md-3 col-sm-6 mb-3">
        <div class="card text-center shadow-sm h-100">
            <div class="card-body">
                <div class="text-primary mb-2" style="font-size: 2.5rem;">
                    <i class="bi bi-calendar-check"></i>
                </div>
                <h3 class="mb-0"><?= $stats['citas_programadas'] ?? 0 ?></h3>
                <p class="text-muted mb-0 small">Citas Próximas</p>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6 mb-3">
        <div class="card text-center shadow-sm h-100">
            <div class="card-body">
                <div class="text-success mb-2" style="font-size: 2.5rem;">
                    <i class="bi bi-clipboard2-pulse"></i>
                </div>
                <h3 class="mb-0"><?= $stats['consultas_mes'] ?? 0 ?></h3>
                <p class="text-muted mb-0 small">Consultas del Mes</p>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6 mb-3">
        <div class="card text-center shadow-sm h-100">
            <div class="card-body">
                <div class="text-info mb-2" style="font-size: 2.5rem;">
                    <i class="bi bi-people"></i>
                </div>
                <h3 class="mb-0"><?= $stats['total_pacientes'] ?? 0 ?></h3>
                <p class="text-muted mb-0 small">Pacientes Atendidos</p>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6 mb-3">
        <div class="card text-center shadow-sm h-100">
            <div class="card-body">
                <div class="text-warning mb-2" style="font-size: 2.5rem;">
                    <i class="bi bi-cash-coin"></i>
                </div>
                <h3 class="mb-0 text-success">Q <?= number_format($medico['costo_consulta'], 2) ?></h3>
                <p class="text-muted mb-0 small">Costo Consulta</p>
            </div>
        </div>
    </div>
</div>

<!-- Contenido Principal -->
<div class="row">
    <!-- Columna Izquierda -->
    <div class="col-lg-4">

        <!-- Información Profesional -->
        <div class="card mb-4 shadow-sm">
            <div class="card-header bg-primary text-white">
                <h5 class="card-title mb-0">
                    <i class="bi bi-hospital me-2"></i>
                    Información Profesional
                </h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <small class="text-muted d-block">Especialidad</small>
                    <strong><?= !empty($medico['especialidad_nombre']) ? htmlspecialchars($medico['especialidad_nombre']) : 'No especificada' ?></strong>
                </div>

                <?php if (!empty($medico['cedula_profesional'])): ?>
                    <div class="mb-3">
                        <small class="text-muted d-block">Cédula Profesional</small>
                        <strong><?= htmlspecialchars($medico['cedula_profesional']) ?></strong>
                    </div>
                <?php endif; ?>

                <?php if (!empty($medico['consultorio'])): ?>
                    <div class="mb-3">
                        <small class="text-muted d-block">Consultorio</small>
                        <strong>
                            <i class="bi bi-door-open me-1"></i>
                            <?= htmlspecialchars($medico['consultorio']) ?>
                        </strong>
                    </div>
                <?php endif; ?>

                <div class="mb-3">
                    <small class="text-muted d-block">Costo de Consulta</small>
                    <strong class="text-success"><?= Util::formatMoney($medico['costo_consulta']) ?></strong>
                </div>

                <?php if (!empty($medico['biografia'])): ?>
                    <div class="mb-0">
                        <small class="text-muted d-block">Biografía</small>
                        <p class="mb-0"><?= nl2br(htmlspecialchars($medico['biografia'])) ?></p>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Horarios de Atención -->
        <div class="card mb-4 shadow-sm">
            <div class="card-header bg-success text-white">
                <h5 class="card-title mb-0">
                    <i class="bi bi-clock-history me-2"></i>
                    Horarios de Atención
                </h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <small class="text-muted d-block">Horario</small>
                    <strong>
                        <?= date('g:i A', strtotime($medico['horario_inicio'])) ?> -
                        <?= date('g:i A', strtotime($medico['horario_fin'])) ?>
                    </strong>
                </div>

                <?php if (!empty($medico['dias_atencion'])): ?>
                    <div class="mb-0">
                        <small class="text-muted d-block mb-2">Días de Atención</small>
                        <?php
                        $dias = explode(',', $medico['dias_atencion']);
                        $diasNombres = [
                            'lunes' => 'Lunes',
                            'martes' => 'Martes',
                            'miercoles' => 'Miércoles',
                            'jueves' => 'Jueves',
                            'viernes' => 'Viernes',
                            'sabado' => 'Sábado',
                            'domingo' => 'Domingo'
                        ];

                        foreach ($dias as $dia):
                            $dia = trim($dia);
                            ?>
                            <span class="badge bg-light text-dark me-1 mb-1">
                                <?= $diasNombres[$dia] ?? $dia ?>
                            </span>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Sección de Permisos y Accesos -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="bi bi-shield-lock me-2"></i>
                    Permisos y Accesos
                </h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <small class="text-muted d-block">Rol</small>
                    <span class="badge bg-primary">
                        <?= htmlspecialchars($medico['rol_nombre'] ?? 'Médico') ?>
                    </span>
                </div>

                <div class="mb-3">
                    <small class="text-muted d-block">Usuario del Sistema</small>
                    <!-- LÍNEA CORREGIDA: Se agregó ?? 'N/A' -->
                    <strong><?= htmlspecialchars($medico['username'] ?? 'N/A') ?></strong>
                </div>

                <div class="mb-3">
                    <small class="text-muted d-block">Estado de la Cuenta</small>
                    <?php if ($medico['is_active'] ?? true): ?>
                        <span class="badge bg-success">
                            <i class="bi bi-check-circle me-1"></i>
                            Cuenta Activa
                        </span>
                    <?php else: ?>
                        <span class="badge bg-danger">
                            <i class="bi bi-x-circle me-1"></i>
                            Cuenta Inactiva
                        </span>
                    <?php endif; ?>
                </div>

                <div class="mb-3">
                    <small class="text-muted d-block">Último Acceso</small>
                    <?php if (!empty($medico['ultimo_acceso'])): ?>
                        <strong><?= Util::formatDateTime($medico['ultimo_acceso']) ?></strong>
                    <?php else: ?>
                        <span class="text-muted">Nunca ha ingresado</span>
                    <?php endif; ?>
                </div>
            </div>
        </div>

    </div>

    <!-- Columna Derecha -->
    <div class="col-lg-8">

        <!-- Próximas Citas -->
        <div class="card mb-4 shadow-sm">
            <div class="card-header bg-warning text-dark">
                <h5 class="card-title mb-0">
                    <i class="bi bi-calendar-event me-2"></i>
                    Próximas Citas
                </h5>
            </div>
            <div class="card-body">
                <?php if (!empty($citas)): ?>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Fecha y Hora</th>
                                    <th>Paciente</th>
                                    <th>Motivo</th>
                                    <th>Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($citas as $cita): ?>
                                    <tr>
                                        <td>
                                            <strong><?= Util::formatDate($cita['fecha_cita']) ?></strong><br>
                                            <small
                                                class="text-muted"><?= date('g:i A', strtotime($cita['hora_cita'])) ?></small>
                                        </td>
                                        <td>
                                            <a href="<?= Util::url('pacientes/ver?id=' . $cita['paciente_id']) ?>">
                                                <?= htmlspecialchars($cita['paciente_nombre']) ?>
                                            </a>
                                        </td>
                                        <td><?= Util::truncate($cita['motivo_consulta'], 50) ?></td>
                                        <td>
                                            <?php
                                            $estadoBadge = [
                                                'programada' => 'bg-info',
                                                'confirmada' => 'bg-primary',
                                                'en_curso' => 'bg-warning',
                                                'completada' => 'bg-success',
                                                'cancelada' => 'bg-danger',
                                                'no_asistio' => 'bg-secondary'
                                            ];
                                            $badge = $estadoBadge[$cita['estado']] ?? 'bg-secondary';
                                            ?>
                                            <span class="badge <?= $badge ?>">
                                                <?= ucfirst(str_replace('_', ' ', $cita['estado'])) ?>
                                            </span>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="text-center py-5">
                        <i class="bi bi-calendar-x text-muted" style="font-size: 3rem;"></i>
                        <p class="text-muted mt-3 mb-0">No hay citas próximas programadas</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Gráfico de Consultas -->
        <div class="card mb-4 shadow-sm">
            <div class="card-header bg-secondary text-white">
                <h5 class="card-title mb-0">
                    <i class="bi bi-graph-up me-2"></i>
                    Estadísticas de Consultas
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4 text-center mb-3">
                        <div class="p-3 bg-light rounded">
                            <h2 class="text-primary mb-0"><?= $stats['consultas_hoy'] ?? 0 ?></h2>
                            <small class="text-muted">Hoy</small>
                        </div>
                    </div>
                    <div class="col-md-4 text-center mb-3">
                        <div class="p-3 bg-light rounded">
                            <h2 class="text-success mb-0"><?= $stats['consultas_semana'] ?? 0 ?></h2>
                            <small class="text-muted">Esta Semana</small>
                        </div>
                    </div>
                    <div class="col-md-4 text-center mb-3">
                        <div class="p-3 bg-light rounded">
                            <h2 class="text-info mb-0"><?= $stats['consultas_mes'] ?? 0 ?></h2>
                            <small class="text-muted">Este Mes</small>
                        </div>
                    </div>
                </div>

                <?php if (isset($stats['promedio_pacientes_dia'])): ?>
                    <div class="alert alert-info mb-0 mt-3">
                        <i class="bi bi-info-circle me-2"></i>
                        <strong>Promedio diario:</strong>
                        <?= number_format($stats['promedio_pacientes_dia'], 1) ?> pacientes por día
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Acciones Rápidas -->
        <div class="card shadow-sm">
            <div class="card-header bg-dark text-white">
                <h5 class="card-title mb-0">
                    <i class="bi bi-lightning me-2"></i>
                    Acciones Rápidas
                </h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-4">
                        <a href="<?= Util::url('citas/crear?medico_id=' . $medico['id']) ?>"
                            class="btn btn-outline-primary w-100 py-3">
                            <i class="bi bi-calendar-plus d-block mb-2" style="font-size: 2rem;"></i>
                            Agendar Nueva Cita
                        </a>
                    </div>
                    <div class="col-md-4">
                        <a href="<?= Util::url('medicos/horarios?id=' . $medico['id']) ?>"
                            class="btn btn-outline-success w-100 py-3">
                            <i class="bi bi-clock-history d-block mb-2" style="font-size: 2rem;"></i>
                            Gestionar Horarios
                        </a>
                    </div>
                    <div class="col-md-4">
                        <a href="<?= Util::url('citas/agenda?medico_id=' . $medico['id']) ?>"
                            class="btn btn-outline-info w-100 py-3">
                            <i class="bi bi-calendar-week d-block mb-2" style="font-size: 2rem;"></i>
                            Ver Agenda
                        </a>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<!-- JavaScript -->
<script>
    // Inicializar tooltips
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // Función para imprimir perfil
    function imprimirPerfil() {
        window.print();
    }

    // Atajos de teclado
    document.addEventListener('keydown', function (e) {
        if (e.ctrlKey) {
            switch (e.key) {
                case 'p':
                    e.preventDefault();
                    imprimirPerfil();
                    break;
                case 'e':
                    e.preventDefault();
                    <?php if (Auth::hasRole('administrador')): ?>
                        window.location.href = '<?= Util::url('medicos/editar?id=' . $medico['id']) ?>';
                    <?php endif; ?>
                    break;
            }
        } else if (e.key === 'Escape') {
            window.location.href = '<?= Util::url('medicos') ?>';
        }
    });
</script>

<!-- CSS para impresión -->
<style>
    @media print {

        .btn,
        .card-header a,
        .modal {
            display: none !important;
        }

        .card {
            break-inside: avoid;
            border: 1px solid #ddd !important;
        }
    }
</style>