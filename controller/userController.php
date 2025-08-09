<?php
require_once 'model/UsuarioModel.php';
if ( !empty($_POST) && isset($_POST['btLogin']) ) {

    switch ($_POST['btLogin']) {
        case '':
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

        case 'login':
            $oUsuario = new Usuario();
            $oUsuario->setPassword($_POST['password']);
            $oUsuario->setEmail($_POST['email']);
            unset($_POST['password']);
            unset($_POST['email']);
            if ($oUsuario->validar())
            {
                require_once 'controller/PageController.php';
            }else{
                //$_SESSION['login_error'] = "Invalid email or password. Please try again.";
                //require_once 'controller/PageController.php';
            }
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

        default:
            require_once 'view/error_404.php';
            break;
    }
}


if ( !empty($_GET) ) 
{

    if ($_GET['opt']=='list')
    {
        $oUsuario = new Usuario();
        $resultado = $oUsuario->getall();		
    }

    if ($_GET['opt']=='edit')
    {
        $oUsuario = new Usuario();
            $resultado = $oUsuario->getUsuarioPorId($idUsuario);
            if ( $resultado!=false )
            {
                $unaUsuario = $resultado->fetch_array();
                $idUsuario  = $unaUsuario['idUsuario'];
                $password  = $unaUsuario['password'];
                $apellido   = $unaUsuario['apellido'];
                $nombres    = $unaUsuario['nombres'];
                $telefono   = $unaUsuario['telefono'];
            }
    }

    if ($_GET['opt']=='delete')
    {
        $oUsuario = new Usuario();
        $resultado = $oUsuario->deleteUsuarioPorId($idUsuario);
    }
}