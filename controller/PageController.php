<?php
// session_start();
include ('./includes/key.php');
if ( !empty($_GET['opt']) ) 
{
	if ($_GET['opt']=='login')
	{
		$valueBt="login";

		require_once 'includes/key.php';
		// $recaptcha_key = $key;
        require_once 'view/login.php';
				
	}

	if ($_GET['opt']=='list')
	{
	
		require_once 'controller/eventController.php';
		
        require_once 'view/listEventos.php';
				
	}

	if ($_GET['opt']=='error_db')
	{
    
        require_once('view/error_db.php');
				
	}
}else{

	$valueBt="login";
	require_once('view/login.php');

}


if ( !empty($_POST['btFormulario']))
{

	echo "<h1 class='bg-black'>EJECUTO VALIDACION<h1>";

    require_once 'controller/userController.php';

	if ($validacion)
	{
		//si no marque el permanecer conectado, quito la validacion
		// if (!isset($_POST['connected'])){
		// 	$validacion = false;
		// }
	
		require_once('view/home.php');
				
	}else{
		$valueBt="login";
    
        require_once('view/login.php');
	}
}
if ( !empty($_POST['btEvento']) )
{
	require_once 'controller/eventController.php';
}