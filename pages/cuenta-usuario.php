<?php 
// 1. Mostrar errores (útil por si hay problemas de conexión)
ini_set('display_errors', 1); 
ini_set('display_startup_errors', 1); 
error_reporting(E_ALL);

include("../includes/db.php");
include("../includes/header.php"); 

// 2. Seguridad: Verificar que el usuario esté logueado
if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit();
}

$email_session = $_SESSION['usuario']; 

// 3. Obtenemos datos del usuario (Ajustado a tu tabla real)
$query_user = "SELECT id, nombre, nombre_usuario FROM usuarios WHERE email = '$email_session'";
$res_user = mysqli_query($conexion, $query_user);
$user_data = mysqli_fetch_assoc($res_user);

if ($user_data) {
    $id_user = $user_data['id'];
    // Si la columna nombre tiene datos, la usa; si no, usa tu nombre de usuario
    $nombre_real = !empty($user_data['nombre']) ? $user_data['nombre'] : $user_data['nombre_usuario'];
} else {
    $id_user = 0;
    $nombre_real = "Usuario Invitado";
}

// 4. Consulta de Apadrinamientos
$query_arboles = "SELECT a.*, ar.nombre_comun, ar.nombre_cientifico, ar.imagen 
                FROM apadrinamientos a 
                JOIN arboles ar ON a.id_arbol = ar.id_arbol 
                WHERE a.id_usuario = '$id_user' 
                ORDER BY a.fecha_apadrinamiento DESC";

$resultado = mysqli_query($conexion, $query_arboles);
$total_adoptados = mysqli_num_rows($resultado);
?>

<main class="profile-section container" style="margin-top: 150px; padding-bottom: 80px;">
    <div class="profile-header" style="margin-bottom: 50px;">
        <h1 style="font-size: 48px; font-weight: 800; color: #1a3c1a;">
            ¡Hola, <span style="color: #5D8736;"><?php echo ucfirst($nombre_real); ?></span>!
        </h1> 
        <p style="font-size: 22px; color: #666; font-weight: 600;">
            Mis árboles apadrinados: <span style="color: #5D8736;"><?php echo $total_adoptados; ?> / 10</span>
        </p>
    </div>

    <div class="adoption-grid" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)); gap: 35px;">
        <?php if($total_adoptados > 0): ?>
            <?php while($fila = mysqli_fetch_assoc($resultado)): ?>
            
            <div class="adoption-card" style="background: #fff; border-radius: 25px; box-shadow: 0 15px 35px rgba(0,0,0,0.08); overflow: hidden; border: 1px solid #f0f0f0; display: flex; flex-direction: column; height: 100%;">
                
                <div style="height: 240px; overflow: hidden; flex-shrink: 0;">
                    <img src="../assets/img/arboles/<?php echo $fila['imagen']; ?>" style="width: 100%; height: 100%; object-fit: cover;">
                </div>
                
                <div style="padding: 30px; display: flex; flex-direction: column; flex-grow: 1;">
                    <h3 style="color: #5D8736; font-size: 28px; margin-bottom: 5px;"><?php echo $fila['nombre_arbol']; ?></h3> 
                    <p style="color: #999; margin-bottom: 15px; font-style: italic;"><?php echo $fila['nombre_cientifico']; ?></p>
                    
                    <div style="border-top: 2px solid #f9f9f9; padding-top: 20px; font-size: 16px; margin-bottom: 25px;">
                        <p style="margin-bottom: 10px;"><i class="ri-calendar-line"></i> Adoptado: <strong><?php echo date("d/m/Y", strtotime($fila['fecha_apadrinamiento'])); ?></strong></p>
                        <p style="margin-bottom: 10px;"><i class="ri-money-dollar-circle-line"></i> Monto: <strong>$<?php echo number_format($fila['monto'], 2); ?> MXN</strong></p>
                        <p><i class="ri-shield-user-line"></i> Tipo: <strong><?php echo $fila['tipo_adopcion']; ?></strong></p>
                    </div>

                    <div style="margin-top: auto;">
                        <a href="generar_certificado.php?id=<?php echo $fila['id_apadrinamiento']; ?>" 
                           class="btn-certificado" 
                           style="display: block; text-align: center; background: #1a3c1a; color: white; padding: 15px; border-radius: 12px; text-decoration: none; font-weight: bold; transition: 0.3s; width: 100%; box-sizing: border-box;">
                            <i class="ri-file-download-line"></i> Descargar Certificado
                        </a>
                    </div>
                </div>
                
            </div>
            <?php endwhile; ?>
        <?php else: ?>
            <div style="grid-column: 1/-1; text-align: center; padding: 50px;">
                <p style="font-size: 18px; color: #666;">Aún no has apadrinado árboles.</p>
                <a href="catalogo.php" style="display: inline-block; margin-top: 20px; background: #5D8736; color: white; padding: 12px 25px; border-radius: 10px; text-decoration: none;">¡Ver catálogo!</a>
            </div>
        <?php endif; ?>
    </div>
</main>

<script src="/Arbol/assets/js/menu.js"></script>
</main>
</body>
</html>
<?php ob_end_flush(); ?>