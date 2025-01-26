<?php
$mensaje = '';

class tipoMantenimiento{
    private $idTipoMantenimiento;
    private $nombreMantenimiento;

    public function __construct()
    {
        require_once 'model/conexion.php';  
        $this->mysqli = new mysqli(DB_HOST,DB_USER,DB_PASS,DB_NAME);
        if ($this->mysqli->connect_error) { die("Error de conexión a la base de datos: " . $this->mysqli->connect_error); }
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

    public function setIDTipoMant($idTipoMantenimiento)
    {
        $this->idTipoMantenimiento = $idTipoMantenimiento; 
    }

    public function setNombreMantenimiento($nombreMantenimiento)
    {
        $this->nombreMantenimiento = $nombreMantenimiento; 
    }

    public function getTipoMantenimientos()
    {
        $sql = "SELECT idTipoMantenimiento, nombre FROM tipoMantenimiento";
        $result = $this->mysqli->query($sql);
        $data = [];
        if ($result->num_rows > 0) { while($row = $result->fetch_assoc()) { $data[] = $row; } }
        $json_data = json_encode($data);
        return $json_data;
    }

    public function save(){
    // Verificar si ya existe
        $sqlCheck = "SELECT tipoMantenimiento.nombre FROM tipoMantenimiento WHERE nombre = ?";
        $stmtCheck = $this->mysqli->prepare($sqlCheck);
        if (!$stmtCheck) { die("Error en la preparación de la consulta de verificación: " . $this->mysqli->error); }
        $stmtCheck->bind_param("s", $this->nombreMantenimiento);
        $stmtCheck->execute();
        $stmtCheck->store_result();
        if ($stmtCheck->num_rows > 0) {
            echo '<script type="text/javascript">alert("Error: el tipo de mantenimiento ya existe.");</script>';
            $stmtCheck->close();
            return false; 
        }
        $stmtCheck->close();
    // Insertar
        $sql = "INSERT INTO tipoMantenimiento (idTipoMantenimiento, nombre) VALUES (?, ?)";
        $stmt = $this->mysqli->prepare($sql);
        if (!$stmt) { die("Error en la preparación de la consulta de inserción: " . $this->mysqli->error); }
    // Enlaza los parámetros y ejecuta la consulta
        $stmt->bind_param("is", $this->idTipoMantenimiento, $this->nombreMantenimiento);
        if (!$stmt->execute()){
            throw new RuntimeException('Error al ejecutar la consulta: ' . $stmt->error); }
    // Cerrar la consulta, NO DEBEMOS CERRAR LA CONEXIÓN, MIENTRAS EXISTA EL OBJETO.
        $stmt->close();
        return true;
    }

    public function update()
    {
        $sql = "UPDATE tipoMantenimiento SET nombre = ? WHERE idTipoMantenimiento = ?";
        $stmt = $this->mysqli->prepare($sql);
        if (!$stmt) {
            die("Error en la preparación de la consulta de actualización: " . $this->mysqli->error);
        }
        $stmt->bind_param("si", $this->nombreMantenimiento, $this->idTipoMantenimiento);
        if (!$stmt->execute()) {
            throw new RuntimeException('Error al ejecutar la consulta: ' . $stmt->error);
        }
        $stmt->close();
    }

    public function deleteTipoMantID($idTipoMantenimiento)
    {
        if ($this->mysqli === null) { throw new RuntimeException('La conexión a la base de datos no está inicializada.'); }
        $sql = "DELETE FROM tipoMantenimiento WHERE idTipoMantenimiento = ?";
        $stmt = $this->mysqli->prepare($sql);
        if ($stmt === false) { throw new RuntimeException('Error al preparar la consulta: ' . $this->mysqli->error); }
        $stmt->bind_param('i', $idTipoMantenimiento);
        if (!$stmt->execute()) { throw new RuntimeException('Error al ejecutar la consulta: ' . $stmt->error); }
        $stmt->close(); 
    }
}

class mantenimientoGranja{
    private $idMantenimientoGranja;
    private $fecha;
	private $idGranja;
    private $mysqli;
    
    public function __construct()
    {
        require_once 'model/conexion.php';  
        $this->mysqli = new mysqli(DB_HOST,DB_USER,DB_PASS,DB_NAME);
        if ($this->mysqli->connect_error) { die("Error de conexión a la base de datos: " . $this->mysqli->connect_error); }
    }
    
    public function setIdMantGranja($idMantenimiento)
    {
        if ( ctype_digit($idMantenimiento)==true )
        {
            $this->idMantenimientoGranja = $idMantenimiento;
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
        if ( ctype_digit( $idTipoMantenimiento )==true ){
            $this->idTipoMantenimiento = $idTipoMantenimiento;
        }  
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

    public function getMantGranjas($idGranjaFiltro)
    {
        $sql = "SELECT mantenimientoGranja.idMantenimientoGranja, mantenimientoGranja.fecha, mantenimientoGranja.idGranja,
        mantenimientoGranja.idTipoMantenimiento, TipoMantenimiento.nombre FROM mantenimientoGranja 
        INNER JOIN tipoMantenimiento ON (tipoMantenimiento.idTipoMantenimiento = mantenimientoGranja.idTipoMantenimiento)
        WHERE mantenimientoGranja.idGranja=".$idGranjaFiltro;
        $result = $this->mysqli->query($sql);
        $data = []; // Array para almacenar los resultados
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
        }
        $json_data = json_encode($data);
        return $json_data;
    }

    public function getGranjas()
    {
        $sql = "SELECT idGranja, nombre, habilitacionSenasa, metrosCuadrados, ubicacion FROM granja";
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
        $sql = "INSERT INTO mantenimientoGranja (idMantenimientoGranja, fecha, idGranja, idTipoMantenimiento) VALUES (?, ?, ?, ?)";
        $stmt = $this->mysqli->prepare($sql);
        if (!$stmt) {die("Error en la preparación de la consulta de inserción: " . $this->mysqli->error);}
        $stmt->bind_param("isii", $this->idMantenimientoGranja, $this->fecha, $this->idGranja, $this->idTipoMantenimiento);
        if (!$stmt->execute()) {throw new RuntimeException('Error al ejecutar la consulta: ' . $stmt->error);}
        $stmt->close();
        return true;
    }

    /* No se usará de momento, se desactivaron los botones editar.
    public function updateMantenimientoGalpon()
    {
        $sql = "UPDATE mantenimientoGalpon SET fecha = ?, idGalpon = ?, idTipoMantenimiento = ? WHERE idMantenimientoGalpon = ?";
        $stmt = $this->mysqli->prepare($sql);
        if (!$stmt) { die("Error en la preparación de la consulta de actualización: " . $this->mysqli->error);}
        $stmt->bind_param("siiii", $this->fecha, $this->idGalpon, $this->idTipoMantenimiento, $this->idMantenimiento);
        if (!$stmt->execute()) {  throw new RuntimeException('Error al ejecutar la consulta: ' . $stmt->error);}
        $stmt->close();
        $this->mysqli->close();
    }

    public function updateMantenimientoGranja()
    {
        $sql = "UPDATE galpon SET identificacion = ?, idTipoAve = ?, capacidad = ?, idGranja = ? WHERE idGalpon = ?";
        $stmt = $this->mysqli->prepare($sql);
        if (!$stmt) {die("Error en la preparación de la consulta de actualización: " . $this->mysqli->error);}
        $stmt->bind_param("siiii", $this->identificacion, $this->idTipoAve, $this->capacidad, $this->idGranja, $this->idGalpon);
        if (!$stmt->execute()) {throw new RuntimeException('Error al ejecutar la consulta: ' . $stmt->error);}
        $stmt->close();
        $this->mysqli->close();
    } */

    public function deleteMantenimientoGranjaId($idMantenimientoGranja)
    {
        if ($this->mysqli === null) { throw new RuntimeException('La conexión a la base de datos no está inicializada.');}
        $sql = "DELETE FROM mantenimientoGranja WHERE idMantenimientoGranja = ?";
        $stmt = $this->mysqli->prepare($sql);
        if ($stmt === false) {throw new RuntimeException('Error al preparar la consulta: ' . $this->mysqli->error);}
        $stmt->bind_param('i', $idMantenimientoGranja);
        if (!$stmt->execute()) { throw new RuntimeException('Error al ejecutar la consulta: ' . $stmt->error); }
        $stmt->close();
    }
}

class mantenimientoGalpon{
    private $idMantenimientoGalpon;
    private $fecha;
    private $idGalpon;
    private $mysqli;

    public function __construct()
    {
        require_once 'model/conexion.php';  
        $this->mysqli = new mysqli(DB_HOST,DB_USER,DB_PASS,DB_NAME);
        if ($this->mysqli->connect_error) { die("Error de conexión a la base de datos: " . $this->mysqli->connect_error); }
    }

    public function setIdMantGalpon($idMantenimiento)
    {
        if ( ctype_digit($idMantenimiento)==true )
        {
            $this->idMantenimientoGalpon = $idMantenimiento;
        }
    }

    public function setIdGalpon($idGalpon)
    {
        if ( ctype_digit($idGalpon)==true )
        {
            $this->idGalpon = $idGalpon;
        }
    }

    public function setFecha($fecha)
    {
        $this->fecha = $fecha;
    }

    public function setIdTipoMantenimiento($idTipoMantenimiento)
    {
        if ( ctype_digit( $idTipoMantenimiento )==true ){
            $this->idTipoMantenimiento = $idTipoMantenimiento;
        }  
    }

    public function setMaxIDMantGalpon()
    {
        $sql = "SELECT MAX(idMantenimientoGalpon) AS maxID FROM mantenimientoGalpon  ";
        $result = $this->mysqli->query($sql);
        $data = [];
        if ($result && $row = $result->fetch_assoc()) {
            $maxID = $row['maxID'] ?? 0;
            $this->idMantenimientoGalpon = $maxID + 1; 
        }else {
            echo "Error al obtener el máximo idMantenimiento: " . $this->mysqli->error;
        }
    }

    public function getMantGalpon($idGalponFiltro)
    {
        $sql = "SELECT mantenimientoGalpon.idMantenimientoGalpon, mantenimientoGalpon.fecha, mantenimientoGalpon.idGalpon,
        mantenimientoGalpon.idTipoMantenimiento, tipoMantenimiento.nombre FROM mantenimientoGalpon 
        INNER JOIN tipoMantenimiento ON (tipoMantenimiento.idTipoMantenimiento = mantenimientoGalpon.idTipoMantenimiento)
        WHERE mantenimientoGalpon.idGalpon=".$idGalponFiltro;
        $result = $this->mysqli->query($sql);
        $data = []; // Array para almacenar los resultados
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
        }
        $json_data = json_encode($data);
        return $json_data;
    }

    public function getGalpones()
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
        $sql = "INSERT INTO mantenimientoGalpon (idMantenimientoGalpon, fecha, idGalpon, idTipoMantenimiento) VALUES (?, ?, ?, ?)";
        $stmt = $this->mysqli->prepare($sql);
        if (!$stmt) {die("Error en la preparación de la consulta de inserción: " . $this->mysqli->error);}
        $stmt->bind_param("isii", $this->idMantenimientoGalpon, $this->fecha, $this->idGalpon, $this->idTipoMantenimiento);
        if (!$stmt->execute()) {throw new RuntimeException('Error al ejecutar la consulta: ' . $stmt->error);}
        $stmt->close();
        return true;
    }

    public function deleteMantenimientoGalponId($idMantenimientoGalpon)
    {
        if ($this->mysqli === null) {throw new RuntimeException('La conexión a la base de datos no está inicializada.');}
        $sql = "DELETE FROM mantenimientoGalpon WHERE idMantenimientoGalpon = ?";
        $stmt = $this->mysqli->prepare($sql);
        if ($stmt === false) {throw new RuntimeException('Error al preparar la consulta: ' . $this->mysqli->error); }
        $stmt->bind_param('i', $idMantenimientoGalpon);
        if (!$stmt->execute()) { throw new RuntimeException('Error al ejecutar la consulta: ' . $stmt->error);}
        $stmt->close();
    }
}


