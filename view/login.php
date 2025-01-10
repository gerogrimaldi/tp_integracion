<?php
$token = rand(5, 1500);
$_SESSION['token'] = $token;

if (empty($idUsuario)) {
    $idUsuario = '';
    $password = '';
}

$body = '
    <link rel="stylesheet" href="css/login.css">

    <div class="login-wrapper d-flex flex-column align-items-center justify-content-center p-3">
        <div class="login-container p-4 p-md-5 rounded-4" style="max-width: 450px; width: 100%;">
            <div class="text-center">
                <div class="company-logo mx-auto">
                    <i class="bi bi-building text-white" style="font-size: 3rem;"></i>
                </div>
                <h1 class="h3 fw-bold mb-2" style="color: var(--primary-color);">Bienvenido</h1>
                <p class="text-muted mb-4">Inicie sesión para acceder a la aplicación</p>
            </div>
            
            <form id="loginForm" action="index.php" method="POST" class="needs-validation" novalidate>
                <div class="mb-4">
                    <div class="input-group has-validation">
                        <span class="input-group-text border-end-0">
                            <i class="bi bi-person"></i>
                        </span>
                        <input type="text" 
                               class="form-control border-start-0" 
                               id="username" 
                               name="username" 
                               placeholder="Usuario"
                               minlength="3"
                               value="'.$idUsuario.'"
                               required>
                        <div class="invalid-feedback">
                            Por favor ingrese un usuario válido (mínimo 3 caracteres)
                        </div>
                    </div>
                </div>
                
                <div class="mb-4">
                    <div class="input-group has-validation">
                        <span class="input-group-text border-end-0">
                            <i class="bi bi-lock"></i>
                        </span>
                        <input type="password" 
                               class="form-control border-start-0" 
                               id="password" 
                               name="password" 
                               placeholder="Contraseña"
                               minlength="6"
                               value="'.$password.'"
                               required>
                        <div class="invalid-feedback">
                            La contraseña debe tener al menos 6 caracteres
                        </div>
                    </div>
                </div>

                <div class="alert alert-danger d-none" id="errorAlert" role="alert">
                </div>';

                // <!-- CAPTCHA -->
                if (isset($_SESSION['captcha_error'])) {
                    $body .= "<p style=\'color: red;\'>" . $_SESSION['captcha_error'] . "</p>";
                    unset($_SESSION['captcha_error']);
                }

                $body .= '
                <div class="g-recaptcha mb-4" data-sitekey="6LfMnkkqAAAAAI10IzlGrCVlcge0CAcxMozY_jBL" required></div>

                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-primary btn-lg fw-bold" name="btFormulario" value="'.$valueBt.'">
                        Iniciar Sesión
                    </button>
                </div>

                <div class="text-center mt-4">
                    <a href="#" class="forgot-password">¿Olvidó su contraseña?</a>
                </div>
            </form>
        </div>
        
        <div class="mt-4 text-center">
            <small class="text-muted">&copy; 2025 Su Empresa. Todos los derechos reservados.</small>
        </div>
    </div>

    <script src="js/clientValidation.js"></script>

';
?>