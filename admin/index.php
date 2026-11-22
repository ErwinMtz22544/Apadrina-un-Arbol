<?php
ob_start();
session_start();
date_default_timezone_set('America/Monterrey');

include("../includes/db.php");

// Verificación de seguridad corregida: acepta 'admin' o el número 1
if (!isset($_SESSION['rol']) || ($_SESSION['rol'] !== 'admin' && $_SESSION['rol'] != 1)) {
    header("Location: ../pages/login.php");
    exit();
}

$nombre_admin = $_SESSION['nombre_usuario'] ?? 'Administrador';

// Obtener estadísticas rápidas desde MongoDB
$queryUsuarios = new MongoDB\Driver\Query([]);
$total_usuarios = count($conexion->executeQuery("ArbolBD.usuarios", $queryUsuarios)->toArray());

$queryAdopciones = new MongoDB\Driver\Query([]);
$total_adopciones = count($conexion->executeQuery("ArbolBD.apadrinamientos", $queryAdopciones)->toArray());

$queryArboles = new MongoDB\Driver\Query([]);
$total_arboles = count($conexion->executeQuery("ArbolBD.arboles", $queryArboles)->toArray());
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Administración | Apadrina un Árbol</title>
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap');
        
        :root { --verde-fuerte: #4a6733; --verde-claro: #5D8736; --bg-color: #f4f7f6; --text-color: #333; }
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Poppins', sans-serif; }
        
        body { display: flex; min-height: 100vh; background-color: var(--bg-color); }
        
        /* Sidebar */
        .sidebar { width: 260px; background-color: #fff; border-right: 1px solid #e0e0e0; display: flex; flex-direction: column; position: fixed; height: 100vh; }
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
        .admin-profile { display: flex; align-items: center; gap: 12px; background: #fff; padding: 8px 15px; border-radius: 30px; box-shadow: 0 2px 10px rgba(0,0,0,0.05); }
        .admin-avatar { width: 40px; height: 40px; background: var(--verde-claro); color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: bold; }
        
        /* Grid de Tarjetas */
        .stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 25px; margin-bottom: 40px; }
        .stat-card { background: #fff; border-radius: 16px; padding: 25px; box-shadow: 0 4px 15px rgba(0,0,0,0.03); display: flex; align-items: center; gap: 20px; }
        .stat-icon { width: 60px; height: 60px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 28px; }
        .stat-info h3 { font-size: 24px; color: #222; margin-bottom: 4px; line-height: 1; }
        .stat-info p { color: #888; font-size: 13px; font-weight: 600; text-transform: uppercase; }
        
        .bg-green { background: #f0fdf4; color: var(--verde-fuerte); }
        .bg-blue { background: #e1f5fe; color: #0288d1; }
        .bg-orange { background: #fff3e0; color: #e65100; }
        .bg-purple { background: #f3e5f5; color: #7b1fa2; }
    </style>
</head>
<body>

    <!-- Menú Lateral -->
    <aside class="sidebar">
        <div class="sidebar-header">
            <i class="ri-tree-fill"></i> Panel Admin
        </div>
        <nav class="sidebar-menu">
            <a href="index.php" class="menu-item active"><i class="ri-dashboard-line"></i> Dashboard</a>
            <a href="gestion_usuarios.php" class="menu-item"><i class="ri-group-line"></i> Usuarios</a>
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

    <!-- Contenido Principal -->
    <main class="main-content">
        <div class="top-bar">
            <h1>Resumen General</h1>
            <div class="admin-profile">
                <div class="admin-avatar"><?php echo strtoupper(substr($nombre_admin, 0, 1)); ?></div>
                <div style="font-size: 14px; font-weight: 600; color: #333;"><?php echo htmlspecialchars($nombre_admin); ?></div>
            </div>
        </div>

        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon bg-green"><i class="ri-user-smile-line"></i></div>
                <div class="stat-info">
                    <h3><?php echo $total_usuarios; ?></h3>
                    <p>Usuarios Registrados</p>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon bg-blue"><i class="ri-leaf-line"></i></div>
                <div class="stat-info">
                    <h3><?php echo $total_arboles; ?></h3>
                    <p>Especies en Catálogo</p>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon bg-orange"><i class="ri-seedling-line"></i></div>
                <div class="stat-info">
                    <h3><?php echo $total_adopciones; ?></h3>
                    <p>Total Adopciones</p>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon bg-purple"><i class="ri-cloud-windy-line"></i></div>
                <div class="stat-info">
                    <h3><?php echo $total_adopciones * 22; ?> kg</h3>
                    <p>CO₂ Compensado Aprox.</p>
                </div>
            </div>
        </div>

        <div style="background: white; padding: 30px; border-radius: 16px; box-shadow: 0 4px 15px rgba(0,0,0,0.03);">
            <h2 style="color: #222; margin-bottom: 15px; font-size: 20px;">Bienvenido al centro de control</h2>
            <p style="color: #666; line-height: 1.6;">Desde el menú lateral podrás gestionar todos los aspectos de la plataforma. Para proteger la base de datos en producción (Render), todas las acciones realizadas aquí se reflejarán inmediatamente en las apps web y móviles conectadas mediante la API.</p>
        </div>
    </main>

</body>
</html>
<?php ob_end_flush(); ?>