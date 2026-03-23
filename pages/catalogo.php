<?php 
if (session_status() === PHP_SESSION_NONE) {
    session_start(); 
}
include("../includes/header.php"); 
include("../includes/db.php"); 

$stmt = $conexion->prepare("SELECT id_arbol, nombre_comun, nombre_cientifico, descripcion, imagen, altura, epoca FROM arboles");
$stmt->execute();
$resultado = $stmt->get_result();
?>

<nav class="breadcrumbs container">
    <a href="/Arbol/index.php">Inicio</a>
    <span class="separator">/</span>
    <span class="current">Catálogo de Árboles</span>
</nav>

<section class="search">
    <div class="search-container">
        <h1>Apadrina tu próximo <span>árbol</span></h1>
        <form id="searchForm" onsubmit="return false;">
            <input type="text" id="searchInput" placeholder="Busca tu árbol...">
            <button type="submit" class="search-button">
                <i class="ri-search-line"></i> 
            </button>
        </form> 
    </div>
</section>

<section class="catalago-section container" style="margin-bottom: 100px;">
    <div class="catalago-grid" id="catalogoGrid">
        <?php 
        if ($resultado->num_rows > 0):
            while($arbol = $resultado->fetch_assoc()): 
        ?>
        <div class="catalago-card">
            <div class="catalago-img">
                <img src="../assets/img/arboles/<?php echo htmlspecialchars($arbol['imagen']); ?>" alt="<?php echo htmlspecialchars($arbol['nombre_comun']); ?>">
            </div>
            
            <div class="catalago-info">
                <h3><?php echo htmlspecialchars($arbol['nombre_cientifico']) . " / " . htmlspecialchars($arbol['nombre_comun']); ?></h3>
                
                <div class="descripcion-box">
                    <p><strong>Descripción:</strong> <?php echo htmlspecialchars($arbol['descripcion']); ?></p>
                </div>

                <div class="plant-details">
                    <div class="detail-item">
                        <i class="ri-ruler-2-line"></i>
                        <span>Altura: <?php echo htmlspecialchars($arbol['altura']); ?></span>
                    </div>
                    <div class="detail-item">
                        <i class="ri-calendar-event-line"></i>
                        <span>Época: <?php echo htmlspecialchars($arbol['epoca']); ?></span>
                    </div>
                </div>

                <a href="arbol-detalle.php?id=<?php echo $arbol['id_arbol']; ?>" class="btn-arbol">
                    Apadrinar este árbol
                </a>
            </div>
        </div>
        <?php 
            endwhile; 
        else:
            echo "<p class='container'>No se encontraron árboles.</p>";
        endif; 
        $stmt->close();
        ?>
    </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const searchInput = document.getElementById('searchInput');
    const catalogoGrid = document.getElementById('catalogoGrid');

    searchInput.addEventListener('input', async (e) => {
        const query = e.target.value.trim();

        try {
            // Llamamos al endpoint de búsqueda (debes crear este archivo en includes)
            const response = await fetch(`../includes/buscar_arboles.php?q=${encodeURIComponent(query)}`);
            const data = await response.json();

            // Limpiar el catálogo actual
            catalogoGrid.innerHTML = '';

            if (data.length === 0) {
                catalogoGrid.innerHTML = '<p class="container">No se encontraron resultados para tu búsqueda.</p>';
                return;
            }

            // Renderizar los nuevos resultados
            data.forEach(arbol => {
                catalogoGrid.innerHTML += `
                    <div class="catalago-card">
                        <div class="catalago-img">
                            <img src="../assets/img/arboles/${arbol.imagen}" alt="${arbol.nombre_comun}">
                        </div>
                        <div class="catalago-info">
                            <h3>${arbol.nombre_cientifico} / ${arbol.nombre_comun}</h3>
                            <div class="descripcion-box">
                                <p><strong>Descripción:</strong> ${arbol.descripcion}</p>
                            </div>
                            <div class="plant-details">
                                <div class="detail-item">
                                    <i class="ri-ruler-2-line"></i>
                                    <span>Altura: ${arbol.altura}</span>
                                </div>
                                <div class="detail-item">
                                    <i class="ri-calendar-event-line"></i>
                                    <span>Época: ${arbol.epoca}</span>
                                </div>
                            </div>
                            <a href="arbol-detalle.php?id=${arbol.id_arbol}" class="btn-arbol">
                                Apadrinar este árbol
                            </a>
                        </div>
                    </div>
                `;
            });
        } catch (error) {
            console.error('Error en la búsqueda:', error);
        }
    });
});
</script>

<?php include("../includes/footer.php"); ?>