<?php
require_once 'model/mantenimientosModel.php';
$validacion = false;

if ( !empty($_POST) ) 
{
    if ( $_POST['btMantenimientos'] == 'addTipoMant')
    {
        $oTipoMantenimiento = new tipoMantenimiento();
        $oTipoMantenimiento->setMaxIDTipoMant();
        $oTipoMantenimiento->setNombreMantenimiento( $_POST['nombreMant']);
        $oTipoMantenimiento->save();
    }

    if ( $_POST['btMantenimientos'] == 'editTipoMant')
    {
        $oTipoMantenimiento = new tipoMantenimiento();
        $oTipoMantenimiento->setIDTipoMant($_POST['idTipoMant']);
        $oTipoMantenimiento->setNombreMantenimiento( $_POST['nombreMantEdit']);
        $oTipoMantenimiento->update();
    }

    if ( $_POST['btMantenimientos'] == 'selectGranja')
    {
        if ( ctype_digit( $_POST['selectGranja'] )==true ) // Evalua que el ID sea positivo y entero
        {
            $oMantenimientoGranja = new mantenimientoGranja();
            $resultado = $oMantenimientoGranja->getMantGranjas($_POST['selectGranja']);
            $selectedGranja = $_POST['selectGranja'];
        }else{
            header("Location: index.php?opt=mantenimientos");
            exit();
        }
        
    }
}

if ( !empty($_GET) ) 
{
    if (isset($_GET['deletetm']) && $_GET['deletetm'] == 'true')
    {
        $oTipoMantenimiento = new tipoMantenimiento();
        $idTipoMant = (int)$_GET['idTipoMant'];
        $oTipoMantenimiento->deleteTipoMantID($idTipoMant);
        // Recargar p�gina para mostrar resultados
        header("Location: index.php?opt=mantenimientos");
        exit();
    }

    if ($_GET['opt']=='mantenimientos')
    {
        $oTipoMantenimiento = new tipoMantenimiento();
        $tiposMant = $oTipoMantenimiento->getTipoMantenimientos();

        $oMantenimientoGranja = new mantenimientoGranja();
        $granjasFiltradas = $oMantenimientoGranja->getGranjas();

    }


}