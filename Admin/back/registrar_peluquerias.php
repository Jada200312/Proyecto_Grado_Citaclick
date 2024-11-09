<?php
include ('../../back/conexion.php'); 
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
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre = $_POST['nombre'];
    $nombreusuario = $_POST['nombreusuario'];
    $celular = $_POST['celular'];
    $contraseña = $_POST['contraseña'];
    $direccion = $_POST['direccion'];
    $ciudad = $_POST['ciudad'];
    $hora_apertura = $_POST['horainicio'];
    $hora_cierre = $_POST['horafin'];
    $intervalo_tiempo = $_POST['intervalo'];

    $fecha_registro = date('Y-m-d H:i:s');
    $fecha_vencimiento = date('Y-m-d H:i:s', strtotime('+1 month', strtotime($fecha_registro)));
    $estado = 1; // Estado inicial
    $contraseña_hash = password_hash($contraseña, PASSWORD_DEFAULT);

    $imagen = $_FILES['imagen'];
    $directorio_imagen = "../uploads/";
    $nombre_imagen = basename($imagen["name"]);
    $ruta_imagen = $directorio_imagen . $nombre_imagen;
    if (move_uploaded_file($imagen['tmp_name'], $ruta_imagen)) {
        // Inserción en la tabla horarios primero
        $query_horario = "INSERT INTO horarios (horaInicio, horaFin, intervalo_tiempo) 
                          VALUES ('$hora_apertura', '$hora_cierre', '$intervalo_tiempo')";
        
        if (mysqli_query($conn, $query_horario)) {
            $ultimo_id_horario = mysqli_insert_id($conn); 
            
            // Inserción en la tabla peluquerias
            $query_peluqueria = "INSERT INTO peluquerias (nombre, nombre_usuario, contraseña, direccion, ciudad, id_horario, celular, fecha_registro, fecha_vencimiento, estado, imagen) 
                                 VALUES ('$nombre', '$nombreusuario', '$contraseña_hash', '$direccion', '$ciudad', '$ultimo_id_horario', '$celular', '$fecha_registro', '$fecha_vencimiento', '$estado', '$ruta_imagen')";

            if (mysqli_query($conn, $query_peluqueria)) {
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
                window.location.href = '../html/login.html'; // Redirigir a la página de acceso
            }
        });
    });
    </script>";
            } else {
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
        } else {
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
    } else {
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

    $query_actualizar_estado = "UPDATE peluquerias SET estado = 0 WHERE fecha_vencimiento < NOW()";
    mysqli_query($conn, $query_actualizar_estado);

    // Cierra la conexión
    mysqli_close($conn);
}
?>
