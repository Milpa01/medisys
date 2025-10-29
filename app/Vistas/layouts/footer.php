<?php
/**
 * Footer - Pie de página del sitio
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
                        <a href="#" class="text-decoration-none">Documentación</a> | 
                        <a href="#" class="text-decoration-none">Soporte</a>
                    </small>
                </div>
            </div>
        </div>
    </footer>
    
    <!-- jQuery (Opcional, para DataTables) -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- DataTables JS (Opcional) -->
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
    
    <!-- Chart.js (Opcional para gráficos) -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    
    <!-- Custom JS -->
    <script src="<?= isset($base_url) ? $base_url : '' ?>/public/js/admin.js"></script>
    
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
        if (themeToggle) {
            themeToggle.addEventListener('click', function() {
                const currentTheme = html.getAttribute('data-theme');
                const newTheme = currentTheme === 'light' ? 'dark' : 'light';
                
                html.setAttribute('data-theme', newTheme);
                localStorage.setItem('theme', newTheme);
                updateThemeIcon(newTheme);
                
                // Animación suave
                document.body.style.transition = 'all 0.3s ease';
            });
        }
        
        function updateThemeIcon(theme) {
            if (themeIcon) {
                if (theme === 'dark') {
                    themeIcon.className = 'bi bi-sun-fill';
                    themeToggle.title = 'Modo claro';
                } else {
                    themeIcon.className = 'bi bi-moon-stars-fill';
                    themeToggle.title = 'Modo oscuro';
                }
            }
        }
        
        // ==================== SIDEBAR MÓVIL ====================
        const sidebarToggle = document.getElementById('sidebarToggle');
        const sidebar = document.getElementById('sidebar');
        
        if (sidebarToggle && sidebar) {
            sidebarToggle.addEventListener('click', function() {
                sidebar.classList.toggle('show');
            });
            
            // Cerrar sidebar al hacer click fuera en móvil
            document.addEventListener('click', function(event) {
                if (window.innerWidth <= 768) {
                    const isClickInsideSidebar = sidebar.contains(event.target);
                    const isClickOnToggle = sidebarToggle.contains(event.target);
                    
                    if (!isClickInsideSidebar && !isClickOnToggle && sidebar.classList.contains('show')) {
                        sidebar.classList.remove('show');
                    }
                }
            });
        }
        
        // ==================== MARCAR ENLACE ACTIVO ====================
        const currentPath = window.location.pathname;
        document.querySelectorAll('.nav-link').forEach(link => {
            const linkHref = link.getAttribute('href');
            if (linkHref && (currentPath === linkHref || currentPath.includes(linkHref))) {
                link.classList.add('active');
            }
        });
        
        // ==================== AUTO-DISMISS ALERTS ====================
        setTimeout(() => {
            document.querySelectorAll('.alert').forEach(alert => {
                if (bootstrap.Alert) {
                    const bsAlert = new bootstrap.Alert(alert);
                    bsAlert.close();
                }
            });
        }, 5000);
        
        // ==================== TOOLTIPS ====================
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
        
        // ==================== POPOVERS ====================
        const popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'));
        popoverTriggerList.map(function (popoverTriggerEl) {
            return new bootstrap.Popover(popoverTriggerEl);
        });
        
        // ==================== CONFIRMACIÓN DE ELIMINACIÓN ====================
        document.querySelectorAll('[data-confirm]').forEach(element => {
            element.addEventListener('click', function(e) {
                const message = this.getAttribute('data-confirm') || '¿Está seguro de que desea continuar?';
                if (!confirm(message)) {
                    e.preventDefault();
                    e.stopPropagation();
                    return false;
                }
            });
        });
        
        // ==================== DATATABLE INICIALIZACIÓN ====================
        if (typeof $.fn.dataTable !== 'undefined') {
            $('.datatable').DataTable({
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/es-ES.json'
                },
                pageLength: 10,
                order: [[0, 'desc']],
                responsive: true
            });
        }
        
        // ==================== FORMATO DE NÚMEROS ====================
        function formatNumber(num) {
            return new Intl.NumberFormat('es-GT', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            }).format(num);
        }
        
        // ==================== FORMATO DE FECHA ====================
        function formatDate(dateString) {
            const date = new Date(dateString);
            return new Intl.DateTimeFormat('es-GT', {
                year: 'numeric',
                month: 'long',
                day: 'numeric'
            }).format(date);
        }
        
        // ==================== NOTIFICACIONES TOAST ====================
        function showToast(message, type = 'success') {
            const toastHtml = `
                <div class="toast align-items-center text-white bg-${type} border-0" role="alert" aria-live="assertive" aria-atomic="true">
                    <div class="d-flex">
                        <div class="toast-body">
                            ${message}
                        </div>
                        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
                    </div>
                </div>
            `;
            
            const toastContainer = document.getElementById('toastContainer') || createToastContainer();
            toastContainer.insertAdjacentHTML('beforeend', toastHtml);
            
            const toastElement = toastContainer.lastElementChild;
            const toast = new bootstrap.Toast(toastElement);
            toast.show();
            
            toastElement.addEventListener('hidden.bs.toast', function() {
                toastElement.remove();
            });
        }
        
        function createToastContainer() {
            const container = document.createElement('div');
            container.id = 'toastContainer';
            container.className = 'toast-container position-fixed top-0 end-0 p-3';
            container.style.zIndex = '9999';
            document.body.appendChild(container);
            return container;
        }
        
        // ==================== SCROLL TO TOP ====================
        const scrollToTopBtn = document.createElement('button');
        scrollToTopBtn.innerHTML = '<i class="bi bi-arrow-up"></i>';
        scrollToTopBtn.className = 'btn btn-primary rounded-circle position-fixed bottom-0 end-0 m-4';
        scrollToTopBtn.style.display = 'none';
        scrollToTopBtn.style.width = '50px';
        scrollToTopBtn.style.height = '50px';
        scrollToTopBtn.style.zIndex = '1000';
        scrollToTopBtn.onclick = () => window.scrollTo({ top: 0, behavior: 'smooth' });
        document.body.appendChild(scrollToTopBtn);
        
        window.addEventListener('scroll', function() {
            if (window.pageYOffset > 300) {
                scrollToTopBtn.style.display = 'block';
            } else {
                scrollToTopBtn.style.display = 'none';
            }
        });
        
        // ==================== LOADING SPINNER ====================
        function showLoading() {
            const loadingHtml = `
                <div id="loadingOverlay" class="position-fixed top-0 start-0 w-100 h-100 d-flex align-items-center justify-content-center" style="background: rgba(0,0,0,0.5); z-index: 9999;">
                    <div class="spinner-border text-light" role="status" style="width: 3rem; height: 3rem;">
                        <span class="visually-hidden">Cargando...</span>
                    </div>
                </div>
            `;
            document.body.insertAdjacentHTML('beforeend', loadingHtml);
        }
        
        function hideLoading() {
            const overlay = document.getElementById('loadingOverlay');
            if (overlay) {
                overlay.remove();
            }
        }
        
        // ==================== ATAJOS DE TECLADO ====================
        document.addEventListener('keydown', function(e) {
            // Ctrl/Cmd + K para buscar
            if ((e.ctrlKey || e.metaKey) && e.key === 'k') {
                e.preventDefault();
                const searchInput = document.querySelector('input[type="search"], input[name="search"]');
                if (searchInput) {
                    searchInput.focus();
                }
            }
            
            // Ctrl/Cmd + N para nuevo registro (si existe el botón)
            if ((e.ctrlKey || e.metaKey) && e.key === 'n') {
                const newButton = document.querySelector('[href*="/crear"], [href*="/nuevo"]');
                if (newButton) {
                    e.preventDefault();
                    newButton.click();
                }
            }
        });
        
        console.log('%c🏥 MediSys v1.0.0', 'color: #0ea5e9; font-size: 20px; font-weight: bold;');
        console.log('%cSistema de Gestión Clínica', 'color: #64748b; font-size: 14px;');
    </script>
</body>
</html>