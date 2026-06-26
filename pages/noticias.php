<?php
session_start(); 
include("../includes/db.php"); 
include("../includes/header.php"); 
?>
    <nav class="breadcrumbs container">
        <a href="/Arbol/index.php">Inicio</a>
        <span class="separator">/</span>
        <span class="current">Noticias Árboleras</span>
    </nav>
<main class="news-page-main">
    <div class="container">
        
        <div class="news-header">
            <h1 class="news-page-title">
                Nuestras Noticias <span> Arboleras</span>
            </h1>
        </div>

        <div class="news-grid">
            <?php
            // Consultamos solo los campos compactos que definiste (Estado = 1 para activas)
            $query_all_news = "SELECT id, titulo, imagen, fecha_publicacion FROM noticias WHERE estado = 1 ORDER BY fecha_publicacion DESC";
            $stmt_all = $conexion->prepare($query_all_news);
            $stmt_all->execute();
            $result_all = $stmt_all->get_result();

            if ($result_all->num_rows > 0):
                while ($row = $result_all->fetch_assoc()):
                    
                    // Formatea la fecha automática (Ej: 18 May, 2026)
                    $date_formatted = date("d M, Y", strtotime($row['fecha_publicacion']));
                    
                    // Imagen por defecto si el campo en la BD está vacío
                    $image_src = !empty($row['imagen']) ? $row['imagen'] : '../assets/img/arboles/anacua.jpg';
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
                            
                            <a href="noticia_contenido.php?id=<?php echo $row['id']; ?>" class="news-link">Leer más</a>
                        </div>
                    </article>
            <?php
                endwhile;
            else:
            ?>
                <p class="news-empty">Por el momento no hay noticias publicadas. ¡Vuelve pronto!</p>
            <?php
            endif;
            $stmt_all->close();
            ?>
        </div>

    </div>
</main>

<?php 
include("../includes/footer.php"); 
?>