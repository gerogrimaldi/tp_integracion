<?php
require_once 'model/testModel.php';
$validacion = false;
if ( !empty($_POST) ) 
{

    if ( $_POST['btTest'] == 'testConnect' )
    {
        echo "<h1 class='bg-white'>Testeando conexión MariaDB</h1>";
        $oTest = new Test();
        $oTest->testConnect();
    }

    if ( $_POST['btTest'] == 'crearBD' )
    {
        echo "<h1 class='bg-white'>Testeando conexión MariaDB</h1>";
        $oTest = new Test();
        $oTest->crearBD();
    }

    if ( $_POST['btTest'] == 'cargarDatos' )
    {
        echo "<h1 class='bg-white'>Cargando datos de prueba...</h1>";
        $oTest = new Test();
        $oTest->cargarDatos();
    }

}
