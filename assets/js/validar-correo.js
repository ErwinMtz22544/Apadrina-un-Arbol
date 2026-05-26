document.addEventListener("DOMContentLoaded", () => {
    const inputCorreo = document.getElementById("email");
    const mensaje = document.getElementById("mensaje-correo");

    if (inputCorreo) {
        inputCorreo.addEventListener("input", () => {
            mensaje.textContent = "";
            inputCorreo.classList.remove("input-error", "input-success");
        });

     
        inputCorreo.addEventListener("blur", async () => {
            //verifica correos validos 
            const emailVeridico = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;

            if (!emailVeridico.test(inputCorreo.value)) {
                mensaje.textContent = "Ingresa un correo electrónico válido.";
                mensaje.className = "mensaje-correo mensaje-error";
                inputCorreo.classList.add("input-error");
                return; 
            }
            if (inputCorreo.value === "") {
                mensaje.textContent = "";
                inputCorreo.classList.remove("input-error", "input-success");
                return;
            }

            const formData = new FormData();
            formData.append("email", inputCorreo.value);

            try {
                const respuesta = await fetch("../includes/verificar_correo.php", {
                    method: "POST",
                    body: formData
                });

                const resultado = await respuesta.text();

                if (resultado === "existe") {
                    mensaje.textContent = "Este correo ya está registrado.";
                    mensaje.className = "mensaje-correo mensaje-error";
                    
                    inputCorreo.classList.add("input-error");
                    inputCorreo.classList.remove("input-success");
                    
                } else {
                    mensaje.textContent = "Correo disponible.";
                    mensaje.className = "mensaje-correo mensaje-success";
                    

                    inputCorreo.classList.add("input-success");
                    inputCorreo.classList.remove("input-error");
                }

            } catch (error) {
                mensaje.textContent = "Error al verificar el correo.";
                mensaje.className = "mensaje-correo mensaje-error";
                inputCorreo.classList.add("input-error");
            }
        });
    }
});