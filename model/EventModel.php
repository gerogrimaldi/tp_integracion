<?php
$mensaje = '';


class Evento{
	
    private $idEvento;
    private $fechaEvento;
	private $lugarEvento;
    private $nombreEvento;

    private $mysqli;
    

	
    public function __construct()
{
    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

    try {
        $this->mysqli = new mysqli("127.0.0.1", "2024_grupo2", "Grupo2_8964", "2024_grupo2");
        $this->mysqli->set_charset("utf8mb4");
    } catch (mysqli_sql_exception $e) {
   
        error_log("Error de conexión a la base de datos: " . $e->getMessage());

        header("Location: index.php?opt=error_db");
        exit; 
    }
}

    public function setIdEvento($idEvento)
    {

        if ( ctype_digit($idEvento)==true )
        {
            $this->idEvento = $idEvento;
        }

    }

    public function setDate($fecha)
    {
        $this->fechaEvento = new DateTime($fecha);
        
        $this->fechaEvento = $this->fechaEvento->format('Y-m-d H:i:s');
    }

    public function setLugarEvento($lugarEvento)
    {
        $this->lugarEvento = $lugarEvento;
        
    }

    public function setNombreEvento($nombreEvento)
    {

        $nombreEvento = trim($nombreEvento); // Eliminar espacios en blanco al inicio y al final

        // Verifica si el nombre no está vacío
        if (!empty($nombreEvento)) {
            $this->nombreEvento = $nombreEvento;
        }

    }

    public function toArray()
    {
        $vEvento=array(
            'idEvento'=>$this->idEvento,
            'lugarEvento'=>$this->lugarEvento,
            'nombreEvento'=>$this->nombreEvento,
            'fechaEvento'=>$this->fechaEvento
        );

        return $vEvento;

    }


    public function getall()
    {

        if ($this->mysqli->connect_error) {
            die("Conexión fallida: " . $this->mysqli->connect_error);
        }
        
        // Leer datos de la tabla 'eventos'
        $sql = "SELECT * FROM eventos";
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


    public function getEventoPorId($idEvento)
    {

        $sql = "SELECT * FROM eventos WHERE idEvento=".$idEvento;

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
    // echo "<h1 class='bg-white'>$this->nombreEvento<h1>";
    // var_dump($this->nombreEvento, $this->lugarEvento, $this->fechaEvento);

    // Consulta para verificar si el Evento ya existe
    $sqlCheck = "SELECT nombreEvento FROM eventos WHERE nombreEvento = ?";
    $stmtCheck = $this->mysqli->prepare($sqlCheck);

    if (!$stmtCheck) {
        die("Error en la preparación de la consulta de verificación: " . $this->mysqli->error);
    }

    // Enlazar parametro
    $stmtCheck->bind_param("s", $this->nombreEvento);
    $stmtCheck->execute();
    $stmtCheck->store_result();

    // Verificar
    if ($stmtCheck->num_rows > 0) {
        echo '<script type="text/javascript">alert("Error: El Evento ya está registrado.");</script>';
        $stmtCheck->close();
        $this->mysqli->close();
        return false; 
    }

    $stmtCheck->close();

    // Inserción del nuevo Evento
    $sql = "INSERT INTO eventos (nombreEvento, lugarEvento, fechaEvento) 
            VALUES (?, ?, ?)";

    $stmt = $this->mysqli->prepare($sql);

    if (!$stmt) {
        die("Error en la preparación de la consulta de inserción: " . $this->mysqli->error);
    }

    // Enlaza los parámetros y ejecuta la consulta
    $stmt->bind_param("sss", $this->nombreEvento, $this->lugarEvento, $this->fechaEvento);
    $stmt->execute();

    echo '<script type="text/javascript">alert("Evento registrado con éxito.");</script>';

    // Cerrar la consulta y la conexión
    $stmt->close();
    $this->mysqli->close();
    return true;
}

    

public function update()
{
    // Preparar la consulta para actualizar los datos del evento
    $sql = "UPDATE eventos SET lugarEvento = ?, nombreEvento = ?, fechaEvento = ? WHERE idEvento = ?";
    $stmt = $this->mysqli->prepare($sql);

    if (!$stmt) {
        die("Error en la preparación de la consulta de actualización: " . $this->mysqli->error);
    }

    // Enlazar parámetros y ejecutar la consulta
    $stmt->bind_param("sssi", $this->lugarEvento, $this->nombreEvento, $this->fechaEvento, $this->idEvento);
    $stmt->execute();

    // Cerrar la consulta
    $stmt->close();
}


    
    public function deleteEventoPorId($idEvento)
    {

        $sql="DELETE FROM eventos WHERE idEvento=$idEvento"; 

        $this->mysqli->query($sql);

    }

    

}
