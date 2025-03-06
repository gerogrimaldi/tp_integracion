<?php
$mensaje = '';
class tipoMantenimiento{
    private $idTipoMantenimiento;
    private $nombreMantenimiento;

    public function __construct()
    {
        require_once 'model/conexion.php';  
        $this->mysqli = new mysqli(DB_HOST,DB_USER,DB_PASS,DB_NAME);
        if ($this->mysqli->connect_error) { 
            die("Error de conexión a la base de datos: " . $this->mysqli->connect_error); 
        }
    }

    public function __destruct()
    {
        if ($this->mysqli !== null) {
            $this->mysqli->close();
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
            return true;
        }else {
            return false;
        }
    }

    public function setIDTipoMant($idTipoMantenimiento)
    {
        if ( ctype_digit($idTipoMantenimiento)==true )
        {
            $this->idTipoMantenimiento = $idTipoMantenimiento; 
        }
    }

    public function setNombreMantenimiento($nombreMantenimiento)
    {
        $this->nombreMantenimiento = trim(strip_tags($nombreMantenimiento));
    }

    public function getTipoMantenimientos()
    {
        try {
            $sql = "SELECT idTipoMantenimiento, nombre FROM tipoMantenimiento";
            $result = $this->mysqli->query($sql);
            if (!$result) {
                throw new RuntimeException('Error al ejecutar la consulta: ' . $this->mysqli->error);
            }
            $data = [];
            if ($result->num_rows > 0) { 
                while($row = $result->fetch_assoc()) { 
                    $data[] = $row; 
                } 
            }
            return $data;
        } catch (RuntimeException $e) {
            throw $e;
        }
    }

    public function save(){
        try{
            if ($this->mysqli === null) {
                throw new RuntimeException('La conexión a la base de datos no está inicializada.');
            }
        // Verificar si ya existe el tipo de mantenimiento
            $sqlCheck = "SELECT tipoMantenimiento.nombre FROM tipoMantenimiento WHERE nombre = ?";
            $stmtCheck = $this->mysqli->prepare($sqlCheck);
            if (!$stmtCheck) { 
                throw new RuntimeException("Error en la preparación de la consulta de verificación: " . $this->mysqli->error); 
            }
            $stmtCheck->bind_param("s", $this->nombreMantenimiento);
            $stmtCheck->execute();
            $stmtCheck->store_result();
            if ($stmtCheck->num_rows > 0) {
                throw new RuntimeException('Error, ya existe: ' . $this->mysqli->error);
                $stmtCheck->close();
                return false;
            }
            $stmtCheck->close();
        // Insertar
            $sql = "INSERT INTO tipoMantenimiento (idTipoMantenimiento, nombre) VALUES (?, ?)";
            $stmt = $this->mysqli->prepare($sql);
            if (!$stmt) {
                throw new RuntimeException('Error al preparar consulta: ' . $this->mysqli->error);
            }
        // Enlaza los parámetros y ejecuta la consulta
            $stmt->bind_param("is", $this->idTipoMantenimiento, $this->nombreMantenimiento);
            if (!$stmt->execute()){
                throw new RuntimeException('Error al ejecutar la consulta: ' . $stmt->error); }
            $stmt->close();
            return true;

        }catch (RuntimeException $e) {
            throw $e;
        }
    }

    public function update()
    {
        try{
            if ($this->mysqli === null) {
                throw new RuntimeException('La conexión a la base de datos no está inicializada.');
            }

            $sql = "UPDATE tipoMantenimiento SET nombre = ? WHERE idTipoMantenimiento = ?";
            $stmt = $this->mysqli->prepare($sql);
            if (!$stmt) {
                throw new RuntimeException("Error en la preparación de la consulta de actualización: " . $this->mysqli->error);
            }
            $stmt->bind_param("si", $this->nombreMantenimiento, $this->idTipoMantenimiento);
            if (!$stmt->execute()) {
                throw new RuntimeException('Error al ejecutar la consulta: ' . $stmt->error);
            }
            $stmt->close();
            return true;
        }catch (RuntimeException $e) {
            throw $e;
            return false;
        }
        
    }

    public function deleteTipoMantID($idTipoMantenimiento)
    {
        try{
            if ($this->mysqli === null) { 
                throw new RuntimeException('La conexión a la base de datos no está inicializada.');
            }
            $sql = "DELETE FROM tipoMantenimiento WHERE idTipoMantenimiento = ?";
            $stmt = $this->mysqli->prepare($sql);
            if ($stmt === false) { 
                throw new RuntimeException('Error al preparar la consulta: ' . $this->mysqli->error); 
            }
            $stmt->bind_param('i', $idTipoMantenimiento);
            if (!$stmt->execute()) { 
                // Verificar si es un error de clave foránea
                if ($this->mysqli->errno == 1451) {
                    throw new RuntimeException('El tipo de mantenimiento tiene registros asociados.');
                }else{
                    throw new RuntimeException('Error al ejecutar la consulta: ' . $stmt->error); 
                }
            }
            $stmt->close(); 
            return true;
        }catch (RuntimeException $e){
            throw $e;
            return false;
        }
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
        $this->fecha =  $fecha;
    }

    public function setIdTipoMantenimiento($idTipoMantenimiento)
    {
        if ( ctype_digit($idTipoMantenimiento)==true ){
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
            return true;
        }else {
            //echo "Error al obtener el máximo idMantenimiento: " . $this->mysqli->error;
            return false;
        }
    }

    public function getMantGranjas($idGranjaFiltro)
    {
        try {
            if (empty($idGranjaFiltro) || !ctype_digit($idGranjaFiltro)) {
                throw new RuntimeException('El ID de la granja es inválido.');
            }
            $sql = "SELECT mantenimientoGranja.idMantenimientoGranja, mantenimientoGranja.fecha, mantenimientoGranja.idGranja,
                mantenimientoGranja.idTipoMantenimiento, tipoMantenimiento.nombre 
                FROM mantenimientoGranja 
                INNER JOIN tipoMantenimiento ON (tipoMantenimiento.idTipoMantenimiento = mantenimientoGranja.idTipoMantenimiento)
                WHERE mantenimientoGranja.idGranja = ?";

            $stmt = $this->mysqli->prepare($sql);
            if (!$stmt) {
                throw new RuntimeException('Error al preparar la consulta: ' . $this->mysqli->error);
            }
            $stmt->bind_param('i', $idGranjaFiltro);
            $stmt->execute();
            $result = $stmt->get_result();
            $data = []; // Array para almacenar los resultados
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    $data[] = $row;
                }
            }
            $stmt->close();
            return $data;
            //$json_data = json_encode($data);
        }  catch (RuntimeException $e) {
            throw $e;
        }
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
        try {
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
                // Verificar si es un error de clave foránea
                if ($this->mysqli->errno == 1451) {
                    throw new RuntimeException('No se puede eliminar el mantenimiento porque está siendo utilizado en otros registros.');
                }
                throw new RuntimeException('Error al ejecutar la consulta: ' . $stmt->error);
            }
            $stmt->close();
            return true;
        } catch (RuntimeException $e) {
            // Propagar el error para manejarlo en el controlador
            throw $e;
        }
    }

}