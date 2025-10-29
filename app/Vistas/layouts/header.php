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
    <title><?= htmlspecialchars($title ?? 'MediSys - Sistema de Gestión Clínica') ?></title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="<?= isset($base_url) ? $base_url : '' ?>/public/img/favicon.ico">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    
    <!-- DataTables CSS (Opcional) -->
    <link href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    
    <!-- Custom CSS -->
    <link href="<?= isset($base_url) ? $base_url : '' ?>/public/css/admin.css" rel="stylesheet">
    
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
            margin: 0;
            padding: 0;
        }
        
        .top-navbar {
            background: var(--navbar-bg);
            padding: 1rem 2rem;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid var(--border-color);
            position: sticky;
            top: 0;
            z-index: 999;
        }
        
        .user-dropdown {
            background: var(--card-bg);
            border: 1px solid var(--border-color);
            border-radius: 0.5rem;
            padding: 0.5rem 1rem;
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
            transform: scale(1.1) rotate(15deg);
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
    </style>
</head>
