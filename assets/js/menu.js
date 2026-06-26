/*=============== SHOW MENU ===============*/
//const showMenu = (toggleId, navId) =>{
  // const toggle = document.getElementById(toggleId),
    //     nav = document.getElementById(navId)

   //toggle.addEventListener('click', () =>{
       // Add show-menu class to nav menu
     //  nav.classList.toggle('show-menu')

       // Add show-icon to show and hide the menu icon
      // toggle.classList.toggle('show-icon')
//   })
// }

// showMenu('nav-toggle','nav-menu')
/*=============== SHOW MENU ===============*/
const showMenu = (toggleId, navId) => {
   const toggle = document.getElementById(toggleId),
         nav = document.getElementById(navId)

   if(toggle && nav){
      // Escuchamos tanto 'click' como 'touchstart' para máxima compatibilidad
      const toggleEvent = () => {
         nav.classList.toggle('show-menu')
         toggle.classList.toggle('show-icon')
      }

      toggle.addEventListener('click', toggleEvent)
   }
}
showMenu('nav-toggle','nav-menu')

/*=============== DROPDOWN REPAIR FOR iOS ===============*/
// Esto busca todos los submenús (como el de Cuenta) y los activa
const dropdownItems = document.querySelectorAll('.dropdown__item')

dropdownItems.forEach((item) => {
    const dropdownButton = item.querySelector('.dropdown__button')

    if(dropdownButton) {
        dropdownButton.addEventListener('click', (e) => {
            // Si estamos en un dispositivo móvil, evitamos que el enlace 
            // intente navegar antes de mostrar el submenú
            if(window.innerWidth < 1118) {
                e.preventDefault()
                item.classList.toggle('show-dropdown')
            }
        })
    }
})