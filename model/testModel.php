<?php
$mensaje = '';


class Test{
    private $mysqli;

    public function __construct()
    {
        //require_once 'model/conexion.php';  
    }

    public function testConnect()
    {
        require_once 'model/conexion.php';  
        echo "<h1 class='bg-white'>Conexion Correcta</h1>";
    }

    public function crearBD()
    {
        mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
        try {
            require_once 'includes/config.php';
            $this->mysqli = mysqli_connect("$db[host]", "$db[username]", "$db[password]");
            $this->mysqli->set_charset("utf8mb4");
            $sql="CREATE DATABASE IF NOT EXISTS granjas";
            echo "<h1 class='bg-white'>BD creada con éxito.</h1>";
            $this->mysqli->query($sql);
        } catch (mysqli_sql_exception $e) {
            echo("Error: " . $e->getMessage());
            exit; 
        }
    }

    public function borrarBD()
    {
        mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
        try {
            require_once 'includes/config.php';
            $this->mysqli = mysqli_connect("$db[host]", "$db[username]", "$db[password]");
            $this->mysqli->set_charset("utf8mb4");
            $sql="DROP DATABASE granjas";
            echo "<h1 class='bg-white'>BD borrada con éxito.</h1>";
            $this->mysqli->query($sql);
        } catch (mysqli_sql_exception $e) {
            echo("Error: " . $e->getMessage());
            exit; 
        }
    }

    public function cargarDatos()
    {
        try {
            require_once 'model/conexion.php';  
            $sql = file_get_contents('db/Datos_granjas.sql');
            $this->mysqli->multi_query($sql);
            echo "<h1 class='bg-white'>Datos cargados con éxito.</h1>";
        } catch (mysqli_sql_exception $e) {
            echo("Error: " . $e->getMessage());
            exit; 
        }
    }

    public function crearTablas()
    {
        try {
            require_once 'model/conexion.php';  
            $sql = file_get_contents('db/Tablas_granjas.sql');
            $this->mysqli->multi_query($sql);
            echo "<h1 class='bg-white'>Tablas creadas con éxito.</h1>";
        } catch (mysqli_sql_exception $e) {
            echo("Error: " . $e->getMessage());
            exit; 
        }
    }
}