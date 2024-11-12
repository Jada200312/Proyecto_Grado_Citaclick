<?php
include 'conexion.php';
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

    // Prepara la consulta para seleccionar al usuario por nombre
    $sql = "SELECT * FROM usuarios WHERE nombre_usuario = ?";
    $stmt = $conn->prepare($sql);

    if ($stmt === false) {
        die("Error al preparar la consulta: " . $conn->error);
    }

    // Vincular el parámetro
    $stmt->bind_param("s", $nombre_usuario);
    $stmt->execute();
    $result = $stmt->get_result();

    // Verificar si se encontró un usuario con el nombre proporcionado
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        // Verificar la contraseña
        if (password_verify($contrasena, $row['contraseña'])) {
            $_SESSION['nombre_usuario'] = $nombre_usuario;

            // Mensaje de éxito (bienvenida)
            echo "<script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    title: 'Tu inicio de sesión fue exitoso.',
                    text: 'Bienvenido, $nombre_usuario',
                    icon: 'success',
                     background: '#000', // Fondo negro
                     color: '#fff', // Texto blanco para mejor visibilidad
                    confirmButtonText: 'Aceptar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = '../index.php'; // Redirigir a la página principal
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
                        window.location.href = '../access.html'; // Redirigir a la página principal
                    }
                });
            });
            </script>";
        }
    } else {
        // Mensaje de error de usuario no encontrado
        echo "<script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                title: 'Usuario no encontrado',
                text: 'El nombre de tu usuario no ha sido encontrado',
                icon: 'question',
                 background: '#000', // Fondo negro
            color: '#fff', // Texto blanco para mejor visibilidad
                confirmButtonText: 'Aceptar'
                
            }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = '../access.html'; // Redirigir a la página principal
                    }
                });
        });
        </script>";
    }

    $stmt->close();
} else {
    echo "No se recibieron datos por el formulario";
}

$conn->close();
?>
