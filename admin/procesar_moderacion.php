<?php
session_start();
include("../includes/db.php");

// Seguridad
if (!isset($_SESSION['rol']) || ($_SESSION['rol'] !== 'admin' && $_SESSION['rol'] != 1)) {
    die("Acceso denegado");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_mongo = $_POST['id_post'] ?? '';
    
    if(!empty($id_mongo)) {
        $bulk = new MongoDB\Driver\BulkWrite;
        $bulk->delete(['_id' => new MongoDB\BSON\ObjectId($id_mongo)]);
        $conexion->executeBulkWrite('ArbolBD.foro', $bulk);
    }
    
    header("Location: moderacion_foro.php");
    exit();
}
?>