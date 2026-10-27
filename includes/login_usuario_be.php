<?php
session_start(); 
include 'db.php'; 

$correo = $_POST['correo'];
$password = $_POST['password'];

$filter = ['email' => $correo];
$query = new MongoDB\Driver\Query($filter);
$cursor = $conexion->executeQuery("ArbolBD.usuarios", $query)->toArray();

if (count($cursor) > 0) {
    $fila = $cursor[0];
    $password_bd = $fila->password; 

    if (password_verify($password, $password_bd)) {
        if ($fila->verificado == 1) {
            $_SESSION['usuario'] = $correo; 
            $_SESSION['id_rol'] = $fila->id_rol;
            $_SESSION['nombre_usuario'] = $fila->nombre_usuario;
            header("location: ../index.php"); 
            exit;
        } else {
            header("location: ../pages/login.php?error=correo_no_verificado");
            exit;
        }
    } else {
        header("location: ../pages/login.php?error=password_incorrecta");
        exit;
    }
} else {
    header("location: ../pages/login.php?error=usuario_no_existe");
    exit;
}
?>