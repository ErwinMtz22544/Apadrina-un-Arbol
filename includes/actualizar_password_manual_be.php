<?php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  
    $email = mysqli_real_escape_string($conexion, $_POST['email']);
    $token = mysqli_real_escape_string($conexion, $_POST['token']);
    $nueva_pass = $_POST['nueva_pass'];

    // --- NUEVA VALIDACIÓN DE SEGURIDAD (BACKEND) ---
    // Verifica: Mínimo 8 caracteres, al menos un número y un carácter especial
    if (strlen($nueva_pass) < 8 || !preg_match('/[0-9]/', $nueva_pass) || !preg_match('/[^A-Za-z0-9]/', $nueva_pass)) {
        // Si falla, regresamos al formulario con el token y email para que no se pierda el enlace
        header("Location: ../pages/restablecer.php?email=$email&token=$token&error=pass_debil");
        exit();
    }
    // -----------------------------------------------

    // encriptacion de la nueva contra
    $pass_encriptada = password_hash($nueva_pass, PASSWORD_BCRYPT);

    // Actualizar la contraseña y LIMPIAR el token
    $query = "UPDATE usuarios SET password = '$pass_encriptada', token = NULL 
              WHERE email = '$email' AND token = '$token'";
    
    $ejecutar = mysqli_query($conexion, $query);

    // 4. Verificar si la actualización fue exitosa
    if ($ejecutar && mysqli_affected_rows($conexion) > 0) {
        // Redirige al login con éxito, cuando eso pasa se muestran las alertas 
        header("Location: ../pages/login.php?res=pass_actualizada");
    } else {
        // Si algo falló (por ejemplo, el token ya no existía)
        header("Location: ../pages/login.php?error=link_invalido");
    }
} else {
    header("Location: ../pages/login.php");
}

mysqli_close($conexion);
?>