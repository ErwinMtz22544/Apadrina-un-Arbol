<?php
// Archivo: includes/verificar.php
include 'db.php';

// Verificamos que los datos lleguen por la URL
if (isset($_GET['email']) && isset($_GET['token'])) {
    
    $email = mysqli_real_escape_string($conexion, $_GET['email']);
    $token = mysqli_real_escape_string($conexion, $_GET['token']);

    // Buscamos si existe un usuario con ese email y token que aún falte verificar
    $consulta = "SELECT * FROM usuarios WHERE email = '$email' AND token = '$token' AND verificado = 0";
    $resultado = mysqli_query($conexion, $consulta);

    if (mysqli_num_rows($resultado) > 0) {
        
    
        $actualizar = "UPDATE usuarios SET verificado = 1 WHERE email = '$email'";
        
        if (mysqli_query($conexion, $actualizar)) {
            // Éxito
            header("Location: ../pages/login.php?status=cuenta_activada");
            exit();
        } else {
            // Error de base de datos
            header("Location: ../pages/login.php?error=error_db");
            exit();
        }

    } else {
        // El token no coincide, el correo ya fue verificado o el link expiró
        header("Location: ../pages/login.php?error=link_invalido");
        exit();
    }

} else {
    // Si alguien entra a este archivo sin darle clic al correo
    header("Location: ../pages/login.php");
    exit();
}

mysqli_close($conexion);
?>