<?php
include("../../includes/db.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre  = trim($_POST['nombre'] ?? '');
    $email   = trim($_POST['email'] ?? '');
    $asunto  = trim($_POST['asunto'] ?? 'Mensaje de soporte');
    $mensaje = trim($_POST['mensaje'] ?? '');
    $origen  = $_POST['origen'] ?? 'ayuda';

    // Definir a qué página regresar
    $destino = ($origen === 'contactanos') ? '../contactanos.php' : 'ayuda.php';

    $bulk = new MongoDB\Driver\BulkWrite;

    $documento = [
        'nombre' => $nombre,
        'email' => $email,
        'asunto' => $asunto,
        'mensaje' => $mensaje,
        'leido' => 0,
        'fecha_envio' => gmdate("Y-m-d\TH:i:s\Z")
    ];

    $bulk->insert($documento);

    try {
        $conexion->executeBulkWrite('ArbolBD.mensajes_soporte', $bulk);
        header("Location: $destino?status=enviado");
        exit();
    } catch (MongoDB\Driver\Exception\Exception $e) {
        echo "Error técnico: " . $e->getMessage();
    }
} else {
    header("Location: ayuda.php");
    exit();
}
?>