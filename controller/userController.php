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
                echo json_encode(['msg' => 'Se ha cerrado la sesión.']);
            } catch (RuntimeException $e) {
                    http_response_code(400);
                    echo json_encode(['msg' => $e->getMessage()]);
            }
            exit();

        case 'getUsuarios':
            header('Content-Type: application/json');
            try {
                $oUser = new Usuario();
                $Usuarios = $oUser->getall();
                   
                if ($Usuarios) {
                    http_response_code(200);
                    echo json_encode($Usuarios);
                }else{
                    http_response_code(200);
                    echo '[]';
                }
            } catch (RuntimeException $e) {
                    http_response_code(400);
                    echo json_encode(['msg' => $e->getMessage()]);
            }
            exit();

        case 'getUsuario':
            header('Content-Type: application/json');
            try {
                if( !isset($_GET['idUsuario']) || $_GET['idUsuario'] === '' )
                {
                    http_response_code(400);
                    echo json_encode(['msg' => 'Error: no se ha seleccionado un usuario.']);
                    exit();
                }
                $oUser = new Usuario();
                $Usuarios = $oUser->getall();
                if ($Usuarios = $oUser->getUsuarioPorId($_GET['idUsuario'])) {
                    http_response_code(200);
                    echo json_encode($Usuarios);
                }else{
                    http_response_code(200);
                    echo '[]';
                }
            } catch (RuntimeException $e) {
                    http_response_code(400);
                    echo json_encode(['msg' => 'Error al obtener usuario.']);
            }
            exit();

        //index.php?opt=usuarios&ajax=updateCampo
        case 'updateCampo':
            header('Content-Type: application/json');
            try {
                if( !isset($_POST['idUsuario']) || $_POST['idUsuario'] === '' ||
                    !isset($_POST['campo']) || $_POST['campo'] === '' ||
                    !isset($_POST['valor']) || $_POST['valor'] === '' )
                {
                    http_response_code(400);
                    echo json_encode(['msg' => 'Error: datos incompletos para actualizar.']);
                    exit();
                }
                $oUser = new Usuario();
                $oUser->setidUsuario($_POST['idUsuario']);
                if ($oUser->updateCampo($_POST['campo'], $_POST['valor'])) {
                    http_response_code(200);
                    echo json_encode(['msg' => 'Campo actualizado correctamente.']);
                }else {
                    http_response_code(400);
                    echo json_encode(['msg' => 'Error en el procedimiento para actualizar el campo.']);
                }
            } catch (RuntimeException $e) {
                    http_response_code(400);
                    echo json_encode(['msg' => 'Error al actualizar el campo: ' . $e->getMessage()]);
            }
            exit();

        case 'registrar':
            try {
                $oUsuario = new Usuario();
                $oUsuario->setPassword($_POST['password']);
                $oUsuario->setNombre($_POST['nombre']);
                $oUsuario->setDireccion($_POST['direccion']);
                $oUsuario->setTelefono($_POST['telefono']);
                $oUsuario->setEmail($_POST['email']);
                if ($oUsuario->save()) {
                    http_response_code(200);
                    echo json_encode(['msg' => 'Usuario registrado correctamente.']);
                } else {
                    http_response_code(400);
                    echo json_encode(['msg' => 'Error al registrar el usuario.']);
                }
            } catch (Exception $e) {
                http_response_code(500);
                echo json_encode(['msg' => 'Error interno del servidor: ' . $e->getMessage()]);
            }


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

4. Backend – Endpoints necesarios
Para que funcione, tu backend debe tener:

getUsuario&idUsuario=... → Devuelve un JSON con todos los campos del usuario.

updateCampo (POST) → Recibe idUsuario, campo, valor y actualiza en la base de datos.


*/