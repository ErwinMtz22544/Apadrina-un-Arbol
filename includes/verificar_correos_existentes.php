<?php
include 'db.php';

$correo = $_POST['email'];
$dominio = substr(strrchr($correo, "@"), 1);

if (!checkdnsrr($dominio, "MX")) {
    echo "inventado"; // El dominio no existe o no puede recibir correos
    exit();
}

$query = new MongoDB\Driver\Query(['email' => $correo]);
$resultado = $conexion->executeQuery('ArbolBD.usuarios', $query)->toArray();

if (count($resultado) > 0) {
    echo "existe";
} else {
    echo "disponible";
}
?>