<?php
include 'verificar_admin.php';
include '../includes/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_a_borrar = $_POST['id_usuario'];
    $id_admin_actual = $_SESSION['usuario']; 

    // evita que el admin se borre a sí mismo
    if ($id_a_borrar == $id_admin_actual) {
        header("Location: gestion_usuarios.php?res=error_autoborrado");
        exit();
    }

    
    $query = "DELETE FROM usuarios WHERE id = '$id_a_borrar'";
    $ejecutar = mysqli_query($conexion, $query);

    if ($ejecutar) {
        header("Location: gestion_usuarios.php?res=borrado_ok");
    } else {
        header("Location: gestion_usuarios.php?res=error");
    }
}
?>