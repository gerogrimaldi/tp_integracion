<?php
// session_start();
require_once 'model/EventModel.php';
$validacion = false;
// echo '<h2 class="bg-white"> HOLA </h2> ';
if ( !empty($_POST) ) 
{

    if ( $_POST['btEvento'] == 'registrarEvento' )
    {
        // echo "<h1 class='bg-white'>ENTRO registrar<h1>";

        $oEvento = new Evento();

            $oEvento->setNombreEvento($_POST['nombre']);
            $oEvento->setLugarEvento($_POST['direccion']);
            $oEvento->setDate($_POST['date']);

        $todo_ok = $oEvento->save();

  
        require_once('view/msjNuevoEvento.php');
    

    }


    if ( $_POST['btEvento'] == 'editarEvento' )
    {
        require_once 'model/EventModel.php';

        $oEvento = new Evento();

            $oEvento->setIdEvento($_POST['idEvento']);
            $oEvento->setNombreEvento($_POST['nombre']);
            $oEvento->setLugarEvento($_POST['direccion']);
            $oEvento->setDate($_POST['date']);

        $oEvento->update();

        require_once('view/msjEditoEvento.php');

    }

}


if ( !empty($_GET) ) 
{

    if ($_GET['opt']=='list')
    {

        $oEvento = new Evento();
        
        $resultado = $oEvento->getall();
			
    }

    if ($_GET['opt']=='edit')
    {

        $oEvento = new Evento();
            
            $resultado = $oEvento->getEventoPorId($idEvento);

            if ( $resultado!=false )
            {
                $unaEvento = $resultado->fetch_array();
                $idEvento  = $unaEvento['idEvento'];
                $nombreEvento   = $unaEvento['nombreEvento'];
                $fechaEvento    = $unaEvento['fechaEvento'];
                $lugarEvento   = $unaEvento['lugarEvento'];
            }

    }

    if ($_GET['opt']=='delete')
    {

        $oEvento = new Evento();
            
            $resultado = $oEvento->deleteEventoPorId($idEvento);

    }

}