<?php 
session_start(); 
include("../../includes/header.php"); // Cargamos el CSS y los iconos del header
?>
<link rel="stylesheet" href="/Arbol/assets/css/style.css">
<main class="support-main" style="background-color: var(--bg-main); padding-top: 140px;">
    <div class="support-card">
        <div style="text-align: center; margin-bottom: 40px;">
            <h1 style="justify-content: center; font-size: 40px;">
                <i class="ri-questionnaire-line"></i> Preguntas Frecuentes
            </h1>
            <p style="font-size: 18px; color: var(--gray-color);">Resuelve tus dudas rápidamente sobre el proyecto Apadrina un Árbol.</p>
        </div>

        <div class="faq-container">
            <div style="margin-bottom: 30px; padding: 20px; border-left: 5px solid var(--green-color); background-color: #f9fdf7; border-radius: 0 10px 10px 0;">
                <h3 style="color: var(--green-dark); margin-bottom: 10px;">
                    <i class="ri-medal-line"></i> ¿Cómo recibo mi certificado?
                </h3>
                <p style="line-height: 1.7; color: var(--gray-color);">
                    Una vez que completes el proceso de apadrinamiento, el sistema generará un certificado digital que podrás descargar directamente desde tu perfil de usuario.
                </p>
            </div>

            <div style="margin-bottom: 30px; padding: 20px; border-left: 5px solid var(--green-color); background-color: #f9fdf7; border-radius: 0 10px 10px 0;">
                <h3 style="color: var(--green-dark); margin-bottom: 10px;">
                    <i class="ri-map-pin-user-line"></i> ¿Puedo visitar mi árbol?
                </h3>
                <p style="line-height: 1.7; color: var(--gray-color);">
                    ¡Claro! Todos los árboles están ubicados en las áreas verdes de la <strong>UTSC</strong>. En tu panel de usuario encontrarás las coordenadas exactas o el número de lote para identificarlo.
                </p>
            </div>

            <div style="margin-bottom: 30px; padding: 20px; border-left: 5px solid var(--green-color); background-color: #f9fdf7; border-radius: 0 10px 10px 0;">
                <h3 style="color: var(--green-dark); margin-bottom: 10px;">
                    <i class="ri-seedling-line"></i> ¿Quién se encarga del riego y cuidado?
                </h3>
                <p style="line-height: 1.7; color: var(--gray-color);">
                    El equipo de mantenimiento forestal de la universidad supervisa la salud de los ejemplares, pero tú puedes acudir a realizar riegos adicionales siguiendo las guías de cuidado.
                </p>
            </div>
        </div>

        <div style="text-align: center; margin-top: 40px; padding: 20px; background-color: var(--bg-main); border-radius: 12px;">
            <p style="font-weight: 600;">¿No encuentras lo que buscas?</p>
            <a href="ayuda.php" style="color: var(--green-color); font-weight: 700; text-decoration: none; display: inline-flex; align-items: center; gap: 5px; margin-top: 10px;">
                <i class="ri-chat-smile-3-line"></i> Contáctanos directamente aquí
            </a>
        </div>
    </div>
</main>

<?php include("../../includes/footer.php"); ?>