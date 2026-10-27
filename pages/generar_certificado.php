<?php
require('../libs/fpdf.php');
include("../includes/db.php");
session_start();

if (!isset($_SESSION['usuario']) || !isset($_GET['id'])) { die("Acceso no autorizado."); }

$id_apadrinamiento = (int)$_GET['id'];
$email_session = $_SESSION['usuario'];

// 1. Buscar apadrinamiento
$queryApadrinamiento = new MongoDB\Driver\Query(['id_apadrinamiento' => $id_apadrinamiento]);
$datosAdopcionArr = $conexion->executeQuery("ArbolBD.apadrinamientos", $queryApadrinamiento)->toArray();
if (empty($datosAdopcionArr)) { die("Certificado no encontrado."); }
$adopcion = $datosAdopcionArr[0];

// 2. Buscar árbol
$queryArbol = new MongoDB\Driver\Query(['id_arbol' => $adopcion->id_arbol]);
$datosArbol = $conexion->executeQuery("ArbolBD.arboles", $queryArbol)->toArray()[0];

// 3. Buscar usuario
$queryUser = new MongoDB\Driver\Query(['id' => $adopcion->id_usuario]);
$datosUser = $conexion->executeQuery("ArbolBD.usuarios", $queryUser)->toArray()[0];

if ($datosUser->email !== $email_session) { die("Este certificado no te pertenece."); }

$nombre_tutor = !empty($datosUser->nombre) ? $datosUser->nombre : $datosUser->nombre_usuario;

$pdf = new FPDF('L', 'mm', 'Letter'); 
$pdf->AddPage();

$pdf->SetDrawColor(93, 135, 54); $pdf->SetLineWidth(2.5); $pdf->Rect(12, 12, 255, 191); 
$pdf->SetDrawColor(0, 0, 0); $pdf->SetLineWidth(0.5); $pdf->Rect(16, 16, 247, 183);
$pdf->SetLineWidth(0.1); $pdf->Rect(18, 18, 243, 179);

$pdf->Ln(15); $pdf->SetFont('Arial', 'B', 14); $pdf->SetTextColor(100, 100, 100);
$pdf->Cell(0, 10, utf8_decode('UNIVERSIDAD TECNOLÓGICA DE SANTA CATARINA'), 0, 1, 'C');

$pdf->Ln(2); $pdf->SetFont('Arial', 'B', 40); $pdf->SetTextColor(93, 135, 54); 
$pdf->Cell(0, 18, utf8_decode('CERTIFICADO DE GUARDIÁN'), 0, 1, 'C');

$pdf->SetDrawColor(200, 200, 200); $pdf->Line(60, 65, 220, 65);

$pdf->Ln(12); $pdf->SetFont('Arial', 'I', 18); $pdf->SetTextColor(50, 50, 50);
$pdf->Cell(0, 10, utf8_decode('Se otorga el presente reconocimiento y título honorífico a:'), 0, 1, 'C');

$pdf->Ln(8); $pdf->SetFont('Arial', 'B', 38); $pdf->SetTextColor(0, 0, 0);
$pdf->Cell(0, 15, utf8_decode(strtoupper($nombre_tutor)), 0, 1, 'C'); 

$pdf->Ln(8); $pdf->SetFont('Arial', '', 16); $pdf->SetTextColor(80, 80, 80);
$pdf->MultiCell(0, 8, utf8_decode("Por su invaluable compromiso con el medio ambiente y la reforestación del campus,\nal apadrinar formalmente un ejemplar de la especie:"), 0, 'C');

$pdf->Ln(6); $pdf->SetFont('Arial', 'B', 22); $pdf->SetTextColor(93, 135, 54);
$pdf->Cell(0, 10, utf8_decode($datosArbol->nombre_comun . ' (' . $datosArbol->nombre_cientifico . ')'), 0, 1, 'C');

$pdf->Ln(5); $pdf->SetFont('Arial', 'I', 18); $pdf->SetTextColor(50, 50, 50);
$pdf->Cell(0, 10, utf8_decode('Bautizado cariñosamente como: "' . $adopcion->nombre_arbol . '"'), 0, 1, 'C');

$pdf->SetY(-55); 
$pdf->SetDrawColor(0, 0, 0); $pdf->SetLineWidth(0.4); $pdf->Line(40, 175, 110, 175);
$pdf->SetFont('Arial', '', 12); $pdf->SetTextColor(0, 0, 0); $pdf->SetXY(40, 177);
$pdf->Cell(70, 5, utf8_decode('Comité de Reforestación UTSC'), 0, 0, 'C');

$pdf->Line(170, 175, 240, 175); $pdf->SetXY(170, 170); 
$pdf->SetFont('Arial', 'B', 12); $pdf->Cell(70, 5, utf8_decode(date("d / m / Y", strtotime($adopcion->fecha_apadrinamiento))), 0, 0, 'C');
$pdf->SetXY(170, 177); $pdf->SetFont('Arial', '', 12); $pdf->Cell(70, 5, utf8_decode('Fecha de Adopción'), 0, 0, 'C');

$pdf->SetY(-25); $pdf->SetFont('Arial', 'I', 9); $pdf->SetTextColor(150, 150, 150);
$folio = str_pad($id_apadrinamiento, 5, "0", STR_PAD_LEFT);
$hash = md5($id_apadrinamiento . $adopcion->fecha_apadrinamiento . $email_session);
$pdf->Cell(0, 5, utf8_decode("Sello Digital de Autenticidad | Folio: $folio | Hash: $hash"), 0, 1, 'C');

$pdf->Output('I', 'Certificado_' . $adopcion->nombre_arbol . '.pdf'); 
?>