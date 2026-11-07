
<?php
header('Content-Type: application/json; charset=utf-8');

include("../includes/db.php");

try {

    // Obtenemos la acción solicitada
    $accion = $_GET['accion'] ?? '';

    // Leemos los datos enviados desde Flutter
    $datos = json_decode(file_get_contents("php://input"), true) ?? [];

  
    // 1. BUSCAR USUARIOS
  
    if ($accion === 'buscar') {

        $busqueda = trim($_GET['q'] ?? '');
        $emailUsuario = strtolower(trim($_GET['email_usuario'] ?? ''));

        if (strlen($busqueda) < 2) {
            echo json_encode([
                'success' => true,
                'usuarios' => []
            ]);
            exit;
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
        $cursor = $conexion->executeQuery("ArbolBD.usuarios", $query);

        $usuarios = [];

        foreach ($cursor as $usuario) {
            $usuarios[] = [
                'nombre_usuario' => $usuario->nombre_usuario ?? '',
                'email' => $usuario->email ?? '',
                'foto_perfil' => $usuario->foto_perfil ?? null,
                'bio' => $usuario->bio ?? ''
            ];
        }

        echo json_encode([
            'success' => true,
            'usuarios' => $usuarios
        ]);
        exit;
    }

    // ------------------------------------------
    // 2. AGREGAR AMIGO
    // ------------------------------------------
    if ($accion === 'agregar') {

        $emailUsuario = strtolower(trim($datos['email_usuario'] ?? ''));
        $emailAmigo = strtolower(trim($datos['email_amigo'] ?? ''));

        if (empty($emailUsuario) || empty($emailAmigo)) {
            http_response_code(400);
            echo json_encode([
                'success' => false,
                'mensaje' => 'Faltan datos del usuario.'
            ]);
            exit;
        }

        if ($emailUsuario === $emailAmigo) {
            echo json_encode([
                'success' => false,
                'mensaje' => 'No puedes agregarte a ti mismo.'
            ]);
            exit;
        }

        // Comprobamos que ambos usuarios existan
        $queryUsuarios = new MongoDB\Driver\Query([
            'email' => ['$in' => [$emailUsuario, $emailAmigo]]
        ]);

        $usuariosEncontrados = $conexion->executeQuery(
            "ArbolBD.usuarios",
            $queryUsuarios
        );

        if (count(iterator_to_array($usuariosEncontrados)) !== 2) {
            echo json_encode([
                'success' => false,
                'mensaje' => 'Uno de los usuarios no existe.'
            ]);
            exit;
        }

        // Comprobamos si ya existe la amistad
        $queryExistente = new MongoDB\Driver\Query([
            'email_usuario' => $emailUsuario,
            'email_amigo' => $emailAmigo
        ]);

        $existente = $conexion->executeQuery(
            "ArbolBD.amigos",
            $queryExistente
        );

        if (count(iterator_to_array($existente)) > 0) {
            echo json_encode([
                'success' => false,
                'mensaje' => 'Este usuario ya está en tus amigos.'
            ]);
            exit;
        }

        // Guardamos la relación
        $bulk = new MongoDB\Driver\BulkWrite();

        $bulk->insert([
            'email_usuario' => $emailUsuario,
            'email_amigo' => $emailAmigo,
            'fecha' => date('Y-m-d H:i:s')
        ]);

        $conexion->executeBulkWrite("ArbolBD.amigos", $bulk);

        echo json_encode([
            'success' => true,
            'mensaje' => 'Amigo agregado correctamente.'
        ]);
        exit;
    }

    // ------------------------------------------
    // 3. LISTAR AMIGOS
    // ------------------------------------------
    if ($accion === 'listar') {

        $emailUsuario = strtolower(trim($_GET['email_usuario'] ?? ''));

        if (empty($emailUsuario)) {
            http_response_code(400);
            echo json_encode([
                'success' => false,
                'mensaje' => 'Falta el correo del usuario.'
            ]);
            exit;
        }

        // Buscamos las relaciones del usuario
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
            echo json_encode([
                'success' => true,
                'amigos' => []
            ]);
            exit;
        }

        // Obtenemos los perfiles de los amigos
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

        echo json_encode([
            'success' => true,
            'amigos' => $amigos
        ]);
        exit;
    }

    // Acción no reconocida
    http_response_code(400);

    echo json_encode([
        'success' => false,
        'mensaje' => 'Acción no válida.'
    ]);

} catch (Throwable $e) {

    http_response_code(500);

    echo json_encode([
        'success' => false,
        'mensaje' => 'Ocurrió un error en el servidor.'
    ]);

    error_log("Error en api_amigos.php: " . $e->getMessage());
}
?>
