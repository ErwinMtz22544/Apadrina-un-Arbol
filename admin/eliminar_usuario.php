<?php
include 'verificar_admin.php';
include '../includes/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_a_borrar = (int)$_POST['id_usuario'];
    $id_admin_actual = (int)$_SESSION['usuario']; 

    if ($id_a_borrar == $id_admin_actual) {
        header("Location: gestion_usuarios.php?res=error_autoborrado");
        exit();
    }

    $bulk = new MongoDB\Driver\BulkWrite;
    $bulk->delete(['id' => $id_a_borrar]);

    try {
        $conexion->executeBulkWrite('ArbolBD.usuarios', $bulk);
        header("Location: gestion_usuarios.php?res=borrado_ok");
    } catch (Exception $e) {
        header("Location: gestion_usuarios.php?res=error");
    }
    exit();
}
?>