<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
if (session_status() === PHP_SESSION_NONE) { session_start(); }

include("db.php");

if (!isset($_SESSION['usuario']) || $_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../pages/foro.php");
    exit();
}

$email_session = $_SESSION['usuario'];
$mensaje = trim($_POST['mensaje']);
$urlImagenFinal = null;


$queryUser = new MongoDB\Driver\Query(['email' => $email_session]);
$cursorUser = $conexion->executeQuery("ArbolBD.usuarios", $queryUser)->toArray();
$nombre_usuario = !empty($cursorUser) ? ($cursorUser[0]->nombre ?? $cursorUser[0]->nombre_usuario) : ($_SESSION['nombre_usuario'] ?? "Usuario Anónimo");

// Procesamiento de imagen con Cloudinary
if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
    $fileTmpPath = $_FILES['foto']['tmp_name'];
    
    // --- CREDENCIALES (Automáticas para Render) ---
    $cloudName = getenv('CLOUDINARY_CLOUD_NAME');
    $apiKey    = getenv('CLOUDINARY_API_KEY');
    $apiSecret = getenv('CLOUDINARY_API_SECRET');

    /* 
      =================================================================
      NOTA PARA LOCAL: Descomenta las 3 líneas de abajo para pruebas rápidas.
      *** BORRAR O COMENTAR ESTO ANTES DE SUBIR A PRODUCCIÓN/RENDER ***
      =================================================================
    */
    // $cloudName = 'k';
    // $apiKey    = '6';     
    // $apiSecret = 'X';  
    /* =============================================================== */

    $timestamp = time();
    $stringToSign = "folder=foro_imagenes&timestamp=" . $timestamp . $apiSecret;
    $signature = sha1($stringToSign);

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "https://api.cloudinary.com/v1_1/{$cloudName}/image/upload");
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    
    $postData = [
        'file' => new CURLFile($fileTmpPath),
        'api_key' => $apiKey,
        'timestamp' => $timestamp,
        'signature' => $signature,
        'folder' => 'foro_imagenes'
    ];
    
    curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
    $response = curl_exec($ch);
    curl_close($ch);

    $resultData = json_decode($response, true);

    if (isset($resultData['secure_url'])) {
        $urlImagenFinal = $resultData['secure_url'];
    }
}


$documento = [
    'email_usuario' => $email_session,
    'nombre_usuario' => $nombre_usuario,
    'mensaje' => $mensaje,
    'imagen' => $urlImagenFinal, // Guardará la URL de Cloudinary 
    'fecha' => date('Y-m-d H:i:s'),
    'comentarios' => []
];

try {
    $bulk = new MongoDB\Driver\BulkWrite;
    $bulk->insert($documento);
    $conexion->executeBulkWrite('ArbolBD.foro', $bulk);
    
    header("Location: ../pages/foro.php?publicado=ok");
    exit();

} catch (MongoDB\Driver\Exception\Exception $e) {
    echo "Error al guardar el post: " . $e->getMessage();
}
?>