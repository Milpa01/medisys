<div class="container-fluid">
    <!-- Encabezado -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-0">
                        <i class="bi bi-prescription2 me-2"></i>
                        Receta Médica
                    </h1>
                    <p class="text-muted mb-0">
                        Consulta: <?= htmlspecialchars($consulta['numero_consulta']) ?> - 
                        <?= Util::formatDate($consulta['fecha_cita']) ?>
                    </p>
                </div>
                <div class="d-print-none">
                    <button type="button" class="btn btn-primary" onclick="window.print()">
                        <i class="bi bi-printer"></i> Imprimir
                    </button>
                    <a href="<?= Util::url('consultas/ver?id=' . $consulta['id']) ?>" class="btn btn-secondary">
                        <i class="bi bi-arrow-left"></i> Volver
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Mensajes Flash -->
    <div class="d-print-none">
        <?= Flash::display() ?>
    </div>

    <!-- Receta Médica -->
    <div class="card receta-medica">
        <div class="card-body">
            <!-- Encabezado de la Receta -->
            <div class="row mb-4">
                <div class="col-md-8">
                    <h2 class="h4 text-primary mb-1">MediSys Clínica</h2>
                    <p class="mb-1">Sistema de Gestión Médica</p>
                    <p class="mb-0 text-muted">Receta Médica Electrónica</p>
                </div>
                <div class="col-md-4 text-end">
                    <div class="border p-3 rounded">
                        <div class="h6 mb-1">No. Receta</div>
                        <div class="h5 text-primary"><?= htmlspecialchars($consulta['numero_consulta']) ?></div>
                        <div class="small text-muted">
                            Fecha: <?= Util::formatDate($consulta['fecha_cita']) ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Información del Médico -->
            <div class="row mb-4">
                <div class="col-md-6">
                    <h6 class="text-primary border-bottom pb-2">MÉDICO PRESCRIPTOR</h6>
                    <div class="mb-2">
                        <strong>Dr. <?= htmlspecialchars($consulta['medico_nombre']) ?></strong>
                    </div>
                    <div class="mb-1">
                        <span class="text-muted">Especialidad:</span> <?= htmlspecialchars($consulta['especialidad']) ?>
                    </div>
                    <div class="mb-1">
                        <span class="text-muted">Cédula Profesional:</span> <?= htmlspecialchars($consulta['cedula_profesional']) ?>
                    </div>
                    <?php if ($consulta['consultorio']): ?>
                    <div class="mb-1">
                        <span class="text-muted">Consultorio:</span> <?= htmlspecialchars($consulta['consultorio']) ?>
                    </div>
                    <?php endif; ?>
                    <?php if ($consulta['medico_telefono']): ?>
                    <div class="mb-0">
                        <span class="text-muted">Teléfono:</span> <?= htmlspecialchars($consulta['medico_telefono']) ?>
                    </div>
                    <?php endif; ?>
                </div>
                
                <div class="col-md-6">
                    <h6 class="text-primary border-bottom pb-2">INFORMACIÓN DEL PACIENTE</h6>
                    <div class="mb-2">
                        <strong><?= htmlspecialchars($consulta['paciente_nombre']) ?></strong>
                    </div>
                    <div class="mb-1">
                        <span class="text-muted">Código:</span> <?= htmlspecialchars($consulta['codigo_paciente']) ?>
                    </div>
                    <?php if ($consulta['fecha_nacimiento']): ?>
                    <div class="mb-1">
                        <span class="text-muted">Edad:</span> <?= Util::calculateAge($consulta['fecha_nacimiento']) ?> años
                    </div>
                    <?php endif; ?>
                    <?php if ($consulta['genero']): ?>
                    <div class="mb-1">
                        <span class="text-muted">Género:</span> 
                        <?php
                        $generos = ['M' => 'Masculino', 'F' => 'Femenino', 'Otro' => 'Otro'];
                        echo $generos[$consulta['genero']] ?? $consulta['genero'];
                        ?>
                    </div>
                    <?php endif; ?>
                    <?php if ($consulta['paciente_telefono']): ?>
                    <div class="mb-0">
                        <span class="text-muted">Teléfono:</span> <?= htmlspecialchars($consulta['paciente_telefono']) ?>
                    </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Diagnóstico -->
            <div class="row mb-4">
                <div class="col-12">
                    <h6 class="text-primary border-bottom pb-2">DIAGNÓSTICO</h6>
                    <div class="bg-light p-3 rounded">
                        <div class="mb-2">
                            <strong>Diagnóstico Principal:</strong>
                        </div>
                        <div class="h6 text-dark">
                            <?= htmlspecialchars($consulta['diagnostico_principal']) ?>
                        </div>
                        
                        <?php if ($consulta['diagnosticos_secundarios']): ?>
                        <div class="mt-3">
                            <strong>Diagnósticos Secundarios:</strong>
                            <div class="mt-1">
                                <?= nl2br(htmlspecialchars($consulta['diagnosticos_secundarios'])) ?>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Prescripciones -->
            <?php if (!empty($prescripciones)): ?>
            <div class="row mb-4">
                <div class="col-12">
                    <h6 class="text-primary border-bottom pb-2">PRESCRIPCIÓN MÉDICA</h6>
                    
                    <?php foreach ($prescripciones as $index => $prescripcion): ?>
                    <div class="prescripcion-item mb-4 p-3 border rounded">
                        <div class="row">
                            <div class="col-md-8">
                                <div class="h6 text-dark mb-1">
                                    <?= ($index + 1) ?>. <?= htmlspecialchars($prescripcion['nombre_comercial']) ?>
                                </div>
                                
                                <?php if ($prescripcion['nombre_generico']): ?>
                                <div class="text-muted mb-2">
                                    <small>Nombre genérico: <?= htmlspecialchars($prescripcion['nombre_generico']) ?></small>
                                </div>
                                <?php endif; ?>
                                
                                <?php if ($prescripcion['presentacion']): ?>
                                <div class="text-info mb-2">
                                    <small><?= htmlspecialchars($prescripcion['presentacion']) ?></small>
                                </div>
                                <?php endif; ?>
                                
                                <div class="prescripcion-detalles">
                                    <div class="row">
                                        <div class="col-sm-6 mb-2">
                                            <strong>Dosis:</strong> <?= htmlspecialchars($prescripcion['dosis']) ?>
                                        </div>
                                        <div class="col-sm-6 mb-2">
                                            <strong>Frecuencia:</strong> <?= htmlspecialchars($prescripcion['frecuencia']) ?>
                                        </div>
                                        <div class="col-sm-6 mb-2">
                                            <strong>Duración:</strong> <?= htmlspecialchars($prescripcion['duracion'] ?: 'Según evolución') ?>
                                        </div>
                                        <div class="col-sm-6 mb-2">
                                            <strong>Vía:</strong> <?= ucfirst($prescripcion['via_administracion']) ?>
                                        </div>
                                    </div>
                                </div>
                                
                                <?php if ($prescripcion['indicaciones_especiales']): ?>
                                <div class="mt-2 p-2 bg-warning bg-opacity-10 rounded">
                                    <strong class="text-warning">
                                        <i class="bi bi-exclamation-triangle me-1"></i>
                                        Indicaciones especiales:
                                    </strong>
                                    <div class="mt-1">
                                        <?= nl2br(htmlspecialchars($prescripcion['indicaciones_especiales'])) ?>
                                    </div>
                                </div>
                                <?php endif; ?>
                            </div>
                            
                            <div class="col-md-4 text-end">
                                <?php if ($prescripcion['cantidad_recetada']): ?>
                                <div class="border-start ps-3">
                                    <div class="text-muted small">Cantidad recetada</div>
                                    <div class="h5 text-primary"><?= $prescripcion['cantidad_recetada'] ?></div>
                                    <div class="text-muted small">unidades</div>
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php else: ?>
            <div class="row mb-4">
                <div class="col-12">
                    <h6 class="text-primary border-bottom pb-2">PRESCRIPCIÓN MÉDICA</h6>
                    <div class="alert alert-info">
                        <i class="bi bi-info-circle me-2"></i>
                        No se prescribieron medicamentos en esta consulta.
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <!-- Indicaciones Generales -->
            <?php if ($consulta['indicaciones']): ?>
            <div class="row mb-4">
                <div class="col-12">
                    <h6 class="text-primary border-bottom pb-2">INDICACIONES GENERALES AL PACIENTE</h6>
                    <div class="p-3 bg-light rounded">
                        <?= nl2br(htmlspecialchars($consulta['indicaciones'])) ?>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <!-- Próxima Cita -->
            <?php if ($consulta['proxima_cita']): ?>
            <div class="row mb-4">
                <div class="col-12">
                    <h6 class="text-primary border-bottom pb-2">SEGUIMIENTO</h6>
                    <div class="alert alert-info">
                        <i class="bi bi-calendar-plus me-2"></i>
                        <strong>Próxima cita programada:</strong> <?= Util::formatDate($consulta['proxima_cita']) ?>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <!-- Pie de receta -->
            <div class="row mt-5">
                <div class="col-md-6">
                    <div class="border-top pt-3">
                        <div class="text-center">
                            <div class="mb-2">_____________________________</div>
                            <div class="fw-bold">Dr. <?= htmlspecialchars($consulta['medico_nombre']) ?></div>
                            <div class="small text-muted"><?= htmlspecialchars($consulta['especialidad']) ?></div>
                            <div class="small text-muted">Cédula: <?= htmlspecialchars($consulta['cedula_profesional']) ?></div>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="text-end">
                        <div class="small text-muted">
                            <div class="mb-1">
                                <strong>Fecha de emisión:</strong> <?= Util::formatDate($consulta['created_at']) ?>
                            </div>
                            <div class="mb-1">
                                <strong>Hora:</strong> <?= Util::formatTime($consulta['created_at']) ?>
                            </div>
                            <div class="mb-1">
                                <strong>Sistema:</strong> MediSys v1.0
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Información Legal (solo en impresión) -->
    <div class="d-none d-print-block mt-4">
        <div class="border-top pt-3">
            <div class="row">
                <div class="col-12">
                    <div class="small text-muted text-center">
                        <p class="mb-1">
                            <strong>IMPORTANTE:</strong> Esta receta es válida únicamente para el paciente indicado.
                            No exceder las dosis prescritas. En caso de efectos adversos, suspender y consultar inmediatamente.
                        </p>
                        <p class="mb-1">
                            Receta generada electrónicamente por MediSys - Sistema de Gestión Médica
                        </p>
                        <p class="mb-0">
                            Fecha de impresión: <?= Util::formatDateTime(date('Y-m-d H:i:s')) ?>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- CSS específico para la receta médica -->
<style>
/* Estilos generales para la receta */
.receta-medica {
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    border: 1px solid #dee2e6;
}

.prescripcion-item {
    background-color: #fafafa;
    border: 1px solid #e9ecef !important;
}

.prescripcion-item:nth-child(even) {
    background-color: #f8f9fa;
}

.prescripcion-detalles .row > div {
    border-right: 1px solid #e9ecef;
}

.prescripcion-detalles .row > div:last-child {
    border-right: none;
}

/* Estilos para impresión */
@media print {
    /* Ocultar elementos no necesarios */
    .d-print-none, .btn, .alert-dismissible .btn-close, .navbar, .sidebar {
        display: none !important;
    }
    
    /* Mostrar elementos solo en impresión */
    .d-none.d-print-block {
        display: block !important;
    }
    
    /* Configuración de página */
    @page {
        margin: 1cm;
        size: A4;
    }
    
    body {
        font-size: 12px;
        line-height: 1.4;
        color: #000;
    }
    
    /* Estilos para la receta */
    .receta-medica {
        border: 2px solid #000 !important;
        box-shadow: none !important;
        page-break-inside: avoid;
    }
    
    /* Encabezado */
    .card-body h2 {
        color: #000 !important;
        font-size: 18px;
    }
    
    .text-primary {
        color: #000 !important;
    }
    
    .text-muted {
        color: #666 !important;
    }
    
    .text-info {
        color: #000 !important;
    }
    
    .text-warning {
        color: #000 !important;
    }
    
    /* Bordes y fondos */
    .border, .border-bottom, .border-top, .border-start {
        border-color: #000 !important;
    }
    
    .bg-light, .bg-warning {
        background-color: #f8f9fa !important;
        border: 1px solid #000 !important;
    }
    
    .alert {
        border: 1px solid #000 !important;
        background-color: #f8f9fa !important;
    }
    
    /* Prescripciones */
    .prescripcion-item {
        border: 1px solid #000 !important;
        background-color: #fff !important;
        margin-bottom: 15px !important;
        page-break-inside: avoid;
    }
    
    .prescripcion-detalles .row > div {
        border-right: 1px solid #ccc !important;
        padding: 5px !important;
    }
    
    /* Firmas */
    .border-top {
        border-top: 2px solid #000 !important;
    }
    
    /* Espaciado */
    .mb-4 {
        margin-bottom: 20px !important;
    }
    
    .mb-2 {
        margin-bottom: 10px !important;
    }
    
    .p-3 {
        padding: 15px !important;
    }
    
    /* Header de impresión */
    .container-fluid::before {
        content: "RECETA MÉDICA ELECTRÓNICA";
        display: block;
        text-align: center;
        font-size: 16px;
        font-weight: bold;
        margin-bottom: 20px;
        padding-bottom: 10px;
        border-bottom: 2px solid #000;
    }
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .prescripcion-detalles .row > div {
        border-right: none;
        border-bottom: 1px solid #e9ecef;
        margin-bottom: 10px;
    }
    
    .prescripcion-detalles .row > div:last-child {
        border-bottom: none;
        margin-bottom: 0;
    }
}

/* Animaciones para pantalla */
@media screen {
    .prescripcion-item {
        transition: box-shadow 0.2s ease-in-out;
    }
    
    .prescripcion-item:hover {
        box-shadow: 0 0.25rem 0.5rem rgba(0, 0, 0, 0.1);
    }
    
    .card {
        animation: fadeIn 0.5s ease-in-out;
    }
}

@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Iconos en prescripciones */
.prescripcion-item .bi {
    color: #6c757d;
}

/* Destacar información importante */
.h6.text-dark {
    font-weight: 600;
    color: #212529 !important;
}

/* Mejorar legibilidad de dosis */
.prescripcion-detalles strong {
    color: #495057;
    font-weight: 600;
}
</style>

<!-- JavaScript para funcionalidades de la receta -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Mejorar la función de impresión
    const originalPrint = window.print;
    
    window.print = function() {
        // Preparar la página para impresión
        document.body.classList.add('printing');
        
        // Crear ventana de impresión personalizada
        const printContent = document.querySelector('.receta-medica').outerHTML;
        const printWindow = window.open('', '_blank', 'width=800,height=600');
        
        printWindow.document.write(`
            <!DOCTYPE html>
            <html>
            <head>
                <title>Receta Médica - ${document.querySelector('h1').textContent}</title>
                <meta charset="utf-8">
                <link href="<?= Util::asset('bootstrap5/css/bootstrap.min.css') ?>" rel="stylesheet">
                <style>
                    ${document.querySelector('style').innerHTML}
                    
                    body {
                        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
                        margin: 0;
                        padding: 20px;
                    }
                    
                    .receta-medica {
                        max-width: 100%;
                        margin: 0 auto;
                    }
                    
                    @media print {
                        body { margin: 0; padding: 0; }
                        .receta-medica { margin: 0; }
                    }
                </style>
            </head>
            <body>
                <div class="container-fluid">
                    ${printContent}
                    
                    <div class="d-print-block mt-4">
                        <div class="border-top pt-3">
                            <div class="row">
                                <div class="col-12">
                                    <div class="small text-muted text-center">
                                        <p class="mb-1">
                                            <strong>IMPORTANTE:</strong> Esta receta es válida únicamente para el paciente indicado.
                                            No exceder las dosis prescritas. En caso de efectos adversos, suspender y consultar inmediatamente.
                                        </p>
                                        <p class="mb-1">
                                            Receta generada electrónicamente por MediSys - Sistema de Gestión Médica
                                        </p>
                                        <p class="mb-0">
                                            Fecha de impresión: ${new Date().toLocaleString('es-GT')}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </body>
            </html>
        `);
        
        printWindow.document.close();
        printWindow.focus();
        
        // Esperar a que cargue completamente antes de imprimir
        printWindow.onload = function() {
            setTimeout(() => {
                printWindow.print();
                printWindow.close();
                document.body.classList.remove('printing');
            }, 500);
        };
    };
    
    // Atajos de teclado
    document.addEventListener('keydown', function(e) {
        if (e.ctrlKey && e.key === 'p') {
            e.preventDefault();
            window.print();
        }
        
        if (e.key === 'Escape') {
            window.location.href = '<?= Util::url('consultas/ver?id=' . $consulta['id']) ?>';
        }
    });
    
    // Animación de entrada para las prescripciones
    const prescripciones = document.querySelectorAll('.prescripcion-item');
    prescripciones.forEach((item, index) => {
        item.style.opacity = '0';
        item.style.transform = 'translateX(-20px)';
        
        setTimeout(() => {
            item.style.transition = 'all 0.4s ease-out';
            item.style.opacity = '1';
            item.style.transform = 'translateX(0)';
        }, index * 150);
    });
    
    // Validar información crítica antes de imprimir
    const btnImprimir = document.querySelector('button[onclick="window.print()"]');
    if (btnImprimir) {
        btnImprimir.addEventListener('click', function(e) {
            e.preventDefault();
            
            // Verificar que hay prescripciones
            const hayPrescripciones = document.querySelectorAll('.prescripcion-item').length > 0;
            
            if (!hayPrescripciones) {
                const confirmar = confirm('Esta consulta no tiene prescripciones. ¿Desea imprimir de todas formas?');
                if (!confirmar) {
                    return;
                }
            }
            
            // Verificar información del médico
            const cedulaProfesional = '<?= htmlspecialchars($consulta['cedula_profesional']) ?>';
            if (!cedulaProfesional) {
                alert('Advertencia: No se ha registrado la cédula profesional del médico.');
            }
            
            window.print();
        });
    }
    
    // Función para copiar número de receta
    function copiarNumeroReceta() {
        const numeroReceta = '<?= htmlspecialchars($consulta['numero_consulta']) ?>';
        navigator.clipboard.writeText(numeroReceta).then(() => {
            // Mostrar mensaje de confirmación
            const alert = document.createElement('div');
            alert.className = 'alert alert-success alert-dismissible fade show position-fixed';
            alert.style.top = '20px';
            alert.style.right = '20px';
            alert.style.zIndex = '9999';
            alert.innerHTML = '<i class="bi bi-check-circle me-2"></i>Número de receta copiado al portapapeles.';
            document.body.appendChild(alert);
            
            setTimeout(() => alert.remove(), 3000);
        });
    }
    
    // Agregar funcionalidad de copia al número de receta
    const numeroReceta = document.querySelector('.h5.text-primary');
    if (numeroReceta) {
        numeroReceta.style.cursor = 'pointer';
        numeroReceta.title = 'Clic para copiar';
        numeroReceta.addEventListener('click', copiarNumeroReceta);
    }
});

// Función para exportar a PDF (placeholder para implementación futura)
function exportarPDF() {
    // Implementar exportación a PDF usando librerías como jsPDF
    console.log('Exportar receta a PDF');
    alert('Funcionalidad de exportación a PDF en desarrollo');
}

// Función para enviar por email (placeholder)
function enviarEmail() {
    // Implementar envío por email
    console.log('Enviar receta por email');
    alert('Funcionalidad de envío por email en desarrollo');
}
</script>