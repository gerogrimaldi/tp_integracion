<?php
require_once 'model/galponModel.php';
$validacion = false;

if ( !empty($_POST) ) 
{
    if ( $_POST['btGalpon'] == 'registrarGalpon' )
    {
        $oGalpon = new Galpon();
        $oGalpon->setMaxID();
        $oGalpon->setIdentificacion($_POST['identificacion']);
        $oGalpon->setIdTipoAve($_POST['idTipoAve']);
        $oGalpon->setCapacidad($_POST['capacidad']);
        $oGalpon->setIdGranja($_POST['idGranja']);
        $oGranja->save();
        //Redirigir a la vista principal de ABM granjas
        //header("Location: index.php?opt=granjas");
        //exit();   
    }

    if ( $_POST['btGalpon'] == 'editarGalpon' )
    {
        $oGalpon = new Galpon();
        $oGalpon->setIdGalpon ($_POST['idGalpon']);
        $oGalpon->setIdentificacion($_POST['identificacion']);
        $oGalpon->setIdTipoAve($_POST['idTipoAve']);
        $oGalpon->setCapacidad($_POST['capacidad']);
        $oGalpon->setIdGranja($_POST['idGranja']);
        $oGalpon->update();
        header("Location: index.php?opt=granjas");
        exit();   
    }
}

if ( !empty($_GET) ) 
{
    if (isset($_GET['delete']) && $_GET['delete'] == 'true')
    {
        UNSET($_GET['delete']);
        $oGalpon = new Galpon();
        $idGalpon = (int)$_GET['idGalpon'];
        $oGalpon->deleteGalponPorId($idGalpon);
        // Recargar página para mostrar resultados
        header("Location: index.php?opt=galpones&idGranja=" .$_GET['idGranja']);
        exit();
    }

    if ($_GET['opt']=='galpones')
    {
        $oGalpon = new Galpon();
        $resultado = $oGalpon->getall( $_GET['idGranja'] );
    }


}