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
    
    <title>Apadrina un árbol | Iniciar Sesión</title>
    <style>
        .input-wrapper {
            position: relative;
        }
        .pass-input {
            padding-right: 45px !important;
        }
        .pass-input::-ms-reveal,
        .pass-input::-ms-clear {
            display: none;
        }
        .toggle-password {
            position: absolute;
            right: 14px;
            top: 50%;
            transform: translateY(-50%);
            width: auto !important;
            height: auto !important;
            margin: 0 !important;
            padding: 4px !important;
            background: transparent !important;
            border: none !important;
            box-shadow: none !important;
            color: #777 !important;
            font-size: 19px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            user-select: none;
            -webkit-user-select: none;
            touch-action: none;
            z-index: 5;
        }
        .toggle-password:hover {
            color: #4a6733 !important;
        }
    </style>
</head>
<body class="contact-body">
    
    <?php include("../includes/header.php"); ?>
    
    <nav class="breadcrumbs container">
        <a href="../index.php">Inicio</a>
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
                    <input type="password" name="password" id="loginPassword" placeholder="Contraseña" class="f-inputs pass-input" novalidate>
                    <button type="button" class="toggle-password" data-target="loginPassword" title="Mantener presionado para ver" aria-label="Mantener presionado para ver contraseña">
                        <i class="ri-eye-line"></i>
                    </button>
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
    </section>
    
    <?php include("../includes/footer.php"); ?>

    <!-- 1. Cargamos SweetAlert ANTES de nuestro script -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- 2. Lógica de alertas de sesión y ojo de contraseña -->
    <script>
        // Mostrar contraseña solo al mantener presionado
        document.querySelectorAll('.toggle-password').forEach((btn) => {
            const input = document.getElementById(btn.dataset.target);
            const icon = btn.querySelector('i');

            const mostrar = (e) => {
                e.preventDefault();
                input.type = 'text';
                icon.classList.replace('ri-eye-line', 'ri-eye-off-line');
            };

            const ocultar = () => {
                input.type = 'password';
                icon.classList.replace('ri-eye-off-line', 'ri-eye-line');
            };

            btn.addEventListener('pointerdown', mostrar);
            btn.addEventListener('pointerup', ocultar);
            btn.addEventListener('pointerleave', ocultar);
            btn.addEventListener('pointercancel', ocultar);
            btn.addEventListener('blur', ocultar);
            btn.addEventListener('contextmenu', (e) => e.preventDefault());
        });

        const urlParams = new URLSearchParams(window.location.search);
        
        // Alerta de registro exitoso
        if (urlParams.get('registro') === 'ok') {
            Swal.fire({
                title: '¡Cuenta Creada!',
                text: 'Tu registro en la UTSC fue exitoso. Ya puedes iniciar sesión.',
                icon: 'success',
                confirmButtonColor: '#5D8736'
            });
        }

        if (urlParams.get('cambio') === 'ok') {
            Swal.fire({
                title: '¡Contraseña Actualizada!',
                text: 'Ya puedes iniciar sesión con tu nueva contraseña personal.',
                icon: 'success',
                confirmButtonColor: '#5D8736'
            });
        }

        // Leer el tipo exacto de error que manda login_usuario_be.php
        const error = urlParams.get('error');
        
        if (error === 'password_incorrecta' || error === 'auth') {
            Swal.fire({
                title: 'Error de Acceso',
                text: 'La contraseña es incorrecta. Inténtalo de nuevo.',
                icon: 'error',
                confirmButtonColor: '#d33'
            });
        } else if (error === 'usuario_no_existe') {
            Swal.fire({
                title: 'Usuario no encontrado',
                text: 'No existe ninguna cuenta con este correo electrónico.',
                icon: 'warning',
                confirmButtonColor: '#d33'
            });
        } else if (error === 'correo_no_verificado') {
            Swal.fire({
                title: 'Cuenta sin verificar',
                text: 'Por favor verifica tu correo electrónico para poder acceder.',
                icon: 'info',
                confirmButtonColor: '#5D8736'
            });
        }
    </script>

    <!-- 3. Cargamos tu JS local al final -->
    <script src="../assets/js/login.js"></script>

</body>
</html>