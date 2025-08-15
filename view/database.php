<?php
require_once __DIR__.'/../model/testModel.php';
$ultimoBackup = Test::obtenerUltimaFechaBackup();
$diasSinBackup = null;
if ($ultimoBackup) {
    $fechaBackup = new DateTime($ultimoBackup);
    $hoy = new DateTime();
    $diasSinBackup = $hoy->diff($fechaBackup)->days;
}

$body = <<<HTML
<div class="container">
    <h1>Backup y restauración</h1>

    <form id="formBackup" enctype="multipart/form-data" style="display:none;">
        <input type="hidden" name="btTest" id="btTest">
        <input type="file" name="archivoBackup" id="archivoBackup" style="display:none;">
    </form>

    <p class="d-inline-flex gap-1">
        <button class="btn btn-primary" type="button" id="btnBackup">Realizar copia de seguridad</button>
        <button class="btn btn-primary" type="button" id="btnRestore">Restaurar copia de seguridad</button>
    </p>

    <div class="card">
        <div class="card-body">
            <h5 class="card-title">Última copia de seguridad</h5>
            <p class="card-text">__INFORMACION__</p>
        </div>
    </div>

</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const btTest = document.getElementById('btTest');
    const archivoBackup = document.getElementById('archivoBackup');

    // Backup
    document.getElementById('btnBackup').addEventListener('click', function () {
        const formData = new FormData();
        formData.append('btTest', 'backupDB');

        fetch('index.php?opt=test', {
            method: 'POST',
            body: formData
        })
        .then(resp => resp.ok ? resp.json() : Promise.reject(resp.json()))
        .then(data => {
            if (data.msg) {
                showToastOkay(data.msg);
            }
        })
        .catch(async err => {
            const data = await err;
            showToastError(data.msg || 'Error al realizar el backup.');
        });
    });

    // Restaurar
    document.getElementById('btnRestore').addEventListener('click', function () {
        archivoBackup.click();
    });

    archivoBackup.addEventListener('change', function () {
        if (archivoBackup.files.length > 0) {
            const formData = new FormData();
            formData.append('btTest', 'restoreDB');
            formData.append('archivoBackup', archivoBackup.files[0]);

            fetch('index.php?opt=test', {
                method: 'POST',
                body: formData
            })
            .then(resp => resp.ok ? resp.json() : Promise.reject(resp.json()))
            .then(data => {
                if (data.msg) {
                    showToastOkay(data.msg);
                }
            })
            .catch(async err => {
                const data = await err;
                showToastError(data.msg || 'Error al restaurar la base de datos.');
            });
        }
    });
});
</script>
HTML;
// Si el backup tiene más de 15 días, generar script JS para mostrar toast
if ($diasSinBackup !== null && $diasSinBackup > 15) {
    $body = str_replace('__INFORMACION__', htmlspecialchars('No se realiza una copia de seguridad hace más de 15 días (último backup: hace __DIAS__ días). Procure realizarla lo antes posible.'), $body);
}else{
    $body = str_replace('__INFORMACION__', htmlspecialchars('Se ha realizado una copia de seguridad hace __DIAS__ días.'), $body);
}
$body = str_replace('__DIAS__', htmlspecialchars($diasSinBackup), $body);
include 'view/toast.php';
$body .= $toast;
?>

