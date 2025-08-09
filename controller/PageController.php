<?php
//Si no tiene nada, le asigna un valor por defecto

require_once __DIR__ . '/../includes/auth.php';
$_GET['opt'] = $_GET['opt'] ?? '';


switch ($_GET['opt']) {
	case '':
	case 'login':
		require_once 'view/login.php';
		break;

	case 'error_db':
		require_once 'view/error_db.php';
		break;

	case 'test':
		require_once 'controller/testController.php';
		require_once 'view/test.php';
		break;

	case 'granjas':
		checkAuth();
		require_once 'controller/abmGranjasController.php';
		require_once 'view/abmGranjas.php';
		break;

	case 'galpones':
		checkAuth();
		require_once 'controller/abmGalponesController.php';
		require_once 'view/abmGalpones.php';
		break;

	case 'mantenimientos':
		checkAuth();
		require_once 'controller/mantenimientosController.php';
		require_once 'view/abmMantenimientos.php';
		break;

	case 'vacunas':
		checkAuth();
		require_once 'controller/vacunasController.php';
		require_once 'view/abmVacunas.php';
		break;

	case 'home':
		checkAuth();
		require_once 'controller/homeController.php';
		break;

	default:
		require_once 'view/error_404.php';
		break;
}

if (!empty($_POST['btLogin'])) {
	//Cargo el controlador de usuario
	//Internamente, cada controlador sabe como manejar los POST y los GET que le corresponden.
	require_once 'controller/userController.php';
}

//var_dump($_SESSION);