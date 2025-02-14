<?php
$mensaje = '';

class galpon{
	/*
    Tabla SQL al momento de crear este Model:
    (idGalpon, identificacion, idTipoAve, capacidad, idGranja)
    */
    private $idGalpon;
    private $identificacion;
	private $idTipoAve;
    private $capacidad;
    private $idGranja;
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
    
    public function setIdGalpon($idGalpon)
    {
        if ( ctype_digit($idGalpon)==true ) // Evalua que el ID sea positivo y entero
        {
            $this->idGalpon = $idGalpon;
        }
    }

    public function setIdGranja($idGranja)
    {
        if ( ctype_digit($idGranja)==true ) // Evalua que el ID sea positivo y entero
        {
            $this->idGranja = $idGranja;
        }
    }

    public function setIdentificacion($identificacion)
    {
        $this->identificacion = trim($identificacion); // Eliminar espacios en blanco al inicio y al final y asignarlo al objeto.
    }

    public function setIdTipoAve($idTipoAve)
    {
        if ( ctype_digit( $idTipoAve )==true ){
            $this->idTipoAve = $idTipoAve;
        }  
    }

    public function setCapacidad($capacidad)
    {
        $this->capacidad = $capacidad; 
    }

    public function setMaxID()
    {
        //Función para obtener el ID que sigue en la tabla galpon, no usamos autoincrement.
        // Leer datos de la tabla 'galpon',
        $sql = "SELECT MAX(idGalpon) AS maxID FROM galpon  ";
        $result = $this->mysqli->query($sql);
        $data = []; // Array para almacenar los datos
        //La consulta devuelve un solo resultado.
        if ($result && $row = $result->fetch_assoc()) {
            $maxID = $row['maxID'] ?? 0; // Si no hay registros, maxID será 0
            $this->idGalpon = $maxID + 1; // Incrementa el ID máximo en 1
        }else {
            echo "Error al obtener el máximo idGalpon: " . $this->mysqli->error;
        }
    }

    public function getall($idGranja)
    {
        // Leer datos de la tabla 'galpon',
        $sql = "SELECT galpon.idGalpon, galpon.identificacion, galpon.idTipoAve, galpon.capacidad, galpon.idGranja, tipoave.nombre FROM galpon INNER JOIN tipoave ON (tipoave.idTipoAve = galpon.idTipoAve) WHERE idGranja=".$idGranja;
        $result = $this->mysqli->query($sql);
        $data = []; // Array para almacenar los resultados
        
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
        }
        // Convertir el array de datos a formato JSON
        $json_data = json_encode($data);
        return $json_data;
    }

    public function getTiposAves()
    {
        // Leer datos de la tabla tipoAve (idTipoAve, nombre)
        $sql = "SELECT idTipoAve, nombre FROM tipoAve";
        $result = $this->mysqli->query($sql);
        $data = []; // Array para almacenar los resultados
        
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
        }
        // Convertir el array de datos a formato JSON
        $json_data = json_encode($data);
        return $json_data;
    }

    public function getGalponPorId($idGalpon)
    {
        $sql = "SELECT idGalpon, identificacion, idTipoAve, capacidad, idGranja FROM galpon WHERE idGalpon=".$idGalpon;
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

    public function getGalponesMasGranjas()
    {
        $sql = "SELECT galpon.idGalpon, galpon.identificacion, galpon.idTipoAve, galpon.capacidad, galpon.idGranja, granja.nombre FROM galpon
        INNER JOIN granja ON (galpon.idGranja = granja.idGranja)";
        $result = $this->mysqli->query($sql);
        $data = [];
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
        }
        $json_data = json_encode($data);
        return $json_data;
    }

    public function save()
    {
        // Consulta para verificar si el galpon ya existe
        $sqlCheck = "SELECT idGalpon FROM galpon WHERE idGalpon = ?";
        $stmtCheck = $this->mysqli->prepare($sqlCheck);

        if (!$stmtCheck) {
            die("Error en la preparación de la consulta de verificación: " . $this->mysqli->error);
        }

        // Enlazar parametro
        $stmtCheck->bind_param("i", $this->idGalpon);
        $stmtCheck->execute();
        $stmtCheck->store_result();

        // Verificar
        if ($stmtCheck->num_rows > 0) {
            echo '<script type="text/javascript">alert("Error: La granja ya existe.");</script>';
            $stmtCheck->close();
            $this->mysqli->close();
            return false; 
        }
        $stmtCheck->close();

        // Inserción
        $sql = "INSERT INTO galpon (idGalpon, identificacion, idTipoAve, capacidad, idGranja) 
                VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->mysqli->prepare($sql);
        if (!$stmt) {
            die("Error en la preparación de la consulta de inserción: " . $this->mysqli->error);
        }

        // Enlaza los parámetros y ejecuta la consulta
        $stmt->bind_param("isiii", $this->idGalpon, $this->identificacion, $this->idTipoAve, $this->capacidad, $this->idGranja);
        if (!$stmt->execute()) {
            throw new RuntimeException('Error al ejecutar la consulta: ' . $stmt->error);
        }
        
        // Cerrar la consulta y la conexión
        $stmt->close();
        $this->mysqli->close();
        return true;
    }

    public function update()
    {

        // Preparar la consulta para actualizar los datos del Granja
        $sql = "UPDATE galpon SET identificacion = ?, idTipoAve = ?, capacidad = ?, idGranja = ? WHERE idGalpon = ?";
        $stmt = $this->mysqli->prepare($sql);
        if (!$stmt) {
            die("Error en la preparación de la consulta de actualización: " . $this->mysqli->error);
        }
        // Enlazar parámetros y ejecutar la consulta
        $stmt->bind_param("siiii", $this->identificacion, $this->idTipoAve, $this->capacidad, $this->idGranja, $this->idGalpon);
        if (!$stmt->execute()) {
            throw new RuntimeException('Error al ejecutar la consulta: ' . $stmt->error);
        }
        // Cerrar la consulta
        $stmt->close();
        $this->mysqli->close();
    }

    public function deleteGalponPorId($idGalpon)
    {

        if ($this->mysqli === null) {
            throw new RuntimeException('La conexión a la base de datos no está inicializada.');
        }
        
        // Usar una consulta preparada para evitar inyección SQL
        $sql = "DELETE FROM galpon WHERE idGalpon = ?";
        $stmt = $this->mysqli->prepare($sql);

        if ($stmt === false) {
            throw new RuntimeException('Error al preparar la consulta: ' . $this->mysqli->error);
        }

        // Enlazar el parámetro a la consulta
        $stmt->bind_param('i', $idGalpon);

        // Ejecutar la consulta
        if (!$stmt->execute()) {
            throw new RuntimeException('Error al ejecutar la consulta: ' . $stmt->error);
        }

        // Cerrar el statement
        $stmt->close();
    }

}