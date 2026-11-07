
<?php
ob_start();

header('Content-Type: application/json; charset=utf-8');

// Responder únicamente JSON
function responder($datos, $codigo = 200) {
    if (ob_get_level() > 0) {
        ob_clean();
    }

    http_response_code($codigo);
    echo json_encode($datos, JSON_UNESCAPED_UNICODE);
    exit;
}

// Comprobar si dos usuarios ya son amigos
function existeAmistad($conexion, $email1, $email2) {

    $query = new MongoDB\Driver\Query([
        '$or' => [
            [
                'email_usuario' => $email1,
                'email_amigo' => $email2
            ],
            [
                'email_usuario' => $email2,
                'email_amigo' => $email1
            ]
        ]
    ]);

    $cursor = $conexion->executeQuery(
        "ArbolBD.amigos",
        $query
    );

    return count(iterator_to_array($cursor)) > 0;
}

// Crear una relación de amistad si todavía no existe
function crearAmistad($conexion, $email1, $email2) {

    if (existeAmistad($conexion, $email1, $email2)) {
        return;
    }

    $bulk = new MongoDB\Driver\BulkWrite();

    $bulk->insert([
        'email_usuario' => $email1,
        'email_amigo' => $email2,
        'fecha' => date('Y-m-d H:i:s')
    ]);

    $conexion->executeBulkWrite(
        "ArbolBD.amigos",
        $bulk
    );
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
            'email' => [
                '$ne' => $emailUsuario
            ],
            '$or' => [
                ['nombre_usuario' => $regex],
                ['email' => $regex]
            ]
        ];

        $query = new MongoDB\Driver\Query(
            $filtro,
            [
                'projection' => [
                    'password' => 0
                ],
                'limit' => 20
            ]
        );

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
    // 2. ENVIAR SOLICITUD DE AMISTAD
    // ==========================================

    if ($accion === 'solicitar' || $accion === 'agregar') {

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
                'mensaje' => 'No puedes enviarte una solicitud.'
            ], 400);
        }

        // Comprobar que ambos usuarios existan
        $queryUsuarios = new MongoDB\Driver\Query([
            'email' => [
                '$in' => [$emailUsuario, $emailAmigo]
            ]
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

        // Comprobar si ya son amigos
        if (existeAmistad($conexion, $emailUsuario, $emailAmigo)) {
            responder([
                'success' => false,
                'mensaje' => 'Este usuario ya es tu amigo.'
            ]);
        }

        // Comprobar solicitudes en ambas direcciones
        $querySolicitud = new MongoDB\Driver\Query([
            '$or' => [
                [
                    'email_remitente' => $emailUsuario,
                    'email_destinatario' => $emailAmigo
                ],
                [
                    'email_remitente' => $emailAmigo,
                    'email_destinatario' => $emailUsuario
                ]
            ]
        ]);

        $solicitudes = $conexion->executeQuery(
            "ArbolBD.solicitudes_amigos",
            $querySolicitud
        );

        $solicitudesExistentes = iterator_to_array($solicitudes);

        if (!empty($solicitudesExistentes)) {

            $solicitud = $solicitudesExistentes[0];

            if (
                ($solicitud->email_remitente ?? '') === $emailAmigo &&
                ($solicitud->email_destinatario ?? '') === $emailUsuario
            ) {
                responder([
                    'success' => false,
                    'mensaje' => 'Esta persona ya te envió una solicitud. Revisa tus solicitudes recibidas.'
                ]);
            }

            responder([
                'success' => false,
                'mensaje' => 'Ya enviaste una solicitud a esta persona.'
            ]);
        }

        // Guardar solicitud pendiente
        $bulk = new MongoDB\Driver\BulkWrite();

        $bulk->insert([
            'email_remitente' => $emailUsuario,
            'email_destinatario' => $emailAmigo,
            'estado' => 'pendiente',
            'fecha' => date('Y-m-d H:i:s')
        ]);

        $conexion->executeBulkWrite(
            "ArbolBD.solicitudes_amigos",
            $bulk
        );

        responder([
            'success' => true,
            'mensaje' => 'Solicitud de amistad enviada.'
        ]);
    }

    // ==========================================
    // 3. LISTAR SOLICITUDES RECIBIDAS
    // ==========================================

    if ($accion === 'recibidas') {

        $emailUsuario = strtolower(trim($_GET['email_usuario'] ?? ''));

        if (empty($emailUsuario)) {
            responder([
                'success' => false,
                'mensaje' => 'Falta el correo del usuario.'
            ], 400);
        }

        $query = new MongoDB\Driver\Query([
            'email_destinatario' => $emailUsuario,
            'estado' => 'pendiente'
        ]);

        $cursor = $conexion->executeQuery(
            "ArbolBD.solicitudes_amigos",
            $query
        );

        $solicitudes = [];

        foreach ($cursor as $solicitud) {

            $emailRemitente = $solicitud->email_remitente ?? '';

            $queryUsuario = new MongoDB\Driver\Query(
                [
                    'email' => $emailRemitente
                ],
                [
                    'projection' => [
                        'password' => 0
                    ],
                    'limit' => 1
                ]
            );

            $usuarios = $conexion->executeQuery(
                "ArbolBD.usuarios",
                $queryUsuario
            );

            foreach ($usuarios as $usuario) {
                $solicitudes[] = [
                    'email' => $usuario->email ?? '',
                    'nombre_usuario' => $usuario->nombre_usuario ?? '',
                    'foto_perfil' => $usuario->foto_perfil ?? null,
                    'bio' => $usuario->bio ?? '',
                    'fecha' => $solicitud->fecha ?? ''
                ];
            }
        }

        responder([
            'success' => true,
            'solicitudes' => $solicitudes
        ]);
    }

    // ==========================================
    // 4. ACEPTAR O RECHAZAR SOLICITUD
    // ==========================================

    if ($accion === 'responder') {

        $emailUsuario = strtolower(trim($datos['email_usuario'] ?? ''));
        $emailSolicitante = strtolower(trim($datos['email_solicitante'] ?? ''));
        $respuesta = strtolower(trim($datos['respuesta'] ?? ''));

        if (
            empty($emailUsuario) ||
            empty($emailSolicitante) ||
            !in_array($respuesta, ['aceptar', 'rechazar'])
        ) {
            responder([
                'success' => false,
                'mensaje' => 'Los datos de la solicitud no son válidos.'
            ], 400);
        }

        // Buscar solicitud pendiente dirigida al usuario actual
        $query = new MongoDB\Driver\Query([
            'email_remitente' => $emailSolicitante,
            'email_destinatario' => $emailUsuario,
            'estado' => 'pendiente'
        ]);

        $cursor = $conexion->executeQuery(
            "ArbolBD.solicitudes_amigos",
            $query
        );

        $solicitudes = iterator_to_array($cursor);

        if (empty($solicitudes)) {
            responder([
                'success' => false,
                'mensaje' => 'La solicitud ya no existe o fue respondida.'
            ]);
        }

        if ($respuesta === 'aceptar') {

            // Crear amistad para ambos usuarios
            crearAmistad(
                $conexion,
                $emailUsuario,
                $emailSolicitante
            );

            crearAmistad(
                $conexion,
                $emailSolicitante,
                $emailUsuario
            );
        }

        // Eliminar la solicitud después de responder
        $bulk = new MongoDB\Driver\BulkWrite();

        $bulk->delete(
            [
                'email_remitente' => $emailSolicitante,
                'email_destinatario' => $emailUsuario,
                'estado' => 'pendiente'
            ],
            [
                'limit' => 1
            ]
        );

        $conexion->executeBulkWrite(
            "ArbolBD.solicitudes_amigos",
            $bulk
        );

        responder([
            'success' => true,
            'mensaje' => $respuesta === 'aceptar'
                ? 'Solicitud aceptada. Ahora son amigos.'
                : 'Solicitud rechazada.'
        ]);
    }

    // ==========================================
    // 5. LISTAR AMIGOS ACEPTADOS
    // ==========================================

    if ($accion === 'listar') {

        $emailUsuario = strtolower(trim($_GET['email_usuario'] ?? ''));

        if (empty($emailUsuario)) {
            responder([
                'success' => false,
                'mensaje' => 'Falta el correo del usuario.'
            ], 400);
        }

        // Buscar amistades en ambas direcciones
        $queryAmigos = new MongoDB\Driver\Query([
            '$or' => [
                [
                    'email_usuario' => $emailUsuario
                ],
                [
                    'email_amigo' => $emailUsuario
                ]
            ]
        ]);

        $cursorAmigos = $conexion->executeQuery(
            "ArbolBD.amigos",
            $queryAmigos
        );

        $correosAmigos = [];

        foreach ($cursorAmigos as $amistad) {

            $correo = ($amistad->email_usuario === $emailUsuario)
                ? ($amistad->email_amigo ?? '')
                : ($amistad->email_usuario ?? '');

            if (!empty($correo)) {
                $correosAmigos[] = $correo;
            }
        }

        $correosAmigos = array_unique($correosAmigos);

        if (empty($correosAmigos)) {
            responder([
                'success' => true,
                'amigos' => []
            ]);
        }

        // Obtener perfiles
        $queryUsuarios = new MongoDB\Driver\Query(
            [
                'email' => [
                    '$in' => array_values($correosAmigos)
                ]
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
