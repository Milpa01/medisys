<?php
if (!defined('APP_PATH')) exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html lang="es" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Sistema de Gestión Clínica MediSys">
    <meta name="author" content="MediSys">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    
    <title><?= htmlspecialchars($title ?? 'MediSys - Sistema de Gestión Clínica') ?></title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="<?= isset($base_url) ? $base_url : '' ?>/public/img/favicon.ico">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" 
          integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    
    <!-- DataTables CSS (Opcional) -->
    <link href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    
    <!-- Custom CSS -->
    <link href="<?= isset($base_url) ? $base_url : '' ?>/public/css/admin.css" rel="stylesheet">
    <link href="<?= isset($base_url) ? $base_url : '' ?>/public/css/sidebar.css" rel="stylesheet">
    
    <?php if (isset($custom_css)): ?>
        <?php foreach ($custom_css as $css): ?>
            <link href="<?= isset($base_url) ? $base_url : '' ?>/public/css/<?= $css ?>" rel="stylesheet">
        <?php endforeach; ?>
    <?php endif; ?>
</head>
<body>
    <!-- Top Navbar -->
    <div class="top-navbar">
        <div class="d-flex align-items-center">
            <button class="btn btn-link d-md-none text-decoration-none" id="sidebarToggle">
                <i class="bi bi-list fs-4"></i>
            </button>
            <h5 class="mb-0 ms-2">
                Bienvenido, <?= htmlspecialchars($current_user['nombre'] ?? 'Usuario') ?>
            </h5>
        </div>
        
        <div class="d-flex align-items-center gap-3">
            <!-- Botón de Modo Nocturno -->
            <button class="theme-toggle" id="themeToggle" title="Cambiar tema">
                <i class="bi bi-moon-stars-fill" id="themeIcon"></i>
            </button>
            
            <!-- Notificaciones (Opcional) -->
            <?php if (isset($notifications) && count($notifications) > 0): ?>
            <div class="dropdown">
                <button class="btn btn-link position-relative text-decoration-none" type="button" 
                        data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="bi bi-bell fs-5"></i>
                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                        <?= count($notifications) ?>
                    </span>
                </button>
                <ul class="dropdown-menu dropdown-menu-end">
                    <?php foreach ($notifications as $notification): ?>
                    <li>
                        <a class="dropdown-item" href="<?= $notification['link'] ?? '#' ?>">
                            <small class="text-muted"><?= $notification['time'] ?? '' ?></small>
                            <p class="mb-0"><?= htmlspecialchars($notification['message']) ?></p>
                        </a>
                    </li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <?php endif; ?>
            
            <!-- Dropdown de Usuario -->
            <div class="dropdown">
                <button class="btn user-dropdown dropdown-toggle" type="button" 
                        data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="bi bi-person-circle me-2"></i>
                    <?= htmlspecialchars($current_user['nombre'] ?? 'Usuario') ?>
                </button>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <a class="dropdown-item text-danger" href="<?= isset($base_url) ? $base_url : '' ?>/logout">
                            <i class="bi bi-box-arrow-right me-2"></i>Cerrar Sesión
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>