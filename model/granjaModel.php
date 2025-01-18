<?php
$mensaje = '';

class granja{
	//(idGranja, nombre, habilitacionSenasa, metrosCuadrados, ubicacion)
    private $idGranja;
    private $nombre;
	private $habilitacionSenasa;
    private $metrosCuadrados;
    private $ubicacion;
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
    
    public function setIdGranja($idGranja)
    {
        if ( ctype_digit($idGranja)==true ) // Evalua que el ID sea positivo y entero
        {
            $this->idGranja = $idGranja;
        }
    }

    public function setNombre($nombre)
    {
        $this->nombre = trim($nombre); // Eliminar espacios en blanco al inicio y al final y asignarlo al objeto.
    }

    public function setHabilitacionSenasa($habilitacionSenasa)
    {
        $this->habilitacionSenasa = trim($habilitacionSenasa); 
    }

    public function setMetrosCuadrados($metrosCuadrados)
    {
        if (empty($metrosCuadrados)) {
            $error = "El campo Metros Cuadrados es obligatorio.";
        } elseif (!is_numeric($metrosCuadrados) || $metrosCuadrados <= 0) {
            $error = "Debe ser un número positivo.";
        } else {
            // Procesar el dato si es válido
            $metrosCuadrados = intval($metrosCuadrados);
        }
        
        
        $this->metrosCuadrados = trim($metrosCuadrados); 
    }

    public function setUbicacion($ubicacion)
    {
        $this->ubicacion = trim($ubicacion); 
    }

    public function setMaxID()
    {
        // Leer datos de la tabla 'granjas',
        $sql = "SELECT MAX(idGranja) AS maxID FROM granja  ";
        $result = $this->mysqli->query($sql);
        $data = []; // Array para almacenar los datos
        //La consulta devuelve un solo resultado.
        if ($result && $row = $result->fetch_assoc()) {
            $maxID = $row['maxID'] ?? 0; // Si no hay registros, maxID será 0
            $this->idGranja = $maxID + 1; // Incrementa el ID máximo en 1
        }else {
            echo "Error al obtener el máximo idGranja: " . $this->mysqli->error;
        }
    }

    public function toArray()
    {
        $vGranja=array(
            'idGranja'=>$this->$idGranja,
            'nombre'=>$this->nombre,
            'habilitacionSenasa'=>$this->habilitacionSenasa,
            'metrosCuadrados'=>$this->metrosCuadrados,
            'ubicacion'=>$this->ubicacion
        );
        return $vGranja;
    }

    public function getall()
    {
        // Leer datos de la tabla 'granjas',
        $sql = "SELECT idGranja, nombre, habilitacionSenasa, metrosCuadrados, ubicacion FROM granja";
        $result = $this->mysqli->query($sql);
        $data = []; // Array para almacenar los datos
        
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
        }
        $this->mysqli->close();
        // Convertir el array de datos a formato JSON
        $json_data = json_encode($data);
        return $json_data;
    }

    public function getGranjaPorId($idGranja)
    {
        $sql = "SELECT idGranja, nombre, habilitacionSenasa, metrosCuadrados, ubicacion FROM granja WHERE idGranja=".$idGranja;
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
    $sqlCheck = "SELECT idGranja FROM granja WHERE idGranja = ?";
    $stmtCheck = $this->mysqli->prepare($sqlCheck);

    if (!$stmtCheck) {
        die("Error en la preparación de la consulta de verificación: " . $this->mysqli->error);
    }

    // Enlazar parametro
    $stmtCheck->bind_param("i", $this->idGranja);
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

    // Inserción del nuevo Granja
    $sql = "INSERT INTO granja (idGranja, nombre, habilitacionSenasa, metrosCuadrados, ubicacion) 
            VALUES (?, ?, ?, ?, ?)";
    $stmt = $this->mysqli->prepare($sql);
    if (!$stmt) {
        die("Error en la preparación de la consulta de inserción: " . $this->mysqli->error);
    }

    // Enlaza los parámetros y ejecuta la consulta
    $stmt->bind_param("issis", $this->idGranja, $this->nombre, $this->habilitacionSenasa, $this->metrosCuadrados, $this->ubicacion);
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
    $sql = "UPDATE granja SET nombre = ?, habilitacionSenasa = ?, metrosCuadrados = ?, ubicacion = ? WHERE idGranja = ?";
    $stmt = $this->mysqli->prepare($sql);
    if (!$stmt) {
        die("Error en la preparación de la consulta de actualización: " . $this->mysqli->error);
    }
    // Enlazar parámetros y ejecutar la consulta
    $stmt->bind_param("ssisi", $this->nombre, $this->habilitacionSenasa, $this->metrosCuadrados, $this->ubicacion, $this->idGranja);
    if (!$stmt->execute()) {
        throw new RuntimeException('Error al ejecutar la consulta: ' . $stmt->error);
    }
    // Cerrar la consulta
    $stmt->close();
    $this->mysqli->close();
        echo("<h2 class='bg-white text-black'>EJECUTO UPDATE</h2>");
        echo var_dump($this->idGranja);
}

public function deleteGranjaPorId($idGranja)
{
    // echo("<h2><script>console.log(".$idGranja.")</script></h2>"); el id granja llega bien
    echo("<h2 class='bg-white text-black'>EJECUTO BORRAR</h2>");

    // Verificar que $idGranja sea un entero
    if (!is_numeric($idGranja)) {
        throw new InvalidArgumentException('El ID de la granja debe ser un número.');
    }

    if ($this->mysqli === null) {
        throw new RuntimeException('La conexión a la base de datos no está inicializada.');
    }
    
    // Usar una consulta preparada para evitar inyección SQL
    $sql = "DELETE FROM granja WHERE idGranja = ?";
    $stmt = $this->mysqli->prepare($sql);

    if ($stmt === false) {
        throw new RuntimeException('Error al preparar la consulta: ' . $this->mysqli->error);
    }

    // Enlazar el parámetro a la consulta
    $stmt->bind_param('i', $idGranja);

    // Ejecutar la consulta
    if (!$stmt->execute()) {
        throw new RuntimeException('Error al ejecutar la consulta: ' . $stmt->error);
    }

    // Cerrar el statement
    $stmt->close();
}

}