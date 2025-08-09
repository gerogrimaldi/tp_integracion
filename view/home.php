<?php
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
                <div class="card h-100 text-center bg-warning text-dark shadow">
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
                <div class="card h-100 text-center bg-info text-dark shadow">
                    <div class="card-body">
                        <i class="bi bi-capsule display-1"></i>
                        <h5 class="card-title mt-3">Vacunas</h5>
                        <p class="card-text">Administrar vacunas y lotes.</p>
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
?>
