
'use strict';

// ==================== CONFIGURACIÓN GLOBAL ====================
const MediSys = {
    version: '1.0.0',
    theme: localStorage.getItem('theme') || 'light',
    sidebarCollapsed: localStorage.getItem('sidebarCollapsed') === 'true',
    
    // Inicializar aplicación
    init() {
        console.log('%c🏥 MediSys v' + this.version, 'color: #0ea5e9; font-size: 20px; font-weight: bold;');
        console.log('%cSistema de Gestión Clínica', 'color: #64748b; font-size: 14px;');
        
        this.initTheme();
        this.initSidebar();
        this.initNavigation();
        this.initAlerts();
        this.initTooltips();
        this.initPopovers();
        this.initConfirmDialogs();
        this.initDataTables();
        this.initScrollToTop();
        this.initKeyboardShortcuts();
    },
    
    // ==================== GESTIÓN DE TEMA ====================
    initTheme() {
        const themeToggle = document.getElementById('themeToggle');
        const themeIcon = document.getElementById('themeIcon');
        const html = document.documentElement;
        
        // Aplicar tema guardado
        html.setAttribute('data-theme', this.theme);
        this.updateThemeIcon(this.theme, themeIcon, themeToggle);
        
        // Event listener para cambio de tema
        if (themeToggle) {
            themeToggle.addEventListener('click', () => {
                const currentTheme = html.getAttribute('data-theme');
                const newTheme = currentTheme === 'light' ? 'dark' : 'light';
                
                html.setAttribute('data-theme', newTheme);
                localStorage.setItem('theme', newTheme);
                this.theme = newTheme;
                this.updateThemeIcon(newTheme, themeIcon, themeToggle);
                
                // Animación suave
                document.body.style.transition = 'all 0.3s ease';
                
                // Mostrar notificación (opcional, comentar si da problemas)
                try {
                    if (typeof this.showToast === 'function') {
                        this.showToast(`Tema ${newTheme === 'dark' ? 'oscuro' : 'claro'} activado`, 'success');
                    }
                } catch(e) {
                    console.log('Tema cambiado a:', newTheme);
                }
            });
        }
    },
    
    updateThemeIcon(theme, iconElement, buttonElement) {
        if (iconElement && buttonElement) {
            if (theme === 'dark') {
                iconElement.className = 'bi bi-sun-fill';
                buttonElement.title = 'Modo claro';
            } else {
                iconElement.className = 'bi bi-moon-stars-fill';
                buttonElement.title = 'Modo oscuro';
            }
        }
    },
    
    // ==================== GESTIÓN DEL SIDEBAR ====================
    initSidebar() {
        const sidebarToggle = document.getElementById('sidebarToggle');
        const sidebar = document.getElementById('sidebar');
        const mainContent = document.querySelector('.main-content');
        
        if (sidebarToggle && sidebar) {
            // Toggle sidebar en móvil
            sidebarToggle.addEventListener('click', (e) => {
                e.stopPropagation();
                sidebar.classList.toggle('show');
                
                // Crear overlay para móvil
                if (window.innerWidth <= 768) {
                    this.toggleSidebarOverlay(sidebar.classList.contains('show'));
                }
            });
            
            // Cerrar sidebar al hacer clic fuera en móvil
            document.addEventListener('click', (event) => {
                if (window.innerWidth <= 768) {
                    const isClickInsideSidebar = sidebar.contains(event.target);
                    const isClickOnToggle = sidebarToggle.contains(event.target);
                    
                    if (!isClickInsideSidebar && !isClickOnToggle && sidebar.classList.contains('show')) {
                        sidebar.classList.remove('show');
                        this.toggleSidebarOverlay(false);
                    }
                }
            });
            
            // Cerrar sidebar con tecla ESC
            document.addEventListener('keydown', (e) => {
                if (e.key === 'Escape' && sidebar.classList.contains('show')) {
                    sidebar.classList.remove('show');
                    this.toggleSidebarOverlay(false);
                }
            });
        }
        
        // Gestionar submenus
        document.querySelectorAll('.nav-link.has-submenu').forEach(link => {
            link.addEventListener('click', (e) => {
                e.preventDefault();
                const submenu = link.nextElementSibling;
                
                if (submenu && submenu.classList.contains('nav-submenu')) {
                    submenu.classList.toggle('show');
                    link.classList.toggle('open');
                }
            });
        });
    },
    
    toggleSidebarOverlay(show) {
        let overlay = document.querySelector('.sidebar-overlay');
        
        if (show && !overlay) {
            overlay = document.createElement('div');
            overlay.className = 'sidebar-overlay';
            document.body.appendChild(overlay);
            
            setTimeout(() => overlay.classList.add('show'), 10);
            
            overlay.addEventListener('click', () => {
                document.getElementById('sidebar').classList.remove('show');
                this.toggleSidebarOverlay(false);
            });
        } else if (!show && overlay) {
            overlay.classList.remove('show');
            setTimeout(() => overlay.remove(), 300);
        }
    },
    
    // ==================== NAVEGACIÓN ACTIVA ====================
    initNavigation() {
        const currentPath = window.location.pathname;
        const navLinks = document.querySelectorAll('.nav-link');
        
        navLinks.forEach(link => {
            const linkHref = link.getAttribute('href');
            
            if (linkHref) {
                // Comparación exacta o por segmento de ruta
                if (currentPath === linkHref || 
                    currentPath.includes(linkHref.split('/').pop()) ||
                    (linkHref !== '#' && currentPath.startsWith(linkHref))) {
                    link.classList.add('active');
                    
                    // Si está en un submenu, abrir el submenu padre
                    const parentSubmenu = link.closest('.nav-submenu');
                    if (parentSubmenu) {
                        parentSubmenu.classList.add('show');
                        const parentLink = parentSubmenu.previousElementSibling;
                        if (parentLink) {
                            parentLink.classList.add('open');
                        }
                    }
                }
            }
        });
    },
    
    // ==================== ALERTAS AUTO-DISMISS ====================
    initAlerts() {
        setTimeout(() => {
            document.querySelectorAll('.alert:not(.alert-permanent)').forEach(alert => {
                if (bootstrap.Alert) {
                    const bsAlert = new bootstrap.Alert(alert);
                    bsAlert.close();
                }
            });
        }, 5000);
    },
    
    // ==================== TOOLTIPS ====================
    initTooltips() {
        const tooltipTriggerList = [].slice.call(
            document.querySelectorAll('[data-bs-toggle="tooltip"]')
        );
        
        tooltipTriggerList.map(tooltipTriggerEl => {
            return new bootstrap.Tooltip(tooltipTriggerEl, {
                trigger: 'hover',
                placement: 'top'
            });
        });
    },
    
    // ==================== POPOVERS ====================
    initPopovers() {
        const popoverTriggerList = [].slice.call(
            document.querySelectorAll('[data-bs-toggle="popover"]')
        );
        
        popoverTriggerList.map(popoverTriggerEl => {
            return new bootstrap.Popover(popoverTriggerEl, {
                trigger: 'focus'
            });
        });
    },
    
    // ==================== CONFIRMACIÓN DE ACCIONES ====================
    initConfirmDialogs() {
        document.querySelectorAll('[data-confirm]').forEach(element => {
            element.addEventListener('click', function(e) {
                const message = this.getAttribute('data-confirm') || 
                               '¿Está seguro de que desea continuar?';
                
                if (!confirm(message)) {
                    e.preventDefault();
                    e.stopPropagation();
                    return false;
                }
            });
        });
    },
    
    // ==================== DATATABLES ====================
    initDataTables() {
        if (typeof $.fn.dataTable !== 'undefined') {
            $('.datatable').each(function() {
                const table = $(this);
                
                table.DataTable({
                    language: {
                        url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/es-ES.json'
                    },
                    pageLength: parseInt(table.data('page-length')) || 10,
                    order: [[0, 'desc']],
                    responsive: true,
                    dom: '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>' +
                         '<"row"<"col-sm-12"tr>>' +
                         '<"row"<"col-sm-12 col-md-5"i><"col-sm-12 col-md-7"p>>',
                    drawCallback: function() {
                        // Re-inicializar tooltips después de dibujar la tabla
                        MediSys.initTooltips();
                    }
                });
            });
        }
    },
    
    // ==================== SCROLL TO TOP ====================
    initScrollToTop() {
        // Crear botón si no existe
        let scrollBtn = document.getElementById('scrollToTop');
        
        if (!scrollBtn) {
            scrollBtn = document.createElement('button');
            scrollBtn.id = 'scrollToTop';
            scrollBtn.innerHTML = '<i class="bi bi-arrow-up"></i>';
            scrollBtn.className = 'btn btn-primary rounded-circle position-fixed';
            scrollBtn.style.cssText = `
                bottom: 2rem;
                right: 2rem;
                width: 50px;
                height: 50px;
                display: none;
                z-index: 1000;
                box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            `;
            document.body.appendChild(scrollBtn);
        }
        
        // Mostrar/ocultar botón según scroll
        window.addEventListener('scroll', () => {
            if (window.pageYOffset > 300) {
                scrollBtn.style.display = 'block';
            } else {
                scrollBtn.style.display = 'none';
            }
        });
        
        // Scroll suave al hacer clic
        scrollBtn.addEventListener('click', () => {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        });
    },
    
    // ==================== ATAJOS DE TECLADO ====================
    initKeyboardShortcuts() {
        document.addEventListener('keydown', (e) => {
            // Ctrl/Cmd + K para buscar
            if ((e.ctrlKey || e.metaKey) && e.key === 'k') {
                e.preventDefault();
                const searchInput = document.querySelector('input[type="search"], input[name="search"]');
                if (searchInput) {
                    searchInput.focus();
                    searchInput.select();
                }
            }
            
            // Ctrl/Cmd + N para nuevo registro
            if ((e.ctrlKey || e.metaKey) && e.key === 'n') {
                const newButton = document.querySelector('[href*="/crear"], [href*="/nuevo"]');
                if (newButton) {
                    e.preventDefault();
                    newButton.click();
                }
            }
            
            // Ctrl/Cmd + B para toggle sidebar en desktop
            if ((e.ctrlKey || e.metaKey) && e.key === 'b' && window.innerWidth > 768) {
                e.preventDefault();
                const sidebar = document.getElementById('sidebar');
                if (sidebar) {
                    sidebar.classList.toggle('collapsed');
                    localStorage.setItem('sidebarCollapsed', sidebar.classList.contains('collapsed'));
                }
            }
        });
    }
};

// ==================== UTILIDADES ====================
const Utils = {
    // Formatear números con separador de miles
    formatNumber(num, decimals = 2) {
        return new Intl.NumberFormat('es-GT', {
            minimumFractionDigits: decimals,
            maximumFractionDigits: decimals
        }).format(num);
    },
    
    // Formatear moneda
    formatCurrency(amount, currency = 'GTQ') {
        return new Intl.NumberFormat('es-GT', {
            style: 'currency',
            currency: currency
        }).format(amount);
    },
    
    // Formatear fecha
    formatDate(dateString, options = {}) {
        const defaultOptions = {
            year: 'numeric',
            month: 'long',
            day: 'numeric'
        };
        
        const date = new Date(dateString);
        return new Intl.DateTimeFormat('es-GT', {...defaultOptions, ...options}).format(date);
    },
    
    // Formatear fecha y hora
    formatDateTime(dateString) {
        return this.formatDate(dateString, {
            year: 'numeric',
            month: 'long',
            day: 'numeric',
            hour: '2-digit',
            minute: '2-digit'
        });
    },
    
    // Validar email
    isValidEmail(email) {
        const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return re.test(email);
    },
    
    // Validar teléfono (formato Guatemala)
    isValidPhone(phone) {
        const re = /^[0-9]{8}$/;
        return re.test(phone.replace(/[\s-]/g, ''));
    },
    
    // Debounce function
    debounce(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    },
    
    // Copiar al portapapeles
    copyToClipboard(text) {
        if (navigator.clipboard) {
            navigator.clipboard.writeText(text).then(() => {
                MediSys.showToast('Copiado al portapapeles', 'success');
            }).catch(() => {
                MediSys.showToast('Error al copiar', 'error');
            });
        }
    }
};

// ==================== SISTEMA DE NOTIFICACIONES ====================
MediSys.showToast = function(message, type = 'success') {
    const toastHtml = `
        <div class="toast align-items-center text-white bg-${type} border-0" 
             role="alert" aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body">
                    <i class="bi bi-${this.getToastIcon(type)} me-2"></i>
                    ${message}
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" 
                        data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        </div>
    `;
    
    let toastContainer = document.getElementById('toastContainer');
    
    if (!toastContainer) {
        toastContainer = document.createElement('div');
        toastContainer.id = 'toastContainer';
        toastContainer.className = 'toast-container position-fixed top-0 end-0 p-3';
        toastContainer.style.zIndex = '9999';
        document.body.appendChild(toastContainer);
    }
    
    toastContainer.insertAdjacentHTML('beforeend', toastHtml);
    
    const toastElement = toastContainer.lastElementChild;
    const toast = new bootstrap.Toast(toastElement, {
        autohide: true,
        delay: 3000
    });
    
    toast.show();
    
    toastElement.addEventListener('hidden.bs.toast', function() {
        toastElement.remove();
    });
};

MediSys.getToastIcon = function(type) {
    const icons = {
        success: 'check-circle-fill',
        error: 'x-circle-fill',
        danger: 'exclamation-triangle-fill',
        warning: 'exclamation-circle-fill',
        info: 'info-circle-fill'
    };
    return icons[type] || 'info-circle-fill';
};

// ==================== LOADING SPINNER ====================
MediSys.showLoading = function(message = 'Cargando...') {
    const loadingHtml = `
        <div id="loadingOverlay" class="position-fixed top-0 start-0 w-100 h-100 d-flex 
             flex-column align-items-center justify-content-center" 
             style="background: rgba(0,0,0,0.7); z-index: 9999; backdrop-filter: blur(5px);">
            <div class="spinner-border text-light mb-3" role="status" 
                 style="width: 3rem; height: 3rem;">
                <span class="visually-hidden">Cargando...</span>
            </div>
            <p class="text-white fw-bold">${message}</p>
        </div>
    `;
    
    if (!document.getElementById('loadingOverlay')) {
        document.body.insertAdjacentHTML('beforeend', loadingHtml);
    }
};

MediSys.hideLoading = function() {
    const overlay = document.getElementById('loadingOverlay');
    if (overlay) {
        overlay.style.opacity = '0';
        setTimeout(() => overlay.remove(), 300);
    }
};

// ==================== MODAL DE CONFIRMACIÓN ====================
MediSys.confirmDialog = function(title, message, onConfirm, onCancel) {
    const modalHtml = `
        <div class="modal fade" id="confirmModal" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">${title}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <p>${message}</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            Cancelar
                        </button>
                        <button type="button" class="btn btn-primary" id="confirmBtn">
                            Confirmar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    `;
    
    // Eliminar modal existente si hay uno
    const existingModal = document.getElementById('confirmModal');
    if (existingModal) {
        existingModal.remove();
    }
    
    document.body.insertAdjacentHTML('beforeend', modalHtml);
    
    const modalElement = document.getElementById('confirmModal');
    const modal = new bootstrap.Modal(modalElement);
    
    document.getElementById('confirmBtn').addEventListener('click', () => {
        modal.hide();
        if (typeof onConfirm === 'function') {
            onConfirm();
        }
    });
    
    modalElement.addEventListener('hidden.bs.modal', () => {
        modalElement.remove();
        if (typeof onCancel === 'function') {
            onCancel();
        }
    });
    
    modal.show();
};

// ==================== VALIDACIÓN DE FORMULARIOS ====================
MediSys.validateForm = function(formId) {
    const form = document.getElementById(formId);
    
    if (!form) return false;
    
    let isValid = true;
    const inputs = form.querySelectorAll('input[required], select[required], textarea[required]');
    
    inputs.forEach(input => {
        if (!input.value.trim()) {
            input.classList.add('is-invalid');
            isValid = false;
        } else {
            input.classList.remove('is-invalid');
            input.classList.add('is-valid');
        }
        
        // Validación específica para email
        if (input.type === 'email' && input.value && !Utils.isValidEmail(input.value)) {
            input.classList.add('is-invalid');
            isValid = false;
        }
        
        // Validación específica para teléfono
        if (input.type === 'tel' && input.value && !Utils.isValidPhone(input.value)) {
            input.classList.add('is-invalid');
            isValid = false;
        }
    });
    
    return isValid;
};

// ==================== INICIALIZACIÓN ====================
// Inicializar cuando el DOM esté listo
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', () => MediSys.init());
} else {
    MediSys.init();
}

// Exponer globalmente
window.MediSys = MediSys;
window.Utils = Utils;