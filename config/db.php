<?php
$host = "localhost";
$user = "db_operator";
$password = "OperatorPassword99!";
$database = "tienda_hogar";

$conexion = mysqli_connect($host, $user, $password, $database);

if (!$conexion) {
    die("Error critico de conexion: " . mysqli_connect_error());
}
?>
