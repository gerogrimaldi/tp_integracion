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
        $oGalpon->setIdTipoAve($_POST['opciones']);
        $oGalpon->setCapacidad($_POST['capacidad']);
        $oGalpon->setIdGranja($_POST['idGranja']);
        $oGalpon->save();
        // Recargar p�gina para mostrar resultados
        header("Location: index.php?opt=galpones&idGranja=" .$_POST['idGranja']);
        exit();
    }

    if ( $_POST['btGalpon'] == 'editarGalpon' )
    {
        $oGalpon = new Galpon();
        $oGalpon->setIdGalpon ($_POST['idGalpon']);
        $oGalpon->setIdentificacion($_POST['identificacion']);
        $oGalpon->setIdTipoAve($_POST['opcionesEditar']);
        $oGalpon->setCapacidad($_POST['capacidad']);
        $oGalpon->setIdGranja($_POST['idGranja']);
        $oGalpon->update();
        header("Location: index.php?opt=galpones&idGranja=" .$_POST['idGranja']);
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
        // Recargar p�gina para mostrar resultados
        header("Location: index.php?opt=galpones&idGranja=" .$_GET['idGranja']);
        exit();
    }

    if ($_GET['opt']=='galpones')
    {
        $oGalpon = new Galpon();
        $resultado = $oGalpon->getall( $_GET['idGranja'] );
        $tiposAves = $oGalpon->getTiposAves();
    }


}