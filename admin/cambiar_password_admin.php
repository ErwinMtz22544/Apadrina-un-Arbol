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
    $id = $_POST['id_usuario'];
    $nueva_pass = $_POST['nueva_pass'];

    // --- NUEVA VALIDACIÓN DE SEGURIDAD PARA EL ADMIN ---
    // Mínimo 8 caracteres, al menos un número y al menos un carácter especial
if (strlen($nueva_pass) < 8 || !preg_match('/[0-9]/', $nueva_pass) || !preg_match('/[^A-Za-z0-9]/', $nueva_pass)) {
    echo "
    <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                icon: 'error',
                title: 'Contraseña no válida',
                text: 'La contraseña debe tener al menos 8 caracteres, incluir un número y un símbolo.',
                confirmButtonColor: '#5D8736',
                confirmButtonText: 'Entendido',
                didOpen: () => {
                    // Aplicamos tus estilos de Poppins directamente aquí
                    const popup = Swal.getPopup();
                    const title = Swal.getTitle();
                    const content = Swal.getHtmlContainer();
                    if (popup) popup.style.fontFamily = \"'Poppins', sans-serif\";
                    if (title) { title.style.fontSize = '20px'; title.style.fontWeight = '600'; }
                    if (content) { content.style.fontSize = '15px'; }
                }
            }).then(() => {
                window.history.back();
            });
        });
    </script>";
    exit();
}
    // ---------------------------------------------------

    // 1. Antes de cambiarla, obtenemos el correo y nombre del usuario
    $consulta_usuario = mysqli_query($conexion, "SELECT nombre_usuario, email FROM usuarios WHERE id = '$id'");
    $datos_usuario = mysqli_fetch_assoc($consulta_usuario);
    $email_usuario = $datos_usuario['email'];
    $nombre_usuario = $datos_usuario['nombre_usuario'];

    // 2. Encriptamos y actualizamos
    $pass_encriptada = password_hash($nueva_pass, PASSWORD_BCRYPT);
    $query = "UPDATE usuarios SET password = '$pass_encriptada' WHERE id = '$id'";
    $ejecutar = mysqli_query($conexion, $query);

    if ($ejecutar) {
        // 3. ENVIAR NOTIFICACIÓN POR CORREO
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
            // Error de envío, pero la contraseña ya se cambió en BD
        }

        header("Location: gestion_usuarios.php?res=pass_ok");
    } else {
        header("Location: gestion_usuarios.php?res=error");
    }
}