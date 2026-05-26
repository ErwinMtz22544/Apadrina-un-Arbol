<?php
require('../libs/fpdf.php');
include("../includes/db.php");
session_start();

// 1. Seguridad y Datos (Tus variables de DB)
if (!isset($_SESSION['usuario']) || !isset($_GET['id'])) {
    die("Acceso no autorizado.");
}

$id_apadrinamiento = $_GET['id'];
$email_session = $_SESSION['usuario'];

$query = "SELECT a.*, ar.nombre_comun, ar.nombre_cientifico, u.nombre_usuario, u.nombre 
          FROM apadrinamientos a 
          JOIN arboles ar ON a.id_arbol = ar.id_arbol 
          JOIN usuarios u ON a.id_usuario = u.id 
          WHERE a.id_apadrinamiento = '$id_apadrinamiento' AND u.email = '$email_session'";

$res = mysqli_query($conexion, $query);
$datos = mysqli_fetch_assoc($res);
$nombre_tutor = !empty($datos['nombre']) ? $datos['nombre'] : $datos['nombre_usuario'];

// 2. GENERACIÓN DEL PDF
$pdf = new FPDF('L', 'mm', 'Letter');
$pdf->AddPage();

// --- MARCO ---
$pdf->SetDrawColor(200, 200, 200);
$pdf->Rect(10, 10, 259, 196);

// --- FONDO (Imagen de los árboles en la esquina derecha) ---
// Se coloca al principio para que no tape los textos
$pdf->Image('../assets/img/arbol-certificado-img.png', 180, 95, 85); 

// --- ENCABEZADO: Título Izquierda, Logo Derecha ---
$pdf->SetXY(25, 25);
$pdf->SetFont('Arial', 'B', 32);
$pdf->SetTextColor(0, 0, 0);
$pdf->Cell(0, 15, utf8_decode('CERTIFICADO'), 0, 1, 'L'); // A la izquierda

$pdf->SetX(25);
$pdf->SetFont('Arial', '', 18);
$pdf->Cell(0, 10, utf8_decode('DE GUARDIÁN'), 0, 1, 'L'); // A la izquierda

$pdf->SetDrawColor(93, 135, 54);
$pdf->SetLineWidth(1.5);
$pdf->Line(25, 52, 55, 52); // Línea decorativa izquierda

$pdf->SetXY(180, 22);
$pdf->SetFont('Arial', 'B', 16);
$pdf->SetTextColor(93, 135, 54); 
$pdf->Cell(80, 10, utf8_decode('Apadrina Un Árbol'), 0, 0, 'R'); // Logo a la derecha

// --- CUERPO: Se otorga (Izquierda) ---
$pdf->SetY(65);
$pdf->SetX(25);
$pdf->SetFont('Arial', '', 18);
$pdf->SetTextColor(60, 60, 60);
$pdf->Cell(0, 10, utf8_decode('Se otorga el presente reconocimiento a'), 0, 1, 'C'); // A la izquierda

// --- NOMBRE DEL TUTOR (CENTRADO) ---
$pdf->Ln(5);
$pdf->SetFont('Arial', 'B', 42); 
$pdf->SetTextColor(93, 135, 54);
$pdf->Cell(0, 20, utf8_decode($nombre_tutor), 0, 1, 'C'); // CENTRADO para impacto visual

// --- DESCRIPCIÓN (IZQUIERDA) ---
$pdf->SetY(110);
$pdf->SetX(25);
$pdf->SetFont('Arial', '', 13);
$pdf->SetTextColor(50, 50, 50);
$pdf->MultiCell(0, 7, utf8_decode("Por su valiosa contribución y compromiso con el medio ambiente\nal apadrinar un ejemplar de " . $datos['nombre_comun'] . " (" . $datos['nombre_cientifico'] . ")"), 0, 'L'); // A la izquierda

// --- BAUTIZADO COMO (CENTRADO) ---
$pdf->Ln(10);
$pdf->SetY(140);
$pdf->SetFont('Arial', '', 20);
$pdf->SetTextColor(40, 40, 40);
$texto1 = utf8_decode('Bautizado como ');
$texto2 = utf8_decode($datos['nombre_arbol']);
$width = $pdf->GetStringWidth($texto1) + $pdf->GetStringWidth($texto2);
$pdf->SetX(($pdf->GetPageWidth() - $width) / 2); // Cálculo para CENTRAR el bloque mixto
$pdf->Write(10, $texto1);
$pdf->SetFont('Arial', 'B', 22);
$pdf->SetTextColor(93, 135, 54);
$pdf->Write(10, $texto2);

// --- FIRMA IZQUIERDA (Autorización) ---
$pdf->SetDrawColor(93, 135, 54); 
$pdf->SetLineWidth(0.5);        

// --- FIRMA IZQUIERDA (Autorización) ---
$pdf->Image('../assets/img/firma.png', 48, 152, 45); 
$pdf->Line(40, 175, 115, 175); 
$pdf->SetXY(40, 177);
$pdf->SetFont('Arial', '', 11);
$pdf->SetTextColor(0, 0, 0);   // Aseguramos que el texto regrese a negro
$pdf->Cell(75, 5, utf8_decode('Firma de autorización'), 0, 0, 'C');

// --- FIRMA DERECHA (Director) ---
$pdf->Image('../assets/img/firma2.png', 180, 152, 45);
$pdf->Line(165, 175, 245, 175); // Línea de la derecha (ya heredó el color verde y el grosor)
$pdf->SetXY(165, 177);
$pdf->Cell(80, 5, utf8_decode('Director de Apadrinamiento'), 0, 0, 'C');
// --- PIE DE PÁGINA ---
$pdf->SetY(185);
$pdf->SetX(25);
$pdf->SetFont('Arial', '', 10);
$pdf->SetTextColor(100, 100, 100);
$pdf->Cell(0, 5, utf8_decode('Fecha de adopción'), 0, 1, 'L');
$pdf->SetX(25);
$pdf->SetFont('Arial', '', 11);
$pdf->SetTextColor(0, 0, 0);
$pdf->Cell(0, 5, date("d/m/Y", strtotime($datos['fecha_apadrinamiento'])), 0, 0, 'L');

$hash = md5($id_apadrinamiento . $datos['fecha_apadrinamiento'] . $email_session);
$pdf->SetXY(160, 185);
$pdf->SetFont('Arial', '', 10);
$pdf->SetTextColor(100, 100, 100);
$pdf->Cell(85, 5, utf8_decode('Sello Digital de Autenticidad'), 0, 1, 'R');
$pdf->SetXY(160, 190);
$pdf->SetFont('Arial', '', 8);
$pdf->Cell(85, 5, $hash, 0, 0, 'R');

$pdf->Output('I', 'Certificado_' . $datos['nombre_arbol'] . '.pdf');
?>