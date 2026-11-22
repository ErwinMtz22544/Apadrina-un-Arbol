<?php
ob_start();
session_start();
date_default_timezone_set('America/Monterrey');
include("../includes/db.php");

// Verificación de seguridad
if (!isset($_SESSION['rol']) || ($_SESSION['rol'] !== 'admin' && $_SESSION['rol'] != 1)) {
    header("Location: ../pages/login.php");
    exit();
}

// Consultar la colección del foro (ordenados del más reciente al más antiguo)
// Si tu campo de fecha se llama diferente, avísame para ajustarlo
$opciones = ['sort' => ['fecha' => -1]]; 
$queryForo = new MongoDB\Driver\Query([], $opciones);
$posts = $conexion->executeQuery("ArbolBD.foro", $queryForo)->toArray();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Moderación del Foro | Panel Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap');
        :root { --verde-fuerte: #4a6733; --verde-claro: #5D8736; --bg-color: #f4f7f6; }
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Poppins', sans-serif; }
        body { display: flex; min-height: 100vh; background-color: var(--bg-color); }
        
        /* Sidebar Navigation */
        .sidebar { width: 260px; background-color: #fff; border-right: 1px solid #e0e0e0; display: flex; flex-direction: column; position: fixed; height: 100vh; z-index: 100; }
        .sidebar-header { padding: 25px 20px; border-bottom: 1px solid #eee; display: flex; align-items: center; gap: 10px; color: var(--verde-fuerte); font-weight: 700; font-size: 20px; }
        .sidebar-menu { padding: 20px 0; flex-grow: 1; }
        .menu-item { padding: 15px 25px; display: flex; align-items: center; gap: 12px; color: #555; text-decoration: none; font-weight: 500; transition: 0.2s; }
        .menu-item:hover, .menu-item.active { background-color: #f0fdf4; color: var(--verde-fuerte); border-right: 4px solid var(--verde-fuerte); }
        .menu-item i { font-size: 20px; }
        .sidebar-footer { padding: 20px; border-top: 1px solid #eee; }
        .btn-logout { display: flex; align-items: center; gap: 10px; color: #d32f2f; text-decoration: none; font-weight: 600; }
        
        /* Contenido Principal */
        .main-content { margin-left: 260px; padding: 40px; flex-grow: 1; width: calc(100% - 260px); }
        .top-bar { display: flex; justify-content: space-between; align-items: center; margin-bottom: 40px; }
        .top-bar h1 { color: #222; font-size: 28px; }

        /* Tabla de Publicaciones */
        .table-container { background: #fff; border-radius: 16px; box-shadow: 0 4px 15px rgba(0,0,0,0.03); overflow: hidden; overflow-x: auto; }
        table { width: 100%; border-collapse: collapse; text-align: left; }
        th { background-color: #fafafa; padding: 18px 25px; color: #555; font-size: 13px; text-transform: uppercase; font-weight: 700; border-bottom: 2px solid #eee; }
        td { padding: 18px 25px; border-bottom: 1px solid #eee; color: #444; font-size: 14px; vertical-align: middle; }
        tr:hover { background-color: #fcfcfc; }
        
        .btn-icon { background: none; border: none; padding: 8px; cursor: pointer; font-size: 18px; border-radius: 6px; transition: 0.2s; }
        .btn-delete { color: #c62828; background: #ffebee; }
        .btn-delete:hover { background: #ffcdd2; transform: scale(1.05); }
        
        .badge-foro { background-color: #e8eaf6; color: #3f51b5; padding: 4px 10px; border-radius: 6px; font-size: 11px; font-weight: 700; text-transform: uppercase; border: 1px solid #c5cae9;}
    </style>
</head>
<body>

    <aside class="sidebar">
        <div class="sidebar-header"><i class="ri-tree-fill"></i> Panel Admin</div>
        <nav class="sidebar-menu">
            <a href="index.php" class="menu-item"><i class="ri-dashboard-line"></i> Dashboard</a>
            <a href="gestion_usuarios.php" class="menu-item"><i class="ri-group-line"></i> Usuarios</a>
            <a href="gestion_catalogo.php" class="menu-item"><i class="ri-plant-line"></i> Catálogo de Árboles</a>
            <a href="buzon_mensajes.php" class="menu-item"><i class="ri-mail-unread-line"></i> Buzón de Soporte</a>
            <a href="moderacion_foro.php" class="menu-item active"><i class="ri-shield-check-line"></i> Moderación Foro</a>
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
                <h1>Moderación del Foro</h1>
                <p style="color: #666;">Monitorea y elimina publicaciones que incumplan las reglas de la comunidad.</p>
            </div>
        </div>

        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Fecha</th>
                        <th>Autor</th>
                        <th>Tema / Contenido</th>
                        <th>Acción</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($posts) > 0): ?>
                        <?php foreach ($posts as $post): ?>
                        <tr>
                            <td style="white-space: nowrap; color: #888; font-size: 13px;">
                                <?php 
                                    $fechaStr = $post->fecha ?? $post->fecha_publicacion ?? 'N/A';
                                    echo ($fechaStr !== 'N/A') ? date("d/m/y H:i", strtotime($fechaStr)) : 'N/A';
                                ?>
                            </td>
                            <td>
                                <strong style="color: #222; display: block; font-size: 14px;">
                                    <?php echo htmlspecialchars($post->autor ?? $post->nombre_usuario ?? 'Anónimo'); ?>
                                </strong>
                            </td>
                            <td>
                                <div style="margin-bottom: 4px;">
                                    <span class="badge-foro">
                                        <?php echo htmlspecialchars($post->titulo ?? $post->tema ?? 'Foro General'); ?>
                                    </span>
                                </div>
                                <div style="font-size: 13px; color: #555; max-width: 400px; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                                    <?php echo htmlspecialchars($post->mensaje ?? $post->contenido ?? 'Sin contenido'); ?>
                                </div>
                            </td>
                            <td style="white-space: nowrap;">
                                <form action="procesar_moderacion.php" method="POST" style="margin:0;" onsubmit="return confirm('¿Estás seguro de que deseas ELIMINAR esta publicación permanentemente?');">
                                    <input type="hidden" name="id_post" value="<?php echo (string)$post->_id; ?>">
                                    <button type="submit" class="btn-icon btn-delete" title="Eliminar Publicación"><i class="ri-delete-bin-line"></i> Borrar</button>
                                </form>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="4" style="text-align: center; padding: 40px; color: #888;">
                                <i class="ri-chat-smile-3-line" style="font-size: 30px; display: block; margin-bottom: 10px; color: #ccc;"></i>
                                No hay publicaciones recientes en el foro.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </main>
</body>
</html>
<?php ob_end_flush(); ?>