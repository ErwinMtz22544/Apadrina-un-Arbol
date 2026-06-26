
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


// 2. Función Cambiar Password 
const cambiarPassword = (id, nombre) => {
    Swal.fire({
        title: `Nueva contraseña para ${nombre}`,
        input: 'password',
        inputLabel: 'Introduce la nueva contraseña',
        inputPlaceholder: 'Mínimo 8 caracteres, número y símbolo', // Actualizado el placeholder
        showCancelButton: true,
        confirmButtonColor: '#5D8736',
        confirmButtonText: 'Actualizar',
        didOpen: stylesMensagge 
    }).then((result) => {
        if (result.isConfirmed && result.value) {
            const pass = result.value;
            // Expresión regular: Mínimo 8 caracteres, un número y un carácter especial
            const regex = /^(?=.*[0-9])(?=.*[^A-Za-z0-9]).{8,}$/;

            if (!regex.test(pass)) {
                Swal.fire({ 
                    icon: 'error', 
                    title: 'Contraseña no válida', 
                    text: 'La contraseña debe tener al menos 8 caracteres, incluir un número y un símbolo (-, _, #).', 
                    didOpen: stylesMensagge 
                });
                return;
            }

            const form = document.createElement('form');
            form.method = 'POST';
            form.action = 'cambiar_password_admin.php';

            const idInput = document.createElement('input');
            idInput.type = 'hidden'; idInput.name = 'id_usuario'; idInput.value = id;

            const passInput = document.createElement('input');
            passInput.type = 'hidden'; passInput.name = 'nueva_pass'; passInput.value = pass;

            form.appendChild(idInput);
            form.appendChild(passInput);
            document.body.appendChild(form);
            form.submit();
        }
    });
};

document.addEventListener('DOMContentLoaded', () => {
    const urlParams = new URLSearchParams(window.location.search);
    const res = urlParams.get('res');

    if (res) {
        window.history.replaceState({}, document.title, window.location.pathname);

        if (res === 'ok') {
            Swal.fire({ icon: 'success', title: '¡Éxito!', text: 'Cambios guardados.', confirmButtonColor: '#28a745', timer: 3000, didOpen: stylesMensagge });
        } else if (res === 'borrado_ok') {
            Swal.fire({ icon: 'success', title: 'Eliminado', text: 'Usuario removido correctamente.', didOpen: stylesMensagge });
        } else if (res === 'pass_ok') {
            Swal.fire({ icon: 'success', title: 'Actualizada', text: 'La contraseña ha sido cambiada.', didOpen: stylesMensagge });
        } else if (res === 'error') {
            Swal.fire({ icon: 'error', title: 'Error', text: 'No se pudo completar la acción.', didOpen: stylesMensagge });
        }
    }

    // Confirmación para cambio de ROL
    document.querySelectorAll('.form-confirmar').forEach(form => {
        form.addEventListener('submit', (e) => {
            e.preventDefault();
            Swal.fire({
                title: '¿Cambiar rol?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#28a745',
                confirmButtonText: 'Sí, cambiar',
                didOpen: stylesMensagge
            }).then((result) => { if (result.isConfirmed) form.submit(); });
        });
    });

  
    document.querySelectorAll('.form-eliminar').forEach(form => {
        form.addEventListener('submit', (e) => {
            e.preventDefault();
            Swal.fire({
                title: '¿Eliminar usuario?',
                text: "Esta acción es irreversible.",
                icon: 'error',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                confirmButtonText: 'Sí, borrar',
                didOpen: stylesMensagge
            }).then((result) => { if (result.isConfirmed) form.submit(); });
        });
    });
});