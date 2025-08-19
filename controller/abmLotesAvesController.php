<?php
require_once 'model/lotesAvesModel.php';

if (isset($_GET['ajax']))
{
    switch ($_GET['ajax']) {
    // ------------------------------------
    // SOLICITUDES AJAX - GRANJAS
    // ------------------------------------
        case 'getTipoAve':
            header('Content-Type: application/json');
            try {
                $oTipoAves = new tipoAves();
                $TipoAves = $oTipoAves->getall();
                if ($TipoAves) {
                    http_response_code(200);
                    echo json_encode($TipoAves);
                }else{
                    http_response_code(200);
                    echo '[]';
                }
            } catch (RuntimeException $e) {
                    http_response_code(400);
                    echo json_encode(['msg' => $e->getMessage()]);
            }
            exit();

        case 'delTipoAve': 
            header('Content-Type: application/json');
            try {
                if (!isset($_GET['idTipoAve']) || $_GET['idTipoAve'] === '')
                {
                    http_response_code(400);
                    echo json_encode(['msg' => 'Error: tipo de ave no seleccionado.']);
                    exit();
                }
                $oTipoAves = new tipoAves();
                $idTipoAve = (int)$_GET['idTipoAve'];

                if ($oTipoAves->deleteTipoAve($idTipoAve)) {
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

        case 'addTipoAve':
            header('Content-Type: application/json');
            try {
                if(empty($_POST['nombre']) )
                {
                    http_response_code(400);
                    echo json_encode(['msg' => 'Error: hay campos vacíos.']);
                    exit();
                }
                $oTipoAves = new tipoAves();
                // Respuesta al frontend
                if ($oTipoAves->agregarNuevo($_POST['nombre'])) {
                    http_response_code(200);
                    echo json_encode(['msg' => 'Tipo de ave agregada correctamente']);
                } 
            } catch (RuntimeException $e) {
                    http_response_code(400);
                    // echo json_encode(['error' => $e->getMessage()]);
                    echo json_encode(['msg' => 'Error al añadir.']);
            }
            exit();
        break;

        case 'editTipoAve':
            header('Content-Type: application/json');
            try {
                if(!isset($_POST['nombre']) || $_POST['nombre'] === '' || !isset($_POST['idTipoAve']) || $_POST['idTipoAve'] === '')
                {
                    error_log($_POST['nombre'] . ' - ' . $_POST['idTipoAve']);
                    http_response_code(400);
                    echo json_encode(['msg' => 'Error: hay campos vacíos.']);
                    exit();
                }
                $oTipoAves = new tipoAves();
                if ($oTipoAves->updateTipoAve($_POST['idTipoAve'], $_POST['nombre'])) {
                    http_response_code(200);
                    echo json_encode(['msg' => 'Cambios guardados correctamente']);
                } 
            }catch (RuntimeException $e) {
                    http_response_code(400);
                    echo json_encode(['msg' => 'Error al editar.']);
            }
            exit();
        break;

        default:
            exit();
        break;
    }
}