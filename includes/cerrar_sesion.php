<?php
// Archivo: includes/cerrar_sesion.php

session_start(); // Inicia la sesión para saber cuál destruir
session_destroy(); // Elimina todos los datos de la sesión actual

// Redirige al usuario al index o al login
header("location: ../index.php"); 
exit;
?>