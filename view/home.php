<?php
require_once __DIR__.'/../model/testModel.php';
$ultimoBackup = Test::obtenerUltimaFechaBackup(); // Devuelve string con fecha o NULL
$diasSinBackup = null;
if ($ultimoBackup) {
    $fechaBackup = new DateTime($ultimoBackup);
    $hoy = new DateTime();
    $diasSinBackup = $hoy->diff($fechaBackup)->days;
}

$body = <<<HTML
<div class="container py-5">
    <h1 class="text-center mb-5">Panel Principal</h1>
    <div class="row row-cols-1 row-cols-md-3 g-4 justify-content-center">
        <div class="col">
            <a href="index.php?opt=granjas" class="text-decoration-none">
                <div class="card h-100 text-center bg-primary text-white shadow">
                    <div class="card-body">
                        <i class="bi bi-house-door display-1"></i>
                        <h5 class="card-title mt-3">Granjas</h5>
                        <p class="card-text">Administrar granjas registradas.</p>
                    </div>
                </div>
            </a>
        </div>
        <div class="col">
            <a href="index.php?opt=galpones" class="text-decoration-none">
                <div class="card h-100 text-center bg-success text-white shadow">
                    <div class="card-body">
                        <i class="bi bi-building display-1"></i>
                        <h5 class="card-title mt-3">Galpones</h5>
                        <p class="card-text">Gestionar galpones de las granjas.</p>
                    </div>
                </div>
            </a>
        </div>
        <div class="col">
            <a href="index.php?opt=mantenimientos" class="text-decoration-none">
                <div class="card h-100 text-center bg-warning text-white shadow">
                    <div class="card-body">
                        <i class="bi bi-tools display-1"></i>
                        <h5 class="card-title mt-3">Mantenimientos</h5>
                        <p class="card-text">Ver y registrar mantenimientos.</p>
                    </div>
                </div>
            </a>
        </div>
        <div class="col">
            <a href="index.php?opt=vacunas" class="text-decoration-none">
                <div class="card h-100 text-center bg-info text-white shadow">
                    <div class="card-body">
                        <i class="bi bi-capsule display-1"></i>
                        <h5 class="card-title mt-3">Vacunas</h5>
                        <p class="card-text">Administrar vacunas y lotes.</p>
                    </div>
                </div>
            </a>
        </div>
        <div class="col">
            <a href="index.php?opt=database" class="text-decoration-none">
                <div class="card h-100 text-center bg-secondary text-white shadow">
                    <div class="card-body">
                        <i class="bi bi-gear display-1"></i>
                        <h5 class="card-title mt-3">Configuración</h5>
                        <p class="card-text">Copias de seguridad y restauración.</p>
                    </div>
                </div>
            </a>
        </div>
        <div class="col">
            <a href="index.php?opt=compuestos" class="text-decoration-none">
                <div class="card h-100 text-center bg-primary text-white shadow">
                    <div class="card-body">
                        <i class="bi bi-leaf display-1"></i>
                        <h5 class="card-title mt-3">Compuestos</h5>
                        <p class="card-text">Administrar compuestos y compras.</p>
                    </div>
                </div>
            </a>
        </div>
        <div class="col">
            <a href="index.php?opt=usuarios" class="text-decoration-none">
                <div class="card h-100 text-center bg-success text-white shadow">
                    <div class="card-body">
                        <i class="bi bi-people display-1"></i>
                        <h5 class="card-title mt-3">Usuarios</h5>
                        <p class="card-text">Gestionar usuarios del sistema.</p>
                    </div>
                </div>
            </a>
        </div>
        <div class="col">
            <a href="index.php?opt=test" class="text-decoration-none">
                <div class="card h-100 text-center bg-secondary text-white shadow">
                    <div class="card-body">
                        <i class="bi bi-clipboard-check display-1"></i>
                        <h5 class="card-title mt-3">Test</h5>
                        <p class="card-text">Acceso a pruebas del sistema.</p>
                    </div>
                </div>
            </a>
        </div>
    </div>
</div>
HTML;

include 'view/toast.php';
$body .= $toast;

// Si el backup tiene más de 15 días, generar script JS para mostrar toast
if ($diasSinBackup !== null && $diasSinBackup > 15) {
    $body .= "<script>showToastError('No se realiza una copia de seguridad hace más de 15 días (último backup: hace {$diasSinBackup} días). Procure realizarla lo antes posible.');</script>";
}

?>