<?php

class vacuna{
	//(idVacuna, nombre, idViaAplicacion, marca, enfermedad)
    private $idVacuna;
    private $nombre;
    private $idViaAplicacion;
    private $marca;
    private $enfermedad;
    private $mysqli;
    
    public function __construct()
    {
        // Inicializar conexión a base de datos
        require_once 'model/conexion.php';  
        $this->mysqli = new mysqli(DB_HOST,DB_USER,DB_PASS,DB_NAME);
        if ($this->mysqli->connect_error) {
            die("Error de conexión a la base de datos: " . $this->mysqli->connect_error);
        }
    }

    public function setIdVacuna($idVacuna)
    {
        if ( ctype_digit($idVacuna)==true )
        {
            $this->idVacuna = $idVacuna;
        }
    }

    public function setIdViaApliacion($idViaAplicacion)
    {
        if ( ctype_digit($idViaAplicacion)==true )
        {
            $this->idViaAplicacion = $idViaAplicacion;
        }
    }

    public function setNombre($nombre)
    {
        $this->nombre = trim($nombre);
    }

    public function setMarca($marca)
    {
        $this->marca = trim($marca); 
    }

    public function setEnfermedad($enfermedad)
    {
        $this->enfermedad = trim($enfermedad); 
    }

    public function setMaxIDVacuna()
    {
        $sql = "SELECT MAX(idVacuna) AS maxID FROM vacuna  ";
        $result = $this->mysqli->query($sql);
        $data = [];
        if ($result && $row = $result->fetch_assoc()) {
            $maxID = $row['maxID'] ?? 0;
            $this->idVacuna = $maxID + 1; 
        }else {
            echo "Error al obtener el máximo idVacuna: " . $this->mysqli->error;
        }
    }

    public function getall()
    {
        $sql = "SELECT vacuna.idVacuna, vacuna.nombre, vacuna.idViaAplicacion, vacuna.marca, vacuna.enfermedad, viaAplicacion.idViaAplicacion, viaAplicacion.via FROM vacuna 
                INNER JOIN viaAplicacion ON (vacuna.idViaAplicacion = viaAplicacion.idViaAplicacion)";
        $result = $this->mysqli->query($sql);
        $data = [];
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
        }
        $this->mysqli->close();
        $json_data = json_encode($data);
        return $json_data;
    }

    public function getVacunaPorId($idVacuna)
    {
        $sql = "SELECT vacuna.idVacuna, vacuna.nombre, vacuna.idViaAplicacion, vacuna.marca, vacuna.enfermedad, viaAplicacion.idViaAplicacion, viaAplicacion.nombre FROM vacuna 
                INNER JOIN viaAplicacion ON (vacuna.idViaAplicacion = viaAplicacion.idViaAplicacion) WHERE idVacuna=".$idVacuna;
        if ( $resultado = $this->mysqli->query($sql) )
		{
			if ( $resultado->num_rows > 0 )
 			{
                 return $resultado;
			}else{
                return false;
            }
        }
        unset($resultado);
        $this->mysqli->close();
    }

    public function save()
    {
        $sqlCheck = "SELECT idVacuna FROM vacuna WHERE idVacuna = ?";
        $stmtCheck = $this->mysqli->prepare($sqlCheck);
        if (!$stmtCheck) { die("Error en la preparación de la consulta de verificación: " . $this->mysqli->error); }
        $stmtCheck->bind_param("i", $this->idVacuna);
        $stmtCheck->execute();
        $stmtCheck->store_result();
        if ($stmtCheck->num_rows > 0) {
            echo '<script type="text/javascript">alert("Error: La vacuna ya existe.");</script>';
            $stmtCheck->close();
            $this->mysqli->close();
            return false; 
        }
        $stmtCheck->close();
        // Inserción
        $sql = "INSERT INTO vacuna (idVacuna, nombre, idViaAplicacion, marca, enfermedad) 
                VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->mysqli->prepare($sql);
        if (!$stmt) {
            die("Error en la preparación de la consulta de inserción: " . $this->mysqli->error);
        }
        $stmt->bind_param("isiss", $this->idVacuna, $this->nombre, $this->idViaAplicacion, $this->marca, $this->enfermedad);
        if (!$stmt->execute()) {
            throw new RuntimeException('Error al ejecutar la consulta: ' . $stmt->error);
        }
        $stmt->close();
        $this->mysqli->close();
        return true;
    }

    public function update()
    {
        $sql = "UPDATE vacuna SET nombre = ?, idViaAplicacion = ?, marca = ?, enfermedad = ? WHERE idVacuna = ?";
        $stmt = $this->mysqli->prepare($sql);
        if (!$stmt) {
            die("Error en la preparación de la consulta de actualización: " . $this->mysqli->error);
        }
        $stmt->bind_param("sissi", $this->nombre, $this->idViaAplicacion, $this->marca, $this->enfermedad, $this->idVacuna);
        if (!$stmt->execute()) {
            throw new RuntimeException('Error al ejecutar la consulta: ' . $stmt->error);
        }
        $stmt->close();
        $this->mysqli->close();
    }

    public function deleteVacunaPorId($idVacuna)
    {
        if ($this->mysqli === null) {
            throw new RuntimeException('La conexión a la base de datos no está inicializada.');
        }
        $sql = "DELETE FROM vacuna WHERE idVacuna = ?";
        $stmt = $this->mysqli->prepare($sql);
        if ($stmt === false) {
            throw new RuntimeException('Error al preparar la consulta: ' . $this->mysqli->error);
        }
        $stmt->bind_param('i', $idVacuna);
        if (!$stmt->execute()) {
            throw new RuntimeException('Error al ejecutar la consulta: ' . $stmt->error);
        }
        $stmt->close();
    }

}