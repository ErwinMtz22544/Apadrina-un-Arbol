<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/css/style.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
     <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css">
     <link href="https://cdn.jsdelivr.net/npm/remixicon@4.8.0/fonts/remixicon.css" rel="stylesheet"/>
    <title>Apadrina un Árbol | UTSC</title>
</head>
<body>
    <header class="header">
        <nav class="nav container">
            <div class="nav-data">
                <a href="../index.php" class="nav-logo">
                    <i class="ri-tree-fill"></i> Apadrina un Árbol
                </a>
                <div class="nav-toggle" id="nav-toggle">
                    <i class="ri-menu-line nav-burger"></i>
                    <i class="ri-close-line nav-close"></i>
                </div>
            </div>

            <div class="nav-menu" id="nav-menu">
                <ul class="nav-list">
                    <li><a href="/Arbol/index.php" class="nav-link">Inicio</a></li>
                    <li><a href="/Arbol/pages/catalogo.php" class="nav-link">Catálogo</a></li> 
                    <li><a href="/Arbol/pages/noticias.php" class="nav-link">Noticias</a></li>
                    <li class="dropdown-item">
                        <div class="nav-link">Cuenta <i class="ri-arrow-down-s-line dropdown-arrow"></i></div>
                        <ul class="dropdown-menu">
                            <?php if(isset($_SESSION['usuario'])): ?>
                                <li>
                            <!-- ../--><a href="/Arbol/pages/cuenta-usuario.php" class="dropdown-link">
                                    <i class="ri-user-line"></i> Mi Perfil</a>
                                </li>
                            <!-- ../-->  <li><a href="includes/cerrar_sesion.php" class="dropdown-link" style="color: #d33;"><i class="ri-logout-box-r-line"></i> Cerrar Sesión</a></li>
                            <?php else: ?>
                                <li><a href="/Arbol/pages/login.php" class="dropdown-link"><i class="ri-login-box-line"></i> Iniciar Sesión</a></li>
                                <li><a href="/Arbol/pages/registro.php" class="dropdown-link"><i class="ri-user-add-line"></i> Registrarse</a></li>
                            <?php endif; ?>
                        </ul>
                    </li> 
                    <li><a href="/Arbol/pages/contactanos.php" class="nav-link">Contáctanos</a></li> 
                    
                    <li class="nav-weather" style="display: flex; align-items: center; list-style: none;">
                        <div class="weather-widget" id="navClima" style="
                            display: flex; 
                            align-items: center; 
                            gap: 8px; 
                            background: #ffffff; 
                            padding: 6px 14px; 
                            border-radius: 20px; 
                            border: 1px solid #d2e7d2;
                            transition: all 0.3s ease;
                        ">
                            <i id="iconoClima" class="ri-sun-fill" style="font-size: 20px; color: #5D8736; display: flex; align-items: center;"></i>
                            
                            <div class="weather-info" style="display: flex; align-items: center;">
                                <span id="tempNav" style="
                                    font-size: 15px; 
                                    font-weight: 700; 
                                    color: #2e4a18; 
                                    font-family: poppins;
                                ">--°C</span>
                            </div>
                        </div>
                    </li>

                    <script>
                    document.addEventListener("DOMContentLoaded", () => {
                        // Coordenadas por defecto  por si no hay GPS activo
                        const defaultLat = 25.6761;
                        const defaultLon = -100.4643;

                        if (navigator.geolocation) {
                            navigator.geolocation.getCurrentPosition(
                                (position) => {
                                    consultarClima(position.coords.latitude, position.coords.longitude);
                                },
                                () => {
                                    consultarClima(defaultLat, defaultLon);
                                }
                            );
                        } else {
                            consultarClima(defaultLat, defaultLon);
                        }
                    });

                    function consultarClima(lat, lon) {
                        const formData = new FormData();
                        formData.append('lat', lat);
                        formData.append('lon', lon);

                        
                        fetch('clima.php', {
                            method: 'POST',
                            body: formData
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (!data.error) {
                               
                                document.getElementById('tempNav').innerText = `${data.temperatura}°C`;
                                document.getElementById('iconoClima').className = data.icono;
                            }
                        })
                        .catch(error => console.error("Error en el Web Service de clima:", error));
                    }
                    </script>
                    

                </ul>
            </div>
        </nav>
    </header>
    <script src="../assets/js/menu.js"></script>