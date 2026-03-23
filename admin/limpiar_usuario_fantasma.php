<?php
session_start();
if (!isset($_SESSION['usuario']) || $_SESSION['id_rol'] != 1) {
    header("Location: ../pages/login.php");
    exit();
}

include '../includes/db.php';

//  Ejecutamos el borrado
$query_borrar = "DELETE FROM usuarios 
                WHERE verificado = 0 
                AND id_rol = 3 
                AND fecha_registro < NOW() - INTERVAL 24 HOUR";

if (mysqli_query($conexion, $query_borrar)) {
    $borrados = mysqli_affected_rows($conexion);

    // 2. SOLO SI SE BORRÓ ALGUIEN, lo anotamos en la bitácora
    if ($borrados > 0) {
        $admin = $_SESSION['usuario'];
        $query_log = "INSERT INTO historial_limpieza (cantidad_eliminados, admin_que_limpio) 
                    VALUES ($borrados, '$admin')";
        mysqli_query($conexion, $query_log);
    }

    header("Location: gestion_usuarios.php?limpieza=$borrados");
    exit();
} else {
    echo "Error: " . mysqli_error($conexion);
}
mysqli_close($conexion);
?>