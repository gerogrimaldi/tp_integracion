<?php
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
try {
    require_once 'includes/config.php';
    $this->mysqli = mysqli_connect("$db[host]", "$db[username]", "$db[password]", "$db[database]");
    $this->mysqli->set_charset("utf8mb4");
} catch (mysqli_sql_exception $e) {
    error_log("Error de conexión a la base de datos: " . $e->getMessage());
    header("Location: index.php?opt=error_db");
    exit; 
}