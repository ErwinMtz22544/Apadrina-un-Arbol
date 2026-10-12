<?php
include 'verificar_admin.php'; 
include '../includes/db.php';     

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    
    $bulk = new MongoDB\Driver\BulkWrite;
    $bulk->update(
        ['_id' => new MongoDB\BSON\ObjectId($id)],
        ['$set' => ['leido' => 1]]
    );
    
    try {
        $conexion->executeBulkWrite('ArbolBD.mensajes_soporte', $bulk);
        http_response_code(200);
        echo "Mensaje marcado como leído";
    } catch (Exception $e) {
        http_response_code(500);
        echo "Error al actualizar: " . $e->getMessage();
    }
} else {
    http_response_code(400);
    echo "ID no proporcionado";
}
?>