<?php
include 'db.php';
header('Content-Type: application/json');

$q = $_GET['q'] ?? '';

// SEGURIDAD: Usamos Prepared Statements con LIKE
$stmt = $conexion->prepare("SELECT * FROM arboles WHERE nombre_comun LIKE ? OR nombre_cientifico LIKE ?");
$term = "%$q%";
$stmt->bind_param("ss", $term, $term);
$stmt->execute();
$resultado = $stmt->get_result();

$arboles = [];
while ($fila = $resultado->fetch_assoc()) {
    $arboles[] = $fila;
}

echo json_encode($arboles);
$stmt->close();
$conexion->close();