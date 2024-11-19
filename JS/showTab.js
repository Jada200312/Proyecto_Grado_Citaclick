        // JavaScript para manejar el cambio de pestañas
        function showTab(tabIndex) {
            // Oculta todas las pestañas
            const tabs = document.querySelectorAll('.tab');
            tabs.forEach((tab, index) => {
                tab.classList.remove('active-tab');
            });

            // Desactiva todos los botones
            const buttons = document.querySelectorAll('.date-picker button');
            buttons.forEach((button, index) => {
                button.classList.remove('active');
            });

            // Activa la pestaña y botón seleccionados
            tabs[tabIndex].classList.add('active-tab');
            buttons[tabIndex].classList.add('active');
        }


        window.addEventListener('DOMContentLoaded', () => {
            // Muestra la primera pestaña por defecto
            showTab(0);
        });