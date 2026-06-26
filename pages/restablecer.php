<?php
// pages/restablecer.php
include '../includes/db.php';

 
$token = isset($_GET['token']) ? $_GET['token'] : '';
$email = isset($_GET['email']) ? $_GET['email'] : '';

$valido = false;

// Verificamos si el token y email coinciden en la BD
if (!empty($token) && !empty($email)) {
    $query = "SELECT id FROM usuarios WHERE email = '$email' AND token = '$token'";
    $resultado = mysqli_query($conexion, $query);

    if (mysqli_num_rows($resultado) > 0) {
        $valido = true; // El enlace es real y seguro
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <title>Restablecer Contraseña | Apadrina un árbol</title>
</head>
<body class="contact-body">

    <?php include("../includes/header.php"); ?>

    <section class="container">
        <div class="contact-container login-container">
            
            <?php if ($valido): ?>
                <div class="f-left">
                    <div class="f-left-title">
                        <h2>Nueva Contraseña</h2>
                        <hr>
                    </div>
                    <p style="font-family: 'Poppins'; color: #666; margin-bottom: 20px;">
                        Token verificado. Por favor, ingresa tu nueva clave de acceso.
                    </p>

                    <form action="../includes/actualizar_password_manual_be.php" method="POST" id="formRestablecer" novalidate>
                        <input type="hidden" name="token" value="<?php echo $token; ?>">
                        <input type="hidden" name="email" value="<?php echo $email; ?>">

                        <div class="input-wrapper">
                            <i class="fa-solid fa-lock f-icon"></i>
                            <input type="password" name="nueva_pass" id="nueva_pass" placeholder="Nueva Contraseña" class="f-inputs" required>
                        </div>

                        <div class="input-wrapper">
                            <i class="fa-solid fa-check-double f-icon"></i>
                            <input type="password" name="confirm_pass" id="confirm_pass" placeholder="Confirmar Nueva Contraseña" class="f-inputs" required>
                        </div>

                        <button type="submit" class="btn-LR">Actualizar Contraseña</button>
                    </form>
                    </div>
            <?php else: ?>
                <div class="f-left">
                    <div class="f-left-title">
                        <h2 style="color: #d33;">Enlace Inválido</h2>
                        <hr>
                    </div>
                    <p style="font-family: 'Poppins'; color: #666;">
                        Lo sentimos, este enlace de recuperación no es válido o ya ha sido utilizado.
                    </p>
                    <a href="login.php" class="btn-LR" style="text-decoration: none; display: inline-block; text-align: center; margin-top: 20px;">Volver al Login</a>
                </div>
            <?php endif; ?>

            <div class="f-right">
                <img src="../assets/img/login-registro/registro.jpg" alt="Restablecer">
            </div>
            
        </div>
    </section>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
<script>
    //MENSAJE de alerta
    function stylesMensagge() {
        const font = Swal.getPopup();
        if (font) font.style.fontFamily = "'Poppins', sans-serif";
    }

    const form = document.getElementById('formRestablecer');
    if(form) {
        form.addEventListener('submit', function(e) {
            const p1 = document.getElementById('nueva_pass').value.trim();
            const p2 = document.getElementById('confirm_pass').value.trim();

            // Expresión regular para 8 caracteres, un número y un símbolo
            const regex = /^(?=.*[0-9])(?=.*[^A-Za-z0-9]).{8,}$/;

            if (!p1 || !p2) {
                e.preventDefault();
                Swal.fire({
                    icon: 'warning',
                    title: 'Campos vacíos',
                    text: 'Por favor, completa ambos campos.',
                    confirmButtonColor: '#5D8736',
                    didOpen: stylesMensagge
                });
            } else if (p1 !== p2) {
                e.preventDefault();
                Swal.fire({
                    icon: 'error',
                    title: 'No coinciden',
                    text: 'Las contraseñas no son iguales.',
                    confirmButtonColor: '#d33',
                    didOpen: stylesMensagge
                });
            } else if (!regex.test(p1)) { // Reemplaza la validación de length < 6
                e.preventDefault();
                Swal.fire({
                    icon: 'warning',
                    title: 'Contraseña no válida',
                    text: 'Debe tener al menos 8 caracteres, incluir un número y un símbolo (-, _, #).',
                    confirmButtonColor: '#5D8736',
                    didOpen: stylesMensagge
                });
            }
        });
    }
</script>

    <?php include("../includes/footer.php"); ?>
</body>
</html>