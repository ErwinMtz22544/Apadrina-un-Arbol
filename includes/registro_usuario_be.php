<?php
// Archivo: includes/registro_usuario_be.php
include 'db.php';
include 'config.php';

// Clases de PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../phpmailer/Exception.php';
require '../phpmailer/PHPMailer.php';
require '../phpmailer/SMTP.php';

// Sanitización de entradas
$nombre = mysqli_real_escape_string($conexion, $_POST['nombre_usuario']);
$correo = mysqli_real_escape_string($conexion, $_POST['email']);
$pass   = $_POST['user-password'];
$pass_c = $_POST['reg_confirm'];

// 1. Validar nombre reservado 
if (strtolower($nombre) == 'admin') {
    echo '
        <script>
            alert("Este nombre de usuario está reservado.");
            window.location = "../pages/registro.php";
        </script>
    ';
    exit();
}

// 2. Validar que las contraseñas coincidan
if ($pass !== $pass_c) {
    header("Location: ../pages/registro.php?error=pass_no_coinciden");
    exit();
}

// --- MEJORA: VERIFICACIÓN DE DUPLICADOS ANTES DE INSERTAR ---

// Verificar que el correo no se repita
$verificar_correo = mysqli_query($conexion, "SELECT * FROM usuarios WHERE email='$correo'");

if(mysqli_num_rows($verificar_correo) > 0){
    echo '
        <script>
            alert("Este correo ya está registrado, intenta con otro diferente.");
            window.location = "../pages/registro.php";
        </script>
    ';
    exit(); 
}

// Verificar que el nombre de usuario no se repita
$verificar_usuario = mysqli_query($conexion, "SELECT * FROM usuarios WHERE nombre_usuario='$nombre'");

if(mysqli_num_rows($verificar_usuario) > 0){
    echo '
        <script>
            alert("Este nombre de usuario ya está en uso, por favor elige otro.");
            window.location = "../pages/registro.php";
        </script>
    ';
    exit();
}

// -----------------------------------------------------------

// 3. Encriptar contraseña
$pass_encriptada = password_hash($pass, PASSWORD_BCRYPT);

// 4. Generación de Token de verificación
$token = bin2hex(random_bytes(16)); 

// 5. Preparar inserción (id_rol 3 = Usuario Registrado)
$id_rol_default = 3;
$query = "INSERT INTO usuarios (nombre_usuario, email, password, token, verificado, id_rol) 
          VALUES ('$nombre', '$correo', '$pass_encriptada', '$token', 0, $id_rol_default)";

$ejecutar = mysqli_query($conexion, $query);

if ($ejecutar) {
    // SI EL REGISTRO EN BD FUE EXITOSO, SE ENVÍA EL CORREO
    $mail = new PHPMailer(true);

    try {
        // Configuración del servidor SMTP 
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = SMTP_USER; 
        $mail->Password   = SMTP_PASS; 
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $mail->Port       = 465;

        $mail->setFrom(SMTP_USER, 'Apadrina un Árbol');
        $mail->addAddress($correo, $nombre);

        $mail->isHTML(true);
        $mail->Subject = 'Verifica tu cuenta - Apadrina un Arbol';
        
        $enlace = "http://localhost/includes/verificar_correos_existentes.php?email=$correo&token=$token";

        $mail->Body = "
            <div style='font-family: sans-serif; border: 1px solid #ddd; padding: 20px;'>
                <h2>¡Hola $nombre!</h2>
                <p>Gracias por registrarte en el proyecto de la UTSC. Para asegurar que este correo es real, por favor confirma tu cuenta haciendo clic en el siguiente botón:</p>
                <a href='$enlace' style='background-color: #5D8736; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; display: inline-block;'>Verificar mi cuenta</a>
                <p>Si no puedes ver el botón, copia y pega este enlace en tu navegador: <br> $enlace </p>
            </div>
        ";

        $mail->send();

        // Redirigir al login informando que debe revisar su correo
        header("Location: ../pages/login.php?status=check_email");

    } catch (Exception $e) {
        // Si el correo falla, notificamos el error técnico
        header("Location: ../pages/registro.php?error=error_envio_correo");
    }

} else {
    // Error genérico de base de datos
    header("Location: ../pages/registro.php?error=error_db");
}

mysqli_close($conexion);
?>