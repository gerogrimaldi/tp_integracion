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
        //En caso de no conectar, aparece la página de error de BD.
        echo "<h1 class='bg-white'>Conexion Correcta</h1>";
    }

    public function crearBD()
    {
        mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
        try {
            $this->mysqli = mysqli_connect("127.0.0.1", "root", "");
            $this->mysqli->set_charset("utf8mb4");
            echo "Creando base de datos 'granjas'";
            $sql="CREATE DATABASE IF NOT EXISTS granjas";
            $this->mysqli->query($sql);
        } catch (mysqli_sql_exception $e) {
            echo("Error: " . $e->getMessage());
            exit; 
        }
    }

    public function cargarDatos()
    {
        //Todavia no sabemos qué datos cargar, placeholder
        mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
        try {
            $this->mysqli = mysqli_connect("127.0.0.1", "root", "");
            $this->mysqli->set_charset("utf8mb4");
            //$sql="CREATE DATABASE IF NOT EXISTS granjas";
            //$this->mysqli->query($sql);
        } catch (mysqli_sql_exception $e) {
            echo("Error: " . $e->getMessage());
            exit; 
        }
    }
}