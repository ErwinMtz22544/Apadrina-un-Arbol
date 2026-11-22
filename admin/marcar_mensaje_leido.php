<?php
session_start();
include("../includes/db.php");

// Seguridad
if (!isset($_SESSION['rol']) || ($_SESSION['rol'] !== 'admin' && $_SESSION['rol'] != 1)) {
    die("Acceso denegado");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_mensaje = $_POST['id_mensaje'] ?? '';

    if (!empty($id_mensaje)) {
        $bulk = new MongoDB\Driver\BulkWrite;
        $bulk->update(
            ['_id' => new MongoDB\BSON\ObjectId($id_mensaje)],
            ['$set' => ['leido' => 1]] // 1 significa que ya fue leído
        );
        $conexion->executeBulkWrite('ArbolBD.mensajes_soporte', $bulk);
    }
    
    header("Location: buzon_mensajes.php");
    exit();
} else {
    echo "ID no proporcionado o método incorrecto.";
}
?>