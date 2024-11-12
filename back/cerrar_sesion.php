<?php
session_start();

// Limpia todas las variables de sesión
$_SESSION = array();

// Destruye la sesión
session_destroy();

// Asegúrate de que SweetAlert está incluido en el HTML
echo '<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">';
echo '<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>';

echo '
<style>
    body {
        background-color: #000; /* Fondo negro */
        color: #fff; /* Texto blanco */
    }
    input, select, textarea {
        background-color: #333; /* Fondo de los inputs */
        color: #fff; /* Texto de los inputs */
        border: 1px solid #555; /* Bordes gris oscuro */
    }
    button, input[type="submit"] {
        background-color: #444; /* Fondo de los botones */
        color: #fff; /* Texto de los botones */
        border: 1px solid #555; /* Borde de los botones */
    }
    a {
        color: #1E90FF; /* Enlaces de color azul */
    }
</style>';
// Muestra una alerta personalizada usando SweetAlert antes de redirigir
echo "<script>
    document.addEventListener('DOMContentLoaded', function() {
        Swal.fire({
            title: '¡Sesión cerrada!',
            text: 'Has cerrado sesión correctamente.',
            icon: 'success',
            background: '#000',
            color: '#fff', // Texto blanco para mejor visibilidad
            confirmButtonText: 'Aceptar'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = '../index.php'; // Redirigir a la página de inicio
            }
        });
    });
</script>";
exit();
?>
