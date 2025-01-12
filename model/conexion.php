<?php
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
try {
    $this->mysqli = mysqli_connect("127.0.0.1", "root", "", "granjas");
    //$conex = mysqli_connect("fcytpa.uader.edu.ar", "2024_grupo2", "Grupo2_8964", "2024_grupo2");
    $this->mysqli->set_charset("utf8mb4");
} catch (mysqli_sql_exception $e) {
    error_log("Error de conexión a la base de datos: " . $e->getMessage());
    header("Location: index.php?opt=error_db");
    exit; 
}