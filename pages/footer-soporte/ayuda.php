<?php 
session_start(); 
include("../../includes/header.php");
?>
<link rel="stylesheet" href="/Arbol/assets/css/style.css">
<main class="support-main" style="background-color: var(--bg-main); padding-top: 140px;">
    <div class="support-card">
        <div style="text-align: center; margin-bottom: 40px;">
            <h1 style="justify-content: center; font-size: 40px;">
                <i class="ri-customer-service-2-line"></i> Centro de Ayuda
            </h1>
            <p style="font-size: 18px; color: var(--gray-color);">Estamos aquí para resolver tus dudas sobre el proyecto Apadrina Un Arbol.</p>
        </div>

        <form action="procesar_ayuda.php" method="POST">
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 30px;">
                <div class="input-group">
                    <label class="form-label">Nombre Completo:</label>
                    <div style="position: relative;">
                        <i class="ri-user-smile-line" style="position: absolute; left: 18px; top: 18px; color: var(--green-color); font-size: 20px;"></i>
                        <input type="text" name="nombre" class="form-input-full" placeholder="Ej. Juan Pérez" required>
                    </div>
                </div>

                <div class="input-group">
                    <label class="form-label">Correo Electrónico:</label>
                    <div style="position: relative;">
                        <i class="ri-mail-send-line" style="position: absolute; left: 18px; top: 18px; color: var(--green-color); font-size: 20px;"></i>
                        <input type="email" name="email" class="form-input-full" placeholder="ejemplo@gmail.com" required>
                    </div>
                </div>
            </div>

            <div class="input-group" style="margin-top: 30px;">
                <label class="form-label">Asunto de la consulta:</label>
                <div style="position: relative;">
                    <i class="ri-question-answer-line" style="position: absolute; left: 18px; top: 18px; color: var(--green-color); font-size: 20px;"></i>
                    <select name="asunto" class="form-input-full" style="appearance: none;">
                        <option value="Duda sobre apadrinamiento">Duda sobre apadrinamiento</option>
                        <option value="Problemas con la cuenta">Problemas con la cuenta</option>
                        <option value="Reportar un error en la web">Reportar un error en la web</option>
                    </select>
                </div>
            </div>

            <div class="input-group" style="margin-top: 30px;">
                <label class="form-label">Describe tu situación:</label>
                <textarea name="mensaje" class="form-input-full" rows="6" style="padding-left: 20px;" placeholder="Escribe aquí los detalles..."></textarea>
            </div>

            <button type="submit" class="btn-soporte-pro">
            <i class="ri-send-plane-fill"></i> Enviar Mensaje a Soporte
            </button>
        </form>
    </div>
</main>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // Detectamos si la URL tiene el parámetro ?status=enviado
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.get('status') === 'enviado') {
        Swal.fire({
            title: '¡Mensaje Recibido!',
            text: 'Tu duda ha sido enviada al equipo de la UTSC. Te responderemos pronto.',
            icon: 'success',
            confirmButtonColor: '#5D8736', // Tu verde corporativo
            timer: 4000
        });
        // Limpiamos la URL para que no vuelva a salir al recargar
        window.history.replaceState({}, document.title, window.location.pathname);
    }
</script>

<?php include("../../includes/footer.php"); ?>