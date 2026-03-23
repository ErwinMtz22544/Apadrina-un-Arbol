const openModal = document.getElementById('open_modal');
const closeModal = document.getElementById('close_modal');
const modal = document.getElementById('modal');
const fondoModal = document.getElementById('fondoModal');

// CARRUSEL 
let slideIndex = 0; 

function mostrarSlide(n) {
    const slides = document.querySelectorAll('.slide');
    if (slides.length === 0) return; // Seguridad por si no hay fotos

  
    slides.forEach(slide => slide.classList.remove('active'));
    
    
    slideIndex = n;
    if (slideIndex >= slides.length) slideIndex = 0;
    if (slideIndex < 0) slideIndex = slides.length - 1;
    
 
    slides[slideIndex].classList.add('active');
}

// FUNCIONES DEL MODAL 
function activarModal() {
    modal.style.display = "flex";
    document.body.style.overflow = "hidden";
    
    // Reiniciar al primer slide cada vez que se abre
    slideIndex = 0;
    mostrarSlide(slideIndex);
}

function desactivarModal() {
    modal.style.display = "none";
    document.body.style.overflow = "auto";
}



// Abrir y Cerrar
openModal.addEventListener('click', activarModal);
closeModal.addEventListener('click', desactivarModal);
fondoModal.addEventListener('click', desactivarModal);

// Controles del Carrusel (Flechas)

document.getElementById('avanzar')?.addEventListener('click', () => mostrarSlide(slideIndex + 1));
document.getElementById('retroceder')?.addEventListener('click', () => mostrarSlide(slideIndex - 1));

// Cierre con tecla Escape
window.addEventListener('keydown', (e) => {
    if (e.key === "Escape") {
        desactivarModal();
    }
});