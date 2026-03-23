<?php
session_start();

// Validamos que exista una sesión y que el id_rol sea 1 (Administrador)
if (!isset($_SESSION['id_rol']) || $_SESSION['id_rol'] != 1) {
    // Si no es admin, lo mandamos al inicio con un error
    header("Location: ../pages/login.php?error=acceso_restringido");
    exit();
}
?>