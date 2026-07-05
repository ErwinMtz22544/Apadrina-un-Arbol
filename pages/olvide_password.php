<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css">
    <title>Recuperar Contraseña</title>
</head>
<body class="contact-body">
    <?php include("../includes/header.php"); ?>
        <nav class="breadcrumbs container">
        <a href="/index.php">Inicio</a>
        <span class="separator">/</span>
        <span class="current">Iniciar Sesión</span>
    </nav>
    <section class="container">
        <div class="contact-container login-container">
            <form action="../includes/recuperar_be.php" method="POST" class="f-left" id="formRecuperar">
                <div class="f-left-title">
                    <h2>Recuperar</h2>
                    <hr>
                </div>
                <p style="margin-bottom: 20px; color: #666; font-family: 'Poppins';">
                    Ingresa tu correo y te enviaremos un enlace seguro.
                </p>
                <div class="input-wrapper">
                    <i class="fas fa-envelope f-icon"></i>
                    <input type="email" name="email" placeholder="Correo electrónico" class="f-inputs" required>
                </div>

                <button type="submit" class="btn-LR">Enviar Enlace</button>
                <p class="register-link"><a href="login.php">Volver al inicio</a></p>
            </form>
            <div class="f-right">
                <img src="../assets/img/login-registro/registro.jpg" alt="Recuperar">
            </div>
        </div>
    </section>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="/assets/js/login.js"></script>
</body>
</html>