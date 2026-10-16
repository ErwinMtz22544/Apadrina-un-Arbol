<?php
// URI de conexión a tu MongoDB Atlas
$uri = "mongodb+srv://erwinmtz22544_db_user:vCqxwDKe5F6Xmrb0@clusterarbol.kx2umeq.mongodb.net/arbolbd?retryWrites=true&w=majority&appName=ClusterArbol";

try {
    // Se inicializa el gestor de conexión nativo de MongoDB
    $conexion = new MongoDB\Driver\Manager($uri);
    
} catch (MongoDB\Driver\Exception\Exception $e) {
    // Si la base de datos se cae, captura el error
    die("<div style='text-align: center; margin-top: 100px; font-family: Arial, sans-serif;'>
            <h2 style='color: #4a6733;'>Sistema temporalmente fuera de servicio</h2>
            <p>Nuestros servidores están plantando nuevos árboles. Error: " . $e->getMessage() . "</p>
        </div>");
}
?>