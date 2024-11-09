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
include ('../../back/conexion.php');
// Recoge los datos del formulario
$nombre_corte = $_POST['nombre_corte'];
$precio_corte = $_POST['precio_corte'];
$categoria_corte = $_POST['categoria_corte'];
$nombre_usuario = $_SESSION['nombre_usuario'];
$query = "SELECT id FROM peluquerias WHERE nombre_usuario = '$nombre_usuario'";
$result = mysqli_query($conn, $query);
$row = mysqli_fetch_assoc($result);
$id_peluqueria = $row['id'];

// Manejo de la subida de archivos
$foto_corte = $_FILES['foto_corte'];
$foto_nombre = $foto_corte['name'];
$foto_tmp = $foto_corte['tmp_name'];
$upload_dir = '../../recursos/'; 

move_uploaded_file($foto_tmp, $upload_dir . $foto_nombre);

$query = "INSERT INTO servicios (nombre, precio, imagen, id_categoria, id_peluqueria) VALUES (?, ?, ?, ?, ?)";
$stmt = $conn->prepare($query);
$stmt->bind_param("sisii", $nombre_corte, $precio_corte, $foto_nombre, $categoria_corte, $id_peluqueria);

if ($stmt->execute()) {
        echo "<script>
    document.addEventListener('DOMContentLoaded', function() {
        Swal.fire({
            title: '¡Registro exitoso!',
            text: 'El corte se ha registrado creada correctamente.',
            icon: 'success',
            background: '#000', // Fondo negro
            color: '#fff', // Texto blanco para mejor visibilidad
            confirmButtonText: 'Aceptar'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = '../html/agregar.php'; // Redirigir a la página de acceso
            }
        });
    });
    </script>";
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
