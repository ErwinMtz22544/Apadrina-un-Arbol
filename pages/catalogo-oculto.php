<?php 
if (session_status() === PHP_SESSION_NONE) {
    session_start(); 
}
// Seguridad: Si no es admin, fuera
if (!isset($_SESSION['id_rol']) || $_SESSION['id_rol'] != 1) {
    header("Location: catalogo.php");
    exit();
}

include("../includes/header.php"); 
include("../includes/db.php"); 

// CONSULTA: Solo los que NO están disponibles (disponible = 0)
$stmt = $conexion->prepare("SELECT id_arbol, nombre_comun, nombre_cientifico, descripcion, imagen, altura, epoca FROM arboles WHERE disponible = 0");
$stmt->execute();
$resultado = $stmt->get_result();
?>

<nav class="breadcrumbs container" style="margin-top: 20px;">
    <a href="../index.php">Inicio</a>
    <span class="separator">/</span>
    <a href="catalogo.php">Catálogo de Árboles</a>
    <span class="separator">/</span>
    <span class="current">Árboles Ocultos</span>
</nav>

<div class="container" style="margin-top: 30px;">
    <a href="catalogo.php" style="text-decoration: none; color: #5D8736; font-weight: 500;"> 
        <i class="ri-arrow-left-line"></i> Volver al catálogo principal
    </a>
    
    <h1 style="margin-top: 20px;">Árboles <span>Ocultos</span></h1>
    <p style="color: #666;">Estos árboles han sido retirados del catálogo público pero siguen asociados a sus padrinos.</p>
</div>

<section class="catalago-section container" style="margin-bottom: 100px; margin-top: 40px;">
    <div class="catalago-grid">
        <?php 
        if ($resultado->num_rows > 0):
            while($arbol = $resultado->fetch_assoc()): 
        ?>
        <div class="catalago-card" style="opacity: 0.85; border: 1px dashed #ccc;"> 
            <div class="catalago-img">
                <img src="../assets/img/arboles/<?php echo htmlspecialchars($arbol['imagen']); ?>" alt="Árbol">
            </div>
            <div class="catalago-info">
                <span style="font-size: 12px; color: #f44336; font-weight: bold;">ESTADO: OCULTO</span>
                <h3 style="margin-top: 5px;"><?php echo htmlspecialchars($arbol['nombre_comun']); ?></h3>
                
                <a href="../includes/activar_arbol_be.php?id=<?php echo $arbol['id_arbol']; ?>" 
                   class="btn-arbol" style="background-color: #2196F3; text-align: center; margin-top: 15px; display: block;">
                    <i class="ri-eye-line"></i> Volver a mostrar
                </a>
            </div>
        </div>
        <?php 
            endwhile; 
        else:
            echo "<div class='container' style='grid-column: 1/-1; text-align: center; padding: 50px;'>
                    <i class='ri-information-line' style='font-size: 40px; color: #ccc;'></i>
                    <p style='color: #888;'>No hay árboles ocultos en este momento.</p>
                  </div>";
        endif; 
        $stmt->close();
        ?>
    </div>
</section>

<?php include("../includes/footer.php"); ?>