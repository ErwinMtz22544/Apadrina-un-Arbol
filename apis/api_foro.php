
<?php

header('Content-Type: application/json; charset=utf-8');

include("../includes/db.php");

try {

    // CREAR PUBLICACIÓN
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        $datos = json_decode(file_get_contents("php://input"), true);

        if (!$datos) {
            echo json_encode([
                'success' => false,
                'message' => 'Datos inválidos'
            ]);
            exit;
        }

        $email = strtolower(trim($datos['email_usuario'] ?? ''));
        $nombre = trim($datos['nombre_usuario'] ?? '');
        $mensaje = trim($datos['mensaje'] ?? '');
        $imagen = $datos['imagen'] ?? null;
        $tipo = $datos['tipo_multimedia'] ?? 'image';

        if ($email === '' || $nombre === '') {
            echo json_encode([
                'success' => false,
                'message' => 'Faltan datos del usuario'
            ]);
            exit;
        }

        if ($mensaje === '' && empty($imagen)) {
            echo json_encode([
                'success' => false,
                'message' => 'La publicación necesita texto o multimedia'
            ]);
            exit;
        }

        if (!in_array($tipo, ['image', 'video'], true)) {
            echo json_encode([
                'success' => false,
                'message' => 'Tipo de archivo no válido'
            ]);
            exit;
        }

        $post = [
            'email_usuario' => $email,
            'nombre_usuario' => $nombre,
            'mensaje' => $mensaje,
            'imagen' => $imagen,
            'tipo_multimedia' => $tipo,
            'fecha' => date('Y-m-d H:i:s'),
            'comentarios' => []
        ];

        $bulk = new MongoDB\Driver\BulkWrite();
        $bulk->insert($post);

        $conexion->executeBulkWrite('ArbolBD.foro', $bulk);

        echo json_encode([
            'success' => true,
            'message' => 'Publicación creada correctamente'
        ]);

        exit;
    }

    // CONSULTAR PUBLICACIONES
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {

        $opciones = [
            'sort' => ['fecha' => -1]
        ];

        $queryForo = new MongoDB\Driver\Query([], $opciones);

        $cursor = $conexion->executeQuery(
            'ArbolBD.foro',
            $queryForo
        );

        $posts = [];

        foreach ($cursor as $document) {

            $posts[] = [
                '_id' => (string)$document->_id,
                'email_usuario' => $document->email_usuario ?? '',
                'nombre_usuario' => $document->nombre_usuario ?? '',
                'mensaje' => $document->mensaje ?? '',
                'imagen' => $document->imagen ?? null,
                'tipo_multimedia' => $document->tipo_multimedia ?? 'image',
                'fecha' => $document->fecha ?? '',
                'comentarios' => $document->comentarios ?? []
            ];
        }

        echo json_encode($posts);
        exit;
    }

    echo json_encode([
        'success' => false,
        'message' => 'Método no permitido'
    ]);

} catch (Throwable $e) {

    http_response_code(500);

    echo json_encode([
        'success' => false,
        'message' => 'Error del servidor',
        'error' => $e->getMessage()
    ]);
}
?>
