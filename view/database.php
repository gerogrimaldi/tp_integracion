<?php
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

</div>


HTML;
// Agregar las funciones y el contenedor de los toast
// Para mostrar notificaciones
include 'view/toast.php';
$body .= $toast;
?>

