function openTab(evt, tabName) {
    var i, tab_content, tab_button;
 
    // Ocultar todas las pestañas y eliminar la clase active
    tab_content = document.getElementsByClassName("tab_content");
    for (i = 0; i < tab_content.length; i++) {
        tab_content[i].style.display = "none";
        tab_content[i].classList.remove("active");
    }
 
    // Quitar la clase active de todos los botones de pestaña
   tab_button = document.getElementsByClassName("tab_button");
    for (i = 0; i < tab_button.length; i++) {
       tab_button[i].classList.remove("active");
    }
 
    // Mostrar la pestaña seleccionada y añadir la clase active al botón correspondiente
    document.getElementById(tabName).style.display = "block";
    document.getElementById(tabName).classList.add("active");
     evt.currentTarget.classList.add("active");
 }