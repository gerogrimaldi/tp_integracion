<?php
// session_start();
require_once 'model/UsuarioModel.php';
$validacion = false;
// echo '<h2 class="bg-white"> HOLA </h2> ';
if ( !empty($_POST) ) 
{

    if ( $_POST['btFormulario'] == 'registrar' )
    {
        $oUsuario = new Usuario();

            $oUsuario->setPassword($_POST['password']);
            $oUsuario->setNombre($_POST['nombre']);
            $oUsuario->setDireccion($_POST['direccion']);
            $oUsuario->setTelefono($_POST['telefono']);
            $oUsuario->setEmail($_POST['email']);
            $oUsuario->setDate($_POST['date']);

        $todo_ok = $oUsuario->save();

        require_once('view/msjNuevoUsuario.php');

    }

    if ( $_POST['btFormulario'] == 'login' )
    {
        $oUsuario = new Usuario();

        $oUsuario->setPassword($_POST['password']);
        $oUsuario->setNombre($_POST['username']);

        if ($validacion = $oUsuario->validar())
        {
            // echo "<h1 class='bg-white'>VALIDACION OK<h1>";
            require_once 'controller/PageController.php';
        }else{
            // echo "<h1 class='bg-white'>ERROR VALIDACION<h1>";
            require_once 'controller/PageController.php';
        }

    }

    if ( $_POST['btFormulario'] == 'editarUsuario' )
    {
        require_once 'model/UsuarioModel.php';

        $oUsuario = new Usuario();

            $oUsuario->setidUsuario($_POST['idUsuario']);
            $oUsuario->setPassword($_POST['password']);
            $oUsuario->setNombre($_POST['nombres']);
            $oUsuario->setDireccion($_POST['direccion']);
            $oUsuario->setTelefono($_POST['telefono']);
            $oUsuario->setEmail($_POST['email']);

        $oUsuario->update();

        require_once('view/msjEditoUsuario.php');

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