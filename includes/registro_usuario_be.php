<?php
include 'db.php';
include 'config.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require '../phpmailer/Exception.php';
require '../phpmailer/PHPMailer.php';
require '../phpmailer/SMTP.php';

$nombre = $_POST['nombre_usuario'];
$correo = $_POST['email'];
$pass   = $_POST['user-password'];
$pass_c = $_POST['reg_confirm'];

if (strtolower($nombre) == 'admin') {
    echo '<script>alert("Este nombre de usuario está reservado."); window.location = "../pages/registro.php";</script>'; exit();
}
if ($pass !== $pass_c) {
    header("Location: ../pages/registro.php?error=pass_no_coinciden"); exit();
}

// Verificar duplicados
$queryCorreo = new MongoDB\Driver\Query(['email' => $correo]);
if(count($conexion->executeQuery("ArbolBD.usuarios", $queryCorreo)->toArray()) > 0){
    echo '<script>alert("Este correo ya está registrado."); window.location = "../pages/registro.php";</script>'; exit(); 
}

$queryNombre = new MongoDB\Driver\Query(['nombre_usuario' => $nombre]);
if(count($conexion->executeQuery("ArbolBD.usuarios", $queryNombre)->toArray()) > 0){
    echo '<script>alert("Este nombre ya está en uso."); window.location = "../pages/registro.php";</script>'; exit();
}

$pass_encriptada = password_hash($pass, PASSWORD_BCRYPT);
$token = bin2hex(random_bytes(16)); 

$documento = [
    "id" => time(), // Generamos un ID numérico temporal
    "nombre_usuario" => $nombre,
    "email" => $correo,
    "password" => $pass_encriptada,
    "token" => $token,
    "verificado" => 0,
    "id_rol" => 3,
    "fecha_registro" => date('c')
];

$bulk = new MongoDB\Driver\BulkWrite;
$bulk->insert($documento);

try {
    $conexion->executeBulkWrite('ArbolBD.usuarios', $bulk);
    $mail = new PHPMailer(true);
    // Configuración SMTP
    $mail->isSMTP(); $mail->Host = 'smtp.gmail.com'; $mail->SMTPAuth = true;
    $mail->Username = SMTP_USER; $mail->Password = SMTP_PASS; 
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS; $mail->Port = 465;
    $mail->setFrom(SMTP_USER, 'Apadrina un Árbol'); $mail->addAddress($correo, $nombre);
    $mail->isHTML(true); $mail->Subject = 'Verifica tu cuenta - Apadrina un Arbol';
    $enlace = "http://localhost/Arbol/includes/verificar_correos_existentes.php?email=$correo&token=$token";
    $mail->Body = "<div style='font-family: sans-serif; border: 1px solid #ddd; padding: 20px;'><h2>¡Hola $nombre!</h2><p>Confirma tu cuenta haciendo clic en el siguiente botón:</p><a href='$enlace' style='background-color: #5D8736; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; display: inline-block;'>Verificar mi cuenta</a></div>";
    $mail->send();
    header("Location: ../pages/login.php?status=check_email");
} catch (Exception $e) {
    header("Location: ../pages/registro.php?error=error_envio_correo");
}
?>