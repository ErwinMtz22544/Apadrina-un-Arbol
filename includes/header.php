<?php
// Detectar automáticamente la URL base del proyecto
$base_url = (strpos($_SERVER['REQUEST_URI'], '/Apadrina-un-Arbol') !== false) ? '/Apadrina-un-Arbol' : '';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo $base_url; ?>/assets/css/style.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <title>Apadrina un Árbol | UTSC</title>
</head>
<body>
    <header class="header">
        <nav class="nav container">
            <div class="nav-data">
                <a href="<?php echo $base_url; ?>/index.php" class="nav-logo">
                    <i class="ri-tree-fill"></i> Apadrina un Árbol
                </a>
                <div class="nav-toggle" id="nav-toggle">
                    <i class="ri-menu-line nav-burger"></i>
                    <i class="ri-close-line nav-close"></i>
                </div>
            </div>

            <div class="nav-menu" id="nav-menu">
                <ul class="nav-list">
                    <li><a href="<?php echo $base_url; ?>/index.php" class="nav-link"><i class="ri-home-4-line"></i> Inicio</a></li>
                    <li><a href="<?php echo $base_url; ?>/pages/catalogo.php" class="nav-link"><i class="ri-plant-line"></i> Catálogo</a></li> 

                    <li class="dropdown-item">
                        <div class="nav-link"><i class="ri-user-smile-line"></i> Cuenta <i class="ri-arrow-down-s-line dropdown-arrow"></i></div>
                        <ul class="dropdown-menu">
                            <?php if(isset($_SESSION['usuario'])): ?>
                                <li><a href="<?php echo $base_url; ?>/pages/cuenta-usuario.php" class="dropdown-link"><i class="ri-user-line"></i> Mi Perfil</a></li>
                                
                                <!-- BOTÓN EXCLUSIVO PARA ADMINISTRADORES -->
                                <?php if(isset($_SESSION['rol']) && ($_SESSION['rol'] === 'admin' || $_SESSION['rol'] == 1)): ?>
                                    <li><a href="<?php echo $base_url; ?>/admin/index.php" class="dropdown-link" style="color: #4a6733; font-weight: 600;"><i class="ri-shield-star-line"></i> Panel Admin</a></li>
                                <?php endif; ?>

                                <li><a href="<?php echo $base_url; ?>/includes/cerrar_sesion.php" class="dropdown-link" style="color: #d33;"><i class="ri-logout-box-r-line"></i> Cerrar Sesión</a></li>
                            <?php else: ?>
                                <li><a href="<?php echo $base_url; ?>/pages/login.php" class="dropdown-link"><i class="ri-login-box-line"></i> Iniciar Sesión</a></li>
                                <li><a href="<?php echo $base_url; ?>/pages/registro.php" class="dropdown-link"><i class="ri-user-add-line"></i> Registrarse</a></li>
                            <?php endif; ?>
                        </ul>
                    </li> 
                    <li><a href="<?php echo $base_url; ?>/pages/contactanos.php" class="nav-link"><i class="ri-mail-send-line"></i> Contáctanos</a></li>
                    <li><a href="<?php echo $base_url; ?>/pages/manual.php" class="nav-link"><i class="ri-book-read-line"></i> Manual</a></li>
                    <li><a href="<?php echo $base_url; ?>/pages/foro.php" class="nav-link"><i class="ri-discuss-line"></i> Foro</a></li>
                </ul>
            </div>
        </nav>
    </header>