
<?php
ob_start();

header('Content-Type: application/json; charset=utf-8');

// ==========================================
// RESPUESTA JSON
// ==========================================

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
    // BUSCAR USUARIO POR CORREO
    // ==========================================

    function buscarUsuario($conexion, $email) {

        $query = new MongoDB\Driver\Query(
            ['email' => $email],
            ['limit' => 1]
        );

        $cursor = $conexion->executeQuery(
            "ArbolBD.usuarios",
            $query
        );

        $usuarios = iterator_to_array($cursor);

        return $usuarios[0] ?? null;
    }

    // ==========================================
    // 1. CREAR APADRINAMIENTO
    // ==========================================

    if ($accion === 'crear') {

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            responder([
                'success' => false,
                'mensaje' => 'Método no permitido.'
            ], 405);
        }

        $emailUsuario = trim($datos['email_usuario'] ?? '');
        $idArbol = (int)($datos['id_arbol'] ?? 0);
        $nombreArbol = trim($datos['nombre_personalizado'] ?? '');
        $tipoAdopcion = trim($datos['tipo_adopcion'] ?? 'Individual');
        $monto = (float)($datos['monto'] ?? 0);
        $metodoPago = trim($datos['metodo_pago'] ?? 'Tarjeta');

        // Validar datos obligatorios
        if (
            empty($emailUsuario) ||
            $idArbol <= 0 ||
            empty($nombreArbol) ||
            $monto <= 0
        ) {
            responder([
                'success' => false,
                'mensaje' => 'Faltan datos para realizar el apadrinamiento.'
            ], 400);
        }

        // Validar tipo de adopción
        $tiposPermitidos = [
            'Individual',
            'Amigos',
            'Familia'
        ];

        if (!in_array($tipoAdopcion, $tiposPermitidos, true)) {
            responder([
                'success' => false,
                'mensaje' => 'El tipo de apadrinamiento no es válido.'
            ], 400);
        }

        // Validar método de pago simulado
        $metodosPermitidos = [
            'Tarjeta',
            'Oxxo',
            'PayPal'
        ];

        if (!in_array($metodoPago, $metodosPermitidos, true)) {
            responder([
                'success' => false,
                'mensaje' => 'El método de pago no es válido.'
            ], 400);
        }

        // Buscar usuario
        $usuario = buscarUsuario($conexion, $emailUsuario);

        if (!$usuario || !isset($usuario->id)) {
            responder([
                'success' => false,
                'mensaje' => 'No se encontró el usuario o no tiene un ID registrado.'
            ], 404);
        }

        $idUsuario = $usuario->id;

        // Comprobar que el árbol exista
        $queryArbol = new MongoDB\Driver\Query([
            'id_arbol' => $idArbol
        ]);

        $arboles = $conexion->executeQuery(
            "ArbolBD.arboles",
            $queryArbol
        );

        if (count(iterator_to_array($arboles)) === 0) {
            responder([
                'success' => false,
                'mensaje' => 'El árbol seleccionado no existe.'
            ], 404);
        }

        // Crear registro
        $documento = [
            'id_apadrinamiento' => (int)(time() . random_int(100, 999)),
            'id_usuario' => $idUsuario,
            'id_arbol' => $idArbol,
            'nombre_arbol' => $nombreArbol,
            'tipo_adopcion' => $tipoAdopcion,
            'fecha_apadrinamiento' => date('c'),
            'monto' => $monto,
            'metodo_pago' => $metodoPago
        ];

        $bulk = new MongoDB\Driver\BulkWrite();

        $bulk->insert($documento);

        $conexion->executeBulkWrite(
            'ArbolBD.apadrinamientos',
            $bulk
        );

        responder([
            'success' => true,
            'mensaje' => '¡Tu árbol ha sido apadrinado!',
            'apadrinamiento' => $documento
        ]);
    }

    // ==========================================
    // 2. LISTAR ÁRBOLES APADRINADOS
    // ==========================================

    if ($accion === 'listar') {

        $emailUsuario = trim($_GET['email_usuario'] ?? '');

        if (empty($emailUsuario)) {
            responder([
                'success' => false,
                'mensaje' => 'Falta el correo del usuario.'
            ], 400);
        }

        // Buscar usuario
        $usuario = buscarUsuario($conexion, $emailUsuario);

        if (!$usuario || !isset($usuario->id)) {
            responder([
                'success' => false,
                'mensaje' => 'No se encontró el usuario.'
            ], 404);
        }

        $idUsuario = $usuario->id;

        // Buscar sus apadrinamientos
        $queryAdopciones = new MongoDB\Driver\Query(
            [
                'id_usuario' => $idUsuario
            ],
            [
                'sort' => [
                    'fecha_apadrinamiento' => -1
                ]
            ]
        );

        $cursorAdopciones = $conexion->executeQuery(
            'ArbolBD.apadrinamientos',
            $queryAdopciones
        );

        $adopciones = [];

        foreach ($cursorAdopciones as $fila) {

            // Buscar información del árbol
            $queryArbol = new MongoDB\Driver\Query([
                'id_arbol' => (int)$fila->id_arbol
            ]);

            $cursorArbol = $conexion->executeQuery(
                'ArbolBD.arboles',
                $queryArbol
            );

            $arboles = iterator_to_array($cursorArbol);

            $arbol = $arboles[0] ?? null;

            if (!$arbol) {
                continue;
            }

            $adopciones[] = [
                'id_apadrinamiento' => $fila->id_apadrinamiento ?? '',
                'id_usuario' => $fila->id_usuario ?? '',
                'id_arbol' => $fila->id_arbol ?? 0,
                'nombre_arbol' => $fila->nombre_arbol ?? '',
                'tipo_adopcion' => $fila->tipo_adopcion ?? '',
                'fecha_apadrinamiento' => $fila->fecha_apadrinamiento ?? '',
                'monto' => $fila->monto ?? 0,
                'metodo_pago' => $fila->metodo_pago ?? '',
                'arbol' => [
                    'nombre_comun' => $arbol->nombre_comun ?? '',
                    'nombre_cientifico' => $arbol->nombre_cientifico ?? '',
                    'imagen' => $arbol->imagen ?? '',
                    'descripcion' => $arbol->descripcion ?? '',
                    'altura' => $arbol->altura ?? '',
                    'epoca' => $arbol->epoca ?? ''
                ]
            ];
        }

        responder([
            'success' => true,
            'total' => count($adopciones),
            'apadrinamientos' => $adopciones
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

    error_log(
        'Error en api_apadrinamientos.php: ' . $e->getMessage()
    );

    responder([
        'success' => false,
        'mensaje' => 'Ocurrió un error en el servidor.'
    ], 500);
}
