<?php

class vacuna{
	//(idGranja, nombre, habilitacionSenasa, metrosCuadrados, ubicacion)
    private $idVacuna;
    private $mysqli;
    
    public function __construct()
    {
        require_once 'model/conexion.php';  
        
        // Inicializar la conexión
        $this->mysqli = new mysqli(DB_HOST,DB_USER,DB_PASS,DB_NAME);
    
        // Verificar si la conexión fue exitosa
        if ($this->mysqli->connect_error) {
            die("Error de conexión a la base de datos: " . $this->mysqli->connect_error);
        }
    }
}