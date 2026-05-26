<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <title>Inicio</title>
</head>
<body>
    <?php
    include('includes/header.php');
    ?>
    <!--Home-->
    <section class="home container" id="home">
        <div class="home-text">
            <h1>Apadrina un <span>árbol</span> y deja huella en el planeta </h1>
            <h3>Con una pequeña acción hoy, cultivas un futuro más verde para todos. 
                Tu apoyo ayuda directamente a la restauración de ecosistemas y al equilibrio de nuestro planeta.
            </h3>
            <a href="pages/catalogo.php" class="btn">Más información</a>
        </div>
        <div class="home-img">
            <img src="assets/img/home/arbol-home.png" alt="arbol">
        </div>
    </section>
    <!--que hacemos-->
    <section class="about container" id="about">
        <div class="about-img">
            <img src="assets/img/home/about-img.jpg" alt="arbol">
        </div>
        <div class="about-text">
            <h2>¿Qué hacemos?</h2>
            <p>
                Vivimos en un mundo donde la deforestación, la contaminación y el cambio climático afectan directamente nuestra calidad de vida. 
                Por ello, a través de este proyecto trabajamos para crear conciencia ambiental y ofrecer a las personas la oportunidad de ser 
                parte activa del cambio.
                <br><br>
                Apadrinar un árbol es una forma sencilla y significativa de contribuir al cuidado del medio ambiente. Cada donación que recibimos
                se transforma en acciones concretas para sembrar vida y construir un futuro más sostenible.
                <br><br>
                A través de las donaciones, impulsamos iniciativas enfocadas en la siembra, cuidado y protección de árboles, así como en la mejora
                de áreas verdes y la concientización ambiental dentro de la comunidad. Nuestro objetivo es asegurar que cada aportación tenga un 
                impacto real, positivo y duradero en el medio ambiente.
                            
            </p>
            <a href="pages/about.php" class="btn">Más información</a>
        </div>
    </section>
    <!--Cuidados-->
<section class="services">
    <div class="container">

    
    <h2 style="text-align: center; color: var(--black-color);">Nuestros Pilares</h2>
    <p style="text-align: center; color: var(--gray-color); margin-bottom: 40px;">Uniendo a la comunidad de la UTSC por un futuro más verde.</p>

    <div class="services-content">
        <div class="services-txt">
            <i class="ri-seedling-line" style="font-size: 50px; color: var(--green-color);"></i>
            <h3>Reforestación Activa</h3>
            <p>Transformamos los espacios de nuestra universidad plantando especies nativas que ayudan a regular la temperatura y mejorar la calidad del aire en el campus.</p>
        </div>

        <div class="services-txt">
            <i class="ri-heart-pulse-line" style="font-size: 50px; color: var(--green-color);"></i>
            <h3>Vínculo Vital</h3>
            <p>Al apadrinar, no solo plantas un árbol; te conviertes en su guardián. Recibes un certificado digital y puedes seguir el crecimiento de tu ejemplar desde tu perfil.</p>
        </div>

        <div class="services-txt">
            <i class="ri-microscope-line" style="font-size: 50px; color: var(--green-color);"></i>
            <h3>Ciencia y Cuidado</h3>
            <p>Integramos el mantenimiento de las áreas verdes con la formación académica, permitiendo que alumnos de todas las carreras participen en el monitoreo ambiental.</p>
        </div>
    </div>
</div>
</section>
<!-- Sección de Noticias Destacadas -->
 <?php
 include("includes/db.php");
 ?>
<section class="news-section">
    <div class="container">
        
        <div class="news-header">
            <h2 class="news-title">Nuestras Noticias Arboleras</h2>
        </div>

        <div class="news-grid">
            
            <?php 
            // Consulta para traer solo las noticias activas (estado=1) que elegiste mostrar en el inicio (destacado=1)
            $query_news = "SELECT id, titulo, imagen, fecha_publicacion FROM noticias WHERE estado = 1 AND destacado = 1 ORDER BY fecha_publicacion DESC LIMIT 3";
            $stmt_news = $conexion->prepare($query_news);
            $stmt_news->execute();
            $result_news = $stmt_news->get_result();

            // Si hay noticias destacadas, las mostramos
            if ($result_news->num_rows > 0): 
                while ($row = $result_news->fetch_assoc()): 
                    
                
                    $date_formatted = date("d M, Y", strtotime($row['fecha_publicacion']));
                    
                    // Imagen por defecto si el campo está vacío en la BD
                 
                    $image_src = !empty($row['imagen']) ? $row['imagen'] : 'assets/img/arboles/anacua.jpg';
                    
                    
                    if (substr($image_src, 0, 3) === '../') {
                        $image_src = substr($image_src, 3);
                    }
            ?>
                    <article class="news-card">
                        <div class="news-thumb">
                            <img src="<?php echo htmlspecialchars($image_src); ?>" alt="<?php echo htmlspecialchars($row['titulo']); ?>">
                        </div>
                        <div class="news-content">
                            <div class="news-date-container">
                                <i class="fa-solid fa-calendar-day news-date-icon"></i>
                                <span class="news-date"><?php echo $date_formatted; ?></span>
                            </div>
                            <h3 class="news-card-title"><?php echo htmlspecialchars($row['titulo']); ?></h3>
                            
                            <a href="pages/noticia.php?id=<?php echo $row['id']; ?>" class="news-link">Leer más</a>
                        </div>
                    </article>
            <?php 
                endwhile; 
            else: 
            ?>
                <p style="grid-column: 1 / -1; text-align: center; color: var(--gray-color); padding: 40px 0;">
                    Próximamente más noticias destacadas.
                </p>
            <?php 
            endif; 
            $stmt_news->close(); 
            ?>

        </div>

        <div class="news-actions">
            <a href="/arbolito-lix/pages/noticias.php" class="btn-view-all">Ver todas las noticias</a>
        </div>

    </div>
</section>

<!--FOOTEEER-->
    <?php
        include("includes/footer.php")
    ?>
</body>
</html>