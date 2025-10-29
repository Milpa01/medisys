<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title ?? 'Login - MediSys') ?></title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    
    <style>
        :root {
            --primary-color: #0ea5e9;
            --primary-dark: #0284c7;
            --primary-light: #e0f2fe;
        }
        
        body {
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .login-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
        }
        
        .login-card {
            background: white;
            border-radius: 1rem;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            overflow: hidden;
            max-width: 400px;
            width: 100%;
        }
        
        .login-header {
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
            color: white;
            padding: 2rem;
            text-align: center;
        }
        
        .login-header h2 {
            margin: 0;
            font-weight: 600;
        }
        
        .login-header p {
            margin: 0.5rem 0 0 0;
            opacity: 0.9;
        }
        
        .login-body {
            padding: 2rem;
        }
        
        .form-floating {
            margin-bottom: 1rem;
        }
        
        .form-control {
            border: 2px solid #e2e8f0;
            border-radius: 0.5rem;
            padding: 0.75rem;
            transition: border-color 0.3s ease;
        }
        
        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.2rem rgba(14, 165, 233, 0.25);
        }
        
        .btn-login {
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
            border: none;
            border-radius: 0.5rem;
            padding: 0.75rem;
            font-weight: 600;
            color: white;
            width: 100%;
            transition: transform 0.3s ease;
        }
        
        .btn-login:hover {
            background: linear-gradient(135deg, var(--primary-dark), #075985);
            transform: translateY(-1px);
            color: white;
        }
        
        .form-check-input:checked {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }
        
        .logo-icon {
            font-size: 3rem;
            margin-bottom: 1rem;
        }
        
        .forgot-password {
            color: var(--primary-color);
            text-decoration: none;
            font-size: 0.9rem;
        }
        
        .forgot-password:hover {
            color: var(--primary-dark);
            text-decoration: underline;
        }
        
        .alert {
            border-radius: 0.5rem;
            border: none;
            margin-bottom: 1rem;
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
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            animation: float 6s ease-in-out infinite;
        }
        
        .floating-elements::before {
            width: 100px;
            height: 100px;
            top: 10%;
            left: 10%;
            animation-delay: 0s;
        }
        
        .floating-elements::after {
            width: 150px;
            height: 150px;
            bottom: 10%;
            right: 10%;
            animation-delay: 3s;
        }
        
        @keyframes float {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-20px) rotate(180deg); }
        }
    </style>
</head>
<body>
    <div class="floating-elements"></div>
    
    <div class="login-container">
        <div class="login-card">
            <div class="login-header">
                <div class="logo-icon">
                    <i class="bi bi-heart-pulse-fill"></i>
                </div>
                <h2>MediSys</h2>
                <p>Sistema de Gestión Clínica</p>
            </div>
            
            <div class="login-body">
                <!-- Mensajes Flash -->
                <?= Flash::display() ?>
                
                <form action="<?= Router::url('auth') ?>" method="POST">
                    <div class="form-floating">
                        <input type="text" class="form-control" id="username" name="username" placeholder="Usuario" required>
                        <label for="username"><i class="bi bi-person me-2"></i>Usuario</label>
                    </div>
                    
                    <div class="form-floating">
                        <input type="password" class="form-control" id="password" name="password" placeholder="Contraseña" required>
                        <label for="password"><i class="bi bi-lock me-2"></i>Contraseña</label>
                    </div>
                    
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" id="remember" name="remember">
                        <label class="form-check-label" for="remember">
                            Recordarme
                        </label>
                    </div>
                    
                    <button type="submit" class="btn btn-login">
                        <i class="bi bi-box-arrow-in-right me-2"></i>
                        Iniciar Sesión
                    </button>
                </form>
                
                <div class="text-center mt-3">
                    <a href="<?= Router::url('recuperar') ?>" class="forgot-password">
                        ¿Olvidaste tu contraseña?
                    </a>
                </div>
                
                <hr class="my-4">
                
                <div class="text-center text-muted">
                    <small>
                        <i class="bi bi-shield-check me-1"></i>
                        Acceso seguro al sistema
                    </small>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Auto-dismiss alerts
        setTimeout(() => {
            document.querySelectorAll('.alert').forEach(alert => {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            });
        }, 5000);
        
        // Focus en el primer campo
        document.getElementById('username').focus();
    </script>
</body>
</html>