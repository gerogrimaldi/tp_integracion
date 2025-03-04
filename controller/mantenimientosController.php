<?php
require_once 'model/mantenimientosModel.php';
require_once 'model/granjaModel.php';
require_once 'model/galponModel.php';
$validacion = false;
if ( !empty($_POST) ) 
{
    if (isset($_POST['btMantenimientos'])){
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
}

if ( !empty($_GET) ) 
{

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

    if (isset($_GET['ajax']))
    {
        switch ($_GET['ajax']) {
        // ------------------------------------
        // SOLICITUDES AJAX - TIPO DE MANTENIMIENTOS
        // ------------------------------------
            case 'addTipoMant':
                header('Content-Type: application/json');
                try {
                    $oTipoMantenimiento = new tipoMantenimiento();
                    $oTipoMantenimiento->setMaxIDTipoMant();
                    $oTipoMantenimiento->setNombreMantenimiento( $_POST['nombreMant']);
                    // Respuesta a JS
                    if ($oTipoMantenimiento->save()) {
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

            case 'delTipoMant': 
                header('Content-Type: application/json');
                try {
                    $oTipoMantenimiento = new tipoMantenimiento();
                    $idTipoMant = (int)$_GET['idTipoMant'];

                    if ($oTipoMantenimiento->deleteTipoMantID($idTipoMant)) {
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
        
            case 'editTipoMant':
                header('Content-Type: application/json');
                try {
                    $oTipoMantenimiento = new tipoMantenimiento();
                    $oTipoMantenimiento->setIDTipoMant($_POST['idTipoMant']);
                    $oTipoMantenimiento->setNombreMantenimiento( $_POST['nombreMantEdit']);
                    
                    if ($oTipoMantenimiento->update()) {
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
        
            case 'getTipoMant':
                header('Content-Type: application/json');
                try {
                    $oTipoMantenimiento = new tipoMantenimiento();
                    $tiposMant = $oTipoMantenimiento->getTipoMantenimientos();
                   
                    if ($tiposMant) {
                        http_response_code(200);
                        echo json_encode($tiposMant);
                    }else{
                        echo '[]';
                    }
                } catch (RuntimeException $e) {
                        http_response_code(400);
                        echo json_encode(['error' => $e->getMessage()]);
                }
                exit();
            break;

        // ------------------------------------
        // SOLICITUDES AJAX - MANTENIMIENTOS DE GRANJA
        // ------------------------------------

            case 'getMantGranjas':
                header('Content-Type: application/json');
                /*try {
                    $oTipoMantenimiento = new tipoMantenimiento();
                    $tiposMant = $oTipoMantenimiento->getTipoMantenimientos();
                   
                    if ($tiposMant) {
                        http_response_code(200);
                        echo json_encode($tiposMant);
                    }else{
                        echo '[]';
                    }
                } catch (RuntimeException $e) {
                        http_response_code(400);
                        echo json_encode(['error' => $e->getMessage()]);
                }*/
                exit();

            default:
                exit();
            break;
        }
    }


}