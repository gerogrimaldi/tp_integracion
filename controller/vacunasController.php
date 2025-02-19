<?php
require_once 'model/vacunasModel.php';

if ( !empty($_POST) ) 
{
    if ( $_POST['btVacunas'] == 'newVacuna')
    {   
        $oVacuna = new vacuna();
        $oVacuna->setMaxIDVacuna();
        $oVacuna->setIdViaApliacion( $_POST['viaAplicacion'] );
        $oVacuna->setNombre( $_POST['nombre'] );
        $oVacuna->setMarca( $_POST['marca'] );
        $oVacuna->setEnfermedad( $_POST['enfermedad'] );
        $oVacuna->save();
    }

    if ( $_POST['btVacunas'] == 'editVacuna')
    {   
        $oVacuna = new vacuna();
        $oVacuna->setIdVacuna($_POST['idVacuna']);
        $oVacuna->setIdViaApliacion( $_POST['viaAplicacion'] );
        $oVacuna->setNombre( $_POST['nombre'] );
        $oVacuna->setMarca( $_POST['marca'] );
        $oVacuna->setEnfermedad( $_POST['enfermedad'] );
        $oVacuna->update();
    }

    if ( $_POST['btVacunas'] == 'editLoteVacuna')
    {   
        $oLoteVacuna = new loteVacuna();
        $oLoteVacuna->setIdLoteVacuna($_POST['idLoteVacuna']);
        $oLoteVacuna->numeroLote( $_POST['numeroLote'] );
        $oLoteVacuna->fechaCompra( $_POST['fechaCompra'] );
        $oLoteVacuna->cantidad( $_POST['cantidad'] );
        $oLoteVacuna->vencimiento( $_POST['vencimiento'] );
        $oLoteVacuna->idVacuna( $_POST['idVacuna'] );
        $oLoteVacuna->update();
    }

    if ( $_POST['btVacunas'] == 'newLoteVacuna')
    {   
        $oLoteVacuna = new loteVacuna();
        $oVacuna->setMaxIDLoteVacuna();
        $oLoteVacuna->numeroLote( $_POST['numeroLote'] );
        $oLoteVacuna->fechaCompra( $_POST['fechaCompra'] );
        $oLoteVacuna->cantidad( $_POST['cantidad'] );
        $oLoteVacuna->vencimiento( $_POST['vencimiento'] );
        $oLoteVacuna->idVacuna( $_POST['idVacuna'] );
        $oLoteVacuna->save();
    }
}

if ( !empty($_GET) ) 
{
    if (isset($_GET['delete']) && $_GET['delete'] == 'true')
    {
        $oVacuna = new vacuna();
        $idVacuna = (int)$_GET['idVacuna'];
        $oVacuna->deleteVacunaPorId($idVacuna);

        // Recargar pagina para mostrar resultados
        header("Location: index.php?opt=vacunas");
        exit();
    }

    if (isset($_GET['delete']) && $_GET['delete'] == 'lote')
    {
        $oLoteVacuna = new loteVacuna();
        $idLoteVacuna = (int)$_GET['idLoteVacuna'];
        $oLoteVacuna->deleteLoteVacunaPorId($idLoteVacuna);

        // Recargar pagina para mostrar resultados
        header("Location: index.php?opt=vacunas");
        exit();
    }

    if ($_GET['opt']=='vacunas')
    {
        $oVacuna = new vacuna();
        $vacunasJSON = $oVacuna->getall();
        $viaAplicacionJSON = $oVacuna->getAllViaAplicacion();

        $oLoteVacuna = new loteVacuna();
        $loteJSON = $oLoteVacuna->getall();
    }
}