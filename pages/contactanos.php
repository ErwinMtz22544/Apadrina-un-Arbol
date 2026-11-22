<?php
require_once __DIR__ . '/../includes/session.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css">
    <link rel="stylesheet" href="https://unpkg.com/boxicons@latest/css/boxicons.min.css">
    <title>Apadrina un árbol | Contáctanos</title>
</head>
<body class="contact-body">
    <?php include("../includes/header.php"); ?>

    <nav class="breadcrumbs container">
        <a href="<?php echo $base_url; ?>/index.php">Inicio</a>
        <span class="separator">/</span>
        <span class="current">Contáctanos</span>
    </nav>

    <section class="container">
        <div class="contact-container">
            <form action="<?php echo $base_url; ?>/pages/footer-soporte/procesar_ayuda.php" method="POST" class="f-left">
                <div class="f-left-title">
                    <h2>Contáctanos</h2>
                    <hr>
                </div>

                <!-- Identificadores ocultos para el backend -->
                <input type="hidden" name="origen" value="contactanos">
                <input type="hidden" name="asunto" value="Mensaje desde Contáctanos">

                <input type="text" name="nombre" placeholder="Ej: Jhon Dear" class="f-inputs" required>
                <input type="email" name="email" placeholder="Ej: jhon@gmail.com" class="f-inputs" required>
                <textarea name="mensaje" placeholder="Escribe tu mensaje / queja / sugerencia" class="f-inputs" required></textarea>

                <button type="submit">Enviar</button>
            </form>

            <div class="f-right">
                <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d641.4885301628381!2d-100.51187479484858!3d25.69237066936889!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x86629ecd749f16df%3A0x525aada7e2a78b2c!2sUniversidad%20Tecnol%C3%B3gica%20Santa%20Catarina!5e0!3m2!1ses!2smx!4v1771013951960!5m2!1ses!2smx"
                    style="border:0;"
                    allowfullscreen=""
                    loading="lazy"
                    referrerpolicy="no-referrer-when-downgrade">
                </iframe>
            </div>
        </div>
    </section>

    <script>
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.get('status') === 'enviado') {
            Swal.fire({
                title: '¡Mensaje Enviado!',
                text: 'Gracias por contactarnos. Tu mensaje ya está en nuestro buzón y te responderemos pronto.',
                icon: 'success',
                confirmButtonColor: '#5D8736',
                timer: 4000
            });
            window.history.replaceState({}, document.title, window.location.pathname);
        }
    </script>

    <?php include("../includes/footer.php"); ?>
</body>
</html>