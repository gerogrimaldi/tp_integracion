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
            <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">


            </head>
            
            <body class="bg-dark text-white">
            <div class="container">';
                
        }
    }

    private function setMenu($_menu = "")
    {
        // NAVBAR
        if($_menu != ""){
            $this->menu = $_menu;
        } else{
            $this->menu = 
                '<nav class="navbar navbar-expand-lg navbar-light bg-light">
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse" id="navbarNav">
                        <ul class="navbar-nav">
                            <li class="nav-item">
                                <a class="nav-link" href="index.php">Inicio</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="index.php?opt=login">Ingresar</a>
                            </li>
                        </ul>
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
