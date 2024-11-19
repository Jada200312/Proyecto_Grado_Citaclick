// Selecciona el botón de menú y el menú de navegación
const menuToggle = document.getElementById('menuToggle');
const navbarMenu = document.getElementById('navbarMenu');

// Añade un evento de click al botón del menú hamburguesa
menuToggle.addEventListener('click', (event) => {
    // Previene que el clic se propague al documento (para que no se cierre inmediatamente)
    event.stopPropagation();
    // Alterna la clase 'open-menu' para mostrar/ocultar el menú
    navbarMenu.classList.toggle('open-menu');
});

// Añade un evento al documento para detectar clics fuera del menú
document.addEventListener('click', (event) => {
    // Si el menú está abierto y el clic no ocurrió en el botón del menú o en el menú
    if (navbarMenu.classList.contains('open-menu') && !navbarMenu.contains(event.target) && !menuToggle.contains(event.target)) {
        // Cierra el menú al quitar la clase 'open-menu'
        navbarMenu.classList.remove('open-menu');
    }
});