<?php
include 'db.php';
header('Content-Type: application/json');

$q = $_GET['q'] ?? '';

// Expresión regular para buscar coincidencias ignorando mayúsculas/minúsculas ('i')
$term = new MongoDB\BSON\Regex($q, 'i');
$filter = [
    '$or' => [
        ['nombre_comun' => $term],
        ['nombre_cientifico' => $term]
    ]
];

$query = new MongoDB\Driver\Query($filter);
$cursor = $conexion->executeQuery("ArbolBD.arboles", $query)->toArray();

echo json_encode($cursor);
?>