<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <title>Registrate</title>
</head>
<body>
    <?php 
        include("../includes/header.php");    
    ?>
    <nav class="breadcrumbs container">
        <a href="../index.php">Inicio</a>
        <span class="separator">/</span>
        <span class="current">Registro</span>
    </nav>

    

    <section class="container">
        <div class="contact-container login-container">
            
            <form action="../includes/registro_usuario_be.php" method="POST" id="formularioRegistro" class="f-left" novalidate>
                <div class="f-left-title">
                    <h2>Registrate</h2>
                    <hr>
                </div>

                <div class="input-wrapper">
                    <i class="fa-solid fa-circle-user f-icon"></i>
                    <input type="text" name="nombre_usuario"  placeholder="Nombre" class="f-inputs" novalidate>
                </div>

                <div class="input-wrapper">
                    <i class="fas fa-envelope f-icon"></i>
                    <input type="email"  name="email" id="email" placeholder="Correo electrónico" class="f-inputs" novalidate>
                    <div class="campo-correo">
                        <small id="mensaje-correo" class="mensaje-correo"></small>
                    </div>
                    
                </div>

                <div class="input-wrapper">
                    <i class="fa-solid fa-lock f-icon"></i>
                    <input type="password" name="user-password" id="password" placeholder="Contraseña" class="f-inputs" novalidate>
                    <div class="campo-correo">
                        <small id="mensaje-password" class="mensaje-correo"></small>
                    </div>
                </div>

                <div class="input-wrapper">
                    <i class="fas fa-check-circle f-icon"></i>
                    <input type="password" name="reg_confirm" placeholder="Confirmar contraseña" class="f-inputs" novalidate>
                </div>

                <button type="submit" class="btn-LR">Registrarme</button>
                
                <p class="register-link">¿Ya tienes cuenta? <a href="login.php">Inicia sesión aquí</a></p>
            </form>

            <div class="f-right">
                <img src="../assets/img/login-registro/registro.jpg" alt="Registro">
            </div>
            
        </div>
    </section>
    <script src="../assets/js/validar-correo.js"></script>
      <script src="../assets/js/validar-password.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="../assets/js/registro.js"></script>
    <?php
        include("../includes/footer.php");
    ?>
</body>
</html>