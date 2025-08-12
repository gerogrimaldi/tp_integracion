<?php
require_once __DIR__.'/../model/testModel.php';
$ultimoBackup = Test::obtenerUltimaFechaBackup();
$body = <<<HTML

<div class="container">
    <h1>Backup y restauración</h1>
    
    <p class="d-inline-flex gap-1">
        <button class="btn btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#verMant" aria-expanded="false" aria-controls="collapseExample">
            Realizar copia de seguridad
        </button>
        <button class="btn btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#verMant" aria-expanded="false" aria-controls="collapseExample">
            Restaurar copia de seguridad
        </button>
    </p>

    <div class="card">
        <div class="card-body">
            <h5 class="card-title">Última copia de seguridad</h5>
            <p class="card-text">__ULTIMO_BACKUP__</p>
        </div>
    </div>

</div>


HTML;
$body = str_replace('__ULTIMO_BACKUP__', htmlspecialchars($ultimoBackup), $body);
// Agregar las funciones y el contenedor de los toast
// Para mostrar notificaciones
include 'view/toast.php';
$body .= $toast;
?>

