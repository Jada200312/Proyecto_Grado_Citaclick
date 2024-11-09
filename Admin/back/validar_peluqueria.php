<?php
include '../../back/conexion.php';
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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre_usuario = $_POST['username'];
    $contrasena = $_POST['password'];

    $sql = "SELECT * FROM peluquerias WHERE nombre_usuario = ?";
    $stmt = $conn->prepare($sql);

    if ($stmt === false) {
        die("Error al preparar la consulta: " . $conn->error);
    }

    // Vincular el parámetro
    $stmt->bind_param("s", $nombre_usuario);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        if (password_verify($contrasena, $row['contraseña'])) {
            $_SESSION['nombre_usuario'] = $nombre_usuario;
            echo "<script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    title: 'Inicio de sesión exitoso',
                    text: 'Bienvenido, $nombre_usuario',
                    icon: 'success',
                    background: '#000', // Fondo negro
                    color: '#fff', // Texto blanco para mejor visibilidad
                    confirmButtonText: 'Aceptar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = '../html/index.php'; // Redirigir a la página principal
                    }
                });
            });
            </script>";
        } else {
            // Mensaje de error de contraseña incorrecta
            echo "<script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    title: 'Contraseña incorrecta',
                    text: 'Has ingresado mal tu contraseña',
                    icon: 'error',
                    background: '#000', // Fondo negro
                    color: '#fff', // Texto blanco para mejor visibilidad
                    confirmButtonText: 'Aceptar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = '../html/login.html'; // Redirigir a la página de acceso
                    }
                });
            });
            </script>";
        }
    } else {
        // Mensaje de error de peluquería no encontrada
        echo "<script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                title: 'Peluquería no encontrada',
                text: 'El nombre de usuario no ha sido encontrado',
                icon: 'question',
                background: '#000', // Fondo negro
                color: '#fff', // Texto blanco para mejor visibilidad
                confirmButtonText: 'Aceptar'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = '../html/login.html'; // Redirigir a la página de acceso
                }
            });
        });
        </script>";
    }

    $stmt->close();
} else {
    echo "<script>
    document.addEventListener('DOMContentLoaded', function() {
        Swal.fire({
            title: 'Error',
            text: 'No se recibieron datos del formulario',
            icon: 'error',
            background: '#000',
            color: '#fff',
            confirmButtonText: 'Aceptar'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = '../html/login.html'; // Redirigir a la página de acceso
            }
        });
    });
    </script>";
}

$conn->close();
?>
