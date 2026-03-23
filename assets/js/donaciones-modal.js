document.addEventListener('DOMContentLoaded', () => {
    const modalAdopcion = document.getElementById('modal-pago-arbol');
    const btnAbrirAdopcion = document.querySelector('.btn-adopted');
    const btnCerrarAdopcion = document.getElementById('close-modal-pago');
    const contentTarjeta = document.getElementById('form-tarjeta-desplegable');
    
    const radioVisa = document.getElementById('radio-visa');
    const todosLosRadios = document.querySelectorAll('input[name="metodo-donar"]');

    if (btnAbrirAdopcion) {
        btnAbrirAdopcion.addEventListener('click', (e) => {
            e.preventDefault();
            contentTarjeta?.classList.add('pago-oculto'); 
            modalAdopcion?.classList.remove('hidden'); 
        });
    }

    if (btnCerrarAdopcion) {
        btnCerrarAdopcion.addEventListener('click', () => {
            modalAdopcion.classList.add('hidden');
        });
    }


    todosLosRadios.forEach(radio => {
        radio.addEventListener('change', () => {
            if (radioVisa.checked) {
                contentTarjeta.classList.remove('pago-oculto');
                 contentTarjeta.classList.remove('pago-detalle-oculto');
            } else {
                contentTarjeta.classList.add('pago-oculto');
                    contentTarjeta.classList.add('pago-detalle-oculto');
            }
        });
    });

    // donar
    const modalDonacion = document.getElementById('modal-donar-arbol');
    const btnAbrirDonacion = document.querySelector('.btn-donar-accion'); 
    const btnCerrarDonacion = document.getElementById('close-modal-donar');

    if (btnAbrirDonacion) {
        btnAbrirDonacion.addEventListener('click', (e) => {
            e.preventDefault();
            console.log("Abriendo modal de donación...");
            modalDonacion?.classList.remove('hidden'); 
        });
    }

    if (btnCerrarDonacion) {
        btnCerrarDonacion.addEventListener('click', () => {
            modalDonacion?.classList.add('hidden');
        });
    }

    // Cerrar cualquier modal si clican fuera
    window.addEventListener('click', (e) => {
        if (e.target === modalAdopcion) modalAdopcion.classList.add('hidden');
        if (e.target === modalDonacion) modalDonacion.classList.add('hidden');
    });
});

const radioTarjetaDonar = document.getElementById('radio-donar-tarjeta');
const formTarjetaDonacion = document.getElementById('form-tarjeta-donacion');
const radiosDonar = document.querySelectorAll('input[name="metodo-donar-donar"]');
const btnAbrirDonacion = document.querySelector('.btn-donar-accion');
const modalDonacion = document.getElementById('modal-donar-arbol');


radiosDonar.forEach(radio => {
    radio.addEventListener('change', () => {
        if (radioTarjetaDonar.checked) {
            formTarjetaDonacion.classList.remove('pago-oculto', 'pago-detalle-oculto');
        } else {
            formTarjetaDonacion.classList.add('pago-oculto', 'pago-detalle-oculto');
        }
    });
});


btnAbrirDonacion.addEventListener('click', (e) => {
    e.preventDefault();
    modalDonacion.classList.remove('hidden');

    // Resetear radios
    radiosDonar.forEach(radio => radio.checked = false);

    // Ocultar formulario
    formTarjetaDonacion.classList.add('pago-oculto', 'pago-detalle-oculto');
});