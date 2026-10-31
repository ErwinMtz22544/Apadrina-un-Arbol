<?php 
if (session_status() === PHP_SESSION_NONE) { session_start(); }
include("../includes/header.php"); 
?>

<style>
    /* Tipografía y colores unificados del proyecto */
    :root { --verde-fuerte: #4a6733; --verde-claro: #5D8736; --gris-borde: #ddd; }
    
    .manual-container { max-width: 900px; margin: 140px auto 80px; font-family: 'Poppins', sans-serif; padding: 0 20px; }
    .manual-header { text-align: center; margin-bottom: 50px; }
    .manual-header h1 { font-size: 2.5rem; color: #222; margin-bottom: 12px; }
    .manual-header h1 strong { color: var(--verde-claro); }
    .manual-header p { color: #666; font-size: 1.1rem; max-width: 600px; margin: 0 auto 20px; line-height: 1.6; }
    
    .btn-tour { background: white; color: var(--verde-fuerte); border: 2px solid var(--verde-fuerte); padding: 12px 25px; border-radius: 10px; font-weight: bold; cursor: pointer; display: inline-flex; align-items: center; gap: 8px; transition: 0.3s; font-family: 'Poppins', sans-serif; font-size: 15px; }
    .btn-tour:hover { background: #f0fdf4; }

    .manual-card { background: white; border: 1px solid var(--gris-borde); border-radius: 16px; padding: 30px; margin-bottom: 20px; display: flex; gap: 25px; align-items: flex-start; transition: transform 0.2s; }
    .manual-card:hover { border-color: var(--verde-claro); box-shadow: 0 4px 15px rgba(93, 135, 54, 0.08); }
    
    .manual-step-num { background: #f0fdf4; color: var(--verde-fuerte); font-weight: bold; font-size: 1.5rem; width: 55px; height: 55px; border-radius: 14px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; border: 1px solid #dcfce7; }
    
    .manual-content h3 { color: #333; margin-top: 0; margin-bottom: 10px; font-size: 1.25rem; }
    .manual-content p { color: #555; line-height: 1.7; font-size: 0.95rem; margin: 0; }
    
    .download-box { background: #fafafa; border: 2px dashed var(--verde-claro); border-radius: 16px; padding: 40px; text-align: center; margin-top: 50px; }
    .download-box h3 { color: var(--verde-fuerte); margin-top: 0; margin-bottom: 10px; font-size: 1.4rem; }
    .btn-download { background: var(--verde-fuerte); color: white; border: none; padding: 14px 30px; border-radius: 10px; font-weight: bold; font-size: 1rem; cursor: pointer; display: inline-flex; align-items: center; gap: 8px; text-decoration: none; margin-top: 15px; transition: 0.3s; font-family: 'Poppins', sans-serif; }
    .btn-download:hover { background: #3a5228; }

    /* Personalización del pop-up del Tour */
    .driver-popover { font-family: 'Poppins', sans-serif !important; border-radius: 12px !important; }
    .driver-popover-title { color: var(--verde-fuerte) !important; font-size: 18px !important; }
    .driver-popover-next-btn, .driver-popover-prev-btn { border-radius: 6px !important; text-shadow: none !important; }
</style>

<main class="manual-container">
    <div class="manual-header">
        <h1>Manual de <strong>Usuario</strong></h1>
        <p>Aprende paso a paso cómo explorar, apadrinar y dar seguimiento a tus árboles dentro de la comunidad de la UTSC.</p>
        <button type="button" class="btn-tour" id="startTour">
            <i class="ri-compass-3-line"></i> Iniciar Tour Guiado
        </button>
    </div>

    <div class="manual-card" id="step-catalogo">
        <div class="manual-step-num">01</div>
        <div class="manual-content">
            <h3>Explora el Catálogo de Arboles</h3>
            <p>Dirígete a la sección de Catálogo para conocer las especies nativas de la región disponibles para apadrinamiento. Utiliza la barra de búsqueda en tiempo real para filtrar por nombre o características ecológicas, y revisa las fichas técnicas detalladas de cada ejemplar.</p>
        </div>
    </div>

    <div class="manual-card" id="step-detalle">
        <div class="manual-step-num">02</div>
        <div class="manual-content">
            <h3>Selecciona y Personaliza tu Adopción</h3>
            <p>Haz clic en el árbol de tu preferencia para ver su información ampliada (Suelo ideal, mantenimiento y captura de CO₂). Podrás elegir si deseas adoptar de forma individual, con amigos o en familia, además de asignar un nombre personalizado único a tu árbol.</p>
        </div>
    </div>

    <div class="manual-card" id="step-cuenta">
        <div class="manual-step-num">03</div>
        <div class="manual-content">
            <h3>Gestiona tu Panel de Padrino</h3>
            <p>Una vez completado el registro y la aportación, el árbol quedará vinculado a tu cuenta. En tu panel personal podrás consultar tu historial, verificar el estado de tus aportaciones y dar seguimiento al impacto ambiental generado.</p>
        </div>
    </div>

    <div class="download-box">
        <h3>¿Prefieres tener la guía en físico o PDF?</h3>
        <p style="color: #666; font-size: 0.95rem; margin-bottom: 15px;">Descarga el manual completo de adopción y cuidados en formato digital para consultarlo cuando quieras.</p>
        <!-- Enlace actualizado para forzar la descarga del PDF -->
        <a href="/Apadrina-un-Arbol/assets/Manual_Usuario.pdf" download="Manual_Apadrina_un_Arbol.pdf" class="btn-download">
            <i class="ri-file-download-line"></i> Descargar Manual en PDF
        </a>
    </div>
</main>

<!-- Importación correcta de Driver.js v1.0.1 -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/driver.js@1.0.1/dist/driver.css"/>
<script src="https://cdn.jsdelivr.net/npm/driver.js@1.0.1/dist/driver.js.iife.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const btnStart = document.getElementById('startTour');
        
        if (btnStart) {
            btnStart.addEventListener('click', () => {
                const driver = window.driver.js.driver;
                const driverObj = driver({
                    showProgress: true,
                    animate: true,
                    doneBtnText: '¡Terminar!',
                    nextBtnText: 'Siguiente &rarr;',
                    prevBtnText: '&larr; Anterior',
                    steps: [
                        { element: '.manual-header', popover: { title: '¡Bienvenido al Manual!', description: 'Sigue este rápido recorrido para entender cómo funciona nuestra plataforma.', side: "bottom", align: 'center' } },
                        { element: '#step-catalogo', popover: { title: 'Paso 1: El Catálogo', description: 'Aquí es donde comienza tu viaje para encontrar la especie ideal para ti.', side: "top", align: 'center' } },
                        { element: '#step-detalle', popover: { title: 'Paso 2: Personalización', description: 'Dale un nombre especial y elige la modalidad de tu apadrinamiento.', side: "top", align: 'center' } },
                        { element: '#step-cuenta', popover: { title: 'Paso 3: Tu Impacto', description: 'Todo tu progreso e impacto ecológico se guardará de forma segura en tu perfil.', side: "top", align: 'center' } },
                        { element: '.download-box', popover: { title: 'Llévalo contigo', description: 'Si prefieres leerlo offline, siempre puedes descargar el PDF desde aquí.', side: "top", align: 'center' } }
                    ]
                });
                driverObj.drive();
            });
        }
    });
</script>

<?php include("../includes/footer.php"); ?>