
<?php
ob_start();

header('Content-Type: application/json; charset=utf-8');

// Función para responder únicamente JSON
function responder($datos, $codigo = 200) {

    if (ob_get_level() > 0) {
        ob_clean();
    }

    http_response_code($codigo);

    echo json_encode($datos, JSON_UNESCAPED_UNICODE);
    exit;
}

include("../includes/db.php");

try {

    $accion = $_GET['accion'] ?? '';
    $datos = json_decode(file_get_contents("php://input"), true) ?? [];

    // ==========================================
    // 1. BUSCAR USUARIOS
    // ==========================================

    if ($accion === 'buscar') {

        $busqueda = trim($_GET['q'] ?? '');
        $emailUsuario = strtolower(trim($_GET['email_usuario'] ?? ''));

        if (strlen($busqueda) < 2) {
            responder([
                'success' => true,
                'usuarios' => []
            ]);
        }

        $regex = new MongoDB\BSON\Regex(
            preg_quote($busqueda),
            'i'
        );

        $filtro = [
            'email' => ['$ne' => $emailUsuario],
            '$or' => [
                ['nombre_usuario' => $regex],
                ['email' => $regex]
            ]
        ];

        $opciones = [
            'projection' => [
                'password' => 0
            ],
            'limit' => 20
        ];

        $query = new MongoDB\Driver\Query($filtro, $opciones);

        $cursor = $conexion->executeQuery(
            "ArbolBD.usuarios",
            $query
        );

        $usuarios = [];

        foreach ($cursor as $usuario) {
            $usuarios[] = [
                'nombre_usuario' => $usuario->nombre_usuario ?? '',
                'email' => $usuario->email ?? '',
                'foto_perfil' => $usuario->foto_perfil ?? null,
                'bio' => $usuario->bio ?? ''
            ];
        }

        responder([
            'success' => true,
            'usuarios' => $usuarios
        ]);
    }

    // ==========================================
    // 2. AGREGAR AMIGO
    // ==========================================

    if ($accion === 'agregar') {

        $emailUsuario = strtolower(trim($datos['email_usuario'] ?? ''));
        $emailAmigo = strtolower(trim($datos['email_amigo'] ?? ''));

        if (empty($emailUsuario) || empty($emailAmigo)) {
            responder([
                'success' => false,
                'mensaje' => 'Faltan datos del usuario.'
            ], 400);
        }

        if ($emailUsuario === $emailAmigo) {
            responder([
                'success' => false,
                'mensaje' => 'No puedes agregarte a ti mismo.'
            ]);
        }

        // Comprobar que ambos usuarios existan
        $queryUsuarios = new MongoDB\Driver\Query([
            'email' => ['$in' => [$emailUsuario, $emailAmigo]]
        ]);

        $usuariosEncontrados = $conexion->executeQuery(
            "ArbolBD.usuarios",
            $queryUsuarios
        );

        if (count(iterator_to_array($usuariosEncontrados)) !== 2) {
            responder([
                'success' => false,
                'mensaje' => 'Uno de los usuarios no existe.'
            ]);
        }

        // Comprobar si ya existe la amistad
        $queryExistente = new MongoDB\Driver\Query([
            'email_usuario' => $emailUsuario,
            'email_amigo' => $emailAmigo
        ]);

        $existente = $conexion->executeQuery(
            "ArbolBD.amigos",
            $queryExistente
        );

        if (count(iterator_to_array($existente)) > 0) {
            responder([
                'success' => false,
                'mensaje' => 'Este usuario ya está en tus amigos.'
            ]);
        }

        // Guardar la relación
        $bulk = new MongoDB\Driver\BulkWrite();

        $bulk->insert([
            'email_usuario' => $emailUsuario,
            'email_amigo' => $emailAmigo,
            'fecha' => date('Y-m-d H:i:s')
        ]);

        $conexion->executeBulkWrite(
            "ArbolBD.amigos",
            $bulk
        );

        responder([
            'success' => true,
            'mensaje' => 'Amigo agregado correctamente.'
        ]);
    }

    // ==========================================
    // 3. LISTAR AMIGOS
    // ==========================================

    if ($accion === 'listar') {

        $emailUsuario = strtolower(trim($_GET['email_usuario'] ?? ''));

        if (empty($emailUsuario)) {
            responder([
                'success' => false,
                'mensaje' => 'Falta el correo del usuario.'
            ], 400);
        }

        // Buscar relaciones del usuario
        $queryAmigos = new MongoDB\Driver\Query([
            'email_usuario' => $emailUsuario
        ]);

        $cursorAmigos = $conexion->executeQuery(
            "ArbolBD.amigos",
            $queryAmigos
        );

        $correosAmigos = [];

        foreach ($cursorAmigos as $amistad) {
            $correosAmigos[] = $amistad->email_amigo;
        }

        if (empty($correosAmigos)) {
            responder([
                'success' => true,
                'amigos' => []
            ]);
        }

        // Obtener perfiles de los amigos
        $queryUsuarios = new MongoDB\Driver\Query(
            [
                'email' => ['$in' => $correosAmigos]
            ],
            [
                'projection' => [
                    'password' => 0
                ]
            ]
        );

        $cursorUsuarios = $conexion->executeQuery(
            "ArbolBD.usuarios",
            $queryUsuarios
        );

        $amigos = [];

        foreach ($cursorUsuarios as $usuario) {
            $amigos[] = [
                'nombre_usuario' => $usuario->nombre_usuario ?? '',
                'email' => $usuario->email ?? '',
                'foto_perfil' => $usuario->foto_perfil ?? null,
                'bio' => $usuario->bio ?? ''
            ];
        }

        responder([
            'success' => true,
            'amigos' => $amigos
        ]);
    }

    // ==========================================
    // ACCIÓN NO RECONOCIDA
    // ==========================================

    responder([
        'success' => false,
        'mensaje' => 'Acción no válida.'
    ], 400);

} catch (Throwable $e) {

    error_log("Error en api_amigos.php: " . $e->getMessage());

    responder([
        'success' => false,
        'mensaje' => 'Ocurrió un error en el servidor.'
    ], 500);
}
