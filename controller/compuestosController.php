<?php
require_once 'model/compuestosModel.php';

if (isset($_GET['ajax']))
{
    switch ($_GET['ajax']) {
    // ------------------------------------
    // SOLICITUDES AJAX - COMPUESTOS
    // ------------------------------------
        case 'addCompuesto':
            header('Content-Type: application/json');
            try {
                if(empty($_POST['nombre']) || empty($_POST['proveedor']))
                {
                    http_response_code(400);
                    echo json_encode(['msg' => 'Error: hay campos vacíos.']);
                    exit();
                }
                $oCompuesto = new compuesto();
                // Respuesta a JS
                if ($oCompuesto->agregarNuevo($_POST['nombre'], $_POST['proveedor'])){
                    http_response_code(200);
                    echo json_encode(['msg' => 'Insertado correctamente']);
                } 
            } catch (RuntimeException $e) {
                    http_response_code(400);
                    // echo json_encode(['error' => $e->getMessage()]);
                    echo json_encode(['msg' => 'Error al añadir. Ya existe.']);
            }
            exit();
        break;

        case 'delCompuesto': 
            header('Content-Type: application/json');
            try {
                if( (!isset($_GET['idCompuesto']) || $_GET['idCompuesto'] === '') )
                {
                    http_response_code(400);
                    echo json_encode(['msg' => 'Error: no se seleccionó un compuesto.']);
                    exit();
                }
                $oCompuesto = new compuesto();
                if ($oCompuesto->deleteCompuesto($_GET['idCompuesto'])) {
                    http_response_code(200);
                    echo json_encode(['msg' => 'Eliminado correctamente.']);
                }
            } catch (RuntimeException $e) {
                http_response_code(400);
                //No pasar los errores el JS, enviar uno personalizado.
                //echo json_encode(['msg' => $e->getMessage()]);
                echo json_encode(['msg' => 'Error al eliminar, tiene registros asociados']);
            }
            exit();
        break;
    
        case 'editCompuesto':
            header('Content-Type: application/json');
            try {
                if( (!isset($_POST['idCompuesto']) || $_POST['idCompuesto'] === '') 
                || empty($_POST['nombre']) || empty($_POST['proveedor']))
                {
                    http_response_code(400);
                    echo json_encode(['msg' => 'Error: hay campos vacíos.']);
                    exit();
                }
                $oCompuesto = new compuesto();
                if ($oCompuesto->update($_POST['idCompuesto'], $_POST['nombre'], $_POST['proveedor'])) {
                    http_response_code(200);
                    echo json_encode(['msg' => 'Cambios guardados correctamente']);
                } 
            } catch (RuntimeException $e) {
                    http_response_code(400);
                    //echo json_encode(['error' => $e->getMessage()]);
                    echo json_encode(['msg' => 'Error al guardar los cambios']);
            }
            exit();
        break;
    
        case 'getCompuestos':
            header('Content-Type: application/json');
            try {
                $oCompuesto = new compuesto();
                $compuestos = $oCompuesto->getCompuestos();
                if ($compuestos) {
                    http_response_code(200);
                    echo json_encode($compuestos);
                }else{
                    echo '[]';
                }
            } catch (RuntimeException $e) {
                    http_response_code(400);
                    echo json_encode(['error' => $e->getMessage()]);
            }
            exit();
        break;
/*
    // ------------------------------------
    // SOLICITUDES AJAX - MANTENIMIENTOS DE GRANJA
    // ------------------------------------

        case 'newMantGranja':
            header('Content-Type: application/json');
            try {
                if( empty($_POST['fechaMant']) || (!isset($_POST['idGranja']) || $_POST['idGranja'] === '') || 
                (!isset($_POST['tipoMantenimiento']) || $_POST['tipoMantenimiento'] === ''))
                {
                    http_response_code(400);
                    echo json_encode(['msg' => 'Error: hay campos vacíos.']);
                    exit();
                }
                $oMantenimientoGranja = new mantenimientoGranja();
                $oMantenimientoGranja->setMaxIDMantGranja();
                $oMantenimientoGranja->setFecha( $_POST['fechaMant']);
                $oMantenimientoGranja->setIdGranja( $_POST['idGranja'] );
                $oMantenimientoGranja->setIdTipoMantenimiento( $_POST['tipoMantenimiento'] );
                if ($oMantenimientoGranja->save()) {
                    http_response_code(200);
                    echo json_encode(['msg' => 'Mantenimiento agregado correctamente']);
                }
            }catch (RuntimeException $e) {
                    http_response_code(400);
                    //echo json_encode(['msg' => $e->getMessage()]);
                    echo json_encode(['msg' => 'Error al ingresar mantenimiento']);
            }
            exit();
        break;

        case 'getMantGranja':
            header('Content-Type: application/json');
            try {
                if( !isset($_GET['idGranja']) || $_GET['idGranja'] === '' )
                {
                    http_response_code(400);
                    echo json_encode(['msg' => 'Error: no se ha seleccionado una granja.']);
                    exit();
                }
                If (!isset($_GET['desde']) || $_GET['desde'] === '' || !isset($_GET['hasta']) || $_GET['hasta'] === '') {
                    http_response_code(400);
                    echo json_encode(['msg' => 'Error: rango de fechas no válido.']);
                    exit();
                }
                $oMantenimientoGranja = new MantenimientoGranja();
                if ($mantGranjas = $oMantenimientoGranja->getMantGranjas($_GET['idGranja'], $_GET['desde'], $_GET['hasta'])){
                    http_response_code(200);
                    echo json_encode($mantGranjas);
                }else{
                    http_response_code(200);
                    echo '[]';
                }
            } catch (RuntimeException $e) {
                    http_response_code(400);
                    //echo json_encode(['msg' => $e->getMessage()]);
                    echo json_encode(['msg' => 'Error al obtener mantenimientos.']);
            }
            exit();

        case 'delMantGranja': 
            header('Content-Type: application/json');
            try {
                if( !isset($_GET['idMantenimientoGranja']) || $_GET['idMantenimientoGranja'] === '' ){
                    http_response_code(400);
                    echo json_encode(['msg' => 'Error: mantenimiento no seleccionado.']);
                    exit();
                }
                $oMantenimientoGranja = new mantenimientoGranja();
                if ($oMantenimientoGranja->deleteMantenimientoGranjaId($_GET['idMantenimientoGranja'])) {
                    http_response_code(200);
                    echo json_encode(['msg' => 'Eliminado correctamente.']);
                }
            } catch (RuntimeException $e) {
                http_response_code(400);
                //echo json_encode(['msg' => $e->getMessage()]);
                echo json_encode(['msg' => 'Error al eliminar']);
            }
            exit();
        break;

    // ------------------------------------
    // SOLICITUDES AJAX - MANTENIMIENTOS GALPONES
    // ------------------------------------

        case 'newMantGalpon':
            header('Content-Type: application/json');
            try {
                if( empty($_POST['fechaMant']) || ( !isset($_POST['idGalpon']) || $_POST['idGalpon'] === '' ) || 
                (!isset($_POST['tipoMantenimiento']) || $_POST['tipoMantenimiento'] === ''))
                {
                    http_response_code(400);
                    echo json_encode(['msg' => 'Error: hay campos vacíos.']);
                    exit();
                }
                $oMantenimientoGalpon = new mantenimientoGalpon();
                $oMantenimientoGalpon->setMaxIDMantGalpon();
                $oMantenimientoGalpon->setFecha( $_POST['fechaMant']);
                $oMantenimientoGalpon->setIdGalpon( $_POST['idGalpon'] );
                $oMantenimientoGalpon->setIdTipoMantenimiento( $_POST['tipoMantenimiento'] ); 
                if ($oMantenimientoGalpon->save()) {
                    http_response_code(200);
                    echo json_encode(['msg' => 'Mantenimiento agregado correctamente']);
                }
            }catch (RuntimeException $e) {
                    http_response_code(400);
                    echo json_encode(['error' => 'Error al ingresar mantenimiento']);
            }
            exit();
        break;
        
        case 'getMantGalpon':
            header('Content-Type: application/json');
            try {
                if( !isset($_GET['idGalpon']) || $_GET['idGalpon'] === '' )
                {
                    http_response_code(400);
                    echo json_encode(['msg' => 'Error: no se ha seleccionado un galpón.']);
                    exit();
                }
                If (!isset($_GET['desde']) || $_GET['desde'] === '' || !isset($_GET['hasta']) || $_GET['hasta'] === '') {
                    http_response_code(400);
                    echo json_encode(['msg' => 'Error: rango de fechas no válido.']);
                    exit();
                }
                $oMantenimientoGalpon = new mantenimientoGalpon();
                if ($mantGalpon = $oMantenimientoGalpon->getMantGalpon($_GET['idGalpon'], $_GET['desde'], $_GET['hasta'])) {
                    http_response_code(200);
                    echo json_encode($mantGalpon);
                }else{
                    http_response_code(200);
                    echo '[]';
                }
            } catch (RuntimeException $e) {
                    http_response_code(400);
                    //echo json_encode(['msg' => $e->getMessage()]);
                    echo json_encode(['msg' => 'Error al obtener mantenimientos.']);
            }
            exit();

        case 'delMantGalpon': 
            header('Content-Type: application/json');
            try {
                if( !isset($_GET['idMantenimientoGalpon']) || $_GET['idMantenimientoGalpon'] === '' )
                {
                    http_response_code(400);
                    echo json_encode(['msg' => 'Error: mantenimiento no seleccionado.']);
                    exit();
                }
                $oMantenimientoGalpon = new mantenimientoGalpon();
                if ($oMantenimientoGalpon->deleteMantenimientoGalponId($_GET['idMantenimientoGalpon'])) {
                    http_response_code(200);
                    echo json_encode(['msg' => 'Eliminado correctamente.']);
                }
            } catch (RuntimeException $e) {
                http_response_code(400);
                //echo json_encode(['msg' => $e->getMessage()]);
                echo json_encode(['msg' => 'Error al eliminar']);
            }
            exit();
        break;
*/
        default:
            exit();
        break;
    }
}