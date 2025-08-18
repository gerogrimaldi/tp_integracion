<?php
//Si no tiene nada, le asigna un valor por defecto

require_once __DIR__ . '/../includes/auth.php';
$_GET['opt'] = $_GET['opt'] ?? '';

switch ($_GET['opt']) {
	case '':
	case 'login':
		$auth = checkAuth();
		if ($auth === 'error_db') {
			$error = 'db';
			require_once 'view/error.php';
			break;
		} elseif ($auth === true) {
			header('Location: index.php?opt=home');
			break;
		} else {
			require_once 'view/login.php';
			break;
		}

	case 'error_db':
		$error = 'db';
		require_once 'view/error.php';
		break;

	case 'test':
		require_once 'controller/testController.php';
		require_once 'view/test.php';
		echo "Testeando conexión MariaDB";
		break;

	case 'compuestos':
		require_once 'controller/compuestosController.php';
		require_once 'view/abmCompuestos.php';
		break;
			case 'database':
				require_once 'view/database.php';
				break;
	case 'granjas':
	case 'galpones':
	case 'mantenimientos':
	case 'vacunas':
	case 'home':

	case 'usuarios':
		$auth = checkAuth();
		if ($auth === 'error_db') {
			$error = 'db';
			require_once 'view/error.php';
			break;
		} elseif ($auth === false) {
			header('Location: index.php?opt=login');
			break;
		}
		switch ($_GET['opt']) {
			case 'granjas':
				require_once 'controller/abmGranjasController.php';
				if ( $_SESSION['tipoUsuario'] === 'Propietario' )
				{
					require_once 'view/abmGranjas.php';
				}else{
					$error = '403';
					require_once 'view/error.php';
				}
				break;
			case 'galpones':
				require_once 'controller/abmGalponesController.php';
				if ( $_SESSION['tipoUsuario'] === 'Propietario' )
				{
					require_once 'view/abmGalpones.php';
				}else{
					$error = '403';
					require_once 'view/error.php';
				}
				break;
			case 'database':
				require_once 'view/database.php';
				break;
			case 'mantenimientos':
				require_once 'controller/mantenimientosController.php';
				require_once 'view/abmMantenimientos.php';
				break;
			case 'vacunas':
				require_once 'controller/vacunasController.php';
				require_once 'view/abmVacunas.php';
				break;
			case 'home':
				require_once 'controller/homeController.php';
				break;
			case 'usuarios':
				require_once 'controller/userController.php';
				if ($_SESSION['tipoUsuario'] === 'Propietario' )
				{
					require_once 'view/abmUsuarios.php';
				}else{
					$error = '403';
					require_once 'view/error.php';
				}
				break;
		}
		break;

	default:
		$error = '404';
		require_once 'view/error.php';
		break;
}

if (!empty($_POST['btLogin'])) {
	//Cargo el controlador de usuario
	//Internamente, cada controlador sabe como manejar los POST y los GET que le corresponden.
	require_once 'controller/userController.php';
}
