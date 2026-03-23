<?php
include 'verificar_admin.php'; // SEGURIDAD: Solo el admin entra aquí
include '../includes/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_usuario = $_POST['id_usuario'];
    $nuevo_rol = $_POST['nuevo_rol'];

   
    $query = "UPDATE usuarios SET id_rol = '$nuevo_rol' WHERE id = '$id_usuario'";
    $ejecutar = mysqli_query($conexion, $query);

    if ($ejecutar) {
        header("Location: gestion_usuarios.php?res=rol_actualizado");
    } else {
        echo "Error al actualizar: " . mysqli_error($conexion);
    }
    
if ($ejecutar) {
    header("Location: gestion_usuarios.php?res=ok"); 
} else {
    header("Location: gestion_usuarios.php?res=error");
}
exit();
}
?>