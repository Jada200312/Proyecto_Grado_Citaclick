<?php
// Iniciar la sesión
session_start();

// Verificar si el usuario está logueado
if (!isset($_SESSION['nombre_usuario'])) {
    header("Location: ../html/index.php");
    exit();
}

// Incluir la conexión a la base de datos
include 'conexion.php';

// Asegúrate de que SweetAlert está incluido en el HTML
echo '<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">';
echo '<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>';

// Añadir CSS para fondo negro y texto blanco
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

// Verificar si se ha recibido una solicitud POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtener los datos del formulario
    $fechaReserva = $_POST['fecha'];
    $horaReserva = $_POST['hora'];
    $nombrePeluqueria = $_POST['nombre'];
    $nombreUsuario = $_SESSION['nombre_usuario'];
    $servicio = $_POST['servicio'];

    // Consulta para obtener el id del usuario a partir del nombre de usuario
    $sqlUsuario = "SELECT id FROM usuarios WHERE nombre_usuario = ?";
    $stmtUsuario = $conn->prepare($sqlUsuario);
    $stmtUsuario->bind_param("s", $nombreUsuario);
    $stmtUsuario->execute();
    $resultUsuario = $stmtUsuario->get_result();

    if ($resultUsuario->num_rows > 0) {
        $rowUsuario = $resultUsuario->fetch_assoc();
        $idUsuario = $rowUsuario['id'];
    } else {
        echo "<script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                title: 'Error',
                text: 'No se encontró el usuario.',
                icon: 'error',
                color: '#fff', // Texto blanco
                background: '#000', // Fondo negro
                confirmButtonText: 'Aceptar'
            });
        });
        </script>";
        exit();
    }

    // Consulta para obtener el id de la peluquería a partir del nombre
    $sqlPeluqueria = "SELECT id FROM peluquerias WHERE nombre = ?";
    $stmtPeluqueria = $conn->prepare($sqlPeluqueria);
    $stmtPeluqueria->bind_param("s", $nombrePeluqueria);
    $stmtPeluqueria->execute();
    $resultPeluqueria = $stmtPeluqueria->get_result();

    if ($resultPeluqueria->num_rows > 0) {
        $rowPeluqueria = $resultPeluqueria->fetch_assoc();
        $idPeluqueria = $rowPeluqueria['id'];
    } else {
        echo "<script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                title: 'Error',
                text: 'No se encontró la peluquería.',
                icon: 'error',
                color: '#fff', // Texto blanco
                background: '#000', // Fondo negro
                confirmButtonText: 'Aceptar'
            });
        });
        </script>";
        exit();
    }

    // Verificar si ya existe una reserva para la misma fecha, hora y peluquería
    $sqlVerificar = "SELECT id FROM reservas WHERE fechaReserva = ? AND horaReserva = ? AND id_peluqueria = ?";
    $stmtVerificar = $conn->prepare($sqlVerificar);
    $stmtVerificar->bind_param("ssi", $fechaReserva, $horaReserva, $idPeluqueria);
    $stmtVerificar->execute();
    $resultVerificar = $stmtVerificar->get_result();

    if ($resultVerificar->num_rows > 0) {
        echo "<script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                title: 'Error',
                text: 'Ya existe una reserva para esta fecha y hora en la peluquería seleccionada.',
                icon: 'error',
                color: '#fff', // Texto blanco
                background: '#000', // Fondo negro
                confirmButtonText: 'Aceptar'
            }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = '../users/agendar1.php'; // Redirigir a la página principal
                    }
                });
        });
        </script>";
        exit();
    }

    // Consulta para insertar la nueva reserva en la tabla 'reservas'
    $sqlInsertar = "INSERT INTO reservas (fechaReserva, horaReserva, id_usuario, id_peluqueria, servicio)
                   VALUES (?, ?, ?, ?, ?)";
    $stmtInsertar = $conn->prepare($sqlInsertar);
    $stmtInsertar->bind_param("ssiii", $fechaReserva, $horaReserva, $idUsuario, $idPeluqueria, $servicio);

    // Ejecutar la consulta de inserción
    if ($stmtInsertar->execute()) {
        // Obtener el ID de la reserva recién insertada
        $idReserva = $conn->insert_id;

        // Consulta para insertar en la tabla 'historial_reservas'
        $sqlHistorial = "INSERT INTO historial_reservas (id_reserva, id_usuario, id_peluqueria)
                         VALUES (?, ?, ?)";
        $stmtHistorial = $conn->prepare($sqlHistorial);
        $stmtHistorial->bind_param("iii", $idReserva, $idUsuario, $idPeluqueria);

        // Ejecutar la consulta de inserción en el historial
        if ($stmtHistorial->execute()) {
            echo "<script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    title: '¡Reserva exitosa!',
                    text: 'Tu reserva ha sido creada correctamente.',
                    icon: 'success',
                    color: '#fff', // Texto blanco
                    background: '#000', // Fondo negro
                    confirmButtonText: 'Aceptar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = '../users/perfil.php'; // Redirigir al perfil
                    }
                });
            });
            </script>";
        } else {
            echo "<script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    title: 'Error',
                    text: 'Error al insertar en el historial: " . $stmtHistorial->error . "',
                    icon: 'error',
                    color: '#fff', // Texto blanco
                    background: '#000', // Fondo negro
                    confirmButtonText: 'Aceptar'
                });
            });
            </script>";
        }

        // Cerrar la declaración del historial
        $stmtHistorial->close();
    } else {
        echo "<script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                title: 'Error',
                text: 'Error al crear la reserva: " . $stmtInsertar->error . "',
                icon: 'error',
                color: '#fff', // Texto blanco
                background: '#000', // Fondo negro
                confirmButtonText: 'Aceptar'
            });
        });
        </script>";
    }

    // Cerrar las declaraciones preparadas
    $stmtUsuario->close();
    $stmtPeluqueria->close();
    $stmtVerificar->close();
    $stmtInsertar->close();
}

// Cerrar la conexión a la base de datos
$conn->close();
?>
