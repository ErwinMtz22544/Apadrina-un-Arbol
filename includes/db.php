<?php
// Archivo: config/db.php
$conexion = mysqli_connect("host.docker.internal", "root", "123456", "arbolbd");

if (!$conexion) {
    die("Error de conexión: " . mysqli_connect_error());
}
?>