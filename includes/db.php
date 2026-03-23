<?php
// Archivo: config/db.php
$conexion = mysqli_connect("localhost", "root", "", "arbolbd");

if (!$conexion) {
    die("Error de conexión: " . mysqli_connect_error());
}
?>