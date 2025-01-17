<?php
require_once 'model/granjaModel.php';
$validacion = false;

if ( !empty($_POST) ) 
{
    if ( $_POST['btGranja'] == 'registrarGranja' )
    {
        $oGranja = new Granja();
        $oGranja->setNombre($_POST['nombre']);
        $oGranja->setHabilitacionSenasa($_POST['habilitacion']);
        $oGranja->setMetrosCuadrados($_POST['date']);
        $oGranja->setUbicacion($_POST['ubicacion']);
        $todo_ok = $oGranja->save();
        //require_once('view/msjNuevoEvento.php');
    }

    if ( $_POST['btGranja'] == 'editarGranja' )
    {
        require_once 'model/GranjaModel.php';
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

    if ($_GET['opt']=='addGranja')
    {
        $oGranja = new Granja();
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

    if ($_GET['opt']=='edit')
    {
        $oGranja = new Granja();
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

    if ($_GET['opt']=='delete')
    {
        $oGranja = new Granja();
        $resultado = $oGranja->deleteGranjaPorId($idGranja);
    }
}