<?php
/**
 * Sidebar - Barra lateral de navegación
 * Ruta: app/Vistas/layouts/sidebar.php
 */
if (!defined('APP_PATH')) exit('No direct script access allowed');
?>
<style>
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
        display: flex;
        align-items: center;
    }
    
    .sidebar-brand .brand-icon {
        animation: pulse 2s infinite;
    }
    
    @keyframes pulse {
        0%, 100% { transform: scale(1); }
        50% { transform: scale(1.1); }
    }
    
    .sidebar-nav {
        padding: 1rem 0;
    }
    
    .nav-section-title {
        color: #94a3b8;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        padding: 0.75rem 1.5rem 0.5rem;
        letter-spacing: 0.5px;
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
        position: relative;
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
    
    .nav-link.active::before {
        content: '';
        position: absolute;
        left: 0;
        top: 50%;
        transform: translateY(-50%);
        width: 4px;
        height: 70%;
        background: white;
        border-radius: 0 4px 4px 0;
    }
    
    .nav-link i {
        width: 20px;
        margin-right: 0.75rem;
        font-size: 1.1rem;
    }
    
    .nav-badge {
        margin-left: auto;
        font-size: 0.7rem;
        padding: 0.25rem 0.5rem;
        border-radius: 0.25rem;
        background: rgba(255,255,255,0.2);
    }
    
    @media (max-width: 768px) {
        .sidebar {
            transform: translateX(-100%);
        }
        
        .sidebar.show {
            transform: translateX(0);
        }
    }
</style>

<div class="sidebar" id="sidebar">
    <div class="sidebar-brand">
        <h4>
            <i class="bi bi-heart-pulse-fill me-2 brand-icon"></i>
            MediSys
        </h4>
    </div>
    
    <nav class="sidebar-nav">
        <!-- Dashboard -->
        <div class="nav-section-title">Principal</div>
        <div class="nav-item">
            <a href="<?= isset($base_url) ? $base_url : '' ?>/dashboard" class="nav-link" data-page="dashboard">
                <i class="bi bi-house-fill"></i>
                <span>Dashboard</span>
            </a>
        </div>
        
        <!-- Gestión Médica -->
        <div class="nav-section-title">Gestión Médica</div>
        
        <?php if (isset($current_user) && in_array($current_user['rol_id'] ?? 0, [1, 3])): ?>
        <div class="nav-item">
            <a href="<?= isset($base_url) ? $base_url : '' ?>/pacientes" class="nav-link" data-page="pacientes">
                <i class="bi bi-people-fill"></i>
                <span>Pacientes</span>
            </a>
        </div>
        <?php endif; ?>
        
        <div class="nav-item">
            <a href="<?= isset($base_url) ? $base_url : '' ?>/citas" class="nav-link" data-page="citas">
                <i class="bi bi-calendar-check-fill"></i>
                <span>Citas</span>
                <?php if (isset($citas_pendientes) && $citas_pendientes > 0): ?>
                    <span class="nav-badge"><?= $citas_pendientes ?></span>
                <?php endif; ?>
            </a>
        </div>
        
        <?php if (isset($current_user) && ($current_user['rol_id'] ?? 0) == 2): ?>
        <div class="nav-item">
            <a href="<?= isset($base_url) ? $base_url : '' ?>/consultas" class="nav-link" data-page="consultas">
                <i class="bi bi-clipboard2-pulse-fill"></i>
                <span>Consultas</span>
            </a>
        </div>
        <?php endif; ?>
        
        <!-- Administración (Solo Admin) -->
        <?php if (isset($current_user) && ($current_user['rol_id'] ?? 0) == 1): ?>
        <div class="nav-section-title">Administración</div>
        
        <div class="nav-item">
            <a href="<?= isset($base_url) ? $base_url : '' ?>/medicos" class="nav-link" data-page="medicos">
                <i class="bi bi-person-badge-fill"></i>
                <span>Médicos</span>
            </a>
        </div>
        
        <div class="nav-item">
            <a href="<?= isset($base_url) ? $base_url : '' ?>/usuarios" class="nav-link" data-page="usuarios">
                <i class="bi bi-people"></i>
                <span>Usuarios</span>
            </a>
        </div>
        
        <div class="nav-item">
            <a href="<?= isset($base_url) ? $base_url : '' ?>/especialidades" class="nav-link" data-page="especialidades">
                <i class="bi bi-heart-pulse"></i>
                <span>Especialidades</span>
            </a>
        </div>
        
        <div class="nav-item">
            <a href="<?= isset($base_url) ? $base_url : '' ?>/reportes" class="nav-link" data-page="reportes">
                <i class="bi bi-graph-up"></i>
                <span>Reportes</span>
            </a>
        </div>
        <?php endif; ?>
        
        <!-- Configuración -->
        <div class="nav-section-title">Sistema</div>
        
        <div class="nav-item">
            <a href="<?= isset($base_url) ? $base_url : '' ?>/perfil" class="nav-link" data-page="perfil">
                <i class="bi bi-person-circle"></i>
                <span>Mi Perfil</span>
            </a>
        </div>
        
        <?php if (isset($current_user) && ($current_user['rol_id'] ?? 0) == 1): ?>
        <div class="nav-item">
            <a href="<?= isset($base_url) ? $base_url : '' ?>/configuracion" class="nav-link" data-page="configuracion">
                <i class="bi bi-gear-fill"></i>
                <span>Configuración</span>
            </a>
        </div>
        
        <div class="nav-item">
            <a href="<?= isset($base_url) ? $base_url : '' ?>/auditoria" class="nav-link" data-page="auditoria">
                <i class="bi bi-shield-check"></i>
                <span>Auditoría</span>
            </a>
        </div>
        <?php endif; ?>
        
        <div class="nav-item">
            <a href="<?= isset($base_url) ? $base_url : '' ?>/ayuda" class="nav-link" data-page="ayuda">
                <i class="bi bi-question-circle"></i>
                <span>Ayuda</span>
            </a>
        </div>
        
        <!-- Cerrar Sesión -->
        <div class="nav-item mt-3">
            <a href="<?= isset($base_url) ? $base_url : '' ?>/logout" class="nav-link" style="color: #f87171;">
                <i class="bi bi-box-arrow-right"></i>
                <span>Cerrar Sesión</span>
            </a>
        </div>
    </nav>
</div>