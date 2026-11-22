<?php
include 'db.php';
if (file_exists(__DIR__ . '/config.php')) {
    include __DIR__ . '/config.php';
}
if (!defined('SMTP_USER')) define('SMTP_USER', getenv('SMTP_USER') ?: '');
if (!defined('SMTP_PASS')) define('SMTP_PASS', getenv('SMTP_PASS') ?: '');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require '../phpmailer/Exception.php';
require '../phpmailer/PHPMailer.php';
require '../phpmailer/SMTP.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email'] ?? '');
    $filter = ['email' => $email];
    $query = new MongoDB\Driver\Query($filter);
    $cursor = $conexion->executeQuery("ArbolBD.usuarios", $query)->toArray();

    if (count($cursor) > 0) {
        $usuario = $cursor[0];
        $nombre = $usuario->nombre_usuario;
        $token = bin2hex(random_bytes(20));

        $bulk = new MongoDB\Driver\BulkWrite;
        $bulk->update(['email' => $email], ['$set' => ['token' => $token]]);
        $conexion->executeBulkWrite('ArbolBD.usuarios', $bulk);

        // Detectar URL base automáticamente (XAMPP o Render)
        $protocolo = 'http';
        if (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') {
            $protocolo = 'https';
        } elseif (($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? '') === 'https') {
            $protocolo = 'https';
        }
        $carpeta = (strpos($_SERVER['REQUEST_URI'], '/Apadrina-un-Arbol') !== false) ? '/Apadrina-un-Arbol' : '';
        $url_base = $protocolo . '://' . $_SERVER['HTTP_HOST'] . $carpeta;

        $mail = new PHPMailer(true);
        try {
            $mail->CharSet = 'UTF-8';
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = SMTP_USER;
            $mail->Password = SMTP_PASS;
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            $mail->Port = 465;
            $mail->setFrom(SMTP_USER, 'Apadrina un Árbol');
            $mail->addAddress($email, $nombre);
            $mail->isHTML(true);
            $mail->Subject = 'Restablecer Contraseña - Apadrina un Árbol';

            $enlace = $url_base . "/pages/restablecer.php?token=" . urlencode($token) . "&email=" . urlencode($email);
            $mail->Body = "<div style='font-family: sans-serif; border: 1px solid #ddd; padding: 20px;'><h2>Hola $nombre,</h2><p>Haz clic en el siguiente botón para continuar:</p><a href='$enlace' style='background-color: #5D8736; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; display: inline-block;'>Restablecer mi contraseña</a></div>";
            $mail->send();
            header("Location: ../pages/login.php?res=email_enviado");
        } catch (Exception $e) {
            header("Location: ../pages/olvide_password.php?error=error_envio");
        }
    } else {
        header("Location: ../pages/login.php?res=no_existe");
    }
} else {
    header("Location: ../pages/login.php");
}
?>