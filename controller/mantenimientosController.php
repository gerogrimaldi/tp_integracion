<?php
require_once 'model/mantenimientosModel.php';
require_once 'model/granjaModel.php';
require_once 'model/galponModel.php';
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

    if ( $_POST['btMantenimientos'] == 'newMantGalpon')
    {
        $oMantenimientoGalpon = new mantenimientoGalpon();
        $oMantenimientoGalpon->setMaxIDMantGalpon();
        $oMantenimientoGalpon->setFecha( $_POST['fechaMantenimiento']);
        $oMantenimientoGalpon->setIdGalpon( $_POST['idGalpon'] );
        $oMantenimientoGalpon->setIdTipoMantenimiento( $_POST['tipoMantenimiento'] );
        $oMantenimientoGalpon->save();
        header("Location: index.php?opt=mantenimientos&selectGalpon=" . $_POST['idGalpon']);     
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
        $oMantenimientoGranja = new mantenimientoGranja();
        $oGranjas = new granja();
        $granjasFiltradas = $oGranjas->getall();
        $oMantenimientoGalpon = new mantenimientoGalpon();
        $oGalpon = new galpon();
        $galponesFiltrados = $oGalpon->getGalponesMasGranjas();

        if ( isset($_GET['selectGranja']) )
        {
            $resultado = $oMantenimientoGranja->getMantGranjas($_GET['selectGranja']);
            $selectedGranja = $_GET['selectGranja'];
        }

        if ( isset($_POST['btMantenimientos']) && ($_POST['btMantenimientos'] == 'selectGranja') )
        {
            if ( ctype_digit( $_POST['selectGranja'] )==true ) // Evalua que el ID sea positivo y entero
            {
            $resultado = $oMantenimientoGranja->getMantGranjas($_POST['selectGranja']);
            $selectedGranja = $_POST['selectGranja'];
            }
        }
        //Si no entró a ninguno de los dos if anteriores, cargar un array vacío.
        $selectedGranja = $selectedGranja ?? '[]';

        if ( isset($_GET['selectGalpon']) )
        {
            $resultadoGalp = $oMantenimientoGalpon->getMantGalpon($_GET['selectGalpon']);
            $selectedGalpon = $_GET['selectGalpon'];
        }

        if ( isset($_POST['btMantenimientos']) && ($_POST['btMantenimientos'] == 'selectGalpon') )
        {
            if ( ctype_digit( $_POST['selectGalpon'] )==true ) // Evalua que el ID sea positivo y entero
            {
            $resultadoGalp = $oMantenimientoGalpon->getMantGalpon($_POST['selectGalpon']);
            $selectedGalpon = $_POST['selectGalpon'];
            }
        }
        //Si no entró a ninguno de los dos if anteriores, cargar un array vacío.
        $selectedGalpon = $selectedGalpon ?? '[]';
    }

    if (isset($_GET['delete']) && $_GET['delete'] == 'granja')
    {
        $oMantenimientoGranja = new mantenimientoGranja();
        $oMantenimientoGranja->deleteMantenimientoGranjaId($_GET['idMantenimientoGranja']);
        header("Location: index.php?opt=mantenimientos&selectGranja=" . $_GET['selectGranja']);
        exit();
    }

    if (isset($_GET['delete']) && $_GET['delete'] == 'galpon')
    {
        $oMantenimientoGalpon = new mantenimientoGalpon();
        $oMantenimientoGalpon->deleteMantenimientoGalponId($_GET['idMantenimientoGalpon']);
        header("Location: index.php?opt=mantenimientos&selectGalpon=" . $_GET['selectGalpon']);
        exit();
    }

    if (isset($_GET['delete']) && $_GET['delete'] == 'galpon')
    {
        $oMantenimientoGalpon = new mantenimientoGalpon();
        $oMantenimientoGalpon->deleteMantenimientoGalponId($_GET['idMantenimientoGalpon']);
        header("Location: index.php?opt=mantenimientos&selectGalpon=" . $_GET['selectGalpon']);
        exit();
    }

    if (isset($_GET['ajax']) && $_GET['ajax'] == 'getTipoMant')
    {
        $oTipoMantenimiento = new tipoMantenimiento();
        $tiposMant = $oTipoMantenimiento->getTipoMantenimientos();
        if ($tiposMant) {
            // Establecer el encabezado para indicar que la respuesta es JSON
            header('Content-Type: application/json');
            // Devolver los datos en formato JSON
            echo json_encode($tiposMant);
        }
        else{
            // Si no hay datos, devolver un mensaje de error en JSON
            header('Content-Type: application/json');
            echo json_encode(['error' => 'No se encontraron tipos de mantenimiento']);
        }
        exit();
    }
}