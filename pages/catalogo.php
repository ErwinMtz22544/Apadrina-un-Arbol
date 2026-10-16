<?php 
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
if (session_status() === PHP_SESSION_NONE) { session_start(); }
include("../includes/header.php"); 
include("../includes/db.php"); 

$query = new MongoDB\Driver\Query([]);$cursor = $conexion->executeQuery("ArbolBD.arboles", $query);
$resultado =$cursor->toArray();
?>

<nav class="breadcrumbs container">
    <a href="/Apadrina-un-Arbol/index.php">Inicio</a><span class="separator">/</span><span class="current">Catálogo de Árboles</span>
</nav>

<section class="search">
    <div class="search-container">
        <h1>Apadrina tu próximo <span>árbol</span></h1>
        <form id="searchForm" onsubmit="return false;">
            <input type="text" id="searchInput" placeholder="Busca tu árbol...">
            <button type="submit" class="search-button"><i class="ri-search-line"></i></button>
        </form> 
    </div>
</section>

<section class="catalago-section container" style="margin-bottom: 100px;">
    <div class="catalago-grid" id="catalogoGrid">
        <?php 
        if (count($resultado) > 0):
            foreach($resultado as$arbol): 
        ?>
        <div class="catalago-card">
            <div class="catalago-img">
                <img src="<?php echo htmlspecialchars($arbol->imagen); ?>" alt="<?php echo htmlspecialchars($arbol->nombre_comun); ?>">
            </div>
            <div class="catalago-info">
                <h3><?php echo htmlspecialchars($arbol->nombre_cientifico) . " / " . htmlspecialchars($arbol->nombre_comun); ?></h3>
                <div class="descripcion-box"><p><strong>Descripción:</strong> <?php echo htmlspecialchars($arbol->descripcion); ?></p></div>
                <div class="plant-details">
                    <div class="detail-item"><i class="ri-ruler-2-line"></i><span>Altura: <?php echo htmlspecialchars($arbol->altura); ?></span></div>
                    <div class="detail-item"><i class="ri-calendar-event-line"></i><span>Época: <?php echo htmlspecialchars($arbol->epoca); ?></span></div>
                </div>
                <a href="arbol-detalle.php?id=<?php echo $arbol->id_arbol; ?>" class="btn-arbol">Apadrinar este árbol</a>
            </div>
        </div>
        <?php 
            endforeach; 
        else:
            echo "<p class='container'>No se encontraron árboles.</p>";
        endif; 
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
            const response = await fetch(`../includes/buscar_arboles.php?q=${encodeURIComponent(query)}`);
            const data = await response.json();
            catalogoGrid.innerHTML = '';
            if (data.length === 0) {
                catalogoGrid.innerHTML = '<p class="container">No se encontraron resultados para tu búsqueda.</p>';
                return;
            }
            data.forEach(arbol => {
                catalogoGrid.innerHTML += `
                    <div class="catalago-card">
                        <div class="catalago-img"><img src="${arbol.imagen}" alt="${arbol.nombre_comun}"></div>
                        <div class="catalago-info">
                            <h3>${arbol.nombre_cientifico} / ${arbol.nombre_comun}</h3>
                            <div class="descripcion-box"><p><strong>Descripción:</strong> ${arbol.descripcion}</p></div>
                            <div class="plant-details">
                                <div class="detail-item"><i class="ri-ruler-2-line"></i><span>Altura: ${arbol.altura}</span></div>
                                <div class="detail-item"><i class="ri-calendar-event-line"></i><span>Época: ${arbol.epoca}</span></div>
                            </div>
                            <a href="arbol-detalle.php?id=${arbol.id_arbol}" class="btn-arbol">Apadrinar este árbol</a>
                        </div>
                    </div>`;
            });
        } catch (error) { console.error('Error:', error); }
    });
});
</script>
<?php include("../includes/footer.php"); ?>