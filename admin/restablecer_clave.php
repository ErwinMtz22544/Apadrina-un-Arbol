<?php
session_start();
header('Content-Type: application/json; charset=utf-8');
include("../includes/db.php");

// Verificación de seguridad
if (!isset($_SESSION['rol']) || ($_SESSION['rol'] !== 'admin' && $_SESSION['rol'] != 1)) {
    http_response_code(403);
    echo json_encode(['success' => false, 'message' => 'No autorizado']);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Método no permitido']);
    exit();
}

$id_usuario = $_POST['id_usuario'] ?? '';

if (empty($id_usuario)) {
    echo json_encode(['success' => false, 'message' => 'Falta el ID del usuario']);
    exit();
}

try {
    $objectId = new MongoDB\BSON\ObjectId($id_usuario);

    // Genera una clave temporal legible y segura (ej. UTSC-8F3A2B)
    $claveTemporal = 'UTSC-' . strtoupper(bin2hex(random_bytes(3)));
    $passwordHash = password_hash($claveTemporal, PASSWORD_BCRYPT);

$bulk = new MongoDB\Driver\BulkWrite;
    $bulk->update(
        ['_id' => $objectId],
        ['$set' => [
            'password' => $passwordHash,
            'debe_cambiar_password' => 1
        ]]
    );

    $resultado = $conexion->executeBulkWrite('ArbolBD.usuarios', $bulk);

    if ($resultado->getMatchedCount() > 0) {
        echo json_encode([
            'success' => true,
            'clave' => $claveTemporal
        ]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Usuario no encontrado']);
    }
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Error técnico: ' . $e->getMessage()]);
}
?>