<?php 
if (session_status() === PHP_SESSION_NONE) {
    session_start(); 
}
include("../includes/header.php"); 
include("../includes/db.php"); 


// --- TU LÓGICA DE CONSULTA ---
// Volvemos a la consulta original para que se vean todos los árboles
$stmt = $conexion->prepare("SELECT id_arbol, nombre_comun, nombre_cientifico, descripcion, imagen, altura, epoca FROM arboles WHERE disponible = 1");
$stmt->execute();
$resultado = $stmt->get_result();
?>

<nav class="breadcrumbs container">
    <a href="../index.php">Inicio</a>
    <span class="separator">/</span>
    <span class="current">Catálogo de Árboles</span>
</nav>
<?php if(isset($_SESSION['id_rol']) && $_SESSION['id_rol'] == 1): ?>
    <div class="admin-actions container" style="margin-top: 20px; display: flex; gap: 10px; justify-content: flex-end;">

        <a href="catalogo-oculto.php" class="btn-arbol" style="background-color: #6c757d; width: auto; padding: 10px 15px; text-decoration: none; border-radius: 8px; color: white; font-size: 14px;">
            <i class="ri-eye-off-line"></i> Ver Ocultos
        </a>
    </div>
<?php endif; ?>
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

    <?php if(isset($_SESSION['id_rol']) && $_SESSION['id_rol'] == 1): ?>
    <div class="admin-controls" style="display: flex; gap: 10px; margin-top: 15px; border-top: 1px solid #eee; padding-top: 10px;">
		<a href="#" onclick="event.preventDefault(); quitarCatalogo(<?php echo $arbol['id_arbol']; ?>)" 
   style="color: #f44336; font-size: 14px; text-decoration: none; cursor: pointer;">
    <i class="ri-eye-off-line"></i> Ocultar del Catálogo
</a>
    </div>
<?php endif; ?>
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
// Función global para eliminar con SweetAlert2
function eliminarArbol(id) {
    Swal.fire({
        title: '¿Eliminar árbol?',
        text: "Esta acción es irreversible.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#5D8736',
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            // Redirige al archivo que tú crees para borrar
            window.location.href = `../includes/eliminar_arbol_be.php?id=${id}`;
        }
    });
}

// --- TU LÓGICA DE BÚSQUEDA ---
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
                catalogoGrid.innerHTML = '<p class="container">No hay resultados.</p>';
                return;
            }

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
            console.error('Error:', error);
        }
    });
});
    function quitarCatalogo(id) {
    Swal.fire({
        title: '¿Ocultar este árbol?',
        text: "Ya no aparecerá en el catálogo para nuevos usuarios, pero se mantendrá para quienes ya lo apadrinaron.",
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#5D8736',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sí, ocultar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            // Aquí es donde mandamos al archivo de backend
            window.location.href = `../includes/ocultar_arbol_be.php?id=${id}`;
        }
    });
}
</script>

<?php include("../includes/footer.php"); ?>