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

// Obtener todos los usuarios de la base de datos
$queryUsuarios = new MongoDB\Driver\Query([]);
$usuarios = $conexion->executeQuery("ArbolBD.usuarios", $queryUsuarios)->toArray();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Usuarios | Panel Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
        
        /* Botones de acción superior */
        .header-actions { display: flex; gap: 15px; }
        .btn-limpiar { background-color: var(--verde-claro); color: white; border: none; padding: 10px 20px; border-radius: 8px; font-weight: 600; cursor: pointer; display: flex; align-items: center; gap: 8px; text-decoration: none; transition: 0.3s; }
        .btn-limpiar:hover { background-color: var(--verde-fuerte); }

        /* Tabla de Usuarios */
        .table-container { background: #fff; border-radius: 16px; box-shadow: 0 4px 15px rgba(0,0,0,0.03); overflow: hidden; overflow-x: auto; }
        table { width: 100%; border-collapse: collapse; text-align: left; }
        th { background-color: var(--verde-fuerte); padding: 18px 25px; color: white; font-size: 14px; text-transform: uppercase; font-weight: 600; }
        td { padding: 18px 25px; border-bottom: 1px solid #eee; color: #444; font-size: 14px; vertical-align: middle; }
        tr:hover { background-color: #fcfcfc; }
        
        /* Badges de Roles */
        .badge { padding: 4px 10px; border-radius: 6px; font-size: 12px; font-weight: 700; text-transform: uppercase; display: inline-block; text-align: center; min-width: 70px; }
        .badge-admin { background-color: #ffebee; color: #c62828; }
        .badge-editor { background-color: #e3f2fd; color: #1565c0; }
        .badge-user { background-color: #f5f5f5; color: #616161; }
        
        /* Controles de tabla */
        select { padding: 8px 12px; border: 1px solid #ddd; border-radius: 6px; font-family: 'Poppins'; outline: none; margin-right: 10px; }
        .btn-actualizar { background: var(--verde-claro); color: white; border: none; padding: 8px 15px; border-radius: 6px; cursor: pointer; font-weight: 600; transition: 0.2s; }
        .btn-actualizar:hover { background: var(--verde-fuerte); }
        .btn-key { background: #ffca28; color: #333; border: none; padding: 8px 12px; border-radius: 6px; cursor: pointer; transition: 0.2s; }
        .btn-key:hover { background: #ffb300; }
    </style>
</head>
<body>

    <!-- Menú Lateral Unificado -->
    <aside class="sidebar">
        <div class="sidebar-header"><i class="ri-tree-fill"></i> Panel Admin</div>
        <nav class="sidebar-menu">
            <a href="index.php" class="menu-item"><i class="ri-dashboard-line"></i> Dashboard</a>
            <a href="gestion_usuarios.php" class="menu-item active"><i class="ri-group-line"></i> Usuarios</a>
            <a href="gestion_catalogo.php" class="menu-item"><i class="ri-plant-line"></i> Catálogo de Árboles</a>
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
                <h1>Gestión de Usuarios</h1>
                <p style="color: #666;">Monitoreo de niveles de acceso y estado de verificación.</p>
            </div>
            <div class="header-actions">
                <a href="limpiar_usuario_fantasma.php" class="btn-limpiar"><i class="ri-user-unfollow-line"></i> Limpiar Fantasmas</a>
            </div>
        </div>

        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Usuario</th>
                        <th>Email</th>
                        <th>Rol Actual</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($usuarios as $user): ?>
                    <?php $nombreMostrar = $user->nombre ?? $user->nombre_usuario ?? 'N/A'; ?>
                    <tr>
                        <td style="color: #888; font-size: 12px;"><?php echo substr((string)$user->_id, -6); ?></td>
                        <td style="font-weight: 600;"><?php echo htmlspecialchars($nombreMostrar); ?></td>
                        <td><?php echo htmlspecialchars($user->email ?? 'N/A'); ?></td>
                        
                        <?php 
                            $rol = strtolower($user->rol ?? '');
                            if (empty($rol)) {
                                $rol = ($user->id_rol == 1) ? 'admin' : (($user->id_rol == 2) ? 'editor' : 'usuario');
                            }
                            $claseBadge = ($rol == 'admin') ? 'badge-admin' : (($rol == 'editor') ? 'badge-editor' : 'badge-user');
                        ?>
                        <td><span class="badge <?php echo $claseBadge; ?>"><?php echo strtoupper($rol); ?></span></td>
                        
                        <td style="white-space: nowrap; display: flex; align-items: center; gap: 15px;">
                            <!-- Formulario para cambiar el rol -->
                            <form action="actualizar_rol.php" method="POST" style="margin: 0;">
                                <input type="hidden" name="id_usuario" value="<?php echo (string)$user->_id; ?>">
                                <select name="nuevo_rol">
                                    <option value="usuario" <?php if($rol == 'usuario') echo 'selected'; ?>>Usuario</option>
                                    <option value="editor" <?php if($rol == 'editor') echo 'selected'; ?>>Editor</option>
                                    <option value="admin" <?php if($rol == 'admin') echo 'selected'; ?>>Admin</option>
                                </select>
                                <button type="submit" class="btn-actualizar">Actualizar</button>
                            </form>
                            
                            <div style="width: 1px; height: 25px; background-color: #e0e0e0;"></div>

                            <!-- Botón funcional para restablecer contraseña -->
                            <button type="button"
                                    class="btn-key"
                                    title="Restablecer Contraseña"
                                    data-id="<?php echo (string)$user->_id; ?>"
                                    data-nombre="<?php echo htmlspecialchars($nombreMostrar, ENT_QUOTES, 'UTF-8'); ?>"
                                    onclick="restablecerClave(this)"
                                    style="display: flex; align-items: center; gap: 6px; font-weight: 600; font-size: 13px;">
                                <i class="ri-key-2-line" style="font-size: 16px;"></i> Restablecer Clave
                            </button>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </main>

    <script>
        function restablecerClave(boton) {
            const idUsuario = boton.dataset.id;
            const nombreUsuario = boton.dataset.nombre;

            Swal.fire({
                title: '¿Restablecer contraseña?',
                text: `Se generará una nueva clave temporal para ${nombreUsuario}. Su contraseña actual dejará de funcionar.`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#5D8736',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí, restablecer',
                cancelButtonText: 'Cancelar'
            }).then(async (result) => {
                if (!result.isConfirmed) return;

                try {
                    const formData = new FormData();
                    formData.append('id_usuario', idUsuario);

                    const response = await fetch('restablecer_clave.php', {
                        method: 'POST',
                        body: formData
                    });
                    const data = await response.json();

                    if (data.success) {
                        Swal.fire({
                            title: '¡Contraseña Restablecida!',
                            html: `Nueva clave temporal para <strong>${nombreUsuario}</strong>:<br><br>
                                   <div style="background:#f4f7f6; padding:12px; border-radius:8px; font-size:20px; font-weight:700; letter-spacing:2px; color:#4a6733; user-select:all;">
                                       ${data.clave}
                                   </div>
                                   <small style="color:#666; display:block; margin-top:10px;">Cópiala y compártela con el usuario (solo se muestra una vez).</small>`,
                            icon: 'success',
                            confirmButtonColor: '#5D8736',
                            confirmButtonText: 'Copiar clave y cerrar'
                        }).then(() => {
                            if (navigator.clipboard) {
                                navigator.clipboard.writeText(data.clave);
                            }
                        });
                    } else {
                        Swal.fire('Error', data.message || 'No se pudo restablecer la contraseña.', 'error');
                    }
                } catch (error) {
                    Swal.fire('Error', 'Hubo un problema de conexión con el servidor.', 'error');
                }
            });
        }
    </script>
</body>
</html>
<?php ob_end_flush(); ?>