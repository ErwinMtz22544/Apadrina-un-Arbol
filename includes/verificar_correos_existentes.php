<?php
include 'db.php';

$correo = isset($_GET['email']) ? trim($_GET['email']) : '';

// Validar que el correo no esté vacío antes de hacer consultas o validaciones DNS
if (empty($correo) || !filter_var($correo, FILTER_VALIDATE_EMAIL)) {
    echo "invalido"; 
    exit();
}

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