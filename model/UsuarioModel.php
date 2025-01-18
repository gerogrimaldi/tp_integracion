<?php
$mensaje = '';

class Usuario{
	
    private $idUsuario;
    private $password;
	private $email;
    private $nombre;
    private $telefono;
    private $direccion;
    private $fechaNac;

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

    public function setidUsuario($idUsuario)
    {

        if ( ctype_digit($idUsuario)==true )
        {
            $this->idUsuario = $idUsuario;
        }

    }

    public function setPassword($password)
    { 
        // $this->password = password_hash($password, PASSWORD_DEFAULT);
        $this->password = $password;
    }

    public function setDireccion($direccion)
    {
        $this->direccion = $direccion;
    }
    
    public function setEmail($email)
    {
        $this->email = $email;
        
    }


    public function setNombre($nombre)
    {

        $nombre = trim($nombre); // Eliminar espacios en blanco al inicio y al final

        // Verifica si el nombre no está vacío
        if (!empty($nombre)) {
            $this->nombre = $nombre;
        }

    }

    public function setDate($fecha)
    {
        $this->fechaNac = new DateTime($fecha);
        
        $this->fechaNac = $this->fechaNac->format('Y-m-d H:i:s');
    }

        public function setTelefono($telefono)
    {

        if ( ctype_alnum($telefono)==true )
        {
            $this->telefono = $telefono;
        }

    }

    public function toArray()
    {
        $vUsuario=array(
            'password'=>$this->password,
            'email'=>$this->email,
            'nombre'=>$this->nombre,
            'telefono'=>$this->telefono,
            'direccion'=>$this->direccion,
            'fechaNac'=>$this->fechaNac
        );

        return $vUsuario;

    }


    public function getall()
    {

        $sql = "SELECT * FROM Usuarios";

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


    public function getUsuarioPorId($idUsuario)
    {

        $sql = "SELECT * FROM usuarios WHERE idUsuario=".$idUsuario;

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

    public function validar()
    {
        require_once("captcha_process.php");
    
        // #############BORRAR#####################
		echo("<h2><script>console.log('Ingreso validar()')</script></h2>"); 

		// echo("<h2><script>console.log('email: ".$this->email."')</script></h2>"); 
		// echo("<h2><script>console.log('password: ".$this->password."')</script></h2>"); 
        // #############BORRAR#####################
        
        if (!isset($_SESSION['captcha']) || !$_SESSION['captcha']) {
            $_SESSION["captcha_error"] = "Por favor complete el captcha";
            echo("<h2><script>console.log('".$_SESSION["captcha_error"]."')</script></h2>"); 
            return false;
        }
    
        try {
            echo("<h2><script>console.log('ingreso try')</script></h2>"); 

            // Prepare the statement to prevent SQL injection
            $sql = "SELECT idUsuario, email, password FROM usuarios WHERE email = ? LIMIT 1";
            $stmt = $this->mysqli->prepare($sql);
            // echo("<h2><script>console.log('sigo')</script></h2>"); 

            if (!$stmt) {
             error_log("Error preparing statement: " . $this->mysqli->error);
                $_SESSION["login_error"] = "Error en el sistema. Por favor intente más tarde.";
                return false;
            }
            // echo("<h2><script>console.log('sigo')</script></h2>"); 

            // Bind the username parameter
            $stmt->bind_param("s", $this->email);
            
            // Execute the query
            if (!$stmt->execute()) {
                error_log("Error executing statement: " . $stmt->error);
                $_SESSION["login_error"] = "Error en el sistema. Por favor intente más tarde.";
                return false;
            }
    
            // Get the result
            $result = $stmt->get_result();
            
            if ($result->num_rows === 1) {
                $usuario = $result->fetch_assoc();
                
                // ###############BORRAR###################
                // echo("<h2><script>console.log('email usuario BDD: ".$usuario['email']."')</script></h2>"); 
                // echo("<h2><script>console.log('password usuario BDD: ".$usuario['password']."')</script></h2>"); 
                // ##############BORRAR####################

                // Verify the password (assuming you're using password_hash)
                // if (password_verify($this->password, $usuario['password'])) 
                if ($this->password === $usuario['password'])
                {
    
                    // Store necessary user data in session
                    $_SESSION['user_id'] = $usuario['idUsuario'];
                    $_SESSION['user_email'] = $usuario['email'];
                    
                    // Optional: Update last login timestamp
                    // $this->updateLastLogin($usuario['id']);
                    
                    return true;
                }
            }
    
            // Invalid credentials
            echo("<h2><script>console.log('error result')</script></h2>"); 

            $_SESSION["login_error"] = "Usuario y/o contraseña incorrectos";
            
            // Optional: Log failed attempt
            // $this->logFailedAttempt();
            
            return false;
    
        } catch (Exception $e) {
            error_log("Login error: " . $e->getMessage());
            $_SESSION["login_error"] = "Error en el sistema. Por favor intente más tarde.";
            return false;
        } finally {
            if (isset($stmt)) {
                $stmt->close();
            }
        }
    }

//###########################################################################
// CARGA; UPDATE, DELTE
public function save()
{
    // var_dump($this->nombre, $this->email, $this->direccion, $this->telefono, $this->password, $this->fechaNac);

    // Consulta para verificar si el usuario ya existe
    $sqlCheck = "SELECT email FROM usuarios WHERE email = ?";
    $stmtCheck = $this->mysqli->prepare($sqlCheck);

    if (!$stmtCheck) {
        die("Error en la preparación de la consulta de verificación: " . $this->mysqli->error);
    }

    // Enlazar parametro
    $stmtCheck->bind_param("s", $this->email);
    $stmtCheck->execute();
    $stmtCheck->store_result();

    // Verificar
    if ($stmtCheck->num_rows > 0) {
        echo '<script type="text/javascript">alert("Error: El usuario con este correo electrónico ya está registrado.");</script>';
        $stmtCheck->close();
        $this->mysqli->close();
        return false; 
    }

    $stmtCheck->close();

    // Inserción del nuevo usuario
    $sql = "INSERT INTO usuarios (nombre, email, direccion, telefono, password, fechaNac) 
            VALUES (?, ?, ?, ?, ?, ?)";

    $stmt = $this->mysqli->prepare($sql);

    if (!$stmt) {
        die("Error en la preparación de la consulta de inserción: " . $this->mysqli->error);
    }

    // Enlaza los parámetros y ejecuta la consulta
    $stmt->bind_param("ssssss", $this->nombre, $this->email, $this->direccion, $this->telefono, $this->password, $this->fechaNac);
    $stmt->execute();

    echo '<script type="text/javascript">alert("Usuario registrado con éxito.");</script>';

    // Cerrar la consulta y la conexión
    $stmt->close();
    $this->mysqli->close();
    return true;
}

    

    public function update()
    {

        $sql="UPDATE Usuarios SET 'password='$this->password',email='$this->email',nombre='$this->nombre',direccion='$this->direccion',telefono='$this->telefono'',fechaNac='$this->fechaNac'', WHERE idUsuario=$this->idUsuario"; 

        $this->mysqli->query($sql);

    }

    
    public function deleteUsuarioPorId($idUsuario)
    {

        $sql="DELETE FROM Usuarios WHERE idUsuario=$idUsuario"; 

        $this->mysqli->query($sql);

    }

    

}
