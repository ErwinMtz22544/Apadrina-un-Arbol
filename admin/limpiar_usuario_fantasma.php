<?php
session_start();
if (!isset($_SESSION['usuario']) || $_SESSION['id_rol'] != 1) {
    header("Location: ../pages/login.php");
    exit();
}

include '../includes/db.php';

// 1. Calculamos la fecha límite (hace 24 horas)
$fecha_limite = date('Y-m-d H:i:s', strtotime('-24 hours'));

// 2. Preparamos el borrado masivo
$bulk_borrar = new MongoDB\Driver\BulkWrite;
$bulk_borrar->delete([
    'verificado' => 0, 
    'id_rol' => 3, 
    'fecha_registro' => ['$lt' => $fecha_limite]
]);

try {
    // 3. Ejecutamos el borrado y obtenemos cuántos se borraron
    $resultado_borrado = $conexion->executeBulkWrite('ArbolBD.usuarios', $bulk_borrar);
    $borrados = $resultado_borrado->getDeletedCount();

    // 4. SOLO SI SE BORRÓ ALGUIEN, lo anotamos en el historial
    if ($borrados > 0) {
        $admin = $_SESSION['usuario_nombre'] ?? 'Admin'; // Cambia 'usuario_nombre' a tu variable de sesión si es diferente
        
        $bulk_log = new MongoDB\Driver\BulkWrite;
        $bulk_log->insert([
            'cantidad_eliminados' => $borrados,
            'admin_que_limpio' => $admin,
            'fecha_limpieza' => new MongoDB\BSON\UTCDateTime()
        ]);
        $conexion->executeBulkWrite('ArbolBD.historial_limpieza', $bulk_log);
    }

    header("Location: gestion_usuarios.php?limpieza=$borrados");
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>