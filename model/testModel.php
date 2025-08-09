<?php
$mensaje = '';


class Test{
    private $mysqli;

    public function __construct()
    {

    }

    public function testConnect()
    {
        $this->mysqli = new mysqli(DB_HOST,DB_USER,DB_PASS,DB_NAME);
    
        // Verificar si la conexión fue exitosa
        if ($this->mysqli->connect_error) {
            die("Error de conexión a la base de datos: " . $this->mysqli->connect_error);
        }else{
            echo "<h1 class='bg-white'>Conexion Correcta</h1>";
        }
    }

    public function crearBD()
    {
        mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
        try {
            $this->mysqli = new mysqli(DB_HOST,DB_USER,DB_PASS);;
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
            $this->mysqli = new mysqli(DB_HOST,DB_USER,DB_PASS);
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
            // Inicializar la conexión
            $this->mysqli = new mysqli(DB_HOST,DB_USER,DB_PASS,DB_NAME);
            // Verificar si la conexión fue exitosa
            if ($this->mysqli->connect_error) {
                die("Error de conexión a la base de datos: " . $this->mysqli->connect_error);
            }
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
            // Inicializar la conexión
            $this->mysqli = new mysqli(DB_HOST,DB_USER,DB_PASS,DB_NAME);
            // Verificar si la conexión fue exitosa
            if ($this->mysqli->connect_error) {
                die("Error de conexión a la base de datos: " . $this->mysqli->connect_error);
            }
            $sql = file_get_contents('db/Tablas_granjas.sql');
            $this->mysqli->multi_query($sql);
            echo "<h1 class='bg-white'>Tablas creadas con éxito.</h1>";
        } catch (mysqli_sql_exception $e) {
            echo("Error: " . $e->getMessage());
            exit; 
        }
    }
}