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

// Obtener los mensajes de la colección correcta, ordenados del más reciente al más antiguo
$opciones = ['sort' => ['fecha_envio' => -1]];
$queryMensajes = new MongoDB\Driver\Query([], $opciones);
$mensajes = $conexion->executeQuery("ArbolBD.mensajes_soporte", $queryMensajes)->toArray();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buzón de Soporte | Panel Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap');
        :root { --verde-fuerte: #4a6733; --verde-claro: #5D8736; --bg-color: #f4f7f6; }
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Poppins', sans-serif; }
        body { display: flex; min-height: 100vh; background-color: var(--bg-color); }
        
        /* Sidebar (Mantenemos la misma estructura de navegación) */
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
        
        /* Tabla de Mensajes */
        .table-container { background: #fff; border-radius: 16px; box-shadow: 0 4px 15px rgba(0,0,0,0.03); overflow: hidden; overflow-x: auto; }
        table { width: 100%; border-collapse: collapse; text-align: left; }
        th { background-color: var(--verde-fuerte); padding: 18px 25px; color: white; font-size: 14px; text-transform: uppercase; font-weight: 600; }
        td { padding: 18px 25px; border-bottom: 1px solid #eee; color: #444; font-size: 14px; vertical-align: middle; }
        tr:hover { background-color: #fcfcfc; }
        
        /* Badges de Estado */
        .badge { padding: 5px 12px; border-radius: 20px; font-size: 11px; font-weight: 700; text-transform: uppercase; }
        .badge-nuevo { background-color: #fff3e0; color: #e65100; border: 1px solid #ffe0b2; }
        .badge-leido { background-color: #f5f5f5; color: #777; border: 1px solid #e0e0e0; }
        
        .btn-action { background: none; border: none; color: var(--verde-claro); cursor: pointer; font-size: 20px; transition: 0.2s; }
        .btn-action:hover { color: var(--verde-fuerte); transform: scale(1.1); }
    </style>
</head>
<body>

    <!-- Menú Lateral -->
    <aside class="sidebar">
        <div class="sidebar-header"><i class="ri-tree-fill"></i> Panel Admin</div>
        <nav class="sidebar-menu">
            <a href="index.php" class="menu-item"><i class="ri-dashboard-line"></i> Dashboard</a>
            <a href="gestion_usuarios.php" class="menu-item"><i class="ri-group-line"></i> Usuarios</a>
            <a href="gestion_catalogo.php" class="menu-item"><i class="ri-plant-line"></i> Catálogo de Árboles</a>
            <a href="buzon_mensajes.php" class="menu-item active"><i class="ri-mail-unread-line"></i> Buzón de Soporte</a>
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
                <h1>Buzón de Soporte</h1>
                <p style="color: #666;">Bandeja de entrada de reportes y dudas de los usuarios.</p>
            </div>
        </div>

        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Estado</th>
                        <th>Fecha</th>
                        <th>Remitente</th>
                        <th>Asunto</th>
                        <th>Mensaje</th>
                        <th>Acción</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($mensajes) > 0): ?>
                        <?php foreach ($mensajes as $msg): ?>
                        <tr style="<?php echo ($msg->leido == 0) ? 'background-color: #fdfbf7;' : ''; ?>">
                            
                            <!-- Estado (Nuevo o Leído) -->
                            <td>
                                <?php if ($msg->leido == 0): ?>
                                    <span class="badge badge-nuevo">Nuevo</span>
                                <?php else: ?>
                                    <span class="badge badge-leido">Leído</span>
                                <?php endif; ?>
                            </td>

                            <!-- Fecha con lógica a prueba de fallos -->
                            <td style="white-space: nowrap; color: #888; font-size: 13px;">
                                <?php 
                                    $fecha = $msg->fecha_envio ?? $msg->fecha ?? 'N/A';
                                    if ($fecha !== 'N/A') {
                                        if (is_object($fecha) && get_class($fecha) === 'MongoDB\BSON\UTCDateTime') {
                                            $datetime = $fecha->toDateTime();
                                            $datetime->setTimezone(new DateTimeZone('America/Monterrey'));
                                            echo $datetime->format("d/m/y H:i");
                                        } else {
                                            echo date("d/m/y H:i", strtotime((string)$fecha));
                                        }
                                    } else {
                                        echo 'N/A';
                                    }
                                ?>
                            </td>

                            <!-- Remitente y Email -->
                            <td>
                                <strong style="color: #222; display: block;"><?php echo htmlspecialchars($msg->nombre ?? 'Anónimo'); ?></strong>
                                <span style="font-size: 12px; color: #777;"><?php echo htmlspecialchars($msg->email ?? '-'); ?></span>
                            </td>

                            <!-- Asunto -->
                            <td style="font-weight: 500;"><?php echo htmlspecialchars($msg->asunto ?? '-'); ?></td>

                            <!-- Mensaje truncado -->
                            <td style="max-width: 250px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                <?php echo htmlspecialchars($msg->mensaje ?? '-'); ?>
                            </td>

                            <!-- Acciones -->
                            <td>
                                <form action="marcar_mensaje_leido.php" method="POST" style="margin:0;">
                                    <input type="hidden" name="id_mensaje" value="<?php echo (string)$msg->_id; ?>">
                                    <button type="submit" class="btn-action" title="<?php echo ($msg->leido == 0) ? 'Marcar como leído' : 'Ya está leído'; ?>">
                                        <i class="<?php echo ($msg->leido == 0) ? 'ri-mail-line' : 'ri-mail-open-line'; ?>" style="<?php echo ($msg->leido == 1) ? 'color: #ccc;' : ''; ?>"></i>
                                    </button>
                                </form>
                            </td>

                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" style="text-align: center; padding: 50px; color: #888;">
                                <i class="ri-mail-check-line" style="font-size: 40px; display: block; margin-bottom: 15px; color: #ccc;"></i>
                                <span style="font-size: 16px;">Bandeja vacía. No hay mensajes nuevos.</span>
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