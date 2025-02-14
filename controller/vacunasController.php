<?php
require_once 'model/vacunasModel.php';

if ( !empty($_POST) ) 
{
    if ( $_POST['btVacunas'] == 'addVacuna')
    {
        $oVacuna = new vacuna();
        $oVacuna->setMaxIDVacuna();
        $oVacuna->setIdViaApliacion( $_POST['idViaAplicacionVac'] );
        $oVacuna->setNombre( $_POST['nombreVac'] );
        $oVacuna->setMarca( $_POST['marcaVac'] );
        $oVacuna->setEnfermedad( $_POST['enfermedadVac'] );
        $oVacuna->save();
    }

    if ( $_POST['btVacunas'] == 'editVacuna')
    {
        $oVacuna = new vacuna();
        $oVacuna->setIdVacuna();
        $oVacuna->setIdViaApliacion( $_POST['idViaAplicacionVac'] );
        $oVacuna->setNombre( $_POST['nombreVac'] );
        $oVacuna->setMarca( $_POST['marcaVac'] );
        $oVacuna->setEnfermedad( $_POST['enfermedadVac'] );
        $oVacuna->update();
    }
}

if ( !empty($_GET) ) 
{
    if (isset($_GET['deleteVacuna']) && $_GET['deleteVacuna'] == 'true')
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
    }
}