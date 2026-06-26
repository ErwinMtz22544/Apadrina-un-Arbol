<?php
session_start();
include("db.php");

if (isset($_GET['id']) && $_SESSION['id_rol'] == 1) {
    $id = intval($_GET['id']);
    
    // Cambiamos disponible a 1 para que vuelva a salir en el catálogo
    $stmt = $conexion->prepare("UPDATE arboles SET disponible = 1 WHERE id_arbol = ?");
    $stmt->bind_param("i", $id);
    
    if ($stmt->execute()) {
        header("Location: ../pages/catalogo.php?status=reactivado");
    }
    $stmt->close();
}
?>