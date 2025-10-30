<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>
        <i class="bi bi-calendar-check text-primary me-2"></i>
        Cita <?= htmlspecialchars($cita['codigo_cita'] ?? 'N/A') ?>
    </h2>
    <div class="btn-group">
        <a href="<?= Router::url('citas') ?>" class="btn btn-secondary">
            <i class="bi bi-arrow-left me-2"></i>Volver
        </a>
        
        <!-- Botones para Administradores y Secretarios -->
        <?php if (Auth::hasRole('administrador') || Auth::hasRole('secretario')): ?>
            <?php if (in_array($cita['estado'] ?? '', ['programada', 'confirmada'])): ?>
                <a href="<?= Router::url('citas/editar?id=' . ($cita['id'] ?? '')) ?>" 
                   class="btn btn-primary">
                    <i class="bi bi-pencil me-2"></i>Editar
                </a>
            <?php endif; ?>
        <?php endif; ?>
        
        <!-- *** NUEVOS BOTONES PARA MÉDICOS *** -->
        <?php if (Auth::hasRole('medico')): ?>
            <!-- Botón para editar cita (solo si no está completada o cancelada) -->
            <?php if (in_array($cita['estado'] ?? '', ['programada', 'confirmada', 'en_curso'])): ?>
                <a href="<?= Router::url('citas/editar?id=' . ($cita['id'] ?? '')) ?>" 
                   class="btn btn-warning">
                    <i class="bi bi-pencil me-2"></i>Editar Cita
                </a>
            <?php endif; ?>
            
            <!-- Botón para iniciar consulta -->
            <?php if (($cita['estado'] ?? '') == 'confirmada' && !$tiene_consulta): ?>
                <a href="<?= Router::url('consultas/nueva?cita_id=' . ($cita['id'] ?? '')) ?>" 
                   class="btn btn-success">
                    <i class="bi bi-play-circle-fill me-2"></i>Iniciar Consulta
                </a>
            <?php endif; ?>
            
            <!-- Botón para ver consulta si existe -->
            <?php if ($tiene_consulta && $consulta_id): ?>
                <a href="<?= Router::url('consultas/ver?id=' . $consulta_id) ?>" 
                   class="btn btn-info">
                    <i class="bi bi-file-medical me-2"></i>Ver Consulta
                </a>
            <?php endif; ?>
            
            <!-- *** BOTÓN IMPORTANTE: Finalizar Cita *** -->
            <?php if (($cita['estado'] ?? '') == 'en_curso'): ?>
                <a href="<?= Router::url('citas/finalizar?id=' . ($cita['id'] ?? '')) ?>" 
                   class="btn btn-success"
                   onclick="return confirm('¿Está seguro de finalizar esta cita? Esto marcará la cita como completada.')">
                    <i class="bi bi-check-circle-fill me-2"></i>Finalizar Cita
                </a>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</div>

<!-- Estado de la Cita -->
<div class="row mb-4">
    <div class="col-12">
        <div class="alert alert-<?= match($cita['estado'] ?? 'programada') {
            'programada' => 'secondary',
            'confirmada' => 'primary',
            'en_curso' => 'warning',
            'completada' => 'success',
            'cancelada' => 'danger',
            'no_asistio' => 'dark',
            default => 'secondary'
        } ?> d-flex justify-content-between align-items-center">
            <div>
                <strong>Estado: </strong>
                <span class="fs-5">
                    <?= ucfirst(str_replace('_', ' ', $cita['estado'] ?? 'programada')) ?>
                </span>
            </div>
            
            <!-- Acciones rápidas de cambio de estado -->
            <?php if (Auth::hasRole('administrador') || Auth::hasRole('secretario')): ?>
                <div class="btn-group btn-group-sm">
                    <?php if (($cita['estado'] ?? '') == 'programada'): ?>
                        <a href="<?= Router::url('citas/cambiarEstado?id=' . ($cita['id'] ?? '') . '&estado=confirmada') ?>" 
                           class="btn btn-sm btn-success" 
                           onclick="return confirm('¿Confirmar esta cita?')">
                            <i class="bi bi-check2"></i> Confirmar
                        </a>
                    <?php endif; ?>
                    
                    <?php if (in_array($cita['estado'] ?? '', ['programada', 'confirmada'])): ?>
                        <a href="<?= Router::url('citas/cambiarEstado?id=' . ($cita['id'] ?? '') . '&estado=cancelada') ?>" 
                           class="btn btn-sm btn-danger" 
                           onclick="return confirm('¿Cancelar esta cita?')">
                            <i class="bi bi-x-circle"></i> Cancelar
                        </a>
                    <?php endif; ?>
                    
                    <?php if (($cita['estado'] ?? '') == 'en_curso'): ?>
                        <a href="<?= Router::url('citas/finalizar?id=' . ($cita['id'] ?? '')) ?>" 
                           class="btn btn-sm btn-success" 
                           onclick="return confirm('¿Marcar como completada?')">
                            <i class="bi bi-check-circle"></i> Completar
                        </a>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Información Principal -->
<div class="row">
    <!-- Columna Izquierda: Información del Paciente y Médico -->
    <div class="col-md-6">
        <!-- Información del Paciente -->
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">
                    <i class="bi bi-person-fill me-2"></i>
                    Información del Paciente
                </h5>
            </div>
            <div class="card-body">
                <div class="d-flex align-items-center mb-3">
                    <div class="avatar-lg bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3">
                        <i class="bi bi-person-fill fs-2"></i>
                    </div>
                    <div>
                        <h5 class="mb-0"><?= htmlspecialchars($cita['paciente_nombre'] ?? 'N/A') ?></h5>
                        <?php if (!empty($cita['codigo_paciente'])): ?>
                            <small class="text-muted">
                                Código: <?= htmlspecialchars($cita['codigo_paciente']) ?>
                            </small>
                        <?php endif; ?>
                    </div>
                </div>
                
                <hr>
                
                <div class="row">
                    <?php if (!empty($cita['paciente_telefono'])): ?>
                        <div class="col-12 mb-2">
                            <strong><i class="bi bi-telephone me-2"></i>Teléfono:</strong>
                            <a href="tel:<?= htmlspecialchars($cita['paciente_telefono']) ?>">
                                <?= htmlspecialchars($cita['paciente_telefono']) ?>
                            </a>
                        </div>
                    <?php endif; ?>
                    
                    <?php if (!empty($cita['paciente_email'])): ?>
                        <div class="col-12 mb-2">
                            <strong><i class="bi bi-envelope me-2"></i>Email:</strong>
                            <a href="mailto:<?= htmlspecialchars($cita['paciente_email']) ?>">
                                <?= htmlspecialchars($cita['paciente_email']) ?>
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
                
                <div class="mt-3">
                    <a href="<?= Router::url('pacientes/ver?id=' . ($cita['paciente_id'] ?? '')) ?>" 
                       class="btn btn-outline-primary btn-sm">
                        <i class="bi bi-folder-open me-2"></i>Ver Expediente Completo
                    </a>
                </div>
            </div>
        </div>
        
        <!-- Información del Médico -->
        <div class="card mb-4">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0">
                    <i class="bi bi-person-badge me-2"></i>
                    Información del Médico
                </h5>
            </div>
            <div class="card-body">
                <div class="d-flex align-items-center mb-3">
                    <div class="avatar-lg bg-success text-white rounded-circle d-flex align-items-center justify-content-center me-3">
                        <i class="bi bi-person-badge fs-2"></i>
                    </div>
                    <div>
                        <h5 class="mb-0">Dr. <?= htmlspecialchars($cita['medico_nombre'] ?? 'N/A') ?></h5>
                        <?php if (!empty($cita['especialidad'])): ?>
                            <span class="badge bg-success">
                                <?= htmlspecialchars($cita['especialidad']) ?>
                            </span>
                        <?php endif; ?>
                    </div>
                </div>
                
                <hr>
                
                <?php if (!empty($cita['consultorio'])): ?>
                    <div class="mb-2">
                        <strong><i class="bi bi-hospital me-2"></i>Consultorio:</strong>
                        <?= htmlspecialchars($cita['consultorio']) ?>
                    </div>
                <?php endif; ?>
                
                <?php if (!empty($cita['costo_consulta'])): ?>
                    <div class="mb-2">
                        <strong><i class="bi bi-cash me-2"></i>Costo:</strong>
                        Q <?= number_format($cita['costo_consulta'], 2) ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <!-- Columna Derecha: Detalles de la Cita -->
    <div class="col-md-6">
        <!-- Detalles de la Cita -->
        <div class="card mb-4">
            <div class="card-header bg-info text-white">
                <h5 class="mb-0">
                    <i class="bi bi-calendar-event me-2"></i>
                    Detalles de la Cita
                </h5>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-6">
                        <strong><i class="bi bi-calendar3 me-2"></i>Fecha:</strong>
                        <br>
                        <span class="fs-5 text-primary">
                            <?= Util::formatDate($cita['fecha_cita'] ?? '') ?>
                        </span>
                    </div>
                    <div class="col-6">
                        <strong><i class="bi bi-clock me-2"></i>Hora:</strong>
                        <br>
                        <span class="fs-5 text-primary">
                            <?= Util::formatTime($cita['hora_cita'] ?? '') ?>
                        </span>
                    </div>
                </div>
                
                <hr>
                
                <div class="mb-3">
                    <strong><i class="bi bi-hourglass me-2"></i>Duración:</strong>
                    <?= ($cita['duracion_minutos'] ?? 30) ?> minutos
                </div>
                
                <div class="mb-3">
                    <strong><i class="bi bi-tag me-2"></i>Tipo de Cita:</strong>
                    <span class="badge bg-secondary">
                        <?= ucfirst(str_replace('_', ' ', $cita['tipo_cita'] ?? 'primera_vez')) ?>
                    </span>
                </div>
                
                <hr>
                
                <div class="mb-3">
                    <strong><i class="bi bi-clipboard-pulse me-2"></i>Motivo de Consulta:</strong>
                    <br>
                    <div class="alert alert-light mt-2 mb-0">
                        <?= nl2br(htmlspecialchars($cita['motivo_consulta'] ?? 'N/A')) ?>
                    </div>
                </div>
                
                <?php if (!empty($cita['notas'])): ?>
                    <hr>
                    <div class="mb-3">
                        <strong><i class="bi bi-journal-text me-2"></i>Notas:</strong>
                        <br>
                        <div class="alert alert-light mt-2 mb-0">
                            <?= nl2br(htmlspecialchars($cita['notas'])) ?>
                        </div>
                    </div>
                <?php endif; ?>
                
                <?php if (!empty($cita['observaciones'])): ?>
                    <hr>
                    <div class="mb-3">
                        <strong><i class="bi bi-info-circle me-2"></i>Observaciones:</strong>
                        <br>
                        <div class="alert alert-warning mt-2 mb-0">
                            <?= nl2br(htmlspecialchars($cita['observaciones'])) ?>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        
        <!-- Información de Registro -->
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0">
                    <i class="bi bi-info-circle me-2"></i>
                    Información de Registro
                </h6>
            </div>
            <div class="card-body">
                <?php if (!empty($cita['registrado_por'])): ?>
                    <div class="mb-2">
                        <strong>Registrado por:</strong>
                        <?= htmlspecialchars($cita['registrado_por']) ?>
                    </div>
                <?php endif; ?>
                
                <div class="mb-2">
                    <strong>Fecha de registro:</strong>
                    <?= Util::formatDateTime($cita['created_at'] ?? '') ?>
                </div>
                
                <?php if (!empty($cita['updated_at']) && $cita['updated_at'] != $cita['created_at']): ?>
                    <div class="mb-2">
                        <strong>Última actualización:</strong>
                        <?= Util::formatDateTime($cita['updated_at']) ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Mensaje de ayuda para médicos -->
<?php if (Auth::hasRole('medico') && ($cita['estado'] ?? '') == 'en_curso'): ?>
    <div class="row mt-4">
        <div class="col-12">
            <div class="alert alert-info">
                <i class="bi bi-info-circle-fill me-2"></i>
                <strong>Nota importante:</strong> Esta cita está en curso. Una vez que hayas registrado la consulta médica, 
                recuerda hacer clic en el botón <strong>"Finalizar Cita"</strong> para marcarla como completada.
            </div>
        </div>
    </div>
<?php endif; ?>

<style>
.avatar-lg {
    width: 80px;
    height: 80px;
}
</style>