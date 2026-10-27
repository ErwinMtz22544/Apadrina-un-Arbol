<?php
session_start();
// 1. Forzar la zona horaria correcta para Monterrey / Centro de México
date_default_timezone_set('America/Monterrey'); 

include 'db.php';

if (!isset($_SESSION['usuario']) || $_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../pages/foro.php");
    exit();
}

$email_session = $_SESSION['usuario'];
$mensaje = trim($_POST['mensaje']);
$ruta_imagen = null;

$queryUser = new MongoDB\Driver\Query(['email' => $email_session]);
$cursorUser = $conexion->executeQuery("ArbolBD.usuarios", $queryUser)->toArray();
$nombre_usuario = !empty($cursorUser) ? ($cursorUser[0]->nombre ?? $cursorUser[0]->nombre_usuario) : "Usuario Anónimo";

// 2. Procesamiento de imagen más robusto
if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
    $directorio_destino = '../assets/images/'; 
    
    // Si la carpeta no existe, PHP la crea automáticamente con permisos
    if (!file_exists($directorio_destino)) {
        mkdir($directorio_destino, 0777, true);
    }
    
    $extension = pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION);
    $nombre_archivo = uniqid('post_') . '.' . $extension;
    $ruta_final = $directorio_destino . $nombre_archivo;
    
    // Validar que realmente se mueva el archivo antes de guardar la ruta
    if (move_uploaded_file($_FILES['foto']['tmp_name'], $ruta_final)) {
        $ruta_imagen = '/Apadrina-un-Arbol/assets/images/' . $nombre_archivo;
    }
}

$documento = [
    'email_usuario' => $email_session,
    'nombre_usuario' => $nombre_usuario,
    'mensaje' => $mensaje,
    'imagen' => $ruta_imagen,
    'fecha' => date('Y-m-d H:i:s'),
    'comentarios' => [] 
];

try {
    $bulk = new MongoDB\Driver\BulkWrite;
    $bulk->insert($documento);
    $conexion->executeBulkWrite('ArbolBD.foro', $bulk);
    
    header("Location: ../pages/foro.php?publicado=ok");
} catch (MongoDB\Driver\Exception\Exception $e) {
    echo "Error al guardar el post: " . $e->getMessage();
}
?>