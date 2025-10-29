<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Página No Encontrada - MediSys</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
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
        
        .floating-elements {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            overflow: hidden;
            pointer-events: none;
        }
        
        .floating-elements::before,
        .floating-elements::after {
            content: '';
            position: absolute;
            width: 100px;
            height: 100px;
            border-radius: 50%;
            background: rgba(255,255,255,0.1);
            animation: float 6s ease-in-out infinite;
        }
        
        .floating-elements::before {
            top: 20%;
            left: 10%;
            animation-delay: 0s;
        }
        
        .floating-elements::after {
            bottom: 20%;
            right: 10%;
            animation-delay: 3s;
        }
        
        @keyframes float {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-20px) rotate(180deg); }
        }
        
        .error-icon {
            font-size: 4rem;
            margin-bottom: 1rem;
            opacity: 0.8;
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
    <div class="floating-elements"></div>
    
    <div class="error-container">
        <div class="error-icon">
            <i class="fas fa-search"></i>
        </div>
        
        <div class="error-code">404</div>
        
        <h1 class="error-title">Página No Encontrada</h1>
        
        <p class="error-description">
            Lo sentimos, la página que está buscando no existe o ha sido movida.
            Puede que haya escrito mal la dirección o que la página haya sido eliminada.
        </p>
        
        <div class="error-actions">
            <a href="<?php echo $_SERVER['HTTP_REFERER'] ?? '/'; ?>" class="btn-custom btn-primary-custom">
                <i class="fas fa-arrow-left"></i>
                Página Anterior
            </a>
            
            <a href="/" class="btn-custom btn-secondary-custom">
                <i class="fas fa-home"></i>
                Ir al Inicio
            </a>
        </div>
        
        <div class="mt-4">
            <small class="opacity-75">
                <i class="fas fa-info-circle me-1"></i>
                Si cree que esto es un error, contacte al administrador del sistema
            </small>
        </div>
    </div>
    
    <script>
        // Auto redirect después de 30 segundos si no hay interacción
        let redirectTimer = setTimeout(function() {
            window.location.href = '/';
        }, 30000);
        
        // Cancelar redirect si el usuario interactúa
        document.addEventListener('click', function() {
            clearTimeout(redirectTimer);
        });
        
        document.addEventListener('keydown', function() {
            clearTimeout(redirectTimer);
        });
    </script>
</body>
</html>