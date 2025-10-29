<?php

if (!defined('APP_PATH')) exit('No direct script access allowed');

// Obtener el rol del usuario
$rol_id = isset($current_user['rol_id']) ? $current_user['rol_id'] : 0;
$is_admin = ($rol_id == 1);
$is_medico = ($rol_id == 2);
$is_secretario = ($rol_id == 3);
?>
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
        
        <?php if ($is_admin || $is_secretario): ?>
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
                <?php 
                // Validar que citas_pendientes existe y es un número
                $num_citas_pendientes = 0;
                if (isset($citas_pendientes)) {
                    // Si es un array, contar sus elementos
                    if (is_array($citas_pendientes)) {
                        $num_citas_pendientes = count($citas_pendientes);
                    } 
                    // Si es un número, usarlo directamente
                    elseif (is_numeric($citas_pendientes)) {
                        $num_citas_pendientes = (int)$citas_pendientes;
                    }
                }
                
                if ($num_citas_pendientes > 0): 
                ?>
                    <span class="nav-badge pulse"><?= $num_citas_pendientes ?></span>
                <?php endif; ?>
            </a>
        </div>
        
        <?php if ($is_medico): ?>
        <div class="nav-item">
            <a href="<?= isset($base_url) ? $base_url : '' ?>/consultas" class="nav-link" data-page="consultas">
                <i class="bi bi-clipboard2-pulse-fill"></i>
                <span>Consultas</span>
            </a>
        </div>
        
        <div class="nav-item">
            <a href="<?= isset($base_url) ? $base_url : '' ?>/expedientes" class="nav-link" data-page="expedientes">
                <i class="bi bi-folder2-open"></i>
                <span>Expedientes</span>
            </a>
        </div>
        <?php endif; ?>
        
        <!-- Administración (Solo Admin) -->
        <?php if ($is_admin): ?>
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
        
        <?php endif; ?>
    </nav>
</div>