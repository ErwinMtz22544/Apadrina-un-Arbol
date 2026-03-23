<?php
require('../libs/fpdf.php');
include("../includes/db.php");
session_start();

// 1. Seguridad
if (!isset($_SESSION['usuario']) || !isset($_GET['id'])) {
    die("Acceso no autorizado.");
}

$id_apadrinamiento = $_GET['id'];
$email_session = $_SESSION['usuario'];

// 2. Consulta de datos (Buscamos 'nombre' por si usaste la Opción B de la base de datos)
$query = "SELECT a.*, ar.nombre_comun, ar.nombre_cientifico, u.nombre_usuario, u.nombre 
          FROM apadrinamientos a 
          JOIN arboles ar ON a.id_arbol = ar.id_arbol 
          JOIN usuarios u ON a.id_usuario = u.id 
          WHERE a.id_apadrinamiento = '$id_apadrinamiento' AND u.email = '$email_session'";

$res = mysqli_query($conexion, $query);
$datos = mysqli_fetch_assoc($res);

if (!$datos) { die("Certificado no encontrado o no te pertenece."); }

// Usamos el nombre real si existe, si no, el usuario de login
$nombre_tutor = !empty($datos['nombre']) ? $datos['nombre'] : $datos['nombre_usuario'];

// 3. INICIO DEL DIBUJO DEL PDF
$pdf = new FPDF('L', 'mm', 'Letter'); // Horizontal
$pdf->AddPage();

// --- BORDES ESTILO DIPLOMA ---
// Borde Exterior (Grueso y Verde UTSC)
$pdf->SetDrawColor(93, 135, 54); 
$pdf->SetLineWidth(2.5);
$pdf->Rect(12, 12, 255, 191); 

// Borde Interior 1 (Fino y Negro)
$pdf->SetDrawColor(0, 0, 0);
$pdf->SetLineWidth(0.5);
$pdf->Rect(16, 16, 247, 183);

// Borde Interior 2 (Muy fino, da efecto de relieve)
$pdf->SetLineWidth(0.1);
$pdf->Rect(18, 18, 243, 179);

// --- ENCABEZADO ---
$pdf->Ln(15);
$pdf->SetFont('Arial', 'B', 14);
$pdf->SetTextColor(100, 100, 100);
$pdf->Cell(0, 10, utf8_decode('UNIVERSIDAD TECNOLÓGICA DE SANTA CATARINA'), 0, 1, 'C');

$pdf->Ln(2);
$pdf->SetFont('Arial', 'B', 40);
$pdf->SetTextColor(93, 135, 54); // Verde UTSC
$pdf->Cell(0, 18, utf8_decode('CERTIFICADO DE GUARDIÁN'), 0, 1, 'C');

// Línea separadora sutil
$pdf->SetDrawColor(200, 200, 200);
$pdf->Line(60, 65, 220, 65);

// --- CUERPO DEL CERTIFICADO ---
$pdf->Ln(12);
$pdf->SetFont('Arial', 'I', 18);
$pdf->SetTextColor(50, 50, 50);
$pdf->Cell(0, 10, utf8_decode('Se otorga el presente reconocimiento y título honorífico a:'), 0, 1, 'C');

$pdf->Ln(8);
$pdf->SetFont('Arial', 'B', 38);
$pdf->SetTextColor(0, 0, 0);
$pdf->Cell(0, 15, utf8_decode(strtoupper($nombre_tutor)), 0, 1, 'C'); //

$pdf->Ln(8);
$pdf->SetFont('Arial', '', 16);
$pdf->SetTextColor(80, 80, 80);
$pdf->MultiCell(0, 8, utf8_decode("Por su invaluable compromiso con el medio ambiente y la reforestación del campus,\nal apadrinar formalmente un ejemplar de la especie:"), 0, 'C');

$pdf->Ln(6);
$pdf->SetFont('Arial', 'B', 22);
$pdf->SetTextColor(93, 135, 54);
$pdf->Cell(0, 10, utf8_decode($datos['nombre_comun'] . ' (' . $datos['nombre_cientifico'] . ')'), 0, 1, 'C');

$pdf->Ln(5);
$pdf->SetFont('Arial', 'I', 18);
$pdf->SetTextColor(50, 50, 50);
$pdf->Cell(0, 10, utf8_decode('Bautizado cariñosamente como: "' . $datos['nombre_arbol'] . '"'), 0, 1, 'C');

// --- ZONA DE FIRMAS Y FECHA ---
$pdf->SetY(-55); // Nos anclamos a 55mm del final de la hoja

// Línea Izquierda (Autoridad)
$pdf->SetDrawColor(0, 0, 0);
$pdf->SetLineWidth(0.4);
$pdf->Line(40, 175, 110, 175);
$pdf->SetFont('Arial', '', 12);
$pdf->SetTextColor(0, 0, 0);
$pdf->SetXY(40, 177);
$pdf->Cell(70, 5, utf8_decode('Comité de Reforestación UTSC'), 0, 0, 'C');

// Línea Derecha (Fecha)
$pdf->Line(170, 175, 240, 175);
$pdf->SetXY(170, 170); // Ponemos la fecha sobre la línea
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(70, 5, utf8_decode(date("d / m / Y", strtotime($datos['fecha_apadrinamiento']))), 0, 0, 'C');
$pdf->SetXY(170, 177); // Texto debajo de la línea
$pdf->SetFont('Arial', '', 12);
$pdf->Cell(70, 5, utf8_decode('Fecha de Adopción'), 0, 0, 'C');

// --- SELLO DIGITAL (FOOTER) ---
$pdf->SetY(-25);
$pdf->SetFont('Arial', 'I', 9);
$pdf->SetTextColor(150, 150, 150);
$folio = str_pad($id_apadrinamiento, 5, "0", STR_PAD_LEFT);
$hash = md5($id_apadrinamiento . $datos['fecha_apadrinamiento'] . $email_session);
$pdf->Cell(0, 5, utf8_decode("Sello Digital de Autenticidad | Folio: $folio | Hash: $hash"), 0, 1, 'C');

// 'I' muestra el PDF en el navegador, 'D' fuerza la descarga. 
// He puesto 'I' para que puedas ver lo bonito que quedó antes de bajarlo.
$pdf->Output('I', 'Certificado_' . $datos['nombre_arbol'] . '.pdf'); 
?>