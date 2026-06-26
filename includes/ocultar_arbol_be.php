<?php
session_start();
include("db.php");

// SEGURIDAD: Solo el admin puede ocultar
if (!isset($_SESSION['id_rol']) || $_SESSION['id_rol'] != 1) {
    header("Location: ../pages/catalogo.php");
    exit();
}

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    
    // CAMBIO CLAVE: No borramos, solo ponemos 'visible' en 0
    $stmt = $conexion->prepare("UPDATE arboles SET disponible = 0 WHERE id_arbol = ?");
    $stmt->bind_param("i", $id);
    
    if ($stmt->execute()) {
        // Redirigimos con un mensaje de "ocultado"
        header("Location: ../pages/catalogo.php?res=ocultado");
    } else {
        header("Location: ../pages/catalogo.php?res=error");
    }
    $stmt->close();
}
?>