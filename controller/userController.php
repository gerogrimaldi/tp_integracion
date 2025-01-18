<?php
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
        echo("<h2><script>console.log('Ingreso login user controller')</script></h2>"); 

        $oUsuario = new Usuario();

        $oUsuario->setPassword($_POST['password']);
        $oUsuario->setEmail($_POST['email']);

        unset($_POST['password']);
        unset($_POST['email']);

        if ($validacion = $oUsuario->validar())
        {
            echo("<h2><script>console.log('Ingreso correcto')</script></h2>"); 
            require_once 'controller/PageController.php';
        }else{
            echo("<h2><script>console.log('Ingreso Erroneo')</script></h2>"); 
            $_SESSION['login_error'] = "Invalid email or password. Please try again.";
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