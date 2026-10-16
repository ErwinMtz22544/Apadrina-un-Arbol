<?php
require('../libs/fpdf.php');
include("../includes/db.php");
session_start();

// 1. Seguridad y Validación
if (!isset($_SESSION['usuario']) || !isset($_GET['id'])) {
    die("Acceso no autorizado.");
}

$id_apadrinamiento = (int)$_GET['id']; // Convertimos a entero según tu estructura en MongoDB
$email_session = $_SESSION['usuario'];

// 2. Buscar apadrinamiento en MongoDB
$queryApadrinamiento = new MongoDB\Driver\Query(['id_apadrinamiento' => $id_apadrinamiento]);
$datosAdopcionArr = $conexion->executeQuery("ArbolBD.apadrinamientos", $queryApadrinamiento)->toArray();

if (empty($datosAdopcionArr)) { 
    die("Certificado no encontrado."); 
}
$adopcion = $datosAdopcionArr[0];

// 3. Buscar usuario asociado
$queryUser = new MongoDB\Driver\Query(['id' => $adopcion->id_usuario]);
$datosUserArr = $conexion->executeQuery("ArbolBD.usuarios", $queryUser)->toArray();

if (empty($datosUserArr)) {
    die("Usuario asociado al apadrinamiento no encontrado.");
}
$datosUser = $datosUserArr[0];

// Validar pertenencia por correo electrónico
if ($datosUser->email !== $email_session) { 
    die("Este certificado no te pertenece."); 
}

// 4. Buscar árbol asociado
$queryArbol = new MongoDB\Driver\Query(['id_arbol' => $adopcion->id_arbol]);
$datosArbolArr = $conexion->executeQuery("ArbolBD.arboles", $queryArbol)->toArray();

if (empty($datosArbolArr)) {
    die("Información del árbol no encontrada.");
}
$datosArbol = $datosArbolArr[0];

// Definir nombre del tutor
$nombre_tutor = !empty($datosUser->nombre) ? $datosUser->nombre : $datosUser->nombre_usuario;

// 5. GENERACIÓN DEL PDF (Diseño moderno solicitado)
$pdf = new FPDF('L', 'mm', 'Letter');
$pdf->AddPage();

// --- MARCO ---
$pdf->SetDrawColor(200, 200, 200);
$pdf->Rect(10, 10, 259, 196);

// --- FONDO (Imagen de los árboles en la esquina derecha) ---
$pdf->Image('../assets/img/arbol-certificado-img.png', 180, 95, 85); 

// --- ENCABEZADO: Título Izquierda, Logo Derecha ---
$pdf->SetXY(25, 25);
$pdf->SetFont('Arial', 'B', 32);
$pdf->SetTextColor(0, 0, 0);
$pdf->Cell(0, 15, utf8_decode('CERTIFICADO'), 0, 1, 'L'); 

$pdf->SetX(25);
$pdf->SetFont('Arial', '', 18);
$pdf->Cell(0, 10, utf8_decode('DE GUARDIÁN'), 0, 1, 'L'); 

$pdf->SetDrawColor(93, 135, 54);
$pdf->SetLineWidth(1.5);
$pdf->Line(25, 52, 55, 52); // Línea decorativa izquierda

$pdf->SetXY(180, 22);
$pdf->SetFont('Arial', 'B', 16);
$pdf->SetTextColor(93, 135, 54); 
$pdf->Cell(80, 10, utf8_decode('Apadrina Un Árbol'), 0, 0, 'R'); 

// --- CUERPO: Se otorga (Izquierda) ---
$pdf->SetY(65);
$pdf->SetX(25);
$pdf->SetFont('Arial', '', 18);
$pdf->SetTextColor(60, 60, 60);
$pdf->Cell(0, 10, utf8_decode('Se otorga el presente reconocimiento a'), 0, 1, 'C'); 

// --- NOMBRE DEL TUTOR (CENTRADO) ---
$pdf->Ln(5);
$pdf->SetFont('Arial', 'B', 42); 
$pdf->SetTextColor(93, 135, 54);
$pdf->Cell(0, 20, utf8_decode($nombre_tutor), 0, 1, 'C'); 

// --- DESCRIPCIÓN (IZQUIERDA) ---
$pdf->SetY(110);
$pdf->SetX(25);
$pdf->SetFont('Arial', '', 13);
$pdf->SetTextColor(50, 50, 50);
$pdf->MultiCell(0, 7, utf8_decode("Por su valiosa contribución y compromiso con el medio ambiente\nal apadrinar un ejemplar de " . $datosArbol->nombre_comun . " (" . $datosArbol->nombre_cientifico . ")"), 0, 'L'); 

// --- BAUTIZADO COMO (CENTRADO) ---
$pdf->Ln(10);
$pdf->SetY(140);
$pdf->SetFont('Arial', '', 20);
$pdf->SetTextColor(40, 40, 40);
$texto1 = utf8_decode('Bautizado como ');
$texto2 = utf8_decode($adopcion->nombre_arbol);
$width = $pdf->GetStringWidth($texto1) + $pdf->GetStringWidth($texto2);
$pdf->SetX(($pdf->GetPageWidth() - $width) / 2); 
$pdf->Write(10, $texto1);
$pdf->SetFont('Arial', 'B', 22);
$pdf->SetTextColor(93, 135, 54);
$pdf->Write(10, $texto2);

// --- FIRMA IZQUIERDA (Autorización) ---
$pdf->SetDrawColor(93, 135, 54); 
$pdf->SetLineWidth(0.5);         

$pdf->Image('../assets/img/firma.png', 48, 152, 45); 
$pdf->Line(40, 175, 115, 175); 
$pdf->SetXY(40, 177);
$pdf->SetFont('Arial', '', 11);
$pdf->SetTextColor(0, 0, 0);   
$pdf->Cell(75, 5, utf8_decode('Firma de autorización'), 0, 0, 'C');

// --- FIRMA DERECHA (Director) ---
$pdf->Image('../assets/img/firma2.png', 180, 152, 45);
$pdf->Line(165, 175, 245, 175); 
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
$pdf->Cell(0, 5, date("d/m/Y", strtotime($adopcion->fecha_apadrinamiento)), 0, 0, 'L');

$hash = md5($id_apadrinamiento . $adopcion->fecha_apadrinamiento . $email_session);
$pdf->SetXY(160, 185);
$pdf->SetFont('Arial', '', 10);
$pdf->SetTextColor(100, 100, 100);
$pdf->Cell(85, 5, utf8_decode('Sello Digital de Autenticidad'), 0, 1, 'R');
$pdf->SetXY(160, 190);
$pdf->SetFont('Arial', '', 8);
$pdf->Cell(85, 5, $hash, 0, 0, 'R');

$pdf->Output('I', 'Certificado_' . $adopcion->nombre_arbol . '.pdf');
?>