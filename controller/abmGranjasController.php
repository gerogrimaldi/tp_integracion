<?php
require_once 'model/granjaModel.php';
$validacion = false;

if ( !empty($_POST) ) 
{
    if ( $_POST['btGranja'] == 'registrarGranja' )
    {
        $oGranja = new Granja();
        $oGranja->setMaxID();
        $oGranja->setNombre($_POST['nombre']);
        $oGranja->setHabilitacionSenasa($_POST['habilitacion']);
        $oGranja->setMetrosCuadrados($_POST['metrosCuadrados']);
        $oGranja->setUbicacion($_POST['ubicacion']);
        $oGranja->save();
        // Redirigir a la vista principal de ABM granjas
        header("Location: index.php?opt=granjas");
        exit();   
    }

    if ( $_POST['btGranja'] == 'editarGranja' )
    {

        $oGranja = new Granja();
            $oGranja->setNombre($_POST['nombre']);
            $oGranja->setHabilitacionSenasa($_POST['habilitacion']);
            $oGranja->setMetrosCuadrados($_POST['metrosCuadrados']);
            $oGranja->setUbicacion($_POST['ubicacion']);
        $oGranja->update();
        //require_once('view/msjEditoEvento.php');
    }
}

if ( !empty($_GET) ) 
{
    if ($_GET['opt']=='granjas')
    {
        $oGranja = new Granja();
        $resultado = $oGranja->getall();
    }

    if (isset($_GET['edit']) &&$_GET['edit']=='true')
    {
        UNSET($_GET['edit']);
        $oGranja = new Granja();
        $idGranja = (int)$_GET['idGranja'];
        $resultado = $oGranja->getGranjaPorId($idGranja);
        if ( $resultado!=false )
        {
            $unaGranja = $resultado->fetch_array();
            $idGranja  = $unaGranja['idGranja'];
            $nombre   = $unaGranja['nombre'];
            $habilitacionSenasa    = $unaGranja['habilitacionSenasa'];
            $metrosCuadrados   = $unaGranja['metrosCuadrados'];
            $ubicacion   = $unaGranja['ubicacion'];
        }
    }

    if (isset($_GET['delete']) && $_GET['delete'] == 'true')
    {
        UNSET($_GET['delete']);
        $oGranja = new Granja();
        $idGranja = (int)$_GET['idGranja'];
        $oGranja->deleteGranjaPorId($idGranja);
        // Recargar p�gina para mostrar resultados
        header("Location: index.php?opt=granjas");
    }
}