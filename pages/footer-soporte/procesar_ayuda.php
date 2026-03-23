<?php
// 1. Incluimos la conexión (subiendo dos niveles para llegar a la raíz)
include("../../includes/db.php");

// 2. Verificamos que los datos lleguen por el método POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // 3. Limpiamos los datos para evitar Inyección SQL
    $nombre  = mysqli_real_escape_string($conexion, $_POST['nombre']);
    $email   = mysqli_real_escape_string($conexion, $_POST['email']);
    $asunto  = mysqli_real_escape_string($conexion, $_POST['asunto']);
    $mensaje = mysqli_real_escape_string($conexion, $_POST['mensaje']);

    // 4. Preparamos la consulta (Esto es lo que faltaba en el error)
    $query = "INSERT INTO mensajes_soporte (nombre, email, asunto, mensaje, leido) 
            VALUES ('$nombre', '$email', '$asunto', '$mensaje', 0)";

    // 5. Ejecutamos la consulta y enviamos la respuesta
    if (mysqli_query($conexion, $query)) {
        // Éxito: Redirigimos de vuelta con un parámetro para el SweetAlert
        header("Location: ayuda.php?status=enviado");
        exit();
    } else {
        // Error de base de datos
        echo "Error técnico: " . mysqli_error($conexion);
    }

} else {
    // Si intentan entrar directo al archivo sin formulario
    header("Location: ayuda.php");
    exit();
}

mysqli_close($conexion);
?>