<?php
$mensaje = '';

class galpon{
	/*
    Tabla SQL al momento de crear este Model:

    CREATE TABLE tipoMantenimiento (
    idTipoMantenimiento INT NOT NULL,
    nombre VARCHAR(80),
    PRIMARY KEY (idTipoMantenimiento)
);

CREATE TABLE mantenimientoGranja (
    idMantenimientoGranja INT NOT NULL,
    fecha DATETIME,
    idGranja INT,
    idTipoMantenimiento INT,
    FOREIGN KEY (idGranja) REFERENCES granja(idGranja),
    FOREIGN KEY (idTipoMantenimiento) REFERENCES tipoMantenimiento(idTipoMantenimiento),
    PRIMARY KEY (idMantenimientoGranja)
);

CREATE TABLE mantenimientoGalpon (
    idMantenimientoGalpon INT NOT NULL,
    fecha DATETIME,
    idGalpon INT,
    idTipoMantenimiento INT,
    FOREIGN KEY (idGalpon) REFERENCES galpon(idGalpon),
    FOREIGN KEY (idTipoMantenimiento) REFERENCES tipoMantenimiento(idTipoMantenimiento),
    PRIMARY KEY (idMantenimientoGalpon)
);
    */
    private $idMantenimientoGalpon;
    private $idMantenimientoGranja;
    private $fecha;
	private $idGranja;
    private $idGalpon;
    private $idTipoMantenimiento;
    private $nombreMantenimiento;
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
    
    public function setIdMantenimiento($idMantenimiento)
    {
        if ( ctype_digit($idGalpon)==true ) // Evalua que el ID sea positivo y entero
        {
            $this->idMantenimiento = $idMantenimiento;
        }
    }

    public function setIdGranja($idGranja)
    {
        if ( ctype_digit($idGranja)==true )
        {
            $this->idGranja = $idGranja;
        }
    }

    public function setFecha($fecha)
    {
        $this->fecha = $fecha;
    }

    public function setIdTipoMantenimiento($idTipoMantenimiento)
    {
        if ( ctype_digit( $idTipoAve )==true ){
            $this->idTipoMantenimiento = $idTipoMantenimiento;
        }  
    }

    public function setNombreMantenimiento($nombreMantenimiento)
    {
        $this->nombreMantenimiento = $nombreMantenimiento; 
    }

    public function setMaxIDMantGranja()
    {
        $sql = "SELECT MAX(idMantenimientoGranja) AS maxID FROM mantenimientoGranja  ";
        $result = $this->mysqli->query($sql);
        $data = [];
        if ($result && $row = $result->fetch_assoc()) {
            $maxID = $row['maxID'] ?? 0;
            $this->idMantenimientoGranja = $maxID + 1; 
        }else {
            echo "Error al obtener el máximo idMantenimiento: " . $this->mysqli->error;
        }
    }

    public function setMaxIDMantGalpon()
    {
        $sql = "SELECT MAX(idMantenimientoGalpon) AS maxID FROM mantenimientoGalpon  ";
        $result = $this->mysqli->query($sql);
        $data = [];
        if ($result && $row = $result->fetch_assoc()) {
            $maxID = $row['maxID'] ?? 0;
            $this->mantenimientoGalpon = $maxID + 1; 
        }else {
            echo "Error al obtener el máximo idMantenimiento: " . $this->mysqli->error;
        }
    }

    public function setMaxIDTipoMant()
    {
        $sql = "SELECT MAX(idTipoMantenimiento) AS maxID FROM TipoMantenimiento";
        $result = $this->mysqli->query($sql);
        $data = [];
        if ($result && $row = $result->fetch_assoc()) {
            $maxID = $row['maxID'] ?? 0; // Si no hay registros, maxID será 0
            $this->idTipoMantenimiento = $maxID + 1; // Incrementa el ID máximo en 1
        }else {
            echo "Error al obtener el máximo idTipoMantenimiento: " . $this->mysqli->error;
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

//###########################################################################
// CARGA; UPDATE, DELTE
public function save()
{

    // Consulta para verificar si el Granja ya existe
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

public function updateMantenimientoGalpon()
{
    $sql = "UPDATE mantenimientoGalpon SET fecha = ?, idGalpon = ?, idTipoMantenimiento = ? WHERE idMantenimientoGalpon = ?";
    $stmt = $this->mysqli->prepare($sql);
    if (!$stmt) {
        die("Error en la preparación de la consulta de actualización: " . $this->mysqli->error);
    }
    $stmt->bind_param("siiii", $this->fecha, $this->idGalpon, $this->idTipoMantenimiento, $this->idMantenimiento);
    if (!$stmt->execute()) {
        throw new RuntimeException('Error al ejecutar la consulta: ' . $stmt->error);
    }
    $stmt->close();
    $this->mysqli->close();
}

public function updateMantenimientoGranja()
{
    $sql = "UPDATE galpon SET identificacion = ?, idTipoAve = ?, capacidad = ?, idGranja = ? WHERE idGalpon = ?";
    $stmt = $this->mysqli->prepare($sql);
    if (!$stmt) {
        die("Error en la preparación de la consulta de actualización: " . $this->mysqli->error);
    }
    $stmt->bind_param("siiii", $this->identificacion, $this->idTipoAve, $this->capacidad, $this->idGranja, $this->idGalpon);
    if (!$stmt->execute()) {
        throw new RuntimeException('Error al ejecutar la consulta: ' . $stmt->error);
    }
    $stmt->close();
    $this->mysqli->close();
}

public function updateTipoMantenimiento()
{
    $sql = "UPDATE tipoMantenimiento SET idTipoMantenimiento = ?, nombre = ?";
    $stmt = $this->mysqli->prepare($sql);
    if (!$stmt) {
        die("Error en la preparación de la consulta de actualización: " . $this->mysqli->error);
    }
    $stmt->bind_param("is", $this->identificacion, $this->idTipoAve, $this->capacidad, $this->idGranja, $this->idGalpon);
    if (!$stmt->execute()) {
        throw new RuntimeException('Error al ejecutar la consulta: ' . $stmt->error);
    }
    $stmt->close();
    $this->mysqli->close();
}

public function deleteMantenimientoGranjaId($idMantenimientoGranja)
{
    if ($this->mysqli === null) {
        throw new RuntimeException('La conexión a la base de datos no está inicializada.');
    }
    $sql = "DELETE FROM mantenimientoGranja WHERE idMantenimientoGranja = ?";
    $stmt = $this->mysqli->prepare($sql);
    if ($stmt === false) {
        throw new RuntimeException('Error al preparar la consulta: ' . $this->mysqli->error);
    }
    $stmt->bind_param('i', $idMantenimientoGranja);
    if (!$stmt->execute()) {
        throw new RuntimeException('Error al ejecutar la consulta: ' . $stmt->error);
    }
    $stmt->close();
}

public function deleteMantenimientoGalponId($idMantenimientoGalpon)
{
    if ($this->mysqli === null) {
        throw new RuntimeException('La conexión a la base de datos no está inicializada.');
    }
    $sql = "DELETE FROM mantenimientoGalpon WHERE idMantenimientoGalpon = ?";
    $stmt = $this->mysqli->prepare($sql);
    if ($stmt === false) {
        throw new RuntimeException('Error al preparar la consulta: ' . $this->mysqli->error);
    }
    $stmt->bind_param('i', $idMantenimientoGalpon);
    if (!$stmt->execute()) {
        throw new RuntimeException('Error al ejecutar la consulta: ' . $stmt->error);
    }
    $stmt->close();
}

}