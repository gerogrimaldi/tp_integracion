<?php
    $token = rand(5, 1500);
    $_SESSION ['token'] = $token;
    echo '<script>console.log("Welcome to GeeksforGeeks!"); </script>'; 
    if (empty($idUsuario))
    {
        
        $idUsuario = '';
        $password = '';

    }

    $body='
    <!--LOGIN-->
    <div class="login-container">
        <form method="post" action="index.php" class="custom-form">
        <!-- TOKEN -->
        <input type="hidden" name="token" id="token" value="<?php echo $token?>">';

    if (isset($_SESSION ['login_error'])){
        $body.= "<p style='color: red;'>" . $_SESSION['login_error'] . "</p>";
        unset($_SESSION['login_error']);
    };
       
    $body.='
        <div class="mb-4">
            <label for="username" class="form-label">Usuario</label>
            <input type="text" id="username" value="'.$idUsuario.'" name="nombre" class="form-control" required maxlength="20" pattern="[a-zA-Z0-9 ]+" title="Solo letras y números, hasta 10 caracteres.">
        </div>
        <div class="mb-4">
            <label for="password" class="form-label">Contraseña</label>
            <input type="password" id="password" value="'.$password.'" name="password" class="form-control" required maxlength="30" pattern="[a-zA-Z]+" title="Solo letras, hasta 30 caracteres.">
        </div>
        <div class="mb-4 form-check">
            <input type="checkbox" id="connected" name="connected" class="form-check-input">
            <label for="connected" class="form-check-label">Permanecer Conectado</label>
        </div>
        <div class="d-grid">';
            
        // <!-- CAPTCHA -->
        if (isset($_SESSION ['captcha_error'])){
            $body.= "<p style='color: red;'>" . $_SESSION['captcha_error'] . "</p>";
            unset($_SESSION['captcha_error']);
        }

        $body.='
        <div class="g-recaptcha" data-sitekey="6LfMnkkqAAAAAI10IzlGrCVlcge0CAcxMozY_jBL" required></div>
            <br/>
            <button type="submit" id="submit" name="btFormulario" value="'.$valueBt.'" class="btn btn-primary  text-wrap">Iniciar Sesión</button>
        </div>
        <div class="my-3">
            <span>No tienes Cuenta? <a href="index.php?opt=new">Regístrate</a></span><br>
            <span><a href="#">Recuperar Password</a></span>
        </div>
        </form>

    </div>
    ';

    ?>
