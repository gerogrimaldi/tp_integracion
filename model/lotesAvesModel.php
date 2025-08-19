<?php
$mensaje = '';

class tipoAves{
    /*INSERT INTO tipoAve (idTipoAve, nombre) VALUES (0, 'Ponedora ligera')*/
    private $idTipoAve;
    private $nombre;
    private $mysqli;

    public function __construct()
    {
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

    public function setIdTipoAve($idTipoAve){$this->idTipoAve = $idTipoAve;}
    public function setNombre($nombre){$this->nombre = htmlspecialchars(strip_tags(trim($nombre)), ENT_QUOTES, 'UTF-8');}

    public function setMaxID()
    {
        try{
            if ($this->mysqli === null) { 
                    throw new RuntimeException('La conexión a la base de datos no está inicializada.');
            }
            // Leer datos de la tabla 'granjas',
            $sql = "SELECT MAX(idTipoAve) AS maxID FROM tipoAve";
            $result = $this->mysqli->query($sql);
            if(!$result){
                throw new RuntimeException('Error al consultar el máximo idTipoAve: ' . $this->mysqli->error);
            }
            if ($result && $row = $result->fetch_assoc()) {
                $maxID = $row['maxID'] ?? 0;
                $this->idTipoAve = $maxID + 1;
            }else {
                throw new RuntimeException("Error al obtener el máximo idTipoAve: " . $this->mysqli->error);
            }
        }catch(RuntimeException $e) {
            throw $e;
        }
    }

    public function agregarNuevo($nombre)
    {
        $this->setNombre($nombre);
        $this->setMaxID();
        try{
            if ($this->mysqli === null) {
                throw new RuntimeException('La conexión a la base de datos no está inicializada.');
            }
            // Consulta para verificar si ya existe
            $sqlCheck = "SELECT idTipoAve, nombre FROM tipoAve WHERE nombre = ?";
            $stmtCheck = $this->mysqli->prepare($sqlCheck);
            if (!$stmtCheck) {
                throw new RuntimeException("Error en la preparación de la consulta de verificación: " . $this->mysqli->error);
            }
            // Enlazar parametro
            $stmtCheck->bind_param("s", $this->nombre);
            $stmtCheck->execute();
            $stmtCheck->store_result();
            // Verificar
            if ($stmtCheck->num_rows > 0) {
                $stmtCheck->close();
                throw new RuntimeException("Error, ya existe: " . $this->mysqli->error);
            }
            $stmtCheck->close();
            // Inserción de Granja
            $sql = "INSERT INTO tipoAve (idTipoAve, nombre) 
                    VALUES (?, ?)";
            $stmt = $this->mysqli->prepare($sql);
            if (!$stmt) {
                throw new RuntimeException("Error en la preparación de la consulta de inserción: " . $this->mysqli->error);
            }
            // Enlaza los parámetros y ejecuta la consulta
            $stmt->bind_param("is", $this->idTipoAve, $this->nombre);
            if (!$stmt->execute()) {
                throw new RuntimeException('Error al ejecutar la consulta: ' . $stmt->error);
            }
            $stmt->close();
            return true;
        }catch(RuntimeException $e) {
            throw $e;
        }
    }

    public function deleteTipoAve($idTipoAve)
    {
        try {
            if ($this->mysqli === null) { 
                throw new RuntimeException('La conexión a la base de datos no está inicializada.');
            }
            // Verificar que el ID sea un entero válido
            if (!is_numeric($idTipoAve) || $idTipoAve <= 0) {
                throw new RuntimeException('El ID debe ser un número válido.');
            }
            $this->setIdTipoAve($idTipoAve);

            $sql = "DELETE FROM tipoAve WHERE idTipoAve = ?";
            $stmt = $this->mysqli->prepare($sql);
            if ($stmt === false) {
                throw new RuntimeException('Error al preparar la consulta: ' . $this->mysqli->error);
            }
            $stmt->bind_param('i', $this->idTipoAve);
            if (!$stmt->execute()) {
                // Verificar si es un error de clave foránea
                if ($this->mysqli->errno == 1451) {
                    throw new RuntimeException('El tipo de ave tiene registros asociados.');
                } else {
                    throw new RuntimeException('Error al ejecutar la consulta: ' . $stmt->error); 
                }
            }
            // VERIFICAR SI REALMENTE SE ELIMINÓ ALGO
            $affectedRows = $stmt->affected_rows;
            $stmt->close();
            if ($affectedRows === 0) {
                error_log('No se encontró el tipo de ave con el ID especificado.');
                error_log($this->idTipoAve);
            }
            return true;
        } catch (RuntimeException $e) {
            throw $e;
        }
    }

    public function updateTipoAve($idTipo, $nombre)
    {
        $this->setIdTipoAve($idTipo);
        $this->setNombre($nombre);
        try{
            if ($this->mysqli === null) {
                throw new RuntimeException('La conexión a la base de datos no está inicializada.');
            }
            $sql = "UPDATE tipoAve SET nombre = ? WHERE idtipoAve = ?";
            $stmt = $this->mysqli->prepare($sql);
            if (!$stmt) {
                throw new RuntimeException("Error en la preparación de la consulta de actualización: " . $this->mysqli->error);
            }
            // Enlazar parámetros y ejecutar la consulta
            $stmt->bind_param("si", $this->nombre, $this->idTipoAve);
            if (!$stmt->execute()) {
                throw new RuntimeException('Error al ejecutar la consulta: ' . $stmt->error);
            }
            // Cerrar la consulta
            $stmt->close();
            return true;
        } catch (RuntimeException $e) {
            throw $e;
        }
    }

    public function getall()
    {
        try{
            if ($this->mysqli === null) { 
                    throw new RuntimeException('La conexión a la base de datos no está inicializada.');
            }
            $sql = "SELECT idTipoAve, nombre FROM tipoAve";
            $result = $this->mysqli->query($sql);
            if ($result === false) {
                //Este error se da si falla el SQL. Si devuelve 0 columnas, no se activa.
                throw new RuntimeException('Error al ejecutar la consulta: ' . $this->mysqli->error);
            }
            $data = []; // Array para almacenar los datos
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    $data[] = $row;
                }
            }
            return $data;
        }catch(RuntimeException $e) {
            throw $e;
        }
    }

    public function instanciarTipo($idTipoAve)
    {
        $this->setIdTipoAve($idTipoAve);
        try {
            if ($this->mysqli === null) {
                throw new RuntimeException('La conexión a la base de datos no está inicializada.');
            }
            $sql = "SELECT idTipoAve, nombre FROM tipoAve WHERE idTipoAve = ?";
            $stmt = $this->mysqli->prepare($sql);
            if (!$stmt) {
                throw new RuntimeException("Error en la preparación de la consulta: " . $this->mysqli->error);
            }
            $stmt->bind_param("i", $idTipoAve);
            if (!$stmt->execute()) {
                throw new RuntimeException('Error al ejecutar la consulta: ' . $stmt->error);
            }
            $result = $stmt->get_result();
            if ($result === false) {
                throw new RuntimeException('Error al obtener el resultado: ' . $stmt->error);
            }
            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $this->setNombre($row['nombre']);
            } else {
                throw new RuntimeException('No se encontró el tipo de ave con ID: ' . $idTipoAve);
            }
            $stmt->close();
            return true;
        } catch (RuntimeException $e) {
            throw $e;
        }
    }
}

class LoteAves{
    /*CREATE TABLE loteAves (
    idLoteAves INT NOT NULL,
    identificador VARCHAR(20),
    fechaNacimiento DATE,
    fechaCompra DATE,
    cantidadAves INT,
    idTipoAve INT,
    FOREIGN KEY (idTipoAve) REFERENCES tipoAve(idTipoAve),
    PRIMARY KEY (idLoteAves)*/
    private $idLoteAves;
    private $idTipoAve;
    private $identificador;
    private $fechaNacimiento;
    private $fechaCompra;
    private $cantidadAves;
    private $tipoAves; //Los datos del tipo de aves lo maneja ese objeto. Uso -> para obtenerlos.
    private $mysqli;

    public function __construct()
    {
        $this->mysqli = new mysqli(DB_HOST,DB_USER,DB_PASS,DB_NAME);
        if ($this->mysqli->connect_error) {
            die("Error de conexión a la base de datos: " . $this->mysqli->connect_error);
        }
        $this->tipoAves = new tipoAves(); // Inicializar la instancia de tipoAves
    }
    public function __destruct()
    {
        if ($this->mysqli !== null) {
            $this->mysqli->close();
        }
    }
    public function setIdTipoAve($idTipoAve){
        $this->idTipoAve = $idTipoAve;
        $this->tipoAves->instanciarTipo($idTipoAve);
    }
    public function setIdentificador($identificador){$this->identificador = htmlspecialchars(strip_tags(trim($identificador)), ENT_QUOTES, 'UTF-8');}
    public function setFechaNac($fecha){
        $this->fechaNacimiento = new DateTime($fecha);
        $this->fechaNacimiento = $this->fechaNacimiento->format('Y-m-d H:i:s');
    }
    public function setFechaCompra($fecha){
        $this->fechaCompra = new DateTime($fecha);
        $this->fechaCompra = $this->fechaCompra->format('Y-m-d H:i:s');
    }
    public function setCantAves($cantidadAves){$this->cantidadAves = $cantidadAves;}
    public function setIdLoteAves($idLoteAves){$this->idLoteAves = $idLoteAves;}

}