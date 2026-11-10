<?php
ob_start();

header('Content-Type: application/json; charset=utf-8');

require_once("../includes/db.php");

try {

    // ==========================================
    // CREAR PUBLICACIÓN
    // ==========================================
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        $datos = json_decode(file_get_contents("php://input"), true);

        if (!is_array($datos)) {
            http_response_code(400);
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
            http_response_code(400);
            echo json_encode([
                'success' => false,
                'message' => 'Faltan datos del usuario'
            ]);
            exit;
        }

        if ($mensaje === '' && empty($imagen)) {
            http_response_code(400);
            echo json_encode([
                'success' => false,
                'message' => 'Escribe algo o selecciona una imagen'
            ]);
            exit;
        }

        // Por ahora solamente permitimos imágenes.
        if ($tipo !== 'image') {
            http_response_code(400);
            echo json_encode([
                'success' => false,
                'message' => 'Solo se permiten imágenes'
            ]);
            exit;
        }

        if ($imagen !== null && !is_string($imagen)) {
            http_response_code(400);
            echo json_encode([
                'success' => false,
                'message' => 'La imagen no es válida'
            ]);
            exit;
        }

        $post = [
            'email_usuario' => $email,
            'nombre_usuario' => $nombre,
            'mensaje' => $mensaje,
            'imagen' => $imagen ?: null,
            'tipo_multimedia' => 'image',
            'fecha' => date('Y-m-d H:i:s'),
            'comentarios' => []
        ];

        $bulk = new MongoDB\Driver\BulkWrite();
        $bulk->insert($post);

        $conexion->executeBulkWrite('ArbolBD.foro', $bulk);

        echo json_encode([
            'success' => true,
            'message' => 'Publicación creada correctamente'
        ], JSON_UNESCAPED_UNICODE);

        exit;
    }

    // ==========================================
    // CONSULTAR PUBLICACIONES
    // ==========================================
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {

        $opciones = [
            'sort' => ['fecha' => -1]
        ];

        $query = new MongoDB\Driver\Query([], $opciones);

        $cursor = $conexion->executeQuery(
            'ArbolBD.foro',
            $query
        );

        $posts = [];

        foreach ($cursor as $documento) {

            $posts[] = [
                '_id' => (string) $documento->_id,
                'email_usuario' => $documento->email_usuario ?? '',
                'nombre_usuario' => $documento->nombre_usuario ?? 'Usuario',
                'mensaje' => $documento->mensaje ?? '',
                'imagen' => $documento->imagen ?? null,
                'tipo_multimedia' => $documento->tipo_multimedia ?? 'image',
                'fecha' => $documento->fecha ?? '',
                'comentarios' => $documento->comentarios ?? []
            ];
        }

        echo json_encode(
            $posts,
            JSON_UNESCAPED_UNICODE
        );

        exit;
    }

    http_response_code(405);

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
    ], JSON_UNESCAPED_UNICODE);
}
