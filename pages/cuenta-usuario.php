<?php 
ini_set('display_errors', 1); 
ini_set('display_startup_errors', 1); 
error_reporting(E_ALL);

require_once __DIR__ . '/../includes/session.php';
include("../includes/db.php");


if (!isset($_SESSION['usuario'])) {
    header("Location: login.php"); exit();
}
include("../includes/header.php");


$email_session = $_SESSION['usuario']; 

$queryUser = new MongoDB\Driver\Query(['email' => $email_session]);
$cursorUser = $conexion->executeQuery("ArbolBD.usuarios", $queryUser)->toArray();

if (!empty($cursorUser)) {
    $user_data = $cursorUser[0];
    $id_user = $user_data->id ?? time();
    $nombre_real = !empty($user_data->nombre) ? $user_data->nombre : $user_data->nombre_usuario;
} else {
    $id_user = 0;
    $nombre_real = "Usuario Invitado";
}

$queryAdopciones = new MongoDB\Driver\Query(['id_usuario' => $id_user]);
$adopciones = $conexion->executeQuery("ArbolBD.apadrinamientos", $queryAdopciones)->toArray();
$total_adoptados = count($adopciones);

// ----------------------------------------------------
// LÓGICA DEL DASHBOARD DE IMPACTO (GAMIFICACIÓN)
// ----------------------------------------------------
$co2_anual_estimado = $total_adoptados * 22; // Aproximamos 22kg de CO2 por árbol al año

$insignia_nombre = "Explorador";
$insignia_icono = "ri-search-eye-line";
$insignia_color = "#9e9e9e"; // Gris
$insignia_bg = "#f5f5f5";

if ($total_adoptados >= 1 && $total_adoptados <= 2) {
    $insignia_nombre = "Semilla";
    $insignia_icono = "ri-seedling-line";
    $insignia_color = "#8d6e63"; // Café tierra
    $insignia_bg = "#efebe9";
} elseif ($total_adoptados >= 3 && $total_adoptados <= 4) {
    $insignia_nombre = "Brote";
    $insignia_icono = "ri-plant-line";
    $insignia_color = "#5D8736"; // Verde proyecto
    $insignia_bg = "#f0fdf4";
} elseif ($total_adoptados >= 5) {
    $insignia_nombre = "Guardián Forestal";
    $insignia_icono = "ri-medal-fill";
    $insignia_color = "#fbc02d"; // Dorado
    $insignia_bg = "#fff9c4";
}
?>

<style>
    .dashboard-stats { display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 20px; margin-bottom: 50px; font-family: 'Poppins', sans-serif; }
    .stat-card { background: white; border: 1px solid #f0f0f0; border-radius: 20px; padding: 25px; display: flex; align-items: center; gap: 20px; box-shadow: 0 5px 15px rgba(0,0,0,0.03); transition: transform 0.2s; }
    .stat-card:hover { transform: translateY(-3px); box-shadow: 0 8px 25px rgba(0,0,0,0.06); }
    .stat-icon { width: 65px; height: 65px; border-radius: 16px; display: flex; align-items: center; justify-content: center; font-size: 30px; flex-shrink: 0; }
    .stat-info h3 { margin: 0 0 4px; font-size: 26px; color: #1a3c1a; line-height: 1; }
    .stat-info p { margin: 0; color: #666; font-size: 14px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; }
</style>

<main class="profile-section container" style="margin-top: 150px; padding-bottom: 80px;">
    
    <div class="profile-header" style="margin-bottom: 40px; font-family: 'Poppins', sans-serif;">
        <h1 style="font-size: 48px; font-weight: 800; color: #1a3c1a; margin-bottom: 10px;">
            ¡Hola, <span style="color: #5D8736;"><?php echo htmlspecialchars(ucfirst($nombre_real)); ?></span>!
        </h1> 
        <p style="font-size: 18px; color: #555;">Aquí está el resumen de tu impacto ecológico en la UTSC.</p>
    </div>

    <!-- DASHBOARD DE MÉTRICAS -->
    <div class="dashboard-stats">
        <!-- Tarjeta 1: Adopciones -->
        <div class="stat-card">
            <div class="stat-icon" style="background: #f0fdf4; color: #5D8736;">
                <i class="ri-tree-line"></i>
            </div>
            <div class="stat-info">
                <h3><?php echo $total_adoptados; ?></h3>
                <p>Árboles apadrinados</p>
            </div>
        </div>

        <!-- Tarjeta 2: Impacto CO2 -->
        <div class="stat-card">
            <div class="stat-icon" style="background: #e1f5fe; color: #0288d1;">
                <i class="ri-cloud-windy-line"></i>
            </div>
            <div class="stat-info">
                <h3><?php echo $co2_anual_estimado; ?> <span style="font-size: 16px;">kg</span></h3>
                <p>CO₂ compensado / año</p>
            </div>
        </div>

        <!-- Tarjeta 3: Gamificación / Insignia -->
        <div class="stat-card">
            <div class="stat-icon" style="background: <?php echo $insignia_bg; ?>; color: <?php echo $insignia_color; ?>;">
                <i class="<?php echo $insignia_icono; ?>"></i>
            </div>
            <div class="stat-info">
                <h3 style="font-size: 20px; color: <?php echo $insignia_color; ?>;"><?php echo $insignia_nombre; ?></h3>
                <p>Insignia actual</p>
            </div>
        </div>
    </div>

    <!-- GRID DE ÁRBOLES ADOPTADOS -->
    <h2 style="font-family: 'Poppins', sans-serif; color: #1a3c1a; margin-bottom: 25px; font-size: 26px;">Mis ejemplares</h2>
    <div class="adoption-grid" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)); gap: 35px; font-family: 'Poppins', sans-serif;">
        <?php if($total_adoptados > 0): ?>
            <?php foreach($adopciones as $fila): 
                $queryArbol = new MongoDB\Driver\Query(['id_arbol' => $fila->id_arbol]);
                $datosArbol = $conexion->executeQuery("ArbolBD.arboles", $queryArbol)->toArray()[0];
            ?>
            <div class="adoption-card" style="background: #fff; border-radius: 25px; box-shadow: 0 15px 35px rgba(0,0,0,0.08); overflow: hidden; border: 1px solid #f0f0f0; display: flex; flex-direction: column; height: 100%;">
                <div style="height: 240px; overflow: hidden; flex-shrink: 0; position: relative;">
                    <img src="<?php echo htmlspecialchars($datosArbol->imagen); ?>" style="width: 100%; height: 100%; object-fit: cover;">
                    <div style="position: absolute; top: 15px; right: 15px; background: rgba(255,255,255,0.9); padding: 5px 12px; border-radius: 20px; font-weight: 600; font-size: 13px; color: #5D8736;">
                        <i class="ri-shield-check-fill"></i> <?php echo htmlspecialchars($fila->tipo_adopcion); ?>
                    </div>
                </div>
                <div style="padding: 30px; display: flex; flex-direction: column; flex-grow: 1;">
                    <h3 style="color: #5D8736; font-size: 26px; margin: 0 0 5px;"><?php echo htmlspecialchars($fila->nombre_arbol); ?></h3> 
                    <p style="color: #999; margin: 0 0 20px; font-style: italic; font-size: 15px;"><?php echo htmlspecialchars($datosArbol->nombre_cientifico); ?></p>
                    
                    <div style="background: #f9fdf7; border: 1px dashed #c8e6c9; border-radius: 12px; padding: 15px; font-size: 14.5px; color: #444; margin-bottom: 25px;">
                        <p style="margin: 0 0 8px;"><i class="ri-calendar-event-line" style="color: #5D8736; margin-right: 5px;"></i> Apadrinado: <strong><?php echo date("d/m/Y", strtotime($fila->fecha_apadrinamiento)); ?></strong></p>
                        <p style="margin: 0;"><i class="ri-coin-line" style="color: #5D8736; margin-right: 5px;"></i> Aportación: <strong>$<?php echo number_format($fila->monto, 2); ?> MXN</strong></p>
                    </div>

                    <div style="margin-top: auto;">
                        <a href="generar_certificado.php?id=<?php echo $fila->id_apadrinamiento ?? $fila->_id; ?>" class="btn-certificado" style="display: flex; align-items: center; justify-content: center; gap: 8px; background: #1a3c1a; color: white; padding: 14px; border-radius: 12px; text-decoration: none; font-weight: bold; font-size: 15px; transition: 0.3s; width: 100%; box-sizing: border-box;">
                            <i class="ri-file-paper-2-line" style="font-size: 20px;"></i> Ver Certificado
                        </a>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div style="grid-column: 1/-1; background: #fafafa; border: 2px dashed #ddd; border-radius: 20px; text-align: center; padding: 60px 20px;">
                <i class="ri-seedling-line" style="font-size: 50px; color: #ccc; margin-bottom: 15px; display: block;"></i>
                <h3 style="font-size: 22px; color: #333; margin-bottom: 10px;">Tu bosque aún está vacío</h3>
                <p style="font-size: 16px; color: #666; margin-bottom: 25px;">Apadrina tu primer ejemplar y comienza a generar un impacto positivo.</p>
                <a href="catalogo.php" style="display: inline-flex; align-items: center; gap: 8px; background: #5D8736; color: white; font-weight: 600; padding: 14px 30px; border-radius: 12px; text-decoration: none; transition: 0.3s;">
                    <i class="ri-search-line"></i> Explorar Catálogo
                </a>
            </div>
        <?php endif; ?>
    </div>
</main>
<script src="/assets/js/menu.js"></script>
<?php include("../includes/footer.php"); ?>