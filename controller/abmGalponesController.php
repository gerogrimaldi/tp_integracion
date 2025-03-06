<?php
require_once 'model/galponModel.php';

if (isset($_GET['ajax']))
{
    switch ($_GET['ajax']) {
    // ------------------------------------
    // SOLICITUDES AJAX - GALPONES
    // ------------------------------------
        case 'getGalponesGranja':
            header('Content-Type: application/json');
            try {
                $oGalpon = new Galpon();
                $idGranja = (int)$_GET['idGranja'];
                if ($galpones = $oGalpon->getall($idGranja)) {
                    http_response_code(200);
                    echo json_encode($galpones);
                }else{
                    //Si no hay registros
                    http_response_code(200);
                    echo '[]';
                }
            } catch (RuntimeException $e) {
                    http_response_code(400);
                    //echo json_encode(['msg' => $e->getMessage()]);
                    echo json_encode(['msg' => 'Error al obtener los galpones.']);
            }
            exit();

        case 'getAllGalpones':
            header('Content-Type: application/json');
            try {
                $oGalpon = new Galpon();
                if ($galponesConGranja = getGalponesMasGranjas()) {
                    http_response_code(200);
                    echo json_encode($galponesConGranja);
                }else{
                    //Si no hay registros
                    http_response_code(200);
                    echo '[]';
                }
            } catch (RuntimeException $e) {
                    http_response_code(400);
                    //echo json_encode(['msg' => $e->getMessage()]);
                    echo json_encode(['msg' => 'Error al obtener los galpones y sus granjas.']);
            }
            exit();

        case 'delGalpon': 
            header('Content-Type: application/json');
            try {
                $oGalpon = new Galpon();
                $idGalpon = (int)$_GET['idGalpon'];

                if ($oGalpon->deleteGalponPorId($idGalpon)) {
                    http_response_code(200);
                    echo json_encode(['msg' => 'Eliminado correctamente.']);
                }
            } catch (RuntimeException $e) {
                http_response_code(400);
                //echo json_encode(['msg' => $e->getMessage()]);
                echo json_encode(['msg' => 'Error al eliminar, tiene registros asociados']);
            }
            exit();
        break;

        case 'addGalpon':
            header('Content-Type: application/json');
            try {
                $oGalpon = new Galpon();
                $oGalpon->setMaxID();
                $oGalpon->setIdentificacion($_POST['identificacion']);
                $oGalpon->setIdTipoAve($_POST['opciones']);
                $oGalpon->setCapacidad($_POST['capacidad']);
                $oGalpon->setIdGranja($_POST['idGranja']);
                
                // Respuesta al frontend
                if ($oGalpon->save()) {
                    http_response_code(200);
                    echo json_encode(['msg' => 'Galpón agregado correctamente']);
                } 
            } catch (RuntimeException $e) {
                    http_response_code(400);
                    // echo json_encode(['error' => $e->getMessage()]);
                    echo json_encode(['msg' => 'Error al añadir.']);
            }
            exit();
        break;

        case 'editGalpon':
            header('Content-Type: application/json');
            try {
                $oGalpon = new Galpon();
                $oGalpon->setIdGalpon ($_POST['idGalpon']);
                $oGalpon->setIdentificacion($_POST['identificacion']);
                $oGalpon->setIdTipoAve($_POST['opcionesEditar']);
                $oGalpon->setCapacidad($_POST['capacidad']);
                $oGalpon->setIdGranja($_POST['idGranja']);
                
                // Respuesta al frontend
                if ($oGalpon->update()) {
                    http_response_code(200);
                    echo json_encode(['msg' => 'Cambios guardados correctamente']);
                    //echo json_encode(['msg' => $e->getMessage()]);
                } 
            } catch (RuntimeException $e) {
                    http_response_code(400);
                    // echo json_encode(['msg' => $e->getMessage()]);
                    echo json_encode(['msg' => 'Error al editar.']);
            }
            exit();
        break;

    // ------------------------------------
    // SOLICITUDES AJAX - TIPOS DE AVES
    // ------------------------------------
        case 'getTipoAves':
            header('Content-Type: application/json');
            try {
                $oGalpon = new Galpon();
                $tiposAves = $oGalpon->getTiposAves();
                if ($tiposAves) {
                    http_response_code(200);
                    echo json_encode($tiposAves);
                }else{
                    http_response_code(200);
                    echo '[]';
                }
            } catch (RuntimeException $e) {
                    http_response_code(400);
                    echo json_encode(['msg' => 'Error al obtener tipos de aves.']);
            }
            exit();
        break;

        default:
            exit();
        break;
    }
}