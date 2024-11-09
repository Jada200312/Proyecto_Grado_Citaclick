<?php
session_start();
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
include('../../back/conexion.php');

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    echo "<script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                title: '¿Estás seguro?',
                text: '¡No podrás revertir esto!',
                icon: 'warning',
                showCancelButton: true,
            
            color: '#fff',
            background: '#000',
            confirmButtonColor: '#fd6d15',
            cancelButtonColor: '#fd6d15',
                confirmButtonText: 'Sí, eliminarlo!',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Aquí se ejecuta el código PHP para eliminar el registro
                    const xhr = new XMLHttpRequest();
                    xhr.open('GET', 'confirmar_eliminar.php?id=$id', true);
                    xhr.onload = function() {
                        const response = JSON.parse(this.responseText);
                        Swal.fire({
                            title: response.title,
                            text: response.text,
                            icon: response.icon,
                            background: '#000',
                            color: '#fff',
                            confirmButtonText: 'Aceptar'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location.href = '../html/editaroeliminar.php';
                            }
                        });
                    };
                    xhr.send();
                } else {
                    // Redirigir a editaroeliminar.php si se cancela
                    window.location.href = '../html/editaroeliminar.php';
                }
            });
        });
    </script>";

} else {
    $title = "Error";
    $text = "ID no proporcionado.";
    $icon = "error";

    echo "<script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                title: '$title',
                text: '$text',
                icon: '$icon',
                background: '#000',
                color: '#fff',
                confirmButtonText: 'Aceptar'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = '../html/editaroeliminar.php';
                }
            });
        });
    </script>";
}
?>