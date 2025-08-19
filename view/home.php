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
<div class="container py-2">
    <h1 class="text-center mb-2">Panel Principal</h1>
    <div class="row row-cols-1 row-cols-md-3 g-4 justify-content-center">
        <!-- Columna 1: Mantenimientos -->
        <div class="col">
            <div class="row g-2">
                <div class="col-12">
                    <a href="index.php?opt=galpones" class="text-decoration-none">
                        <div class="card h-100 text-center bg-secondary text-white shadow">
                            <div class="card-body">
                                <i class="bi bi-building display-1"></i>
                                <h6 class="card-title mt-2">Gestión Galpones</h6>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-12">
                    <a href="index.php?opt=mantenimientosGalpones" class="text-decoration-none">
                        <div class="card h-100 text-center bg-warning text-white shadow">
                            <div class="card-body">
                                <i class="bi bi-tools display-1"></i>
                                <h6 class="card-title mt-2">Mantenimientos Galpones</h6>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </div>

        <!-- Columna 2: Gestión -->
        <div class="col">
            <div class="row g-2">
                <div class="col-12">
                    <a href="index.php?opt=granjas" class="text-decoration-none">
                        <div class="card h-100 text-center bg-success text-white shadow">
                            <div class="card-body">
                                <i class="bi bi-house-door display-1"></i>
                                <h6 class="card-title mt-2">Gestión Granjas</h6>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-12">
                    <a href="index.php?opt=mantenimientosGranjas" class="text-decoration-none">
                        <div class="card h-100 text-center bg-warning text-white shadow">
                            <div class="card-body">
                                <i class="bi bi-tools display-1"></i>
                                <h6 class="card-title mt-2">Mantenimientos Granjas</h6>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </div>

        <!-- Columna 3: Vacunas + Compuestos -->
        <div class="col">
            <div class="row g-2">
                <div class="col-12">
                    <a href="index.php?opt=vacunas" class="text-decoration-none">
                        <div class="card h-100 text-center bg-info text-white shadow">
                            <div class="card-body">
                                <i class="bi bi-capsule display-1"></i>
                                <h6 class="card-title mt-2">Gestionar Vacunas</h6>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-12">
                    <a href="index.php?opt=compuestos" class="text-decoration-none">
                        <div class="card h-100 text-center bg-success text-white shadow">
                            <div class="card-body">
                                <i class="bi bi-leaf display-1"></i>
                                <h6 class="card-title mt-2">Gestionar Compuestos</h6>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Sección Lotes de aves 2x2 -->
    <div class="row row-cols-1 row-cols-md-2 g-4 mt-2 justify-content-center">
        <!-- Lotes de aves -->
        <div class="col">
            <div class="row g-2">
                <div class="col-6">
                    <a href="index.php?opt=lotesAves" class="text-decoration-none">
                        <div class="card h-100 text-center bg-primary text-white shadow">
                            <div class="card-body">
                                <i class="bi bi-egg-fried display-1"></i>
                                <h6 class="card-title mt-2">Gestionar Lotes</h6>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-6">
                    <a href="index.php?opt=cargarMortandad" class="text-decoration-none">
                        <div class="card h-100 text-center bg-danger text-white shadow">
                            <div class="card-body">
                                <i class="bi bi-heartbreak display-1"></i>
                                <h6 class="card-title mt-2">Cargar Mortandad</h6>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-6">
                    <a href="index.php?opt=cargarPesaje" class="text-decoration-none">
                        <div class="card h-100 text-center bg-warning text-white shadow">
                            <div class="card-body">
                                <i class="bi bi-speedometer2 display-1"></i>
                                <h6 class="card-title mt-2">Cargar Pesaje</h6>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-6">
                    <a href="index.php?opt=aplicarVacunas" class="text-decoration-none">
                        <div class="card h-100 text-center bg-info text-white shadow">
                            <div class="card-body">
                                <i class="bi bi-journal-medical display-1"></i>
                                <h6 class="card-title mt-2">Aplicar Vacunas</h6>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </div>

    <!-- Usuarios, Backup y Test -->

    <div class="col">
        <div class="row g-2">
            <div class="col-12">
                <a href="index.php?opt=usuarios" class="text-decoration-none">
                    <div class="card h-100 text-center bg-primary text-white shadow">
                        <div class="card-body">
                            <i class="bi bi-people display-1"></i>
                            <h5 class="card-title mt-2">Gestionar Usuarios</h5>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-12">
                <a href="index.php?opt=database" class="text-decoration-none">
                    <div class="card h-100 text-center bg-dark text-white shadow">
                        <div class="card-body">
                            <i class="bi bi-gear display-1"></i>
                            <h5 class="card-title mt-2">Copia de Seguridad</h5>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>
</div>
HTML;

include 'view/toast.php';
$body .= $toast;

if ($diasSinBackup !== null && $diasSinBackup > 15) {
    $body .= "<script>showToastError('No se realiza una copia de seguridad hace más de 15 días (último backup: hace {$diasSinBackup} días). Procure realizarla lo antes posible.');</script>";
}
?>
