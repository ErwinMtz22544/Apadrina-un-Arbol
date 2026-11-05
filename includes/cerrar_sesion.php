<?php
// Archivo: includes/cerrar_sesion.php

//session_start(); // Inicia la sesión para saber cuál destruir
//session_destroy(); // Elimina todos los datos de la sesión actual

// Redirige al usuario al index o al login
//header("location: ../index.php"); 
//exit;

require_once __DIR__ . '/session.php';

// Eliminar los datos de la sesión
$_SESSION = [];

// Destruir la sesión
session_destroy();

// Regresar al inicio
header("Location: /index.php");
exit;
?>