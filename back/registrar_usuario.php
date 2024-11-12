<?php
include 'conexion.php';

// Recibo los datos del formulario
$nombres = $_POST['nombres'];
$apellidos = $_POST['apellidos'];
$nombre_usuario = $_POST['nombre_usuario'];
$celular = $_POST['celular'];
$correo = $_POST['correo'];
$contrasena_registro = $_POST['contrasena_registro'];

// Hash de la contraseña para almacenarla encriptada
$hashed_password = password_hash($contrasena_registro, PASSWORD_DEFAULT);

// Consulta SQL para insertar el nuevo usuario
$sql = "INSERT INTO usuarios (nombre_usuario, contraseña, nombres, apellidos, celular, correo) VALUES (?, ?, ?, ?, ?, ?)";

// Preparo la declaración
$stmt = $conn->prepare($sql);

// Verifico si la preparación de la declaración fue exitosa
if ($stmt === false) {
    die("Error al preparar la consulta: " . $conn->error);
}

// Vinculo los parámetros
$stmt->bind_param("ssssss", $nombre_usuario, $hashed_password, $nombres, $apellidos, $celular, $correo);

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
// Ejecuto la declaración
if ($stmt->execute()) {
    // Alerta de éxito con SweetAlert y fondo negro
    echo "<script>
    document.addEventListener('DOMContentLoaded', function() {
        Swal.fire({
            title: '¡Registro exitoso!',
            text: 'Tu cuenta ha sido creada correctamente.',
            icon: 'success',
            background: '#000', // Fondo negro
            color: '#fff', // Texto blanco para mejor visibilidad
            confirmButtonText: 'Aceptar'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = '../access.html'; // Redirigir a la página de acceso
            }
        });
    });
    </script>";
} else {
    // Mensaje de error si algo falla
    echo "<script>
    document.addEventListener('DOMContentLoaded', function() {
        Swal.fire({
            title: 'Error',
            text: 'Hubo un problema al registrar. Inténtalo de nuevo.',
            icon: 'error',
             background: '#000', // Fondo negro
            color: '#fff', // Texto blanco para mejor visibilidad 
            confirmButtonText: 'Aceptar'
        });
    });
    </script>";
}

// Cierro la declaración y la conexión
$stmt->close();
$conn->close();
?>
