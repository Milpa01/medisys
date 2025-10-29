<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title ?? 'MediSys') ?></title>
    
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
        }
        
        body {
            background-color: #f8fafc;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .navbar {
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            width: var(--sidebar-width);
            background: linear-gradient(180deg, #1e293b, #334155);
            z-index: 1000;
            transition: transform 0.3s ease;
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
            background: white;
            padding: 1rem 2rem;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            display: flex;
            justify-content: between;
            align-items: center;
        }
        
        .content-wrapper {
            padding: 2rem;
        }
        
        .card {
            border: none;
            border-radius: 0.75rem;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            margin-bottom: 1.5rem;
        }
        
        .card-header {
            background: linear-gradient(135deg, var(--primary-light), white);
            border-bottom: 1px solid #e2e8f0;
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
        }
        
        .table th {
            background-color: var(--primary-light);
            border: none;
            font-weight: 600;
            color: var(--primary-dark);
        }
        
        .badge {
            border-radius: 0.375rem;
            padding: 0.375rem 0.75rem;
            font-weight: 500;
        }
        
        .user-dropdown {
            background: white;
            border-radius: 0.5rem;
            padding: 0.5rem 1rem;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
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
    </style>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <div class="sidebar-brand">
            <h4><i class="bi bi-heart-pulse-fill me-2"></i>MediSys</h4>
        </div>
        
        <nav class="sidebar-nav">
            <div class="nav-item">
                <a href="<?= Router::url('dashboard') ?>" class="nav-link">
                    <i class="bi bi-house-fill"></i>Dashboard
                </a>
            </div>
            
            <?php if (Auth::hasRole('administrador') || Auth::hasRole('secretario')): ?>
            <div class="nav-item">
                <a href="<?= Router::url('pacientes') ?>" class="nav-link">
                    <i class="bi bi-people-fill"></i>Pacientes
                </a>
            </div>
            <?php endif; ?>
            
            <div class="nav-item">
                <a href="<?= Router::url('citas') ?>" class="nav-link">
                    <i class="bi bi-calendar-check-fill"></i>Citas
                </a>
            </div>
            
            <?php if (Auth::hasRole('medico')): ?>
            <div class="nav-item">
                <a href="<?= Router::url('consultas') ?>" class="nav-link">
                    <i class="bi bi-clipboard2-pulse-fill"></i>Consultas
                </a>
            </div>
            <?php endif; ?>
            
            <?php if (Auth::hasRole('administrador')): ?>
            <div class="nav-item">
                <a href="<?= Router::url('medicos') ?>" class="nav-link">
                    <i class="bi bi-person-badge-fill"></i>Médicos
                </a>
            </div>
            
            <div class="nav-item">
                <a href="<?= Router::url('usuarios') ?>" class="nav-link">
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
                <h5 class="mb-0 ms-2">Bienvenido, <?= htmlspecialchars($current_user['nombre'] ?? 'Usuario') ?></h5>
            </div>
            
            <div class="dropdown">
                <button class="btn user-dropdown dropdown-toggle" type="button" data-bs-toggle="dropdown">
                    <i class="bi bi-person-circle me-2"></i>
                    <?= htmlspecialchars($current_user['nombre'] ?? 'Usuario') ?>
                </button>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="#"><i class="bi bi-person me-2"></i>Perfil</a></li>
                    <li><a class="dropdown-item" href="#"><i class="bi bi-gear me-2"></i>Configuración</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item" href="<?= Router::url('logout') ?>"><i class="bi bi-box-arrow-right me-2"></i>Cerrar Sesión</a></li>
                </ul>
            </div>
        </div>
        
        <!-- Content -->
        <div class="content-wrapper">
            <!-- Mensajes Flash -->
            <?= Flash::display() ?>
            
            <!-- Contenido de la página -->
            <?= $content ?>
        </div>
    </div>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Toggle sidebar en móvil
        document.getElementById('sidebarToggle')?.addEventListener('click', function() {
            document.getElementById('sidebar').classList.toggle('show');
        });
        
        // Marcar enlace activo
        const currentPath = window.location.pathname;
        document.querySelectorAll('.nav-link').forEach(link => {
            if (link.getAttribute('href') === currentPath) {
                link.classList.add('active');
            }
        });
        
        // Auto-dismiss alerts
        setTimeout(() => {
            document.querySelectorAll('.alert').forEach(alert => {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            });
        }, 5000);
    </script>
</body>
</html>