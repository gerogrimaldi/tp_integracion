<?php
require_once 'model/UsuarioModel.php';

//Manejo del login, se utiliza el formulario tal cual sin JavaScript.
if ( !empty($_POST) && isset($_POST['btLogin']) ) {
    switch ($_POST['btLogin']) {
        case 'login':
            $oUsuario = new Usuario();
            $oUsuario->setPassword($_POST['password']);
            $oUsuario->setEmail($_POST['email']);
            unset($_POST['password']);
            unset($_POST['email']);
            if ($oUsuario->validar())
            {
                //Si es correcto, envio a la pantalla de home
                header('Location: index.php?opt=home'); exit;
            }else{
                //Si falla en el inicio de sesión, redirige al login
                require_once 'controller/PageController.php';
            }
            break;
    }
}

//Para todo lo demás, se utiliza AJAX
if (isset($_GET['ajax']))
{
    switch ($_GET['ajax']) {
    // SOLICITUDES AJAX DE USUARIOS
        case 'logout':
            header('Content-Type: application/json');
            try {
                $oUser = new Usuario();
                $oUser->setidUsuario($_SESSION['user_id']);
                $oUser->cerrarSesion();
                http_response_code(200);
            } catch (RuntimeException $e) {
                    http_response_code(400);
                    echo json_encode(['msg' => $e->getMessage()]);
            }
            exit();
    }
}

/*
CASOS QUE PUEDEN SERVIR LUEGO PARA HACER EL AJAX
        case 'registrar': 
            $oUsuario = new Usuario();
            $oUsuario->setPassword($_POST['password']);
            $oUsuario->setNombre($_POST['nombre']);
            $oUsuario->setDireccion($_POST['direccion']);
            $oUsuario->setTelefono($_POST['telefono']);
            $oUsuario->setEmail($_POST['email']);
            $oUsuario->setDate($_POST['date']);
            $todo_ok = $oUsuario->save();
            break;

        case 'editarUsuario':
            require_once 'model/UsuarioModel.php';
            $oUsuario = new Usuario();
            $oUsuario->setidUsuario($_POST['idUsuario']);
            $oUsuario->setPassword($_POST['password']);
            $oUsuario->setNombre($_POST['nombres']);
            $oUsuario->setDireccion($_POST['direccion']);
            $oUsuario->setTelefono($_POST['telefono']);
            $oUsuario->setEmail($_POST['email']);
            $oUsuario->update();
            break;
*/