<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css">
    <!-- <link rel="stylesheet" href="css/style.css"> -->
    <title>Apadrina un árbol | Sobre Nosotros</title>
</head>
<body>
    <?php
    include("../includes/header.php");
    ?>
    <nav class="breadcrumbs container">
        <a href="index.php">Inicio</a>
        <span class="separator">/</span>
        <span class="current">Sobre nosotros</span>
    </nav>
    <section class="container-about container">

        <div class="content-box-about">
            <h2>Sobre Nosotros</h2>
            
            <p>
                Somos un equipo apasionado dedicado al rescate y el cuidado de la reforestación y el medio ambiente. 
                Fundada en 2026, nuestra iniciativa busca ser responsable y contribuir activamente a la 
                protección de los ecosistemas, la conciencia ambiental y el desarrollo sostenible.
            <br><br>
                Nos centramos en ser un referente para el futuro, impulsando la educación sobre 
                el cuidado de la naturaleza y ejecutando acciones directas que generen un impacto positivo 
                en nuestro planeta. Únete a nosotros y juntos devolvamos la vida a la Tierra, árbol a árbol.
            </p>
        </div>
            <div class="image-box">
            <img src="../assets/img/about/nosotros-img.jpg" alt="Equipo trabajando en reforestación">
        </div>
    </section>
    
    <section class="mision container">
        <div class="mision-img">
            <img src="../assets/img/about/mision-img.jpg" alt="arbol mision">
        </div>
        <div class="mision-text">
            <h2>Misión y <span> Visión </span></h2>
            <p>
                Nuestra misión se centra en promover la reforestación y el cuidado del medio ambiente mediante el apadrinamiento de árboles,
                canalizando donaciones hacia acciones responsables que contribuyan a la protección de los ecosistemas,
                la conciencia ambiental y el desarrollo sostenible.
                <br><br>
                Mientras que, nuestra visión se centra en Ser una iniciativa reconocida por su compromiso con la reforestación y el cuidado del medio
                ambiente, generando un impacto positivo y duradero en los ecosistemas y fomentando una cultura de responsabilidad ambiental.
            </p>
        </div>
    </section>
    <section class="valores container">
        <div class="valores-text">
            <h2>Valores</h2>
            <ul class="valores-ul">
                <li>
                    <i class="fa-solid fa-leaf"></i>
                    <div class="valor-info">
                        <h4>Responsabilidad ambiental</h4>
                        <p>Actuamos con respeto y compromiso hacia la naturaleza.</p>
                    </div>
                </li>

                <li>
                    <i class="fa-solid fa-handshake"></i>
                    <div class="valor-info">
                        <h4>Compromiso social</h4>
                        <p>Creemos en la participación activa de la comunidad.</p>
                    </div>
                    
                </li>

                <li>
                    <i class="fa-solid fa-coins"></i>
                    <div class="valor-info">
                        <h4>Transparencia</h4>
                        <p>Manejamos las donaciones de forma responsable y clara.</p>
                    </div>
                    
                </li>

                <li>
                    <i class="fa-solid fa-recycle"></i>
                    <div class="valor-info">
                         <h4>Sostenibilidad</h4>
                        <p>Impulsamos acciones que buscan equilibrio entre desarrollo y conservación.</p>
                    </div>
                   
                </li>

                <li>
                    <i class="fa-solid fa-user"></i>
                    <div class="valor-info">
                        <h4>Solidaridad</h4>
                        <p>Apoyamos causas que beneficien al medio ambiente y a futuras generaciones.</p>
                    </div>
                   
                </li>
            </ul>

        </div>
        <div class="valores-img">
            <img src="../assets/img/about/ods-img.png" alt="ODS15">
        </div>
    </section>
   <!-- <section class="team-section container">
    <div class="team-title">
        <h2>Nuestro <span>Equipo</span></h2>
    </div>

    <div class="team-grid">
        <div class="team-card">
            <div class="team-img">
                <img src="../assets/img/about/nosotros-img.jpg" alt="">
            </div>
            <div class="team-info">
                <h3>Lizeth</h3>
                <p>lorem lorem</p>
            </div>
        </div>

        <div class="team-card">
            <div class="team-img">
                <img src="../assets/img/about/nosotros-img.jpg" alt="">
            </div>
            <div class="team-info">
                <h3>Rubi</h3>
                <p>lorem lorem</p>
            </div>
        </div>

        <div class="team-card">
            <div class="team-img">
                <img src="../assets/img/about/nosotros-img.jpg" alt="">
            </div>
            <div class="team-info">
                <h3>Erwin</h3>
                <p>lorem lorem</p>
            </div>
        </div>

        <div class="team-card">
            <div class="team-img">
                <img src="../assets/img/about/nosotros-img.jpg" alt="">
            </div>
            <div class="team-info">
                <h3>Erick</h3>
                <p>Lorem Lorem</p>
            </div>
        </div>

        <div class="team-card">
            <div class="team-img">
                <img src="../assets/img/about/nosotros-img.jpg" alt="">
            </div>
            <div class="team-info">
                <h3>Ochoa</h3>
                <p>Lorem Lorem</p>
            </div>
        </div>
    </div>
</section> -->

    <?php
        include("../includes/footer.php");
    ?>
</body>
</html>