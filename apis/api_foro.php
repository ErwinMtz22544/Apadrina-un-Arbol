<?php
header('Content-Type: application/json');
include("../includes/db.php"); // Asegúrate de que apunte correctamente a tu archivo de conexión a MongoDB

try {
    // Consultar los posts del foro ordenados por fecha (del más nuevo al más viejo)
    $opciones = ['sort' => ['fecha' => -1]];
    $queryForo = new MongoDB\Driver\Query([], $opciones);
    $cursor = $conexion->executeQuery("ArbolBD.foro", $queryForo);
    
    $posts = [];
    foreach ($cursor as $document) {
        $posts[] = [
            '_id' => (string)$document->_id,
            'email_usuario' => $document->email_usuario ?? '',
            'nombre_usuario' => $document->nombre_usuario ?? '',
            'mensaje' => $document->mensaje ?? '',
            'imagen' => $document->imagen ?? null,
            'fecha' => $document->fecha ?? '',
            'comentarios' => $document->comentarios ?? []
        ];
    }
    
    echo json_encode($posts);
} catch (Exception $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
?>