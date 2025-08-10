<?php
// Función para validar sesión y expiración de token usando el modelo Usuario

function checkAuth() {
    try {
        require_once __DIR__ . '/../model/UsuarioModel.php';
        if ( !(isset($_SESSION['user_id']) ) || empty($_SESSION['token'])) {
            return false;
        }
        $usuario = new Usuario();
        $usuario->setidUsuario($_SESSION['user_id']);
        if (!$usuario->validarToken($_SESSION['token'])) {
            session_destroy();
            return false;
        } else {
            return true;
        }
    } catch (mysqli_sql_exception $e) {
        return 'error_db';
    }
}
