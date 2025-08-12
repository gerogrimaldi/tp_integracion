<?php

class Page
{
    private $header;
    private $menu;
    private $body;
    private $footer;

    function __construct()
    {
        $this->setHeader();
        $this->setMenu();
        $this->setFooter();
    }

    private function setHeader($_header = "")
    {
        // HEAD
        if ($_header!=""){
            $this->header = $_header;
        }else{

            $this->header = 
            '<!DOCTYPE html>
            <html lang="es">
            <head>
            <meta charset="utf-8">
            <meta http-equiv="X-UA-Compatible" content="IE=edge">
            <title>' . EMPRESA_NOMBRE . '</title>
            <meta name="description" content="">
            <meta name="viewport" content="width=device-width, initial-scale=1">
            <script src="https://www.google.com/recaptcha/api.js" async defer></script>
            <link rel="shortcut icon" type="image/x-icon" href="./img/favicon.ico" />
            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
            <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
            <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
            </head>
            <body class="bg-dark text-white">
            <div class="container">';
                
        }
    }

private function setMenu($_menu = ""){
        // NAVBAR
        if($_menu != ""){
            $this->menu = $_menu;
        } else{
            // Determinar si el usuario está autenticado
            require_once __DIR__ . '/../includes/auth.php';
            $isLogged = checkAuth();
            $navItems = '';
            $userDropdown = '';
            if ($isLogged) {
                $navItems = '
                    <li class="nav-item"><a class="nav-link" href="index.php?opt=granjas">Granjas</a></li>
                    <li class="nav-item"><a class="nav-link" href="index.php?opt=galpones">Galpones</a></li>
                    <li class="nav-item"><a class="nav-link" href="index.php?opt=mantenimientos">Mantenimientos</a></li>
                    <li class="nav-item"><a class="nav-link" href="index.php?opt=vacunas">Vacunas</a></li>
                    <li class="nav-item"><a class="nav-link" href="index.php?opt=compuestos">Compuestos</a></li>
                    <li class="nav-item"><a class="nav-link" href="index.php?opt=usuarios">Usuarios</a></li>
                    <li class="nav-item"><a class="nav-link" href="index.php?opt=database">Configuración</a></li>
                    <li class="nav-item"><a class="nav-link" href="index.php?opt=test">Test</a></li>
                ';
                $userName = isset($_SESSION['user_name']) ? htmlspecialchars($_SESSION['user_name']) : 'Usuario';
                $userDropdown = '
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="bi bi-person-circle me-1"></i> ' . $userName . '
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                                <li><span class="dropdown-item-text fw-bold"><i class="bi bi-person me-1"></i>Ha iniciado sesión como ' . $userName . '</span></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item text-danger" href="#" id="logoutBtn"><i class="bi bi-box-arrow-right me-1"></i>Cerrar sesión</a></li>
                            </ul>
                        </li>
                    </ul>
                    <script>
                    document.addEventListener("DOMContentLoaded", function() {
                        var logoutBtn = document.getElementById("logoutBtn");
                        if (logoutBtn) {
                            logoutBtn.addEventListener("click", function(e) {
                                e.preventDefault();
                                fetch("index.php?opt=usuarios&ajax=logout", {
                                    method: "GET"
                                })
                                .then(response => {
                                    if (response.ok) {
                                        window.location.href = "index.php?opt=login";
                                    } else {
                                        showToastError("Error al cerrar sesión");
                                    }
                                })
                                .catch(error => {
                                    console.error("Error en la solicitud AJAX:", error);
                                    showToastError("Error desconocido.");
                                });
                            });
                        }
                    });
                    </script>';
            } else {
                $navItems = '
                    <li class="nav-item"><a class="nav-link" href="index.php">Inicio</a></li>
                    <li class="nav-item"><a class="nav-link" href="index.php?opt=login">Ingresar</a></li>
                ';
            }
            $this->menu =
                '<nav class="navbar navbar-expand-lg navbar-light bg-light">
                    <div class="container-fluid">
                        <a class="navbar-brand" href="index.php">' . EMPRESA_NOMBRE . '</a>
                        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                            <span class="navbar-toggler-icon"></span>
                        </button>
                        <div class="collapse navbar-collapse" id="navbarNav">
                            <ul class="navbar-nav">' . $navItems . '</ul>' . $userDropdown . '
                        </div>
                    </div>
                </nav>
                <br />';
        }
    }

    public function setBody($body)
    {
        $this->body = $body;
    }

    private function setFooter($_footer = "")
    {
        if($_footer!= ""){
            $this->footer = $_footer;
        } else{
        $this->footer = 
            '<footer class="footer bg-dark text-white text-center py-3 mt-5">
                <div class="container">
                    <i>Gotte-Grimaldi-Murguia</i>
                </div>
            </footer>
            </div>
                <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
                <link href="js/DataTables/datatables.min.css" rel="stylesheet">
                <script src="js/DataTables/datatables.min.js"></script>
                <script>
                $(document).ready(function() {
                    $.extend(true, $.fn.dataTable.defaults, {
                        language: {
                           url: "js/DataTables/Spanish.json"
                        }
                    });
                });
                </script>
                </body>
            </html>';
        }
    }

    public function getHtml()
    {
        $Pagina = $this->header;
        $Pagina .= $this->menu;
        $Pagina .= $this->body;
        $Pagina .= $this->footer;
        return $Pagina;
    }
}
