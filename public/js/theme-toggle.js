/**
 * MediSys - Sistema de Gestión Clínica
 * Archivo: theme-toggle.js
 * Descripción: Funcionalidad de modo nocturno simplificada
 * Versión: 1.0.0
 */

(function() {
    'use strict';
    
    // ==================== MODO NOCTURNO ====================
    
    // Elementos del DOM
    const themeToggle = document.getElementById('themeToggle');
    const themeIcon = document.getElementById('themeIcon');
    const html = document.documentElement;
    
    // Cargar tema guardado al iniciar
    function initTheme() {
        const savedTheme = localStorage.getItem('theme') || 'light';
        html.setAttribute('data-theme', savedTheme);
        updateThemeIcon(savedTheme);
        console.log('Tema cargado:', savedTheme);
    }
    
    // Actualizar el icono según el tema
    function updateThemeIcon(theme) {
        if (themeIcon) {
            if (theme === 'dark') {
                themeIcon.className = 'bi bi-sun-fill';
                if (themeToggle) themeToggle.title = 'Modo claro';
            } else {
                themeIcon.className = 'bi bi-moon-stars-fill';
                if (themeToggle) themeToggle.title = 'Modo oscuro';
            }
        }
    }
    
    // Cambiar tema
    function toggleTheme() {
        const currentTheme = html.getAttribute('data-theme');
        const newTheme = currentTheme === 'light' ? 'dark' : 'light';
        
        // Aplicar nuevo tema
        html.setAttribute('data-theme', newTheme);
        localStorage.setItem('theme', newTheme);
        
        // Actualizar icono
        updateThemeIcon(newTheme);
        
        // Animación suave
        document.body.style.transition = 'all 0.3s ease';
        
        console.log('Tema cambiado a:', newTheme);
        
        // Mostrar mensaje (opcional)
        showMessage(newTheme === 'dark' ? 'Modo oscuro activado' : 'Modo claro activado');
    }
    
    // Mostrar mensaje simple
    function showMessage(message) {
        // Crear o actualizar el mensaje
        let messageElement = document.getElementById('theme-message');
        
        if (!messageElement) {
            messageElement = document.createElement('div');
            messageElement.id = 'theme-message';
            messageElement.style.cssText = `
                position: fixed;
                bottom: 20px;
                right: 20px;
                background: var(--primary-color);
                color: white;
                padding: 12px 20px;
                border-radius: 8px;
                box-shadow: 0 4px 12px rgba(0,0,0,0.2);
                z-index: 9999;
                opacity: 0;
                transition: opacity 0.3s ease;
            `;
            document.body.appendChild(messageElement);
        }
        
        messageElement.textContent = message;
        messageElement.style.opacity = '1';
        
        // Ocultar después de 2 segundos
        setTimeout(() => {
            messageElement.style.opacity = '0';
        }, 2000);
    }
    
    // Event listener para el botón
    if (themeToggle) {
        themeToggle.addEventListener('click', function(e) {
            e.preventDefault();
            toggleTheme();
        });
        
        console.log('Botón de tema inicializado correctamente');
    } else {
        console.warn('No se encontró el botón de tema con ID "themeToggle"');
    }
    
    // Inicializar tema al cargar
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initTheme);
    } else {
        initTheme();
    }
    
    // Detectar cambio de preferencia del sistema (opcional)
    if (window.matchMedia) {
        const darkModeQuery = window.matchMedia('(prefers-color-scheme: dark)');
        
        darkModeQuery.addEventListener('change', (e) => {
            // Solo cambiar si el usuario no ha establecido una preferencia
            if (!localStorage.getItem('theme')) {
                const newTheme = e.matches ? 'dark' : 'light';
                html.setAttribute('data-theme', newTheme);
                updateThemeIcon(newTheme);
            }
        });
    }
    
})();
