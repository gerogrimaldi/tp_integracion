<?php
include ('./includes/key.php');
$_GET['opt'] = $_GET['opt'] ?? '';

switch ($_GET['opt']) {
	case '':
	case 'login': // Caso adicional para manejar 'login'
		$valueBt = 'login';
		require_once 'includes/key.php';
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
		require_once 'controller/abmGranjasController.php';
		require_once 'view/abmGranjas.php';
		break;

	case 'galpones':
		require_once 'controller/abmGalponesController.php';
		require_once 'view/abmGalpones.php';
		break;

	case 'mantenimientos':
		require_once 'controller/mantenimientosController.php';
		require_once 'view/abmMantenimientos.php';
		break;

	case 'vacunas':
		require_once 'controller/vacunasController.php';
		require_once 'view/abmVacunas.php';
		break;

	default:
		require_once 'view/error_404.php';
		break;
}


if ( !empty($_POST['btFormulario']))
{
	echo "<h1 class='bg-black'>EJECUTO VALIDACION<h1>";
	echo("<h2><script>console.log(".$_POST['btFormulario'].")</script></h2>"); 

    require_once 'controller/userController.php';
	if ($validacion)
	{
		//si no marque el permanecer conectado, quito la validacion
		// if (!isset($_POST['connected'])){
		// 	$validacion = false;
		// }
		echo("<h2><script>console.log('Ingreso Home')</script></h2>"); 

		require_once('view/home.php');		
	}else{
		echo("<h2><script>console.log('validacion falsa')</script></h2>"); 

		$valueBt="login";
        require_once('view/login.php');
	}
}

if ( !empty($_POST['btEvento']) )
{
	require_once 'controller/eventController.php';
}
