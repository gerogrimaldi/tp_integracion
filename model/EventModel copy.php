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
    require_once 'model/conexion.php';  
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

        if ( ctype_alpha($nombreEvento)==true )
        {
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

        $sql = "SELECT * FROM eventos";

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
    var_dump($this->nombreEvento, $this->lugarEvento, $this->fechaEvento);

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
        return; 
    }

    $stmtCheck->close();

    // Inserción del nuevo Evento
    $sql = "INSERT INTO eventos (nombreEvento, lugarEvento, fechaEvento) 
            VALUES (?, ?, ?, ?, ?, ?)";

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
}

    

    public function update()
    {

        $sql="UPDATE eventos SET lugarEvento='$this->lugarEvento',nombreEvento='$this->nombreEvento',fechaEvento='$this->fechaEvento'', WHERE idEvento=$this->idEvento"; 

        $this->mysqli->query($sql);

    }

    
    public function deleteEventoPorId($idEvento)
    {

        $sql="DELETE FROM eventos WHERE idEvento=$idEvento"; 

        $this->mysqli->query($sql);

    }

    

}
