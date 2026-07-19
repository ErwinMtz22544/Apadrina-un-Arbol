<?php
<<<<<<< Updated upstream
// Archivo: config/db.php
$conexion = mysqli_connect("localhost", "root", "", "arbolbd");
=======
try {
    // Intenta realizar la conexión normal
    $conexion = mysqli_connect('db', 'root', '123456', 'arbolbd');
    
    // Obliga a la conexión a usar UTF-8
    mysqli_set_charset($conexion, "utf8mb4");
>>>>>>> Stashed changes

} catch (mysqli_sql_exception $e) {
    // Si la base de datos se cae, captura el error y muestra este mensaje seguro
    die("<div style='text-align: center; margin-top: 100px; font-family: Arial, sans-serif;'>
            <h2 style='color: #4a6733;'>Sistema temporalmente fuera de servicio</h2>
            <p>Nuestros servidores están plantando nuevos árboles. Por favor, vuelve a intentarlo en unos minutos.</p>
        </div>");
}
?>