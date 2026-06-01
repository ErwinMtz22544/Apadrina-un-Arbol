<?php
session_start();
include("../includes/db.php");
include("../includes/header.php");

// 1. Validamos que el ID de la noticia exista y sea un número
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id_noticia = intval($_GET['id']);

    // 2. Traemos la información de la BD
    $query = "SELECT titulo, contenido, imagen, fecha_publicacion FROM noticias WHERE id = ? AND estado = 1";
    $stmt = $conexion->prepare($query);
    $stmt->bind_param("i", $id_noticia);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows > 0) {
        $noticia = $resultado->fetch_assoc();
        $date_formatted = date("d/m/y", strtotime($noticia['fecha_publicacion']));
        $image_src = !empty($noticia['imagen']) ? $noticia['imagen'] : '../assets/img/arboles/anacua.jpg';
    } else {
        header("Location: noticias.php");
        exit();
    }
    $stmt->close();
} else {
    header("Location: noticias.php");
    exit();
}
?>

<link rel="stylesheet" href="../assets/css/style.css">

<main class="nt-article-wrapper">
    <div class="container">
    <div class="nt-article-container">
        
        <div class="nt-article-back-nav">
            <a href="noticias.php" class="nt-back-link">
                <i class="fa-solid fa-arrow-left"></i> Ver todos los artículos de noticias
            </a>
        </div>

        <div class="nt-article-hero">
            <img src="<?php echo htmlspecialchars($image_src); ?>" alt="<?php echo htmlspecialchars($noticia['titulo']); ?>">
        </div>

        <div class="nt-article-meta">
            <i class="fa-regular fa-newspaper nt-article-icon"></i>
            <span class="nt-article-date"><?php echo $date_formatted; ?></span>
        </div>

        <h1 class="nt-article-title"><?php echo htmlspecialchars($noticia['titulo']); ?></h1>
        <div class="nt-article-body">
            <?php 
            
            $texto_limpio = strip_tags($noticia['contenido'], '<strong><b>'); 
            
         
            echo nl2br($texto_limpio); 
            ?>
        </div>

    </div>
</div>
</main>

<?php 
include("../includes/footer.php"); 
?>