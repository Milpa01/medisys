<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title ?? 'Login - MediSys') ?></title>
    
    <!-- Bootstrap CSS -->
    <link href="<?= isset($base_url) ? $base_url : '' ?>/public/bootstrap5/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="<?= isset($base_url) ? $base_url : '' ?>/public/css/login.css" rel="stylesheet">

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
    <script src="<?= isset($base_url) ? $base_url : '' ?>/public/bootstrap5/js/bootstrap.bundle.min.js"></script>

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