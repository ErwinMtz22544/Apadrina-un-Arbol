<?php
ob_start();
session_start();
date_default_timezone_set('America/Monterrey');
include("../includes/db.php");

if (!isset($_SESSION['rol']) || ($_SESSION['rol'] !== 'admin' && $_SESSION['rol'] != 1)) {
    header("Location: ../pages/login.php");
    exit();
}

$queryArboles = new MongoDB\Driver\Query([]);
$arboles = $conexion->executeQuery("ArbolBD.arboles", $queryArboles)->toArray();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Catálogo de Árboles | Panel Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap');
        :root { --verde-fuerte: #4a6733; --verde-claro: #5D8736; --bg-color: #f4f7f6; }
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Poppins', sans-serif; }
        body { display: flex; min-height: 100vh; background-color: var(--bg-color); }
        
        .sidebar { width: 260px; background-color: #fff; border-right: 1px solid #e0e0e0; display: flex; flex-direction: column; position: fixed; height: 100vh; z-index: 100; }
        .sidebar-header { padding: 25px 20px; border-bottom: 1px solid #eee; display: flex; align-items: center; gap: 10px; color: var(--verde-fuerte); font-weight: 700; font-size: 20px; }
        .sidebar-menu { padding: 20px 0; flex-grow: 1; }
        .menu-item { padding: 15px 25px; display: flex; align-items: center; gap: 12px; color: #555; text-decoration: none; font-weight: 500; transition: 0.2s; }
        .menu-item:hover, .menu-item.active { background-color: #f0fdf4; color: var(--verde-fuerte); border-right: 4px solid var(--verde-fuerte); }
        .menu-item i { font-size: 20px; }
        .sidebar-footer { padding: 20px; border-top: 1px solid #eee; }
        .btn-logout { display: flex; align-items: center; gap: 10px; color: #d32f2f; text-decoration: none; font-weight: 600; }
        
        .main-content { margin-left: 260px; padding: 40px; flex-grow: 1; width: calc(100% - 260px); }
        .top-bar { display: flex; justify-content: space-between; align-items: center; margin-bottom: 40px; }
        .top-bar h1 { color: #222; font-size: 28px; }
        
        .btn-nuevo { background-color: var(--verde-fuerte); color: white; border: none; padding: 12px 24px; border-radius: 8px; font-weight: 600; cursor: pointer; display: flex; align-items: center; gap: 8px; font-size: 15px; transition: 0.3s; }
        .btn-nuevo:hover { background-color: #3a5228; transform: translateY(-2px); }

        .table-container { background: #fff; border-radius: 16px; box-shadow: 0 4px 15px rgba(0,0,0,0.03); overflow: hidden; overflow-x: auto; }
        table { width: 100%; border-collapse: collapse; text-align: left; }
        th { background-color: #fafafa; padding: 18px 25px; color: #555; font-size: 13px; text-transform: uppercase; font-weight: 700; border-bottom: 2px solid #eee; }
        td { padding: 15px 25px; border-bottom: 1px solid #eee; color: #444; font-size: 14px; vertical-align: middle; }
        tr:hover { background-color: #fcfcfc; }
        
        .img-arbol { width: 50px; height: 50px; border-radius: 8px; object-fit: cover; background: #eee; border: 1px solid #ddd; }
        
        .btn-icon { background: none; border: none; padding: 8px; cursor: pointer; font-size: 18px; border-radius: 6px; transition: 0.2s; margin-right: 5px; }
        .btn-edit { color: #0288d1; background: #e1f5fe; }
        .btn-edit:hover { background: #b3e5fc; }
        .btn-delete { color: #c62828; background: #ffebee; }
        .btn-delete:hover { background: #ffcdd2; }

        .modal-overlay { position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); display: none; justify-content: center; align-items: center; z-index: 1000; opacity: 0; transition: opacity 0.3s; }
        .modal-overlay.active { display: flex; opacity: 1; }
        .modal-content { background: white; width: 100%; max-width: 650px; border-radius: 16px; padding: 30px; transform: translateY(-20px); transition: transform 0.3s; max-height: 90vh; overflow-y: auto; }
        .modal-overlay.active .modal-content { transform: translateY(0); }
        .modal-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px; }
        .modal-header h2 { font-size: 22px; color: #222; }
        .btn-close { background: none; border: none; font-size: 24px; color: #888; cursor: pointer; }
        
        .form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
        .form-group { margin-bottom: 15px; }
        .form-group.full-width { grid-column: span 2; }
        .form-group label { display: block; margin-bottom: 8px; font-weight: 600; color: #555; font-size: 13px; text-transform: uppercase; }
        .form-group input, .form-group textarea { width: 100%; padding: 12px 15px; border: 1px solid #ddd; border-radius: 8px; font-family: 'Poppins'; outline: none; transition: 0.3s; }
        .form-group input:focus, .form-group textarea:focus { border-color: var(--verde-claro); box-shadow: 0 0 0 3px rgba(93,135,54,0.1); }
        
        .btn-submit { width: 100%; background: var(--verde-fuerte); color: white; border: none; padding: 14px; border-radius: 8px; font-weight: 600; font-size: 16px; cursor: pointer; margin-top: 10px; transition: 0.3s; }
        .btn-submit:hover { background: #3a5228; }
    </style>
</head>
<body>

    <aside class="sidebar">
        <div class="sidebar-header"><i class="ri-tree-fill"></i> Panel Admin</div>
        <nav class="sidebar-menu">
            <a href="index.php" class="menu-item"><i class="ri-dashboard-line"></i> Dashboard</a>
            <a href="gestion_usuarios.php" class="menu-item"><i class="ri-group-line"></i> Usuarios</a>
            <a href="gestion_catalogo.php" class="menu-item active"><i class="ri-plant-line"></i> Catálogo de Árboles</a>
            <a href="buzon_mensajes.php" class="menu-item"><i class="ri-mail-unread-line"></i> Buzón de Soporte</a>
            <a href="moderacion_foro.php" class="menu-item"><i class="ri-shield-check-line"></i> Moderación Foro</a>
        </nav>
<div class="sidebar-footer">
            <a href="../index.php" class="btn-logout" style="color: var(--verde-fuerte); margin-bottom: 15px; text-decoration: none; font-weight: 600; display: flex; align-items: center; gap: 10px;">
                <i class="ri-home-smile-line" style="font-size: 20px;"></i> Ir a la página web
            </a>
            
            <a href="../includes/cerrar_sesion.php" class="btn-logout">
                <i class="ri-logout-box-r-line"></i> Cerrar Sesión
            </a>
        </div>
    </aside>

    <main class="main-content">
        <div class="top-bar">
            <div>
                <h1>Catálogo de Árboles</h1>
                <p style="color: #666;">Gestión de especies disponibles para apadrinamiento.</p>
            </div>
            <button class="btn-nuevo" onclick="abrirModalCrear()"><i class="ri-add-line"></i> Registrar Especie</button>
        </div>

        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Imagen</th>
                        <th>Nombre / Científico</th>
                        <th>Altura</th>
                        <th>Época</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($arboles) > 0): ?>
                        <?php foreach ($arboles as $arbol): ?>
                        <tr>
                            <td>
                                <?php $img = $arbol->imagen ?? '../assets/img/default-tree.jpg'; ?>
                                <img src="<?php echo htmlspecialchars($img); ?>" class="img-arbol" alt="Árbol">
                            </td>
                            <td>
                                <strong style="color: #222; display: block; font-size: 15px;"><?php echo htmlspecialchars($arbol->nombre_comun ?? 'N/A'); ?></strong>
                                <span style="font-size: 12px; color: #777;"><i><?php echo htmlspecialchars($arbol->nombre_cientifico ?? '-'); ?></i></span>
                            </td>
                            <td style="font-weight: 500; color: #555;"><i class="ri-ruler-line"></i> <?php echo htmlspecialchars($arbol->altura ?? 'N/A'); ?></td>
                            <td style="font-weight: 600; color: var(--verde-fuerte);"><i class="ri-sun-line"></i> <?php echo htmlspecialchars($arbol->epoca ?? 'N/A'); ?></td>
                            <td style="white-space: nowrap;">
                                
                                <!-- BOTÓN DE EDITAR ACTUALIZADO CON DATOS -->
                                <button type="button" class="btn-icon btn-edit" title="Editar especie"
                                    data-id="<?php echo (string)$arbol->_id; ?>"
                                    data-comun="<?php echo htmlspecialchars($arbol->nombre_comun ?? ''); ?>"
                                    data-cientifico="<?php echo htmlspecialchars($arbol->nombre_cientifico ?? ''); ?>"
                                    data-altura="<?php echo htmlspecialchars($arbol->altura ?? ''); ?>"
                                    data-epoca="<?php echo htmlspecialchars($arbol->epoca ?? ''); ?>"
                                    data-imagen="<?php echo htmlspecialchars($arbol->imagen ?? ''); ?>"
                                    data-desc="<?php echo htmlspecialchars($arbol->descripcion ?? ''); ?>"
                                    data-desclarga="<?php echo htmlspecialchars($arbol->descripcion_larga ?? ''); ?>"
                                    data-datos="<?php echo htmlspecialchars($arbol->datos_interesantes ?? ''); ?>"
                                    onclick="abrirModalEditar(this)">
                                    <i class="ri-pencil-line"></i>
                                </button>

                                <form action="procesar_catalogo.php" method="POST" style="display:inline-block;" onsubmit="return confirm('¿Seguro que quieres borrar este árbol del catálogo?');">
                                    <input type="hidden" name="accion" value="borrar">
                                    <input type="hidden" name="id_arbol" value="<?php echo (string)$arbol->_id; ?>">
                                    <button type="submit" class="btn-icon btn-delete" title="Eliminar del catálogo"><i class="ri-delete-bin-line"></i></button>
                                </form>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" style="text-align: center; padding: 40px; color: #888;">No hay árboles registrados en el catálogo.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </main>

    <!-- Modal para Agregar/Editar Árbol -->
    <div class="modal-overlay" id="modalArbol">
        <div class="modal-content">
            <div class="modal-header">
                <h2 id="modalTitle">Registrar Nueva Especie</h2>
                <button class="btn-close" onclick="cerrarModal()"><i class="ri-close-line"></i></button>
            </div>
            
            <form action="procesar_catalogo.php" method="POST" id="formCatalogo">
                <!-- Campos ocultos para saber qué hacer en el backend -->
                <input type="hidden" name="accion" id="formAccion" value="crear">
                <input type="hidden" name="id_mongo" id="formIdMongo" value="">

                <div class="form-grid">
                    <div class="form-group">
                        <label>Nombre Común</label>
                        <input type="text" name="nombre_comun" id="inpComun" placeholder="Ej. Ébano" required>
                    </div>
                    <div class="form-group">
                        <label>Nombre Científico</label>
                        <input type="text" name="nombre_cientifico" id="inpCientifico" placeholder="Ej. Ebenopsis ebano" required>
                    </div>
                    <div class="form-group">
                        <label>Altura Estimada</label>
                        <input type="text" name="altura" id="inpAltura" placeholder="Ej. 6m - 9m" required>
                    </div>
                    <div class="form-group">
                        <label>Época de Floración</label>
                        <input type="text" name="epoca" id="inpEpoca" placeholder="Ej. Primavera" required>
                    </div>
                    <div class="form-group full-width">
                        <label>Enlace de Imagen (Cloudinary u otro URL)</label>
                        <input type="url" name="imagen" id="inpImagen" placeholder="https://res.cloudinary.com/..." required>
                    </div>
                    <div class="form-group full-width">
                        <label>Descripción Corta</label>
                        <textarea name="descripcion" id="inpDesc" rows="2" placeholder="Resumen breve del árbol..." required></textarea>
                    </div>
                    <div class="form-group full-width">
                        <label>Descripción Larga</label>
                        <textarea name="descripcion_larga" id="inpDescLarga" rows="3" placeholder="Información detallada sobre beneficios al ecosistema..." required></textarea>
                    </div>
                    <div class="form-group full-width">
                        <label>Datos Interesantes</label>
                        <textarea name="datos_interesantes" id="inpDatos" rows="2" placeholder="Curiosidad sobre el árbol..." required></textarea>
                    </div>
                </div>

                <button type="submit" class="btn-submit" id="btnSubmitForm"><i class="ri-save-line"></i> Guardar Especie</button>
            </form>
        </div>
    </div>

    <script>
        const modal = document.getElementById('modalArbol');
        
        function abrirModalCrear() { 
            document.getElementById('modalTitle').innerText = 'Registrar Nueva Especie';
            document.getElementById('formAccion').value = 'crear';
            document.getElementById('formIdMongo').value = '';
            document.getElementById('btnSubmitForm').innerHTML = '<i class="ri-save-line"></i> Guardar Especie en Catálogo';
            document.getElementById('formCatalogo').reset(); // Limpia todo
            modal.classList.add('active'); 
        }

        function abrirModalEditar(btn) {
            document.getElementById('modalTitle').innerText = 'Editar Especie';
            document.getElementById('formAccion').value = 'editar';
            document.getElementById('formIdMongo').value = btn.getAttribute('data-id');
            document.getElementById('btnSubmitForm').innerHTML = '<i class="ri-refresh-line"></i> Actualizar Árbol';

            // Rellenar formulario con los datos del botón
            document.getElementById('inpComun').value = btn.getAttribute('data-comun');
            document.getElementById('inpCientifico').value = btn.getAttribute('data-cientifico');
            document.getElementById('inpAltura').value = btn.getAttribute('data-altura');
            document.getElementById('inpEpoca').value = btn.getAttribute('data-epoca');
            document.getElementById('inpImagen').value = btn.getAttribute('data-imagen');
            document.getElementById('inpDesc').value = btn.getAttribute('data-desc');
            document.getElementById('inpDescLarga').value = btn.getAttribute('data-desclarga');
            document.getElementById('inpDatos').value = btn.getAttribute('data-datos');

            modal.classList.add('active'); 
        }

        function cerrarModal() { modal.classList.remove('active'); }
        modal.addEventListener('click', function(e) { if (e.target === modal) cerrarModal(); });
    </script>
</body>
</html>
<?php ob_end_flush(); ?>