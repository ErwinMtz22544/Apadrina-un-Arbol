<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
   
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css">
    <link rel="stylesheet" href="https://unpkg.com/boxicons@latest/css/boxicons.min.css">
    <!-- <link rel="stylesheet" href="../assets/css/style.css"> -->
    <title>Apadrina un árbol | Contactanos</title>
    <title>Contactanos</title>
</head>
<body class="contact-body">
    <?php
        include("../includes/header.php")
    ?>
    <nav class="breadcrumbs container">
        <a href="/Arbol/index.php">Inicio</a>
        <span class="separator">/</span>
        <span class="current">Contáctanos</span>
    </nav>
    <section class="container">
    <div class="contact-container">
        <!-- <div class="contact-image">
            <img src="img/gato.jpg" alt="Imagen contacto">
        </div> -->

        <form action="https://api.web3forms.com/submit" method="POST" class="f-left">
            <div class="f-left-title">
                <h2>Contáctanos</h2>
                <hr>
            </div>
             <input type="hidden" name="access_key" value="4ed4e34e-e267-4eee-9af0-b0a896c0eee8">
            <input type="text" name="name" placeholder="Ej: Jhon Dear" class="f-inputs" required>
            <input type="email" name="email" placeholder="Ej: jhon@gmail.com" class="f-inputs" required>

            <textarea name="messaje" placeholder="Escribe tu mensaje / queja / sugerencia" class="f-inputs" require></textarea>

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
    <?php
        include("../includes/footer.php");
    ?>
</body>
</html>