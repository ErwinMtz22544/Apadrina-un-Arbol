<?php 
ini_set('display_errors', 1);
error_reporting(E_ALL);
if (session_status() === PHP_SESSION_NONE) { session_start(); }
include("../includes/db.php");
include("../includes/header.php"); 

// Consultar los posts del foro ordenados por fecha (del más nuevo al más viejo)
$opciones = ['sort' => ['fecha' => -1]];
$queryForo = new MongoDB\Driver\Query([], $opciones);
$posts = $conexion->executeQuery("ArbolBD.foro", $queryForo)->toArray();
?>

<style>
    :root { --verde-fuerte: #4a6733; --verde-claro: #5D8736; --gris-borde: #ddd; }
    .foro-container { max-width: 800px; margin: 140px auto 80px; font-family: 'Poppins', sans-serif; padding: 0 20px; }
    
    /* Formulario de publicación */
    .post-box { background: white; border: 1px solid var(--gris-borde); border-radius: 16px; padding: 25px; margin-bottom: 40px; box-shadow: 0 4px 15px rgba(0,0,0,0.03); }
    .post-box textarea { width: 100%; border: 1px solid var(--gris-borde); border-radius: 10px; padding: 15px; font-family: 'Poppins', sans-serif; font-size: 15px; resize: none; min-height: 100px; box-sizing: border-box; margin-bottom: 15px; }
    .post-box textarea:focus { outline: none; border-color: var(--verde-claro); }
    .post-actions { display: flex; justify-content: space-between; align-items: center; }
    .file-input-wrapper { position: relative; overflow: hidden; display: inline-block; }
    .file-input-wrapper input[type=file] { font-size: 100px; position: absolute; left: 0; top: 0; opacity: 0; cursor: pointer; }
    .btn-upload { background: #f0fdf4; color: var(--verde-fuerte); padding: 10px 20px; border-radius: 8px; font-weight: 600; border: 1px solid #dcfce7; display: flex; align-items: center; gap: 8px; cursor: pointer; transition: 0.3s; }
    .btn-upload:hover { background: #dcfce7; }
    .btn-publicar { background: var(--verde-fuerte); color: white; border: none; padding: 10px 25px; border-radius: 8px; font-weight: bold; font-family: 'Poppins', sans-serif; cursor: pointer; transition: 0.3s; }
    .btn-publicar:hover { background: #3a5228; }

    /* Feed de publicaciones */
    .post-card { background: white; border: 1px solid var(--gris-borde); border-radius: 16px; padding: 25px; margin-bottom: 25px; }
    .post-header { display: flex; align-items: center; gap: 15px; margin-bottom: 15px; }
    .avatar { width: 45px; height: 45px; background: var(--verde-claro); color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 20px; font-weight: bold; }
    .post-meta h4 { margin: 0; font-size: 16px; color: #222; }
    .post-meta span { font-size: 12px; color: #888; }
    .post-text { color: #444; font-size: 15px; line-height: 1.6; margin-bottom: 15px; }
    .post-img { width: 100%; border-radius: 12px; max-height: 400px; object-fit: cover; }
    
    /* Alertas */
    .alerta { padding: 15px; border-radius: 8px; margin-bottom: 20px; font-size: 14px; font-weight: 600; text-align: center; }
    .alerta-ok { background: #f0fdf4; color: var(--verde-fuerte); border: 1px solid #dcfce7; }
</style>

<main class="foro-container">
    <div style="text-align: center; margin-bottom: 40px;">
        <h1 style="font-size: 2.5rem; color: #222; margin-bottom: 10px;">Foro de la <strong>Comunidad</strong></h1>
        <p style="color: #666;">Comparte el crecimiento de tu árbol y tus experiencias con la comunidad de la UTSC.</p>
    </div>

    <?php if(isset($_GET['publicado']) && $_GET['publicado'] == 'ok'): ?>
        <div class="alerta alerta-ok"><i class="ri-check-line"></i> ¡Tu actualización se ha publicado con éxito!</div>
    <?php endif; ?>

    <?php if(isset($_SESSION['usuario'])): ?>
    <!-- Caja para publicar (Solo usuarios logueados) -->
    <div class="post-box">
        <form action="../includes/procesar_foro.php" method="POST" enctype="multipart/form-data">
            <textarea name="mensaje" placeholder="¿Cómo va tu árbol hoy? Comparte una actualización o duda..." required></textarea>
            <div class="post-actions">
                <div class="file-input-wrapper">
                    <div class="btn-upload">
                        <i class="ri-image-add-line"></i> <span id="file-name">Subir foto</span>
                    </div>
                    <input type="file" name="foto" accept="image/*" onchange="document.getElementById('file-name').innerText = this.files[0].name">
                </div>
                <button type="submit" class="btn-publicar">Publicar</button>
            </div>
        </form>
    </div>
    <?php else: ?>
    <div class="post-box" style="text-align: center; padding: 40px;">
        <i class="ri-lock-line" style="font-size: 40px; color: #ccc;"></i>
        <h3 style="margin: 10px 0; color: #555;">Inicia sesión para participar</h3>
        <p style="color: #888; font-size: 14px; margin-bottom: 20px;">Únete a la conversación y comparte tus fotos.</p>
        <a href="login.php" class="btn-publicar" style="text-decoration: none;">Iniciar sesión</a>
    </div>
    <?php endif; ?>

    <!-- Feed del Foro -->
    <div class="foro-feed">
        <?php if(count($posts) > 0): ?>
            <?php foreach($posts as $post): ?>
                <div class="post-card">
                    <div class="post-header">
                        <div class="avatar"><?php echo strtoupper(substr($post->nombre_usuario, 0, 1)); ?></div>
                        <div class="post-meta">
                            <h4><?php echo htmlspecialchars($post->nombre_usuario); ?></h4>
                            <span><?php echo date("d/m/Y h:i A", strtotime($post->fecha)); ?></span>
                        </div>
                    </div>
                    <div class="post-text">
                        <?php echo nl2br(htmlspecialchars($post->mensaje)); ?>
                    </div>
                    <?php if(!empty($post->imagen)): ?>
                        <img src="<?php echo htmlspecialchars($post->imagen); ?>" class="post-img" alt="Foto del foro">
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div style="text-align: center; color: #999; padding: 40px;">
                <i class="ri-discuss-line" style="font-size: 45px;"></i>
                <p>Aún no hay publicaciones en el foro. ¡Sé el primero en compartir algo!</p>
            </div>
        <?php endif; ?>
    </div>
</main>

<?php include("../includes/footer.php"); ?>