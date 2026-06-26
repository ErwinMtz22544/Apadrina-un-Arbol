document.addEventListener('DOMContentLoaded', () => {
    // === ELEMENTOS ADOPCIÓN (CONFORME A TUS VARIABLES) ===
    const modalAdopcion = document.getElementById('modal-pago-arbol');
    const btnAbrirAdopcion = document.querySelector('.btn-adopted');
    const btnCerrarAdopcion = document.getElementById('close-modal-pago');
    const contentTarjeta = document.getElementById('form-tarjeta-desplegable');
    const radioVisa = document.getElementById('radio-visa');
    const todosLosRadios = document.querySelectorAll('input[name="metodo-donar"]');

    // === ELEMENTOS DONACIÓN ===
    const modalDonacion = document.getElementById('modal-donar-arbol');
    const btnAbrirDonacion = document.querySelector('.btn-donar-accion'); 
    const btnCerrarDonacion = document.getElementById('close-modal-donar');
    const radioTarjetaDonar = document.getElementById('radio-donar-tarjeta');
    const formTarjetaDonacion = document.getElementById('form-tarjeta-donacion');
    const radiosDonar = document.querySelectorAll('input[name="metodo-donar-donar"]');

    // --- LÓGICA MODAL ADOPCIÓN ---
    if (btnAbrirAdopcion) {
        btnAbrirAdopcion.addEventListener('click', (e) => {
            e.preventDefault();
            contentTarjeta?.classList.add('pago-oculto'); 
            modalAdopcion?.classList.remove('hidden'); 
            document.body.style.overflow = 'hidden'; // Detiene scroll fondo
        });
    }

    if (btnCerrarAdopcion) {
        btnCerrarAdopcion.addEventListener('click', () => {
            modalAdopcion.classList.add('hidden');
            document.body.style.overflow = 'auto';
        });
    }

    // --- LÓGICA MODAL DONACIÓN ---
    if (btnAbrirDonacion) {
        btnAbrirDonacion.addEventListener('click', (e) => {
            e.preventDefault();
            modalDonacion?.classList.remove('hidden'); 
            radiosDonar.forEach(radio => radio.checked = false);
            formTarjetaDonacion?.classList.add('pago-oculto', 'pago-detalle-oculto');
            document.body.style.overflow = 'hidden';
        });
    }

    if (btnCerrarDonacion) {
        btnCerrarDonacion.addEventListener('click', () => {
            modalDonacion?.classList.add('hidden');
            document.body.style.overflow = 'auto';
        });
    }

    // --- TOGGLE TARJETAS (USANDO TUS CLASES) ---
    todosLosRadios.forEach(radio => {
        radio.addEventListener('change', () => {
            if (radioVisa && radioVisa.checked) {
                contentTarjeta?.classList.remove('pago-oculto', 'pago-detalle-oculto');
            } else {
                contentTarjeta?.classList.add('pago-oculto', 'pago-detalle-oculto');
            }
        });
    });

    radiosDonar.forEach(radio => {
        radio.addEventListener('change', () => {
            if (radioTarjetaDonar && radioTarjetaDonar.checked) {
                formTarjetaDonacion?.classList.remove('pago-oculto', 'pago-detalle-oculto');
            } else {
                formTarjetaDonacion?.classList.add('pago-oculto', 'pago-detalle-oculto');
            }
        });
    });

    // === NUEVO: RESTRICCIONES DE SEGURIDAD Y MÁSCARAS ===

    // 1. Nombres (Solo letras y espacios)
    document.querySelectorAll('input[placeholder*="Bruno Diaz"]').forEach(input => {
        input.addEventListener('keypress', (e) => {
            if (/[0-9]/.test(e.key)) e.preventDefault();
        });
        input.addEventListener('input', (e) => {
            e.target.value = e.target.value.replace(/[0-9]/g, '');
        });
    });

    // 2. CVV (Solo 3 números)
    document.querySelectorAll('input[placeholder="CVC"], input[placeholder="Número de seguridad"]').forEach(input => {
        input.addEventListener('input', (e) => {
            e.target.value = e.target.value.replace(/\D/g, '').substring(0, 3);
        });
    });

    // 3. Máscara Tarjeta (Espacios cada 4)
    document.querySelectorAll('.card-mask').forEach(input => {
        input.addEventListener('input', (e) => {
            let v = e.target.value.replace(/\D/g, '').substring(0, 16);
            e.target.value = v.replace(/(\d{4})(?=\d)/g, '$1 ');
        });
    });

    // 4. Máscara Expiración (MM/YY)
    document.querySelectorAll('.expiry-mask').forEach(input => {
        input.addEventListener('input', (e) => {
            let v = e.target.value.replace(/\D/g, '').substring(0, 4);
            if (v.length > 2) v = v.substring(0, 2) + '/' + v.substring(2);
            e.target.value = v;
        });
    });

    // Cerrar si clican fuera
    window.addEventListener('click', (e) => {
        if (e.target === modalAdopcion) { modalAdopcion.classList.add('hidden'); document.body.style.overflow = 'auto'; }
        if (e.target === modalDonacion) { modalDonacion.classList.add('hidden'); document.body.style.overflow = 'auto'; }
    });
});