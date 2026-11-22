<?php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email'] ?? '');
    $token = trim($_POST['token'] ?? '');
    $nueva_pass = $_POST['nueva_pass'] ?? '';
    $confirmar_pass = $_POST['confirmar_pass'] ?? '';

    if (empty($email) || empty($token)) {
        header("Location: ../pages/login.php?error=link_invalido");
        exit();
    }

    $volver = "../pages/restablecer.php?token=" . urlencode($token) . "&email=" . urlencode($email);

    // Validar requisitos de seguridad
    $cumpleSeguridad =
        strlen($nueva_pass) >= 8 &&
        preg_match('/[A-Z]/', $nueva_pass) &&
        preg_match('/[a-z]/', $nueva_pass) &&
        preg_match('/[0-9]/', $nueva_pass) &&
        preg_match('/[^A-Za-z0-9\s]/', $nueva_pass);

    if (!$cumpleSeguridad) {
        header("Location: " . $volver . "&error=requisitos");
        exit();
    }

    if ($nueva_pass !== $confirmar_pass) {
        header("Location: " . $volver . "&error=no_coinciden");
        exit();
    }

    $pass_encriptada = password_hash($nueva_pass, PASSWORD_BCRYPT);

    // Actualizar contraseña, limpiar token y desactivar bandera temporal
    $bulk = new MongoDB\Driver\BulkWrite;
    $bulk->update(
        ['email' => $email, 'token' => $token],
        ['$set' => [
            'password' => $pass_encriptada,
            'token' => null,
            'debe_cambiar_password' => 0
        ]]
    );

    try {
        $resultado = $conexion->executeBulkWrite('ArbolBD.usuarios', $bulk);

        if ($resultado->getModifiedCount() > 0) {
            header("Location: ../pages/login.php?res=pass_actualizada");
        } else {
            header("Location: ../pages/login.php?error=link_invalido");
        }
    } catch (Exception $e) {
        header("Location: ../pages/login.php?error=link_invalido");
    }
} else {
    header("Location: ../pages/login.php");
}
?>