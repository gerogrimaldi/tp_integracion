<?php
require_once 'model/testModel.php';
if (!empty($_POST)) {
    $oTest = new Test();
    if ($_POST['btTest'] == 'backupDB') {
        if ($oTest->backupDB()) {
            $oTest->descargarBackupBD();
            $oTest->guardarFechaBackup();
            $oTest->destruirBackup();
            exit;
        } else {
            $error = 'db';
			require_once 'view/error.php';
        }
    } else {
        // Handle other test cases
        echo "<h1 class='bg-white'>";
        switch ($_POST['btTest']) {
            case 'testConnect':
                echo "Testeando conexión MariaDB";
                $oTest->testConnect();
                break;
            case 'crearBD':
                echo "Creando base de datos...";
                $oTest->crearBD();
                break;
            case 'borrarDB':
                echo "Borrando base de datos...";
                $oTest->borrarBD();
                break;
            case 'cargarDatos':
                
                echo "Cargando datos de prueba... Los password del test estan hasheados usar: 12345678";
                $oTest->cargarDatos();
                break;
            case 'crearTablas':
                echo "Creando tablas...";
                $oTest->crearTablas();
                break;
        }
        echo "</h1>";
    }
}