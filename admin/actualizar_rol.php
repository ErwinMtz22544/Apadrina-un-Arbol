<?php
include 'verificar_admin.php';
include '../includes/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Convertimos a número entero asumiendo que tu ID en SQL era numérico
    $id_usuario = (int)$_POST['id_usuario']; 
    $nuevo_rol = (int)$_POST['nuevo_rol'];

    $bulk = new MongoDB\Driver\BulkWrite;
    $bulk->update(
        ['id' => $id_usuario],
        ['$set' => ['id_rol' => $nuevo_rol]]
    );

    try {
        $conexion->executeBulkWrite('ArbolBD.usuarios', $bulk);
        header("Location: gestion_usuarios.php?res=ok"); 
    } catch (Exception $e) {
        header("Location: gestion_usuarios.php?res=error");
    }
    exit();
}
?>