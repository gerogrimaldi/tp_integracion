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
        echo "<h1 class='bg-white'>Creando base de datos...</h1>";
        $oTest = new Test();
        $oTest->crearBD();
    }

    if ( $_POST['btTest'] == 'borrarDB' )
    {
        echo "<h1 class='bg-white'>Borrando base de datos...</h1>";
        $oTest = new Test();
        $oTest->borrarBD();
    }

    if ( $_POST['btTest'] == 'cargarDatos' )
    {
        echo "<h1 class='bg-white'>Cargando datos de prueba...</h1>";
        $oTest = new Test();
        $oTest->cargarDatos();
    }

    if ( $_POST['btTest'] == 'crearTablas' )
    {
        echo "<h1 class='bg-white'>Creando tablas...</h1>";
        $oTest = new Test();
        $oTest->crearTablas();
    }

    if ( $_POST['btTest'] == 'backupDB' )
    {
        echo "<h1 class='bg-white'>Realizando backup...</h1>";
        $oTest = new Test();
        $oTest->backupDB();
    }

}