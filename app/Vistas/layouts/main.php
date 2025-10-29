<?php
if (!defined('APP_PATH')) exit('No direct script access allowed');

// Incluir el header
require_once 'header.php';

// Incluir el sidebar
require_once 'sidebar.php';
?>

<!-- Main Content -->
<div class="main-content">
    <!-- Content Wrapper -->
    <div class="content-wrapper">
        <!-- Breadcrumb (Opcional) -->
        <?php if (isset($breadcrumb) && !empty($breadcrumb)): ?>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="<?= isset($base_url) ? $base_url : '' ?>/dashboard">
                        <i class="bi bi-house-door"></i> Inicio
                    </a>
                </li>
                <?php foreach ($breadcrumb as $index => $item): ?>
                    <?php if ($index === array_key_last($breadcrumb)): ?>
                        <li class="breadcrumb-item active" aria-current="page">
                            <?= htmlspecialchars($item['title']) ?>
                        </li>
                    <?php else: ?>
                        <li class="breadcrumb-item">
                            <a href="<?= htmlspecialchars($item['url']) ?>">
                                <?= htmlspecialchars($item['title']) ?>
                            </a>
                        </li>
                    <?php endif; ?>
                <?php endforeach; ?>
            </ol>
        </nav>
        <?php endif; ?>
        
        <!-- Mensajes Flash -->
        <?php if (isset($_SESSION['flash']) && !empty($_SESSION['flash'])): ?>
            <?php foreach ($_SESSION['flash'] as $type => $messages): ?>
                <?php foreach ($messages as $message): ?>
                    <div class="alert alert-<?= $type === 'error' ? 'danger' : htmlspecialchars($type) ?> alert-dismissible fade show" role="alert">
                        <i class="bi bi-<?= MediSys_getAlertIcon($type) ?> me-2"></i>
                        <?= htmlspecialchars($message) ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endforeach; ?>
            <?php endforeach; ?>
            <?php unset($_SESSION['flash']); ?>
        <?php endif; ?>
        
        <!-- Contenido de la página -->
        <?= $content ?? '' ?>
    </div>
</div>

<?php
// Incluir el footer
require_once 'footer.php';

/**
 * Función auxiliar para obtener el icono según el tipo de alerta
 */
function MediSys_getAlertIcon($type) {
    $icons = [
        'success' => 'check-circle-fill',
        'error' => 'x-circle-fill',
        'danger' => 'exclamation-triangle-fill',
        'warning' => 'exclamation-circle-fill',
        'info' => 'info-circle-fill'
    ];
    
    return $icons[$type] ?? 'info-circle-fill';
}
?>