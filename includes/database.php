<?php 

$mysqli = new mysqli("127.0.0.1", "root", "", "test");

// $sql = "CREATE DATABASE tp_grupo5";
// if ($mysqli->query($sql) === TRUE) {
//   echo "Database created successfully";
// } else {
//   echo "Error creating database: " . $mysqli->error;
// }

/* execute multi query */
$sql = file_get_contents('db/tp_grupo2.sql');
$mysqli->multi_query($sql);
