<?php
include 'db.php';
header('Content-Type: application/json');

$q = $_GET['q'] ?? '';

$term = new MongoDB\BSON\Regex($q, 'i');
$filter = [
    '$or' => [
        ['nombre_comun' => $term],
        ['nombre_cientifico' => $term],
        ['epoca' => $term],
        ['descripcion' => $term]
    ]
];

$query = new MongoDB\Driver\Query($filter);
$cursor = $conexion->executeQuery("ArbolBD.arboles", $query)->toArray();

echo json_encode($cursor);
?>