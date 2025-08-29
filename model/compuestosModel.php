<?php
class compuesto{
    /*
    CREATE TABLE compuesto (
        idCompuesto INT NOT NULL,
        nombre VARCHAR(80) NOT NULL,
        proveedor VARCHAR(80),
        PRIMARY KEY (idCompuesto)
    );

    CREATE TABLE compra (
        idCompraCompuesto INT NOT NULL AUTO_INCREMENT,
        idGranja INT NOT NULL,
        idCompuesto INT NOT NULL,
        cantidad DECIMAL(10,2),
        PRIMARY KEY (idCompraCompuesto),
        FOREIGN KEY (idGranja) REFERENCES granja(idGranja),
        FOREIGN KEY (idCompuesto) REFERENCES compuesto(idCompuesto)
    );
    */
    private $idCompuesto;
    private $nombre;
    private $proveedor;
    private $mysqli;

    public function __construct(){
        $this->mysqli = new mysqli(DB_HOST,DB_USER,DB_PASS,DB_NAME);
        if ($this->mysqli->connect_error) { 
            die("Error de conexión a la base de datos: " . $this->mysqli->connect_error); 
        }
    }

    public function __destruct(){
        if ($this->mysqli !== null) {
            $this->mysqli->close();
        }
    }

    //public function setMaxID(){} Se usa Autoincrement

    public function setID($id){ $this->idCompuesto = (int)$id; }
    public function setNombre($nombre){ $this->nombre = htmlspecialchars(strip_tags(trim($nombre)), ENT_QUOTES, 'UTF-8'); }
    public function setProveedor($nombre){ $this->proveedor = htmlspecialchars(strip_tags(trim($nombre)), ENT_QUOTES, 'UTF-8'); }

    public function getCompuestos()
    {
        try {
            if ($this->mysqli === null) { throw new RuntimeException('La conexión a la base de datos no está inicializada.');}
            //Consulta sin preparación ya que no trae datos externos
            $sql = "SELECT idCompuesto, nombre, proveedor FROM compuesto";
            $result = $this->mysqli->query($sql);
            if (!$result) {throw new RuntimeException('Error al ejecutar la consulta: ' . $this->mysqli->error);}
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

    public function agregarNuevo($nombre, $proveedor){
        $this->setNombre($nombre);
        $this->setProveedor($proveedor);
        //El id se setea solo porque es autoincrement
        try{
            if ($this->mysqli === null) {throw new RuntimeException('La conexión a la base de datos no está inicializada.');}
        // Verificar si ya existe el tipo de mantenimiento
            $sqlCheck = "SELECT compuesto.nombre FROM compuesto WHERE nombre = ?";
            $stmtCheck = $this->mysqli->prepare($sqlCheck);
            if (!$stmtCheck) {throw new RuntimeException("Error en la preparación de la consulta de verificación: " . $this->mysqli->error);}
            $stmtCheck->bind_param("s", $this->nombre);
            $stmtCheck->execute();
            $stmtCheck->store_result();
            if ($stmtCheck->num_rows > 0) {
                $stmtCheck->close();
                throw new RuntimeException('Error, ya existe: ' . $this->mysqli->error);
            }
            $stmtCheck->close();
        // Insertar
            $sql = "INSERT INTO compuesto (nombre, proveedor) VALUES (?, ?)";
            $stmt = $this->mysqli->prepare($sql);
            if (!$stmt) {throw new RuntimeException('Error al preparar consulta: ' . $this->mysqli->error);}
            // Enlaza los parámetros y ejecuta la consulta
            $stmt->bind_param("ss", $this->nombre, $this->proveedor);
            if (!$stmt->execute()){throw new RuntimeException('Error al ejecutar la consulta: ' . $stmt->error);}
            $stmt->close();
            return true;
        }catch (RuntimeException $e) {
            throw $e;
        }
    }

    public function update($idcomp, $nombre, $proveedor){
        $this->setNombre($nombre);
        $this->setProveedor($proveedor);
        $this->SetId($idcomp);
        try{
            if ($this->mysqli === null) {throw new RuntimeException('La conexión a la base de datos no está inicializada.');}
            $sql = "UPDATE compuesto SET nombre = ?, proveedor = ? WHERE idCompuesto = ?";
            $stmt = $this->mysqli->prepare($sql);
            if (!$stmt) {throw new RuntimeException("Error en la preparación de la consulta de actualización: " . $this->mysqli->error);}
            $stmt->bind_param("ssi", $this->nombre, $this->proveedor, $this->idCompuesto);
            if (!$stmt->execute()) {throw new RuntimeException('Error al ejecutar la consulta: ' . $stmt->error);}
            $stmt->close();
            return true;
        }catch (RuntimeException $e) {
            throw $e;
        }
    }

    public function deleteCompuesto($idCompuesto){
        $this->setID($idCompuesto);
        try{
            if ($this->mysqli === null) {throw new RuntimeException('La conexión a la base de datos no está inicializada.');}
            $sql = "DELETE FROM compuesto WHERE idCompuesto = ?";
            $stmt = $this->mysqli->prepare($sql);
            if ($stmt === false) {throw new RuntimeException('Error al preparar la consulta: ' . $this->mysqli->error);}
            $stmt->bind_param('i', $this->idCompuesto);
            if (!$stmt->execute()) { 
                // Verificar si es un error de clave foránea
                if ($this->mysqli->errno == 1451) {
                    throw new RuntimeException('El compuesto tiene registros asociados.');
                }else{
                    throw new RuntimeException('Error al ejecutar la consulta: ' . $stmt->error); 
                }
            }
            $stmt->close(); 
            return true;
        }catch (RuntimeException $e){
            throw $e;
        }
    }
}

/*
CREATE TABLE compra (
    idCompraCompuesto INT NOT NULL AUTO_INCREMENT,
    idGranja INT NOT NULL,
    idCompuesto INT NOT NULL,
    cantidad DECIMAL(10,2),
    precioCompra DECIMAL(10,2),
    PRIMARY KEY (idCompraCompuesto),
    FOREIGN KEY (idGranja) REFERENCES granja(idGranja),
    FOREIGN KEY (idCompuesto) REFERENCES compuesto(idCompuesto)
);
*/
class ComprasCompuesto{
    private $idCompraCompuesto;
    private $idGranja;
	private $idCompuesto;
    private $cantidad;
    private $precioCompra;
    private $mysqli;
    
    public function __construct()
    {
    require_once 'includes/config.php';
        $this->mysqli = new mysqli(DB_HOST,DB_USER,DB_PASS,DB_NAME);
        if ($this->mysqli->connect_error) { die("Error de conexión a la base de datos: " . $this->mysqli->connect_error); }
    }

    public function __destruct()
    {
        if ($this->mysqli !== null) {
            $this->mysqli->close();
        }
    }
    
    public function setIdcompracompuesto($idCompraCompuesto){$this->idCompraCompuesto = (int)$idCompraCompuesto;}
    public function setIdGranja($idGranja){$this->idGranja = (int)$idGranja;}
    public function setIdCompuesto($idCompuesto){$this->idCompuesto = $idCompuesto;}

    public function setCantidad($cantidad){$this->cantidad = $cantidad;}
    public function setPrecioCompra($precioCompra)
    {
        if (is_numeric($precioCompra) && $precioCompra >= 0) {
            $this->precioCompra = $precioCompra;
        } else {
            throw new RuntimeException('Precio de compra inválido. Debe ser un número no negativo.');
        }
    }

    public function getComprasCompuesto($idCompraCompuesto, $idGranja, $idCompuesto, $cantidad, $precioCompra)
    {
        try {
            if ($this->mysqli === null) {
                throw new RuntimeException('La conexión a la base de datos no está inicializada.');
            }
            if (!is_numeric($idCompraCompuesto)) {
                throw new RuntimeException('El ID de la compra debe ser un numero.');
            }
            // Preparar la consulta con rango de fechas
            $sql = "SELECT cc.idcomprascompuesto, cc.idgranja, cc.idcompuesto, cc.cantidad, cc.preciocompra
                    FROM Comprascompuesto cc
                    INNER JOIN granja ON granja.idgranja = cc.idgranja
                    INNER JOIN compuesto ON compuesto.idcompuesto = cc.idcompuesto
                    WHERE cc.idGranja = ?";

            $stmt = $this->mysqli->prepare($sql);
            if (!$stmt) { throw new RuntimeException("Error en la preparación de la consulta: " . $this->mysqli->error);}
            $stmt->bind_param("i", $this->idGranja);
            if (!$stmt->execute()) {
                throw new RuntimeException('Error al ejecutar la consulta: ' . $stmt->error);
            }
            $result = $stmt->get_result();
            if ($result === false) {throw new RuntimeException('Error al obtener el resultado: ' . $stmt->error);}
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

    public function save($idGranja, $idCompuesto, $cantidad, $preciocompra)
    {
        $this->setIdGranja($idGranja); 
        $this->setIdCompuesto($idCompuesto);
        $this->setCantidad($cantidad);
        $this->setPrecioCompra($preciocompra);
        try{
            if ($this->mysqli === null) {
                throw new RuntimeException('La conexión a la base de datos no está inicializada.');
            }
            // Inserción de ComprasCompuesto, no es necesario chequear existencia
            $sql = "INSERT INTO comprascompuesto (idcomprascompuesto, idgranja, idcompuesto, cantidad, preciocompra) VALUES (?, ?, ?, ?, ?)";
            $stmt = $this->mysqli->prepare($sql);
            if (!$stmt) {
                throw new RuntimeException("Error en la preparación de la consulta de inserción: " . $this->mysqli->error);
            }
            // Enlaza los parámetros y ejecuta la consulta
            $stmt->bind_param("iiidd", $this->Idcompracompuesto, $this->IdCompuesto, $this->IdGranja, $this->Cantidad, $this->PrecioCompra);
            if (!$stmt->execute()) {
                throw new RuntimeException('Error al ejecutar la consulta: ' . $stmt->error);
            }
            // Cerrar la consulta
            $stmt->close();
            return true;
        }catch(RuntimeException $e) {
            throw $e;
        }
    }

    public function update($idComprasCompuesto, $idGranja, $idCompuesto, $cantidad, $preciocompra)
    {
        $this->setIdcompracompuesto($idComprasCompuesto);
        $this->setIdGranja($idGranja); 
        $this->setIdCompuesto($idCompuesto);
        $this->setCantidad($cantidad);
        $this->setPrecioCompra($preciocompra);
        try{
            if ($this->mysqli === null) {
                throw new RuntimeException('La conexión a la base de datos no está inicializada.');
            }
            $sql = "UPDATE Comprascompuesto SET idgranja = ?, idcompuesto = ?,
            cantidad = ?, preciocompra = ? WHERE idcomprascompuesto = ?";
            $stmt = $this->mysqli->prepare($sql);
            if (!$stmt) {
                throw new RuntimeException("Error en la preparación de la consulta de actualización: " . $this->mysqli->error);
            }
            // Enlazar parámetros y ejecutar la consulta
            $stmt->bind_param("iiddi", $this->$IdGranja, $this->$IdCompuesto, $this->$Cantidad, 
                            $this->$PrecioCompra, $this->$Idcompracompuesto);
            if (!$stmt->execute()) {
                throw new RuntimeException('Error al ejecutar la consulta: ' . $stmt->error);
            }
            $stmt->close();
            return true;
        } catch (RuntimeException $e) {
            throw $e;
        }
    }

    public function deleteComprasCompuestoId($idCompraCompuesto)
    {
        try{
            if ($this->mysqli === null) { 
                throw new RuntimeException('La conexión a la base de datos no está inicializada.');
            }
            if (!is_numeric($idCompraCompuesto)) {
                throw new RuntimeException('El ID de la compra debe ser un número.');
            }
            $sql = "DELETE FROM comprascompuesto WHERE idcomprascompuesto = ?";
            $stmt = $this->mysqli->prepare($sql);
            if ($stmt === false) {
                throw new RuntimeException('Error al preparar la consulta: ' . $this->mysqli->error);
            }
            $stmt->bind_param('i', $idCompraCompuesto);
            if (!$stmt->execute()) {
                throw new RuntimeException('Error al ejecutar la consulta: ' . $stmt->error); 
            }
            $stmt->close();
            return true;
        } catch (runtimeException $e) {
            throw $e;
        }
    }
}
