<?php
include 'db.php';
include 'config.php'; // Para usar SMTP_USER y SMTP_PASS

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../phpmailer/Exception.php';
require '../phpmailer/PHPMailer.php';
require '../phpmailer/SMTP.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = mysqli_real_escape_string($conexion, $_POST['email']);

    // 1. Verificar si el correo existe en la base de datos
    $consulta = "SELECT nombre_usuario FROM usuarios WHERE email = '$email'";
    $resultado = mysqli_query($conexion, $consulta);

    if (mysqli_num_rows($resultado) > 0) {
        $usuario = mysqli_fetch_assoc($resultado);
        $nombre = $usuario['nombre_usuario'];

        //  Generar un Token único para la recuperación
        $token = bin2hex(random_bytes(20));

        // Guardar el token en el usuario 
        // Esto invalidará su link de activación viejo, pero le permitirá resetear la clave
        $actualizar = "UPDATE usuarios SET token = '$token' WHERE email = '$email'";
        mysqli_query($conexion, $actualizar);

        // Configurar y enviar el correo con PHPMailer
        $mail = new PHPMailer(true);

        try {
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = SMTP_USER;
            $mail->Password   = SMTP_PASS;
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            $mail->Port       = 465;

            //NO BORRAR --para subir a la web cambiar
          //  $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // Cambiar SMTPS por STARTTLS
		//	$mail->Port       = 587;                          // Cambiar 465 por 587


            $mail->setFrom(SMTP_USER, 'Apadrina un Arbol');
            $mail->addAddress($email, $nombre);

            $mail->isHTML(true);
            $mail->Subject = 'Restablecer Contraseña - Apadrina un Arbol';

            // Enlace hacia la página donde el usuario pondrá su nueva clave
            $enlace = "http://localhost/Arbol/pages/restablecer.php?token=$token&email=$email";

             // NOBORRAR ----Enlace hacia la página donde el usuario pondrá su nueva clave
          // $enlace = "http://apadrinaunarbol.free.nf/pages/restablecer.php?token=$token&email=$email";

            $mail->Body = "
                <div style='font-family: sans-serif; border: 1px solid #ddd; padding: 20px;'>
                    <h2>Hola $nombre,</h2>
                    <p>Has solicitado restablecer tu contraseña. Haz clic en el siguiente botón para continuar:</p>
                    <a href='$enlace' style='background-color: #5D8736; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; display: inline-block;'>Restablecer mi contraseña</a>
                    <p>Si no solicitaste este cambio, puedes ignorar este correo.</p>
                </div>
            ";

            $mail->send();
            
           
            header("Location: ../pages/login.php?res=email_enviado");

        } catch (Exception $e) {
            // Error de envío
            header("Location: ../pages/olvide_password.php?error=error_envio");
        }

    } else {
        // El correo no existe en la BD
        header("Location: ../pages/login.php?res=no_existe");
    }
} else {
    header("Location: ../pages/login.php");
}
#
mysqli_close($conexion);
?>