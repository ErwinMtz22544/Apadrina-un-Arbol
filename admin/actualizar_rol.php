<?php
session_start();
include("../includes/db.php");

// Seguridad
if (!isset($_SESSION['rol']) || ($_SESSION['rol'] !== 'admin' && $_SESSION['rol'] != 1)) {
    die("Acceso denegado");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_usuario = $_POST['id_usuario'] ?? '';
    $nuevo_rol = $_POST['nuevo_rol'] ?? '';

    if (!empty($id_usuario) && !empty($nuevo_rol)) {
        // Convertimos el rol de texto a su ID numérico correspondiente
        $id_rol_num = 3; // Usuario normal por defecto
        if ($nuevo_rol === 'admin') $id_rol_num = 1;
        if ($nuevo_rol === 'editor') $id_rol_num = 2;

        $bulk = new MongoDB\Driver\BulkWrite;
        $bulk->update(
            ['_id' => new MongoDB\BSON\ObjectId($id_usuario)],
            ['$set' => ['rol' => $nuevo_rol, 'id_rol' => $id_rol_num]]
        );
        $conexion->executeBulkWrite('ArbolBD.usuarios', $bulk);
    }
    
    header("Location: gestion_usuarios.php");
    exit();
}
?>