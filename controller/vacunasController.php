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

    if ($_GET['opt']=='vacunas')
    {
        $oVacuna = new vacuna();
        $vacunasJSON = $oVacuna->getall();
        $viaAplicacionJSON = $oVacuna->getAllViaAplicacion();
    }
}