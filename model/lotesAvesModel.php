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
            $sqlCheck = "SELECT idTipoAve, nombre FROM tipoAve WHERE nombre = ?";
            $stmtCheck = $this->mysqli->prepare($sqlCheck);
            if (!$stmtCheck) {
                throw new RuntimeException("Error en la preparación de la consulta de verificación: " . $this->mysqli->error);
            }
            $stmtCheck->bind_param("s", $this->nombre);
            $stmtCheck->execute();
            $stmtCheck->store_result();
            if ($stmtCheck->num_rows > 0) {
                $stmtCheck->close();
                throw new RuntimeException("Error, ya existe: " . $this->mysqli->error);
            }
            $stmtCheck->close();
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

    public function setMaxID()
    {
        try{
            if ($this->mysqli === null) { 
                    throw new RuntimeException('La conexión a la base de datos no está inicializada.');
            }
            $sql = "SELECT MAX(idLoteAves) AS maxID FROM loteAves";
            $result = $this->mysqli->query($sql);
            if(!$result){
                throw new RuntimeException('Error al consultar el máximo idLoteAves: ' . $this->mysqli->error);
            }
            if ($result && $row = $result->fetch_assoc()) {
                $maxID = $row['maxID'] ?? 0;
                $this->idLoteAves = $maxID + 1;
            }else {
                throw new RuntimeException("Error al obtener el máximo idLoteAves: " . $this->mysqli->error);
            }
        }catch(RuntimeException $e) {
            throw $e;
        }
    }
    public function agregarNuevo($identificador, $fechaNac, $fechaCompra, $cantidadAves, $idTipoAve)
    {
        $this->setIdentificador($identificador);
        $this->setFechaNac($fechaNac);
        $this->setFechaCompra($fechaCompra);
        $this->setCantAves($cantidadAves);
        $this->setIdTipoAve($idTipoAve);
        $this->setMaxID();

        try {
            if ($this->mysqli === null) {throw new RuntimeException('La conexión a la base de datos no está inicializada.');}
            // === Verificación previa: evitar duplicados por identificador ===
            $sqlCheck = "SELECT idLoteAves FROM loteAves WHERE identificador = ?";
            $stmtCheck = $this->mysqli->prepare($sqlCheck);
            if (!$stmtCheck) {
                throw new RuntimeException("Error en la preparación de la consulta de verificación: " . $this->mysqli->error);
            }
            $stmtCheck->bind_param("s", $this->identificador);
            $stmtCheck->execute();
            $stmtCheck->store_result();
            if ($stmtCheck->num_rows > 0) {
                $stmtCheck->close();
                throw new RuntimeException("Error: ya existe un lote con identificador '{$this->identificador}'.");
            }
            $stmtCheck->close();
            // === Inserción del nuevo lote ===
            $sql = "INSERT INTO loteAves (idLoteAves, identificador, fechaNacimiento, fechaCompra, cantidadAves, idTipoAve)
                    VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = $this->mysqli->prepare($sql);
            if (!$stmt) {
                throw new RuntimeException("Error en la preparación de la consulta de inserción: " . $this->mysqli->error);
            }
            $stmt->bind_param(
                "isssii",
                $this->idLoteAves,
                $this->identificador,
                $this->fechaNacimiento,
                $this->fechaCompra,
                $this->cantidadAves,
                $this->idTipoAve
            );
            if (!$stmt->execute()) {
                throw new RuntimeException('Error al ejecutar la consulta: ' . $stmt->error);
            }
            $stmt->close();
            return true;

        } catch (RuntimeException $e) {
            throw $e;
        }
    }

    public function deleteLoteAves($idLoteAves)
    {
        try {
            if ($this->mysqli === null) { 
                throw new RuntimeException('La conexión a la base de datos no está inicializada.');
            }
            if (!is_numeric($idLoteAves) || $idLoteAves <= 0) {
                throw new RuntimeException('El ID debe ser un número válido.');
            }
            $this->setIdLoteAves($idLoteAves);

            $sql = "DELETE FROM loteAves WHERE idLoteAves = ?";
            $stmt = $this->mysqli->prepare($sql);
            if ($stmt === false) {
                throw new RuntimeException('Error al preparar la consulta: ' . $this->mysqli->error);
            }
            $stmt->bind_param('i', $this->idLoteAves);
            if (!$stmt->execute()) {
                if ($this->mysqli->errno == 1451) {
                    throw new RuntimeException('El lote de aves tiene registros asociados.');
                } else {
                    throw new RuntimeException('Error al ejecutar la consulta: ' . $stmt->error); 
                }
            }
            $affectedRows = $stmt->affected_rows;
            $stmt->close();
            if ($affectedRows === 0) {
                error_log('No se encontró el lote de aves con el ID especificado: ' . $this->idLoteAves);
            }
            return true;
        } catch (RuntimeException $e) {
            throw $e;
        }
    }

    public function updateLoteAves($idLoteAves, $identificador, $fechaNac, $fechaCompra, $cantidadAves, $idTipoAve)
    {
        $this->setIdLoteAves($idLoteAves);
        $this->setIdentificador($identificador);
        $this->setFechaNac($fechaNac);
        $this->setFechaCompra($fechaCompra);
        $this->setCantAves($cantidadAves);
        $this->setIdTipoAve($idTipoAve);
        try {
            if ($this->mysqli === null) {
                throw new RuntimeException('La conexión a la base de datos no está inicializada.');
            }
            $sql = "UPDATE loteAves 
                    SET identificador = ?, fechaNacimiento = ?, fechaCompra = ?, cantidadAves = ?, idTipoAve = ? 
                    WHERE idLoteAves = ?";
            $stmt = $this->mysqli->prepare($sql);
            if (!$stmt) {
                throw new RuntimeException("Error en la preparación de la consulta de actualización: " . $this->mysqli->error);
            }
            $stmt->bind_param(
                "sssiii",
                $this->identificador,
                $this->fechaNacimiento,
                $this->fechaCompra,
                $this->cantidadAves,
                $this->idTipoAve,
                $this->idLoteAves
            );
            if (!$stmt->execute()) {
                throw new RuntimeException('Error al ejecutar la consulta: ' . $stmt->error);
            }
            $stmt->close();
            return true;
        } catch (RuntimeException $e) {
            throw $e;
        }
    }

    public function getAll($idGranja, $desde, $hasta)
    //Getall seria muy bestia. Se aplicaron 3 filtros básicos. 
    //Se podría aplicar filtro por galpón, pero en la datatable
    //con escribirse el nombre del galpon basta para filtrar por eso.
    {
        try {
            if ($this->mysqli === null) { 
                throw new RuntimeException('La conexión a la base de datos no está inicializada.');
            }

            $sql = "SELECT 
                        l.idLoteAves,
                        l.identificador,
                        l.fechaNacimiento,
                        l.fechaCompra,
                        l.cantidadAves,
                        t.nombre AS tipoAveNombre,
                        g.idGalpon,
                        g.identificacion AS galponIdentificacion,
                        gr.idGranja,
                        gr.nombre AS granjaNombre
                    FROM loteAves l
                    INNER JOIN tipoAve t ON l.idTipoAve = t.idTipoAve
                    INNER JOIN galpon_loteAves gl ON l.idLoteAves = gl.idLoteAves
                    INNER JOIN galpon g ON gl.idGalpon = g.idGalpon
                    INNER JOIN granja gr ON g.idGranja = gr.idGranja
                    WHERE gr.idGranja = ?
                    AND l.fechaNacimiento BETWEEN ? AND ?
                    ORDER BY l.idLoteAves ASC";

            $stmt = $this->mysqli->prepare($sql);
            if ($stmt === false) {
                throw new RuntimeException('Error al preparar la consulta: ' . $this->mysqli->error);
            }

            $stmt->bind_param('iss', $idGranja, $desde, $hasta);

            if (!$stmt->execute()) {
                throw new RuntimeException('Error al ejecutar la consulta: ' . $stmt->error);
            }

            $result = $stmt->get_result();
            $data = [];
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }

            $stmt->close();
            return $data;

        } catch (RuntimeException $e) {
            throw $e;
        }
    }


    public function getById($idLoteAves)
    {
        try {
            if ($this->mysqli === null) { 
                throw new RuntimeException('La conexión a la base de datos no está inicializada.');
            }
            $sql = "SELECT 
                        l.idLoteAves,
                        l.identificador,
                        l.fechaNacimiento,
                        l.fechaCompra,
                        l.cantidadAves,
                        t.nombre AS tipoAveNombre,
                        g.idGalpon,
                        g.identificacion AS galponIdentificacion,
                        gr.idGranja,
                        gr.nombre AS granjaNombre
                    FROM loteAves l
                    INNER JOIN tipoAve t ON l.idTipoAve = t.idTipoAve
                    INNER JOIN galpon_loteAves gl ON l.idLoteAves = gl.idLoteAves
                    INNER JOIN galpon g ON gl.idGalpon = g.idGalpon
                    INNER JOIN granja gr ON g.idGranja = gr.idGranja
                    WHERE l.idLoteAves = ?";

            $stmt = $this->mysqli->prepare($sql);
            if ($stmt === false) {
                throw new RuntimeException('Error al preparar la consulta: ' . $this->mysqli->error);
            }

            $stmt->bind_param('i', $idLoteAves);

            if (!$stmt->execute()) {
                throw new RuntimeException('Error al ejecutar la consulta: ' . $stmt->error);
            }

            $result = $stmt->get_result();
            $data = $result->fetch_assoc();
            
            $stmt->close();
            
            if (!$data) {
                throw new RuntimeException("No se encontró un lote de aves con el ID especificado.");
            }
            
            return $data;

        } catch (RuntimeException $e) {
            throw $e;
        }
    }

}