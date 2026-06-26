const passwordInput = document.getElementById('password');
const mensajePassword = document.getElementById('mensaje-password');

passwordInput.addEventListener('input', () => {
    const pass = passwordInput.value;
    
    // Expresión regular: Mínimo 8 caracteres, un número y un carácter especial
    const regex = /^(?=.*[0-9])(?=.*[^A-Za-z0-9]).{8,}$/;

    if (pass === "") {
        mensajePassword.textContent = ""; // Si está vacío, no sale nada
    } else if (regex.test(pass)) {
        mensajePassword.textContent = "Contraseña segura";
        mensajePassword.style.color = "green"; // Color éxito
    } else {
        mensajePassword.textContent = "Mínimo 8 caracteres, un número y un símbolo (-, _, #)";
        mensajePassword.style.color = "red"; // Color error
    }
});