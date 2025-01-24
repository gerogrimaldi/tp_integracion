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

    if ( $_POST['btMantenimientos'] == 'newMantGranja')
    {
        $oMantenimientoGranja = new mantenimientoGranja();
        $oMantenimientoGranja->setMaxIDMantGranja();
        $oMantenimientoGranja->setFecha( $_POST['fechaMantenimiento']);
        $oMantenimientoGranja->setIdGranja( $_POST['idGranja'] );
        $oMantenimientoGranja->setIdTipoMantenimiento( $_POST['tipoMantenimiento'] );
        $oMantenimientoGranja->save();
        header("Location: index.php?opt=mantenimientos&selectGranja=" . $_POST['idGranja']);
                
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
        
        if ( isset($_GET['selectGranja']) )
        {
            $resultado = $oMantenimientoGranja->getMantGranjas($_GET['selectGranja']);
            $selectedGranja = $_GET['selectGranja'];
        }

        if ( isset($_POST['btMantenimientos']) && ($_POST['btMantenimientos'] == 'selectGranja') )
        {
            $resultado = $oMantenimientoGranja->getMantGranjas($_POST['selectGranja']);
            $selectedGranja = $_POST['selectGranja'];
        }
    }

    if (isset($_GET['delete']) && $_GET['delete'] == 'true')
    {
        $oMantenimientoGranja = new mantenimientoGranja();
        $oMantenimientoGranja->deleteMantenimientoGranjaId($_GET['idMantenimientoGranja']);
        header("Location: index.php?opt=mantenimientos&selectGranja=" . $_GET['selectGranja']);
        exit();
    }

}