<?php

/* Variables dentro de $_SESSION 
Se debe mantener EXCLUSIVA para Login, datos de usuario y errores de login.

USUARIO
- user_id: ID del usuario autenticado
- user_email: Email del usuario autenticado
- tipoUsuario: Tipo de usuario (dueno, encargado)
- user_name: Nombre del usuario autenticado

LOGIN PROCESS
- captcha_error: Mensaje de error del captcha si no se completa correctamente
- captcha: Resultado del captcha
- login_error: Mensaje de error de login si las credenciales son incorrectas
- token: Token de sesión para seguridad adicional */

class Usuario{
    private $idUsuario;
    private $password;
	private $email;
    private $nombre;
    private $telefono;
    private $direccion;
    private $fechaNac;
    private $tipoUsuario; // dueno, encargado
    private $mysqli;
	
    public function __construct()
    {
        $this->mysqli = new mysqli(DB_HOST,DB_USER,DB_PASS,DB_NAME);
        if ($this->mysqli->connect_error) {
            die("Error de conexión a la base de datos: " . $this->mysqli->connect_error);
        }
    }

    public function setidUsuario($idUsuario)
    {
        $idUsuario = trim($idUsuario);
        if (is_numeric($idUsuario)) {
            $this->idUsuario = (int)$idUsuario;
        }
    }

    public function setPassword($password)
    { 
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
        $nombre = trim($nombre);
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

    public function setTipoUsuario($tipoUsuario)
    {
        if (ctype_alnum($tipoUsuario)==true ){
            $this->tipoUsuario = $tipoUsuario;
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
			if ( $resultado->num_rows > 0 ){
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
        if ( $resultado = $this->mysqli->query($sql) ){
			if ( $resultado->num_rows > 0 ){
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
        if (!isset($_SESSION['captcha']) || !$_SESSION['captcha']) {
            $_SESSION["captcha_error"] = "Por favor complete el captcha";
            return false;
        }
    
        try {
            // Prepare the statement to prevent SQL injection
            $sql = "SELECT idUsuario, email, password, tipoUsuario, nombre FROM usuarios WHERE email = ? LIMIT 1";
            $stmt = $this->mysqli->prepare($sql);

            if (!$stmt) {
             error_log("Error preparing statement: " . $this->mysqli->error);
                $_SESSION["login_error"] = "Error en el sistema: Validación SQL User.";
                return false;
            }
            // Bind the username parameter
            $stmt->bind_param("s", $this->email);
            
            // Execute the query
            if (!$stmt->execute()) {
                error_log("Error executing statement: " . $stmt->error);
                $_SESSION["login_error"] = "Error en el sistema. Ejecución SQL User.";
                return false;
            }
            // Get the result
            $result = $stmt->get_result();
            
            if ($result->num_rows === 1) {
                $usuario = $result->fetch_assoc();
                if ($this->password === $usuario['password'])
                {
                    $this->setidUsuario($usuario['idUsuario']);
                    $this->setTipoUsuario($usuario['tipoUsuario']);
                    $this->setNombre($usuario['nombre']);
                    $this->iniciarSesion();
                    return true;
                }
            }
            $_SESSION["login_error"] = "Usuario y/o contraseña incorrectos";
            return false;
    
        } catch (Exception $e) {
            error_log("Login error: " . $e->getMessage());
            $_SESSION["login_error"] = $e->getMessage();
            return false;
        } finally {
            if (isset($stmt)) {
                $stmt->close();
            }
        }
    }

    // Valida el token de sesión y su expiración
    public function validarToken($token)
    {
        // Verificar que idUsuario sea un número válido (incluido 0)
        if (!is_int($this->idUsuario) || $this->idUsuario < 0) {
            return false;
        }
        // Verificar que el token no esté vacío
        if (!is_string($token) || $token === '') {
            return false;
        }
        $sql = "SELECT user_token, user_token_expir 
                FROM usuarios 
                WHERE idUsuario = ? 
                LIMIT 1";

        $stmt = $this->mysqli->prepare($sql);
        if (!$stmt) {
            return false;
        }
        $stmt->bind_param("i", $this->idUsuario);
        $stmt->execute();
        $stmt->bind_result($db_token, $db_token_expir);
        $valido = false;
        if ($stmt->fetch()) {
            if ($db_token === $token && strtotime($db_token_expir) > time()) {
                $valido = true;
            }
        }
        $stmt->close();
        return $valido;
    }

// CARGA; UPDATE, DELTE
    public function save()
    {
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

    private Function iniciarSesion()
    {
        $_SESSION['user_id'] = $this->idUsuario;
        $_SESSION['token'] = bin2hex(random_bytes(32)); // Genera un token de sesión seguro
        $_SESSION['tipoUsuario'] = $this->tipoUsuario;
        $_SESSION['user_name'] = $this->nombre;
        // Inserción del token en el usuario, para validaciones
        $sql = "UPDATE usuarios SET user_token = ?, user_token_expir = DATE_ADD(NOW(), INTERVAL 12 HOUR) WHERE idUsuario = ?"; 
        $stmt = $this->mysqli->prepare($sql);
        if (!$stmt) {
            die("Error en la preparación de la consulta de inserción: " . $this->mysqli->error);
        }
        // Enlaza los parámetros y ejecuta la consulta
        $stmt->bind_param("si", $_SESSION['token'], $_SESSION['user_id']);
        $stmt->execute();
    }

    public function cerrarSesion()
    {
        // Elimina el token de sesión del usuario
        $sql = "UPDATE usuarios SET user_token = NULL, user_token_expir = NULL WHERE idUsuario = ?";
        $stmt = $this->mysqli->prepare($sql);
        if (!$stmt) { 
            throw new RuntimeException("Error en la preparación de la consulta de cierre de sesión: " . $this->mysqli->error); 
        }
        $stmt->bind_param("i", $_SESSION['user_id']);
        $stmt->execute();
        //Elimina la sesión
        session_unset(); // Elimina todas las variables de sesión
        session_destroy(); // Destruye la sesión
        // No hacer header ni exit aquí, dejar que el controlador maneje la respuesta
        return true;
    }

}