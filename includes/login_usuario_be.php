<?php
require_once __DIR__ . '/session.php';
require_once __DIR__ . '/db.php';

$correo = $_POST['correo'] ?? '';
$password = $_POST['password'] ?? '';

$filter = ['email' => $correo];
$query = new MongoDB\Driver\Query($filter);
$cursor = $conexion->executeQuery("ArbolBD.usuarios", $query)->toArray();

if (count($cursor) > 0) {
    $fila = $cursor[0];
    $password_bd = $fila->password;

    if (password_verify($password, $password_bd)) {
        if ($fila->verificado == 1) {

            // Si tiene clave temporal asignada por un admin, obligar a cambiarla antes de entrar
            if (!empty($fila->debe_cambiar_password) && $fila->debe_cambiar_password == 1) {
                $_SESSION['cambio_pendiente_email'] = $correo;
                header("Location: ../pages/cambiar_password.php");
                exit;
            }

            // Sesión normal
            $_SESSION['usuario'] = $correo;
            $_SESSION['id_rol'] = $fila->id_rol ?? null;
            $_SESSION['nombre_usuario'] = $fila->nombre_usuario;

            $rol_actual = $fila->rol ?? $fila->id_rol ?? 'usuario';
            $_SESSION['rol'] = $rol_actual;

            if ($rol_actual === 'admin' || $rol_actual == 1) {
                header("Location: ../admin/index.php");
            } else {
                header("Location: ../index.php");
            }
            exit;
        } else {
            header("Location: ../pages/login.php?error=correo_no_verificado");
            exit;
        }
    } else {
        header("Location: ../pages/login.php?error=password_incorrecta");
        exit;
    }
} else {
    header("Location: ../pages/login.php?error=usuario_no_existe");
    exit;
}
?>