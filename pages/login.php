<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css">
    <link rel="stylesheet" href="https://unpkg.com/boxicons@latest/css/boxicons.min.css">
    <!-- <link rel="stylesheet" href="../assets/css/style.css"> -->
    <title>Apadrina un árbol | Iniciar Sesión</title>
    <title>Contactanos</title>
</head>
<body class="contact-body">
    <?php
        include("../includes/header.php")
    ?>
    <nav class="breadcrumbs container">
        <a href="/Arbol/index.php">Inicio</a>
        <span class="separator">/</span>
        <span class="current">Iniciar Sesión</span>
    </nav>

    <section class="container">
    <div class="contact-container login-container">
        
        <form action="../includes/login_usuario_be.php" method="POST" class="f-left" id="formularioLogin" novalidate>
            <div class="f-left-title">
                <h2>Bienvenido</h2>
                <hr>
            </div>
            <div class="input-wrapper">
                <i class="fas fa-envelope f-icon"></i>
                <input type="email" name="correo" placeholder="Correo electrónico" class="f-inputs" novalidate>
            </div>
            
            <div class="input-wrapper">
                <i class="fa-solid fa-lock f-icon"></i>
                <input type="password" name="password" placeholder="Contraseña" class="f-inputs" novalidate>
            </div>

            <div class="LR-options forgot-pass">
                <a href="olvide_password.php">¿Olvidaste tu contraseña?</a>
            </div>

            <button type="submit" class="btn-LR">Iniciar Sesión</button>
            
            <p class="register-link">¿No tienes cuenta? <a href="registro.php">Regístrate aquí</a></p>
        </form>

        <div class="f-right">
            <img src="../assets/img/login-registro/registro.jpg" alt="Login">
        </div>
        
    </div>

    <script>
    const urlParams = new URLSearchParams(window.location.search);
    
    // Si el registro fue exitoso
    if (urlParams.get('registro') === 'ok') {
        Swal.fire({
            title: '¡Cuenta Creada!',
            text: 'Tu registro en la UTSC fue exitoso. Ya puedes iniciar sesión.',
            icon: 'success',
            confirmButtonColor: '#5D8736'
        });
    }

    // Si hubo un error en el login
    if (urlParams.get('error') === 'auth') {
        Swal.fire({
            title: 'Error de Acceso',
            text: 'El correo o la contraseña son incorrectos. Inténtalo de nuevo.',
            icon: 'error',
            confirmButtonColor: '#d33'
        });
    }
</script>

</section>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script src="/Arbol/assets/js/login.js"></script>
    <?php
        include("../includes/footer.php");
    ?>
    
</body>
</html>