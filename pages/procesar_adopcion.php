<?php
error_reporting(E_ALL);
ini_set('display_errors', 0);
session_start();
include("../includes/db.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!isset($_SESSION['usuario'])) {
        header("Location: login.php"); exit();
    }
    
    // Buscar ID del usuario logueado en Mongo
    $email_session = $_SESSION['usuario'];
    $cursorUser = $conexion->executeQuery("ArbolBD.usuarios", new MongoDB\Driver\Query(['email' => $email_session]))->toArray();
    $id_usuario = count($cursorUser) > 0 ? $cursorUser[0]->id : time();
    
    $id_arbol      = (int)($_POST['id_arbol'] ?? 0);
    $nombre_arbol  = $_POST['nombre_personalizado'] ?? 'Aporte de Mantenimiento'; 
    $tipo_adopcion = $_POST['tipo_adopcion'] ?? 'Individual';
    $monto         = (float)($_POST['monto'] ?? 0);
    $metodo_pago   = $_POST['metodo_pago'] ?? 'Tarjeta';

    if ($metodo_pago === 'Tarjeta') {
        $num_tarjeta = $_POST['num_tarjeta'] ?? '';
        if (empty($num_tarjeta)) {
            header("Location: arbol-detalle.php?id=$id_arbol&error=datos_incompletos"); exit();
        }
    }

    $documento = [
        "id_apadrinamiento" => time(), // Generamos ID único numérico
        "id_usuario" => $id_usuario,
        "id_arbol" => $id_arbol,
        "nombre_arbol" => $nombre_arbol,
        "tipo_adopcion" => $tipo_adopcion,
        "fecha_apadrinamiento" => date('c'),
        "monto" => $monto,
        "metodo_pago" => $metodo_pago
    ];

    $bulk = new MongoDB\Driver\BulkWrite;
    $bulk->insert($documento);

    try {
        $conexion->executeBulkWrite('ArbolBD.apadrinamientos', $bulk);
        header("Location: arbol-detalle.php?id=$id_arbol&status=adoptado"); exit();
    } catch (MongoDB\Driver\Exception\Exception $e) {
        error_log("Error de DB en adopción: " . $e->getMessage());
        echo "Ocurrió un error al procesar tu adopción.";
    }
} else {
    header("Location: catalogo.php"); exit();
}
?>