<?php
/**
 * Layout Principal - main.php
 * Ruta: app/Vistas/layouts/main.php
 */
if (!defined('APP_PATH')) exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html lang="es" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title ?? 'MediSys - Sistema de Gestión Clínica') ?></title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    
    <style>
        :root {
            --primary-color: #0ea5e9;
            --primary-dark: #0284c7;
            --primary-light: #e0f2fe;
            --sidebar-width: 250px;
            --bg-color: #f8fafc;
            --card-bg: #ffffff;
            --text-color: #1e293b;
            --text-muted: #64748b;
            --border-color: #e2e8f0;
            --sidebar-bg: linear-gradient(180deg, #1e293b, #334155);
            --navbar-bg: #ffffff;
        }
        
        [data-theme="dark"] {
            --bg-color: #0f172a;
            --card-bg: #1e293b;
            --text-color: #e2e8f0;
            --text-muted: #94a3b8;
            --border-color: #334155;
            --sidebar-bg: linear-gradient(180deg, #020617, #0f172a);
            --navbar-bg: #1e293b;
        }
        
        * {
            transition: background-color 0.3s ease, color 0.3s ease;
        }
        
        body {
            background-color: var(--bg-color);
            color: var(--text-color);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            width: var(--sidebar-width);
            background: var(--sidebar-bg);
            z-index: 1000;
            transition: transform 0.3s ease;
            overflow-y: auto;
        }
        
        .sidebar-brand {
            padding: 1.5rem 1rem;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }
        
        .sidebar-brand h4 {
            color: white;
            margin: 0;
            font-weight: 600;
        }
        
        .sidebar-nav {
            padding: 1rem 0;
        }
        
        .nav-item {
            margin: 0.25rem 1rem;
        }
        
        .nav-link {
            color: #cbd5e1;
            padding: 0.75rem 1rem;
            border-radius: 0.5rem;
            transition: all 0.3s ease;
            text-decoration: none;
            display: flex;
            align-items: center;
        }
        
        .nav-link:hover {
            background-color: rgba(255,255,255,0.1);
            color: white;
            transform: translateX(4px);
        }
        
        .nav-link.active {
            background-color: var(--primary-color);
            color: white;
        }
        
        .nav-link i {
            width: 20px;
            margin-right: 0.75rem;
        }
        
        .main-content {
            margin-left: var(--sidebar-width);
            min-height: 100vh;
        }
        
        .top-navbar {
            background: var(--navbar-bg);
            padding: 1rem 2rem;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid var(--border-color);
        }
        
        .content-wrapper {
            padding: 2rem;
        }
        
        .card {
            background-color: var(--card-bg);
            border: 1px solid var(--border-color);
            border-radius: 0.75rem;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            margin-bottom: 1.5rem;
        }
        
        .card-header {
            background: var(--card-bg);
            border-bottom: 1px solid var(--border-color);
            padding: 1.25rem 1.5rem;
            border-radius: 0.75rem 0.75rem 0 0 !important;
        }
        
        .btn-primary {
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
            border: none;
            border-radius: 0.5rem;
            padding: 0.5rem 1.5rem;
            font-weight: 500;
        }
        
        .btn-primary:hover {
            background: linear-gradient(135deg, var(--primary-dark), #075985);
            transform: translateY(-1px);
        }
        
        .stat-card {
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
            color: white;
            border-radius: 1rem;
            padding: 1.5rem;
            margin-bottom: 1rem;
        }
        
        .stat-card .stat-number {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }
        
        .stat-card .stat-label {
            opacity: 0.9;
            font-size: 0.9rem;
        }
        
        .table {
            border-radius: 0.5rem;
            overflow: hidden;
            color: var(--text-color);
        }
        
        .table th {
            background-color: var(--primary-light);
            border: none;
            font-weight: 600;
            color: var(--primary-dark);
        }
        
        [data-theme="dark"] .table th {
            background-color: #334155;
            color: var(--primary-light);
        }
        
        .badge {
            border-radius: 0.375rem;
            padding: 0.375rem 0.75rem;
            font-weight: 500;
        }
        
        .user-dropdown {
            background: var(--card-bg);
            border: 1px solid var(--border-color);
            border-radius: 0.5rem;
            padding: 0.5rem 1rem;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            color: var(--text-color);
        }
        
        .theme-toggle {
            background: var(--card-bg);
            border: 1px solid var(--border-color);
            border-radius: 50%;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .theme-toggle:hover {
            transform: scale(1.1);
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
        }
        
        .dropdown-menu {
            background: var(--card-bg);
            border: 1px solid var(--border-color);
        }
        
        .dropdown-item {
            color: var(--text-color);
        }
        
        .dropdown-item:hover {
            background-color: var(--primary-light);
        }
        
        [data-theme="dark"] .dropdown-item:hover {
            background-color: #334155;
        }
        
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
            }
            
            .sidebar.show {
                transform: translateX(0);
            }
            
            .main-content {
                margin-left: 0;
            }
        }
        
        /* Scrollbar personalizado */
        ::-webkit-scrollbar {
            width: 8px;
        }
        
        ::-webkit-scrollbar-track {
            background: var(--bg-color);
        }
        
        ::-webkit-scrollbar-thumb {
            background: var(--border-color);
            border-radius: 4px;
        }
        
        ::-webkit-scrollbar-thumb:hover {
            background: var(--text-muted);
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <div class="sidebar-brand">
            <h4>
                <i class="bi bi-heart-pulse-fill me-2"></i>
                MediSys
            </h4>
        </div>
        
        <nav class="sidebar-nav">
            <div class="nav-item">
                <a href="<?= isset($base_url) ? $base_url : '' ?>/dashboard" class="nav-link">
                    <i class="bi bi-house-fill"></i>Dashboard
                </a>
            </div>
            
            <?php if (isset($current_user) && ($current_user['rol_id'] ?? 0) == 1 || ($current_user['rol_id'] ?? 0) == 3): ?>
            <div class="nav-item">
                <a href="<?= isset($base_url) ? $base_url : '' ?>/pacientes" class="nav-link">
                    <i class="bi bi-people-fill"></i>Pacientes
                </a>
            </div>
            <?php endif; ?>
            
            <div class="nav-item">
                <a href="<?= isset($base_url) ? $base_url : '' ?>/citas" class="nav-link">
                    <i class="bi bi-calendar-check-fill"></i>Citas
                </a>
            </div>
            
            <?php if (isset($current_user) && ($current_user['rol_id'] ?? 0) == 2): ?>
            <div class="nav-item">
                <a href="<?= isset($base_url) ? $base_url : '' ?>/consultas" class="nav-link">
                    <i class="bi bi-clipboard2-pulse-fill"></i>Consultas
                </a>
            </div>
            <?php endif; ?>
            
            <?php if (isset($current_user) && ($current_user['rol_id'] ?? 0) == 1): ?>
            <div class="nav-item">
                <a href="<?= isset($base_url) ? $base_url : '' ?>/medicos" class="nav-link">
                    <i class="bi bi-person-badge-fill"></i>Médicos
                </a>
            </div>
            
            <div class="nav-item">
                <a href="<?= isset($base_url) ? $base_url : '' ?>/usuarios" class="nav-link">
                    <i class="bi bi-gear-fill"></i>Usuarios
                </a>
            </div>
            <?php endif; ?>
        </nav>
    </div>
    
    <!-- Main Content -->
    <div class="main-content">
        <!-- Top Navbar -->
        <div class="top-navbar">
            <div class="d-flex align-items-center">
                <button class="btn btn-link d-md-none" id="sidebarToggle">
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
                
                <!-- Dropdown de Usuario -->
                <div class="dropdown">
                    <button class="btn user-dropdown dropdown-toggle" type="button" data-bs-toggle="dropdown">
                        <i class="bi bi-person-circle me-2"></i>
                        <?= htmlspecialchars($current_user['nombre'] ?? 'Usuario') ?>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li>
                            <a class="dropdown-item" href="#">
                                <i class="bi bi-person me-2"></i>Perfil
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="#">
                                <i class="bi bi-gear me-2"></i>Configuración
                            </a>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <a class="dropdown-item" href="<?= isset($base_url) ? $base_url : '' ?>/logout">
                                <i class="bi bi-box-arrow-right me-2"></i>Cerrar Sesión
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        
        <!-- Content -->
        <div class="content-wrapper">
            <!-- Mensajes Flash -->
            <?php if (isset($_SESSION['flash'])): ?>
                <?php foreach ($_SESSION['flash'] as $type => $messages): ?>
                    <?php foreach ($messages as $message): ?>
                        <div class="alert alert-<?= $type === 'error' ? 'danger' : $type ?> alert-dismissible fade show" role="alert">
                            <?= htmlspecialchars($message) ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endforeach; ?>
                <?php endforeach; ?>
                <?php unset($_SESSION['flash']); ?>
            <?php endif; ?>
            
            <!-- Contenido de la página -->
            <?= $content ?? '' ?>
        </div>
    </div>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // ==================== MODO NOCTURNO ====================
        const themeToggle = document.getElementById('themeToggle');
        const themeIcon = document.getElementById('themeIcon');
        const html = document.documentElement;
        
        // Cargar tema guardado
        const currentTheme = localStorage.getItem('theme') || 'light';
        html.setAttribute('data-theme', currentTheme);
        updateThemeIcon(currentTheme);
        
        // Toggle tema
        themeToggle.addEventListener('click', function() {
            const currentTheme = html.getAttribute('data-theme');
            const newTheme = currentTheme === 'light' ? 'dark' : 'light';
            
            html.setAttribute('data-theme', newTheme);
            localStorage.setItem('theme', newTheme);
            updateThemeIcon(newTheme);
        });
        
        function updateThemeIcon(theme) {
            if (theme === 'dark') {
                themeIcon.className = 'bi bi-sun-fill';
                themeToggle.title = 'Modo claro';
            } else {
                themeIcon.className = 'bi bi-moon-stars-fill';
                themeToggle.title = 'Modo oscuro';
            }
        }
        
        // ==================== SIDEBAR MÓVIL ====================
        const sidebarToggle = document.getElementById('sidebarToggle');
        const sidebar = document.getElementById('sidebar');
        
        if (sidebarToggle) {
            sidebarToggle.addEventListener('click', function() {
                sidebar.classList.toggle('show');
            });
            
            // Cerrar sidebar al hacer click fuera en móvil
            document.addEventListener('click', function(event) {
                const isClickInsideSidebar = sidebar.contains(event.target);
                const isClickOnToggle = sidebarToggle.contains(event.target);
                
                if (!isClickInsideSidebar && !isClickOnToggle && sidebar.classList.contains('show')) {
                    sidebar.classList.remove('show');
                }
            });
        }
        
        // ==================== MARCAR ENLACE ACTIVO ====================
        const currentPath = window.location.pathname;
        document.querySelectorAll('.nav-link').forEach(link => {
            const linkPath = new URL(link.href).pathname;
            if (linkPath === currentPath || currentPath.includes(linkPath.split('/').pop())) {
                link.classList.add('active');
            }
        });
        
        // ==================== AUTO-DISMISS ALERTS ====================
        setTimeout(() => {
            document.querySelectorAll('.alert').forEach(alert => {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            });
        }, 5000);
        
        // ==================== TOOLTIPS ====================
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
        
        // ==================== CONFIRMACIÓN DE ELIMINACIÓN ====================
        document.querySelectorAll('[data-confirm]').forEach(element => {
            element.addEventListener('click', function(e) {
                const message = this.getAttribute('data-confirm') || '¿Está seguro de que desea continuar?';
                if (!confirm(message)) {
                    e.preventDefault();
                    e.stopPropagation();
                }
            });
        });
    </script>
</body>
</html>