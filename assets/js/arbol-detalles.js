const inputQuantity = document.querySelector('.input-quantity');
const btnIncrement = document.querySelector('#increment');
const btnDecrement = document.querySelector('#decrement');

let valueByDefault = parseInt(inputQuantity.value);

// Funciones Click (Cantidad)
btnIncrement.addEventListener('click', () => {
    valueByDefault += 1;
    inputQuantity.value = valueByDefault;
});

btnDecrement.addEventListener('click', () => {
    if (valueByDefault === 1) return;
    valueByDefault -= 1;
    inputQuantity.value = valueByDefault;
});

// Toggle - Selección de elementos
const toggleDescription = document.querySelector('.title-description-arbol');
const toggleMore = document.querySelector('.title-more');

const contentDescription = document.querySelector('.text-description-arbol');
const contentMore = document.querySelector('.text-more');

// Funciones Toggle (Solo para los que existen)
if (toggleDescription && contentDescription) {
    toggleDescription.addEventListener('click', () => {
        contentDescription.classList.toggle('hidden');
    });
}

if (toggleMore && contentMore) {
    toggleMore.addEventListener('click', () => {
        contentMore.classList.toggle('hidden');
    });
}