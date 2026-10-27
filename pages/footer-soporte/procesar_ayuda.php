<?php
// 1. Incluimos la conexión de MongoDB
include("../../includes/db.php");

// 2. Verificamos que los datos lleguen por el método POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // 3. Recibimos los datos directos (MongoDB no necesita escape manual como MySQL)
    $nombre  = $_POST['nombre'];
    $email   = $_POST['email'];
    $asunto  = $_POST['asunto'];
    $mensaje = $_POST['mensaje'];

    // 4. Preparamos la herramienta de inserción (BulkWrite)
    $bulk = new MongoDB\Driver\BulkWrite;

    // 5. Armamos el documento como si fuera un JSON
    $documento = [
        'nombre' => $nombre,
        'email' => $email,
        'asunto' => $asunto,
        'mensaje' => $mensaje,
        'leido' => 0, // Mantenemos tu lógica original de no leído
        'fecha' => new MongoDB\BSON\UTCDateTime() // Guarda la fecha y hora exacta
    ];

    // 6. Agregamos el documento a la cola
    $bulk->insert($documento);

    // 7. Ejecutamos la inserción con un bloque try-catch para capturar posibles errores
    try {
        // IMPORTANTE: Asegúrate de que las mayúsculas coincidan con tu Compass (ArbolBD)
        $resultado = $conexion->executeBulkWrite('ArbolBD.mensajes_soporte', $bulk);
        
        // Éxito: Redirigimos de vuelta con un parámetro para el SweetAlert
        header("Location: ayuda.php?status=enviado");
        exit();
        
    } catch (MongoDB\Driver\Exception\Exception $e) {
        // Error de base de datos
        echo "Error técnico: " . $e->getMessage();
    }

} else {
    // Si intentan entrar directo al archivo sin formulario
    header("Location: ayuda.php");
    exit();
}
?>