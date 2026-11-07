<?php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $token = $_POST['token'];
    $nueva_pass = $_POST['nueva_pass'];

    // encriptacion de la nueva contra
    $pass_encriptada = password_hash($nueva_pass, PASSWORD_BCRYPT);

    // Actualizar la contraseña y LIMPIAR el token
    $bulk = new MongoDB\Driver\BulkWrite;
    $bulk->update(
        ['email' => $email, 'token' => $token],
        ['$set' => ['password' => $pass_encriptada, 'token' => null]]
    );

    try {
        $resultado = $conexion->executeBulkWrite('ArbolBD.usuarios', $bulk);
        
        // 4. Verificar si la actualización fue exitosa
        if ($resultado->getModifiedCount() > 0) {
            // Redirige al login con éxito, cuando eso pasa se muestran las alertas 
            header("Location: ../pages/login.php?res=pass_actualizada");
        } else {
            // Si algo falló (por ejemplo, el token ya no existía)
            header("Location: ../pages/login.php?error=link_invalido");
        }
    } catch (Exception $e) {
        header("Location: ../pages/login.php?error=link_invalido");
    }
} else {
    header("Location: ../pages/login.php");
}
?>