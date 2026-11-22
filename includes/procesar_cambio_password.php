<?php
require_once __DIR__ . '/session.php';
require_once __DIR__ . '/db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_SESSION['cambio_pendiente_email'])) {
    header("Location: ../pages/login.php");
    exit;
}

$correo = $_SESSION['cambio_pendiente_email'];
$nueva = $_POST['nueva_password'] ?? '';
$confirmar = $_POST['confirmar_password'] ?? '';

// Validar requisitos de seguridad en el servidor
$cumpleSeguridad =
    strlen($nueva) >= 8 &&
    preg_match('/[A-Z]/', $nueva) &&
    preg_match('/[a-z]/', $nueva) &&
    preg_match('/[0-9]/', $nueva) &&
    preg_match('/[^A-Za-z0-9\s]/', $nueva);

if (!$cumpleSeguridad) {
    header("Location: ../pages/cambiar_password.php?error=requisitos");
    exit;
}

if ($nueva !== $confirmar) {
    header("Location: ../pages/cambiar_password.php?error=no_coinciden");
    exit;
}

$query = new MongoDB\Driver\Query(['email' => $correo]);
$cursor = $conexion->executeQuery("ArbolBD.usuarios", $query)->toArray();

if (empty($cursor)) {
    unset($_SESSION['cambio_pendiente_email']);
    header("Location: ../pages/login.php");
    exit;
}

// Evitar que conserve exactamente la misma clave temporal
if (password_verify($nueva, $cursor[0]->password)) {
    header("Location: ../pages/cambiar_password.php?error=misma_clave");
    exit;
}

$nuevoHash = password_hash($nueva, PASSWORD_BCRYPT);

$bulk = new MongoDB\Driver\BulkWrite;
$bulk->update(
    ['email' => $correo],
    ['$set' => [
        'password' => $nuevoHash,
        'debe_cambiar_password' => 0
    ]]
);
$conexion->executeBulkWrite('ArbolBD.usuarios', $bulk);

unset($_SESSION['cambio_pendiente_email']);
header("Location: ../pages/login.php?cambio=ok");
exit;
?>