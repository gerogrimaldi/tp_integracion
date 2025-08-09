<?php
// includes/auth.php
// Función para validar sesión y expiración de token usando el modelo Usuario

require_once __DIR__ . '/../model/UsuarioModel.php';

function checkAuth() {
    if ( !(isset($_SESSION['user_id']) ) || empty($_SESSION['token'])) {
        //Informo al usuario que se cerró sesión o nunca se inició
        //$_SESSION['login_error'] = 'Por favor, inicie sesión para continuar.';
        header('Location: index.php?opt=login');
        exit;
    }
    $usuario = new Usuario();
    $usuario->setidUsuario($_SESSION['user_id']);
    if (!$usuario->validarToken($_SESSION['token'])) {
        session_destroy();
        header('Location: index.php?opt=login');
        exit;
    }
}
