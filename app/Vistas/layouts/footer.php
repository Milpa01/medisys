<?php
/**
 * Footer - Pie de página del sitio (con modo nocturno inline)
 * Ruta: app/Vistas/layouts/footer.php
 */
if (!defined('APP_PATH')) exit('No direct script access allowed');
?>
    <!-- Footer -->
    <footer class="footer mt-auto py-3 bg-light">
        <div class="container-fluid px-4">
            <div class="row">
                <div class="col-md-6">
                    <p class="mb-0 text-muted">
                        © <?= date('Y') ?> MediSys - Sistema de Gestión Clínica
                    </p>
                </div>
                <div class="col-md-6 text-end">
                    <small class="text-muted">
                        Versión 1.0.0 | 
                        <a href="<?= isset($base_url) ? $base_url : '' ?>/documentacion" class="text-decoration-none">Documentación</a> | 
                        <a href="<?= isset($base_url) ? $base_url : '' ?>/soporte" class="text-decoration-none">Soporte</a>
                    </small>
                </div>
            </div>
        </div>
    </footer>
    
    <!-- jQuery (Para DataTables) -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" 
            integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" 
            integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>
    
    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
    
    <!-- Chart.js (Opcional para gráficos) -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    
    <!-- MODO NOCTURNO - Código inline para garantizar funcionamiento -->
    <script>
    (function() {
        'use strict';
        
        // Esperar a que el DOM esté completamente cargado
        document.addEventListener('DOMContentLoaded', function() {
            // Elementos del DOM
            const themeToggle = document.getElementById('themeToggle');
            const themeIcon = document.getElementById('themeIcon');
            const html = document.documentElement;
            
            // Función para actualizar el icono
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
            
            // Cargar tema guardado
            const savedTheme = localStorage.getItem('theme') || 'light';
            html.setAttribute('data-theme', savedTheme);
            updateThemeIcon(savedTheme);
            
            console.log('🌙 Tema cargado:', savedTheme);
            
            // Event listener para el botón
            if (themeToggle) {
                themeToggle.addEventListener('click', function(e) {
                    e.preventDefault();
                    
                    const currentTheme = html.getAttribute('data-theme');
                    const newTheme = currentTheme === 'light' ? 'dark' : 'light';
                    
                    // Aplicar nuevo tema
                    html.setAttribute('data-theme', newTheme);
                    localStorage.setItem('theme', newTheme);
                    
                    // Actualizar icono
                    updateThemeIcon(newTheme);
                    
                    // Transición suave
                    document.body.style.transition = 'all 0.3s ease';
                    
                    console.log('✅ Tema cambiado a:', newTheme);
                    
                    // Mostrar mensaje (opcional)
                    showThemeMessage(newTheme === 'dark' ? '🌙 Modo oscuro activado' : '☀️ Modo claro activado');
                });
                
                console.log('✅ Botón de tema inicializado correctamente');
            } else {
                console.warn('⚠️ No se encontró el botón con ID "themeToggle"');
            }
            
            // Función para mostrar mensaje
            function showThemeMessage(message) {
                let messageDiv = document.getElementById('theme-toast');
                
                if (!messageDiv) {
                    messageDiv = document.createElement('div');
                    messageDiv.id = 'theme-toast';
                    messageDiv.style.cssText = `
                        position: fixed;
                        bottom: 20px;
                        right: 20px;
                        background: var(--primary-color);
                        color: white;
                        padding: 12px 20px;
                        border-radius: 8px;
                        box-shadow: 0 4px 12px rgba(0,0,0,0.3);
                        z-index: 9999;
                        opacity: 0;
                        transition: opacity 0.3s ease;
                        font-size: 14px;
                        font-weight: 500;
                    `;
                    document.body.appendChild(messageDiv);
                }
                
                messageDiv.textContent = message;
                messageDiv.style.opacity = '1';
                
                setTimeout(() => {
                    messageDiv.style.opacity = '0';
                }, 2000);
            }
        });
    })();
    </script>
    
    <!-- Custom JS -->
    <script src="<?= isset($base_url) ? $base_url : '' ?>/public/js/admin.js"></script>
    
    <?php if (isset($custom_js)): ?>
        <?php foreach ($custom_js as $js): ?>
            <script src="<?= isset($base_url) ? $base_url : '' ?>/public/js/<?= $js ?>"></script>
        <?php endforeach; ?>
    <?php endif; ?>
</body>
</html>