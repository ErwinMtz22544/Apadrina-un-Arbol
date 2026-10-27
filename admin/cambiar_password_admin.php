<?php
include 'verificar_admin.php';
include '../includes/db.php';
include '../includes/config.php'; // credenciales SMTP

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../phpmailer/Exception.php';
require '../phpmailer/PHPMailer.php';
require '../phpmailer/SMTP.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = (int)$_POST['id_usuario'];
    $nueva_pass = $_POST['nueva_pass'];

    // 1. Buscamos al usuario en MongoDB para sacar su email y nombre
    $query_busqueda = new MongoDB\Driver\Query(['id' => $id]);
    $usuarios = $conexion->executeQuery('ArbolBD.usuarios', $query_busqueda)->toArray();

    if (count($usuarios) > 0) {
        $usuario = $usuarios[0];
        $email_usuario = $usuario->email;
        $nombre_usuario = $usuario->nombre_usuario;

        // 2. Encriptamos y preparamos la actualización
        $pass_encriptada = password_hash($nueva_pass, PASSWORD_BCRYPT);
        $bulk = new MongoDB\Driver\BulkWrite;
        $bulk->update(
            ['id' => $id], 
            ['$set' => ['password' => $pass_encriptada]]
        );

        try {
            // 3. Ejecutamos la actualización
            $conexion->executeBulkWrite('ArbolBD.usuarios', $bulk);

            // 4. ENVIAR NOTIFICACIÓN POR CORREO (Se mantiene igual que el tuyo)
            $mail = new PHPMailer(true);
            try {
                $mail->isSMTP();
                $mail->Host       = 'smtp.gmail.com';
                $mail->SMTPAuth   = true;
                $mail->Username   = SMTP_USER;
                $mail->Password   = SMTP_PASS;
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
                $mail->Port       = 465;

                $mail->setFrom(SMTP_USER, 'Apadrina un Árbol');
                $mail->addAddress($email_usuario, $nombre_usuario);

                $mail->isHTML(true);
                $mail->Subject = 'Tu contraseña ha sido actualizada por un administrador';
                $mail->Body = "
                    <div style='font-family: Poppins, sans-serif; border: 1px solid #e0e0e0; padding: 20px; border-radius: 10px;'>
                        <h2 style='color: #5D8736;'>Hola, $nombre_usuario</h2>
                        <p>Te informamos que un administrador de <strong>Apadrina un Árbol</strong> ha actualizado tu contraseña de acceso.</p>
                        <p>Tu nueva contraseña temporal es: <strong style='background: #f4f4f4; padding: 5px;'>$nueva_pass</strong></p>
                        <p style='color: #666; font-size: 13px;'>Te recomendamos cambiar esta contraseña una vez que inicies sesión por motivos de seguridad.</p>
                        <hr style='border: 0; border-top: 1px solid #eee;'>
                        <p style='font-size: 12px; color: #999;'>Si tú no solicitaste este cambio, por favor contacta soporte de inmediato.</p>
                    </div>
                ";
                $mail->send();
            } catch (Exception $e) {
                // Si el correo falla, igual se cambió la contraseña, ignoramos el error de correo
            }

            header("Location: gestion_usuarios.php?res=pass_ok");
        } catch (Exception $e) {
            header("Location: gestion_usuarios.php?res=error");
        }
    } else {
        header("Location: gestion_usuarios.php?res=error");
    }
    exit();
}
?>