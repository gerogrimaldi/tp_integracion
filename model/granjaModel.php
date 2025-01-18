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
    }

    public function setIdEvento($idGranja)
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
        $this->$habilitacionSenasa = trim($habilitacionSenasa); 
    }

    public function setMetrosCuadrados($metrosCuadrados)
    {
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
        $vEvento=array(
            'idGranja'=>$this->$dGranja,
            'nombre'=>$this->nombre,
            'habilitacionSenasa'=>$this->habilitacionSenasa,
            'metrosCuadrados'=>$this->metrosCuadrados,
            'ubicacion'=>$this->ubicacion
        );
        return $vEvento;
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
        } else {
            echo "0 resultados";
        }
        $this->mysqli->close();
        // Convertir el array de datos a formato JSON
        $json_data = json_encode($data);
        return $json_data;
    }

    public function getGranjaPorId($idEvento)
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

    // Consulta para verificar si el Evento ya existe
    $sqlCheck = "SELECT idGranja FROM granja WHERE idGranja = ?";
    $stmtCheck = $this->mysqli->prepare($sqlCheck);

    if (!$stmtCheck) {
        die("Error en la preparación de la consulta de verificación: " . $this->mysqli->error);
    }

    // Enlazar parametro
    $stmtCheck->bind_param("s", $this->idGranja);
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

    // Inserción del nuevo Evento
    $sql = "INSERT INTO granja (idGranja, nombre, habilitacionSenasa, metrosCuadrados, ubicacion) 
            VALUES (?, ?, ?, ?, ?)";
    $stmt = $this->mysqli->prepare($sql);
    if (!$stmt) {
        die("Error en la preparación de la consulta de inserción: " . $this->mysqli->error);
    }

    // Enlaza los parámetros y ejecuta la consulta
    $stmt->bind_param("issss", $this->idGranja, $this->nombre, $this->habilitacionSenasa, $this->metrosCuadrados, $this->ubicacion);
    $stmt->execute();
    echo '<script type="text/javascript">alert("Granja registrada con éxito.");</script>';
    
    // Cerrar la consulta y la conexión
    $stmt->close();
    //$this->mysqli->close();
    return true;
}

public function update()
{
    // Preparar la consulta para actualizar los datos del evento
    $sql = "UPDATE granja SET nombre = ?, habilitacionSenasa = ?, metrosCuadrados = ?, ubicacion = ? WHERE idGranja = ?";
    $stmt = $this->mysqli->prepare($sql);
    if (!$stmt) {
        die("Error en la preparación de la consulta de actualización: " . $this->mysqli->error);
    }
    // Enlazar parámetros y ejecutar la consulta
    $stmt->bind_param("sssi", $this->idGranja, $this->nombre, $this->habilitacionSenasa, $this->metrosCuadrados, $this->ubicacion);
    $stmt->execute();
    // Cerrar la consulta
    $stmt->close();
}

public function deleteGranjaPorId($idGranja)
    {
        $sql="DELETE FROM granja WHERE idGranja=$idGranja"; 
        $this->mysqli->query($sql);
    }
}
