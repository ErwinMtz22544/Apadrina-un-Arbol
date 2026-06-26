<?php
include("../includes/db.php");
include("../includes/header.php");


$id_apadrinamiento = isset($_GET['id']) ? intval($_GET['id']) : 0;


$query = "SELECT a.fecha_apadrinamiento, a.nombre_arbol, a.tipo_adopcion, a.monto,
                 ar.nombre_comun, ar.nombre_cientifico, ar.imagen,
                 u.nombre, u.nombre_usuario
          FROM apadrinamientos a
          LEFT JOIN arboles ar ON a.id_arbol = ar.id_arbol
          LEFT JOIN usuarios u ON a.id_usuario = u.id
          WHERE a.id_apadrinamiento = '$id_apadrinamiento'";

$res = mysqli_query($conexion, $query);
$arbol = mysqli_fetch_assoc($res);

if ($arbol) {
    // Si el usuario tiene nombre real lo usamos, si no, su nombre_usuario
    $padrino = !empty($arbol['nombre']) ? $arbol['nombre'] : $arbol['nombre_usuario'];
    $fecha_formateada = date("d/m/Y", strtotime($arbol['fecha_apadrinamiento']));
}
?>

<main class="container" style="margin-top: 50px; text-align: center; padding: 20px;">
    <?php if($arbol): ?>
        <h1 style="color: #5D8736;"> ¡Escaneo Exitoso!</h1>
        <div style="background: white; padding: 30px; border-radius: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.05); display: inline-block; margin-top: 20px; max-width: 400px; width: 100%;">
            
            <img src="../assets/img/arboles/<?php echo $arbol['imagen']; ?>" style="width: 200px; height: 200px; object-fit: cover; border-radius: 15px;">
            
            <h2 style="margin-top: 15px; color: #1a3c1a; font-size: 28px; margin-bottom: 5px;"><?php echo $arbol['nombre_arbol']; ?></h2>
            
            <p style="color: #666; font-weight: 600; margin-bottom: 2px;">Especie: <?php echo $arbol['nombre_comun']; ?></p>
            <p style="font-style: italic; color: #888; margin-bottom: 20px;"><?php echo $arbol['nombre_cientifico']; ?></p>
            
            <div style="border-top: 1px solid #f0f0f0; padding-top: 15px; text-align: left; font-size: 15px; color: #444;">
                <p style="margin-bottom: 8px;"> Padrino: <strong style="color: #5D8736;"><?php echo ucfirst($padrino); ?></strong></p>
                <p style="margin-bottom: 8px;"> Fecha de adopción: <strong><?php echo $fecha_formateada; ?></strong></p>
                <p style="margin-bottom: 0;"> Estado: <strong style="color: #5D8736;">Protegido y Monitoreado :p</strong></p>
            </div>
            
        </div>
    <?php else: ?>
        <div style="background: white; padding: 30px; border-radius: 20px; display: inline-block; margin-top: 20px;">
            <p style="font-size: 18px; color: #666;"> Árbol no encontrado o aún no ha sido apadrinado.</p>
        </div>
    <?php endif; ?>
</main>

<script src="../assets/js/menu.js"></script>
</body>
</html>