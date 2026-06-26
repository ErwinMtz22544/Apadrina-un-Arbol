<?php
// Archivo: includes/login_usuario_be.php

session_start(); 

include 'db.php'; 

$correo = $_POST['correo'];
$password = $_POST['password'];

// 1. Validar si el correo existe
$validar_login = mysqli_query($conexion, "SELECT * FROM usuarios WHERE email='$correo'");

if (mysqli_num_rows($validar_login) > 0) {
    // Si el correo existe, traemos los datos
    $fila = mysqli_fetch_assoc($validar_login);
    $password_bd = $fila['password']; 

    // Verificar la contraseña con el hash
    if (password_verify($password, $password_bd)) {
        
        //  VALIDACIÓN DE CORREO REAL 
        if ($fila['verificado'] == 1) {
            // ¡LOGIN EXITOSO!
            $_SESSION['usuario'] = $correo; 
            $_SESSION['id_usuario'] = $fila['id'];
            // Esto nos permitirá saber si es Admin (1), Editor (2) o Usuario (3)
            $_SESSION['id_rol'] = $fila['id_rol'];
            $_SESSION['nombre_usuario'] = $fila['nombre_usuario'];
            header("location: ../index.php"); 
            exit;
        } else {
            // Usuario existe y contraseña es correcta, pero NO ha verificado su correo
            header("location: ../pages/login.php?error=correo_no_verificado");
            exit;
        }
        // -----------------------------------------------------

    } else {
        // Contraseña incorrecta
        header("location: ../pages/login.php?error=password_incorrecta");
        exit;
    }
} else {
    // Correo no encontrado
    header("location: ../pages/login.php?error=usuario_no_existe");
    exit;
}
?>