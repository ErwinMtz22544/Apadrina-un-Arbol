document.addEventListener('DOMContentLoaded', () => {
    
    function stylesMensagge() {
        const font = Swal.getPopup();
        const title = Swal.getTitle();
        const icon = Swal.getIcon();
        const content = Swal.getHtmlContainer();

        if (font) font.style.fontFamily = "'Poppins', sans-serif";

        if (title) {
            title.style.fontSize = '18px'; 
            title.style.fontWeight = '600';
            title.style.color = '#333';
        }

        if (content) {
            content.style.fontSize = '15px';
            content.style.fontWeight = '400';
            content.style.color = '#666';
        }

        if (icon) icon.style.fontSize = '14px';
    }

    const formulario = document.getElementById('formularioLogin');

    if (formulario) {
        formulario.addEventListener('submit', (e) => {
            const correoInput = formulario.querySelector('input[name="correo"]');
            const passwordInput = formulario.querySelector('input[name="password"]');

            if (!correoInput.value.trim() || !passwordInput.value.trim()) {
                e.preventDefault(); 

                Swal.fire({
                    icon: 'warning',
                    title: 'Campos incompletos',
                    text: 'Por favor, rellena todos los campos para continuar.',
                    confirmButtonColor: '#5D8736',
                    width: '400px',
                    didOpen: stylesMensagge
                });
            }
        });
    }

    const urlParams = new URLSearchParams(window.location.search);
    const error = urlParams.get('error');
    const status = urlParams.get('status');
    const res = urlParams.get('res'); // Capturamos la respuesta de recuperación

    // --- CASOS DE ERROR ---
    if (error === 'password_incorrecta') {
        Swal.fire({
            icon: 'error',
            title: 'Contraseña incorrecta',
            text: 'Por favor, verifica tus datos e intenta de nuevo.',
            confirmButtonColor: '#5D8736',
            confirmButtonText: 'Entendido',
            width: '400px',
            didOpen: stylesMensagge
        });

    } else if (error === 'usuario_no_existe') {
        Swal.fire({
            icon: 'warning',
            title: 'Usuario no encontrado',
            text: 'Este correo no está registrado.',
            showCancelButton: true,
            confirmButtonText: 'Registrarme',
            cancelButtonText: 'Cerrar',
            confirmButtonColor: '#5D8736',
            cancelButtonColor: '#d33',
            width: '400px',
            didOpen: stylesMensagge
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = "registro.php";
            }
        });

    } else if (error === 'correo_no_verificado') {
        Swal.fire({
            icon: 'info',
            title: 'Verificación pendiente',
            text: 'Tu cuenta aún no ha sido activada. Por favor, revisa tu correo electrónico.',
            confirmButtonColor: '#5D8736',
            width: '400px',
            didOpen: stylesMensagge
        });

    } else if (error === 'link_invalido') {
        Swal.fire({
            icon: 'error',
            title: 'Enlace expirado',
            text: 'El token de verificación es inválido o ya ha sido utilizado.',
            confirmButtonColor: '#d33',
            width: '400px',
            didOpen: stylesMensagge
        });
    }

    // --- NUEVOS CASOS DE RECUPERACIÓN (RES) ---
    if (res === 'email_enviado') {
        Swal.fire({
            icon: 'success',
            title: '¡Correo Enviado!',
            text: 'Revisa tu bandeja de entrada para restablecer tu clave.',
            confirmButtonColor: '#5D8736',
            width: '400px',
            didOpen: stylesMensagge
        });
    } else if (res === 'no_existe') {
        Swal.fire({
            icon: 'error',
            title: 'No encontrado',
            text: 'Ese correo electrónico no está registrado.',
            confirmButtonColor: '#d33',
            width: '400px',
            didOpen: stylesMensagge
        });
    } else if (res === 'pass_actualizada') {
        Swal.fire({
            icon: 'success',
            title: '¡Clave actualizada!',
            text: 'Ya puedes iniciar sesión con tu nueva contraseña.',
            confirmButtonColor: '#5D8736',
            width: '400px',
            didOpen: stylesMensagge
        });
    }

    // --- CASOS DE ÉXITO (STATUS) ---
    if (status === 'check_email') {
        Swal.fire({
            icon: 'success',
            title: '¡Registro casi listo!',
            text: 'Te hemos enviado un correo de verificación. Por favor, confírmalo para poder entrar.',
            confirmButtonColor: '#5D8736',
            width: '400px',
            didOpen: stylesMensagge
        });

    } else if (status === 'cuenta_activada') {
        Swal.fire({
            icon: 'success',
            title: '¡Cuenta activada!',
            text: 'Tu correo ha sido verificado con éxito. Ya puedes iniciar sesión.',
            confirmButtonColor: '#5D8736',
            width: '400px',
            didOpen: stylesMensagge
        });
    }

    // Limpiar la URL para que no se repita el alert al recargar
    if (error || status || res) {
        window.history.replaceState({}, document.title, window.location.pathname);
    }
});