<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Error del Servidor - MediSys</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    
    <style>
        body {
            background: linear-gradient(135deg, #f39c12 0%, #e67e22 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Inter', sans-serif;
        }
        
        .error-container {
            text-align: center;
            color: white;
            max-width: 600px;
            padding: 2rem;
        }
        
        .error-code {
            font-size: 8rem;
            font-weight: 900;
            line-height: 1;
            margin-bottom: 1rem;
            text-shadow: 0 4px 8px rgba(0,0,0,0.3);
        }
        
        .error-title {
            font-size: 2.5rem;
            font-weight: 600;
            margin-bottom: 1rem;
        }
        
        .error-description {
            font-size: 1.2rem;
            margin-bottom: 2rem;
            opacity: 0.9;
        }
        
        .error-actions {
            display: flex;
            gap: 1rem;
            justify-content: center;
            flex-wrap: wrap;
        }
        
        .btn-custom {
            padding: 12px 24px;
            border-radius: 8px;
            font-weight: 500;
            text-decoration: none;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .btn-primary-custom {
            background: rgba(255,255,255,0.2);
            border: 2px solid rgba(255,255,255,0.3);
            color: white;
        }
        
        .btn-primary-custom:hover {
            background: rgba(255,255,255,0.3);
            transform: translateY(-2px);
            color: white;
        }
        
        .btn-secondary-custom {
            background: transparent;
            border: 2px solid rgba(255,255,255,0.5);
            color: white;
        }
        
        .btn-secondary-custom:hover {
            background: rgba(255,255,255,0.1);
            transform: translateY(-2px);
            color: white;
        }
        
        .error-icon {
            font-size: 4rem;
            margin-bottom: 1rem;
            opacity: 0.8;
            animation: pulse 2s infinite;
        }
        
        @keyframes pulse {
            0% { opacity: 0.8; transform: scale(1); }
            50% { opacity: 1; transform: scale(1.05); }
            100% { opacity: 0.8; transform: scale(1); }
        }
        
        .error-details {
            background: rgba(0,0,0,0.2);
            border-radius: 12px;
            padding: 1rem;
            margin: 2rem 0;
            text-align: left;
        }
        
        .retry-button {
            position: relative;
            overflow: hidden;
        }
        
        .retry-button:hover::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            animation: shimmer 1s ease-in-out;
        }
        
        @keyframes shimmer {
            0% { left: -100%; }
            100% { left: 100%; }
        }
        
        @media (max-width: 768px) {
            .error-code {
                font-size: 6rem;
            }
            
            .error-title {
                font-size: 2rem;
            }
            
            .error-description {
                font-size: 1rem;
            }
            
            .error-actions {
                flex-direction: column;
                align-items: center;
            }
        }
    </style>
</head>
<body>
    <div class="error-container">
        <div class="error-icon">
            <i class="fas fa-tools"></i>
        </div>
        
        <div class="error-code">500</div>
        
        <h1 class="error-title">Error del Servidor</h1>
        
        <p class="error-description">
            Oops! Algo salió mal en nuestro servidor. Nuestro equipo ha sido notificado 
            automáticamente y está trabajando para solucionarlo.
        </p>
        
        <div class="error-details">
            <div class="small mb-2">
                <i class="fas fa-info-circle me-2"></i>
                Detalles del error:
            </div>
            <div class="fw-bold mb-2"><?php echo $message ?? 'Error interno del servidor'; ?></div>
            <div class="small text-white-50">
                <i class="fas fa-clock me-1"></i>
                Tiempo: <?php echo date('d/m/Y H:i:s'); ?>
            </div>
            <div class="small text-white-50">
                <i class="fas fa-hashtag me-1"></i>
                ID de error: <?php echo uniqid('ERR_'); ?>
            </div>
        </div>
        
        <div class="error-actions">
            <button onclick="location.reload()" class="btn-custom btn-primary-custom retry-button">
                <i class="fas fa-redo"></i>
                Intentar de Nuevo
            </button>
            
            <a href="/" class="btn-custom btn-secondary-custom">
                <i class="fas fa-home"></i>
                Ir al Inicio
            </a>
        </div>
        
        <div class="mt-4">
            <details class="text-start">
                <summary class="mb-2" style="cursor: pointer;">
                    <i class="fas fa-cog me-1"></i>
                    Opciones Avanzadas
                </summary>
                <div class="small">
                    <div class="mb-2">
                        <strong>¿Qué puede hacer?</strong>
                    </div>
                    <ul class="text-start">
                        <li>Espere unos minutos e intente nuevamente</li>
                        <li>Verifique su conexión a internet</li>
                        <li>Limpie la caché de su navegador</li>
                        <li>Contacte al administrador del sistema si persiste</li>
                    </ul>
                    
                    <div class="mt-3">
                        <strong>Información técnica:</strong><br>
                        <small class="text-white-50">
                            User-Agent: <span id="userAgent"></span><br>
                            URL: <?php echo $_SERVER['REQUEST_URI'] ?? 'Unknown'; ?><br>
                            Método: <?php echo $_SERVER['REQUEST_METHOD'] ?? 'Unknown'; ?>
                        </small>
                    </div>
                </div>
            </details>
        </div>
        
        <div class="mt-4">
            <small class="opacity-75">
                <i class="fas fa-envelope me-1"></i>
                Si el problema persiste, contacte a soporte técnico
            </small>
        </div>
    </div>
    
    <script>
        // Mostrar información del navegador
        document.getElementById('userAgent').textContent = navigator.userAgent;
        
        // Auto-refresh después de 60 segundos
        let refreshTimer = setTimeout(function() {
            location.reload();
        }, 60000);
        
        // Cancelar auto-refresh si el usuario interactúa
        document.addEventListener('click', function() {
            clearTimeout(refreshTimer);
        });
        
        // Contador visual para auto-refresh
        let countdown = 60;
        const countdownInterval = setInterval(function() {
            countdown--;
            if (countdown <= 0) {
                clearInterval(countdownInterval);
            }
        }, 1000);
        
        // Reportar error automáticamente si es posible
        if ('navigator' in window && 'sendBeacon' in navigator) {
            const errorData = JSON.stringify({
                error: '500_server_error',
                url: window.location.href,
                userAgent: navigator.userAgent,
                timestamp: new Date().toISOString(),
                message: '<?php echo $message ?? 'Unknown error'; ?>'
            });
            
            try {
                navigator.sendBeacon('/api/error-report', errorData);
            } catch (e) {
                console.warn('No se pudo enviar reporte de error:', e);
            }
        }
        
        // Keyboard shortcuts
        document.addEventListener('keydown', function(e) {
            if (e.key === 'r' && e.ctrlKey) {
                e.preventDefault();
                location.reload();
            } else if (e.key === 'h' && e.ctrlKey) {
                e.preventDefault();
                window.location.href = '/';
            }
        });
    </script>
</body>
</html>