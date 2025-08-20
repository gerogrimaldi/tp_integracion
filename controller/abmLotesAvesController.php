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
    // ------------------------------------
    // SOLICITUDES AJAX - LOTE DE AVES
    // ------------------------------------
        // === Obtener todos los lotes filtrados por granja y fecha de nacimiento ===
        case 'getLotesAves':
            header('Content-Type: application/json');
            //index.php?opt=lotesAves&ajax=getLotes&idGranja=0&desde=2023-07-20&hasta=2025-08-20
            if (!isset($_GET['idGranja']) || $_GET['idGranja'] === '' ||
                !isset($_GET['desde']) || $_GET['desde'] === '' ||
                !isset($_GET['hasta']) || $_GET['hasta'] === ''){
                    http_response_code(400);
                    echo json_encode(['msg' => 'Error: falta aplicar filtros.']);
                    exit();
                }
            try {
                $oLotes = new LoteAves();
                $lotes = $oLotes->getAll($_GET['idGranja'], $_GET['desde'], $_GET['hasta']);
                if ($lotes) {
                    http_response_code(200);
                    echo json_encode($lotes);
                } else {
                    http_response_code(200);
                    echo '[]';
                }
            } catch (RuntimeException $e) {
                http_response_code(400);
                echo json_encode(['msg' => $e->getMessage()]);
            }
            exit();

        // === Obtener un lote por ID ===
        case 'getLoteAvesById':
            header('Content-Type: application/json');
            try {
                if (!isset($_GET['idLoteAves']) || $_GET['idLoteAves'] === '') {
                    http_response_code(400);
                    echo json_encode(['msg' => 'Error: lote no seleccionado.']);
                    exit();
                }
                $oLotes = new LoteAves();
                $id = (int)$_GET['idLoteAves'];
                $lote = $oLotes->getById($id);

                http_response_code(200);
                echo json_encode($lote);
            } catch (RuntimeException $e) {
                http_response_code(400);
                echo json_encode(['msg' => $e->getMessage()]);
            }
            exit();
        break;

        // === Eliminar un lote ===
        case 'delLoteAves': 
            header('Content-Type: application/json');
            try {
                if (!isset($_GET['idLoteAves']) || $_GET['idLoteAves'] === '') {
                    http_response_code(400);
                    echo json_encode(['msg' => 'Error: lote no seleccionado.']);
                    exit();
                }
                $oLotes = new LoteAves();
                $id = (int)$_GET['idLoteAves'];
                if ($oLotes->deleteLoteAves($id)) {
                    http_response_code(200);
                    echo json_encode(['msg' => 'Eliminado correctamente.']);
                }
            } catch (RuntimeException $e) {
                http_response_code(400);
                echo json_encode(['msg' => 'Error al eliminar, tiene registros asociados']);
            }
            exit();
        break;

        // === Agregar un nuevo lote ===
        case 'addLoteAves':
            header('Content-Type: application/json');
            try {
                if (!isset($_GET['identificador']) || $_GET['identificador'] === '' ||
                    !isset($_GET['fechaNac']) || $_GET['fechaNac'] === '' ||
                    !isset($_GET['fechaCompra']) || $_GET['fechaCompra'] === '' ||
                    !isset($_GET['cantidadAves']) || $_GET['cantidadAves'] === '' ||
                    !isset($_GET['idTipoAve']) || $_GET['idTipoAve'] === '' ||
                    !isset($_GET['idGalpon']) || $_GET['idGalpon'] === '')
                {
                    http_response_code(400);
                    echo json_encode(['msg' => 'Error: hay campos vacíos.']);
                    exit();
                }
                $oLotes = new LoteAves();
                if ($oLotes->agregarNuevo(
                        $_POST['identificador'],
                        $_POST['fechaNac'],
                        $_POST['fechaCompra'],
                        (int)$_POST['cantidadAves'],
                        (int)$_POST['idTipoAve']
                    )) {
                    http_response_code(200);
                    echo json_encode(['msg' => 'Lote agregado correctamente']);
                } 
            } catch (RuntimeException $e) {
                http_response_code(400);
                echo json_encode(['msg' => 'Error al añadir.']);
            }
            exit();
        break;

        // === Editar un lote existente ===
        case 'editLoteAves':
            header('Content-Type: application/json');
            try {
                if (!isset($_GET['idLoteAves']) || $_GET['idLoteAves'] === '' ||
                    !isset($_GET['identificador']) || $_GET['identificador'] === '' ||
                    !isset($_GET['fechaNac']) || $_GET['fechaNac'] === '' ||
                    !isset($_GET['fechaCompra']) || $_GET['fechaCompra'] === '' ||
                    !isset($_GET['cantidadAves']) || $_GET['cantidadAves'] === '' ||
                    !isset($_GET['idTipoAve']) || $_GET['idTipoAve'] === '')
                {
                    http_response_code(400);
                    echo json_encode(['msg' => 'Error: hay campos vacíos.']);
                    exit();
                }
                $oLotes = new LoteAves();
                if ($oLotes->updateLoteAves(
                        (int)$_POST['idLoteAves'],
                        $_POST['identificador'],
                        $_POST['fechaNac'],
                        $_POST['fechaCompra'],
                        (int)$_POST['cantidadAves'],
                        (int)$_POST['idTipoAve']
                )) {
                    http_response_code(200);
                    echo json_encode(['msg' => 'Cambios guardados correctamente']);
                } 
            } catch (RuntimeException $e) {
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