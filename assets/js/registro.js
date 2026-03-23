document.addEventListener('DOMContentLoaded', () => {

    const formulario = document.getElementById('formularioRegistro');

    const stylesMensagge = () => {
        const font = Swal.getPopup();
        const title = Swal.getTitle();
        const content = Swal.getHtmlContainer();
        const icon = Swal.getIcon();

        if (font) font.style.fontFamily = "'Poppins', sans-serif";
        if (title) {
            title.style.fontSize = '20px';
            title.style.fontWeight = '600';
            title.style.color = '#333';
        }
        if (content) {
            content.style.fontSize = '15px';
            content.style.color = '#666';
        }
        if (icon) icon.style.fontSize = '14px';
    };

    // Validación de campos antes de enviar
    if (formulario) {
        formulario.addEventListener('submit', (e) => {
       
            const nombre = formulario.querySelector('input[name="nombre_usuario"]').value.trim();
            const correo = formulario.querySelector('input[name="email"]').value.trim();
            const pass = formulario.querySelector('input[name="user-password"]').value.trim();
            const passConfirm = formulario.querySelector('input[name="reg_confirm"]').value.trim();

            // Validar campos vacíos
            if (!nombre || !correo || !pass || !passConfirm) {
                e.preventDefault();
                Swal.fire({
                    icon: 'warning',
                    title: 'Campos incompletos',
                    text: 'Por favor, llena todos los espacios para registrarte.',
                    confirmButtonColor: '#5D8736',
                    width: '400px',
                    didOpen: stylesMensagge
                });
                return;
            }

            // Validar nombre reservado "Admin"
            if (nombre.toLowerCase() === 'admin') {
                e.preventDefault();
                Swal.fire({
                    icon: 'warning',
                    title: 'Nombre reservado',
                    text: 'El nombre "Admin" no está disponible. Por favor elige otro.',
                    confirmButtonColor: '#5D8736',
                    width: '400px',
                    didOpen: stylesMensagge
                });
                return;
            }

            //Validar contraseñas
            if (pass !== passConfirm) {
                e.preventDefault();
                Swal.fire({
                    icon: 'error',
                    title: 'Las contraseñas no coinciden',
                    text: 'Revisa que ambos campos de contraseña sean iguales.',
                    confirmButtonColor: '#d33',
                    width: '400px',
                    didOpen: stylesMensagge
                });
            }
        });
    }

    // Capturar parámetros de la URL
    const urlParams = new URLSearchParams(window.location.search);
    const error = urlParams.get('error');
    const status = urlParams.get('status');

    // --- CASOS DE ERROR ---
    if (error === 'pass_no_coinciden') {
        Swal.fire({
            icon: 'error',
            title: 'Error en contraseñas',
            text: 'Las contraseñas introducidas no son iguales.',
            confirmButtonColor: '#d33',
            didOpen: stylesMensagge
        });
    } else if (error === 'usuario_duplicado') {
        Swal.fire({
            icon: 'warning',
            title: 'Cuenta existente',
            text: 'Este usuario o correo ya está registrado. ¿Quieres iniciar sesión?',
            showCancelButton: true,
            confirmButtonText: 'Ir al Login',
            cancelButtonText: 'Intentar de nuevo',
            confirmButtonColor: '#5D8736',
            didOpen: stylesMensagge
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = "login.php";
            }
        });
    } else if (error === 'error_envio_correo') { 
        Swal.fire({
            icon: 'error',
            title: 'Error de servidor',
            text: 'No pudimos enviar el correo de verificación. Inténtalo más tarde.',
            confirmButtonColor: '#d33',
            didOpen: stylesMensagge
        });
    }

    // --- CASO DE ÉXITO ---
  
    if (status === 'check_email') { 
        Swal.fire({
            icon: 'success',
            title: '¡Casi listo!',
            text: 'Te hemos enviado un enlace de activación a tu correo. Por favor, verifícalo para poder entrar.',
            confirmButtonColor: '#5D8736',
            confirmButtonText: 'Entendido',
            width: '450px',
            didOpen: stylesMensagge
        }).then(() => {
            window.location.href = "login.php";
        });
    }

    // Limpiar la URL
    if (error || status) {
        window.history.replaceState({}, document.title, window.location.pathname);
    }
});