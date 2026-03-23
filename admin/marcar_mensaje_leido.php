<?php
include 'verificar_admin.php'; // Protección de seguridad
include '../includes/db.php';     // Conexión a la BD

if (isset($_GET['id'])) {
    // Limpiamos el ID para evitar inyecciones
    $id = mysqli_real_escape_string($conexion, $_GET['id']);
    
    // Actualizamos el estado a 'leido = 1'
    $query = "UPDATE mensajes_soporte SET leido = 1 WHERE id = '$id'";
    
    if (mysqli_query($conexion, $query)) {
        // Enviamos un código de éxito (200 OK)
        http_response_code(200);
        echo "Mensaje marcado como leído";
    } else {
        // Enviamos un código de error (500)
        http_response_code(500);
        echo "Error al actualizar: " . mysqli_error($conexion);
    }
} else {
    http_response_code(400);
    echo "ID no proporcionado";
}
?>