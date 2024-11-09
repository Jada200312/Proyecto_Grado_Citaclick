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


$nombre_corte = mysqli_real_escape_string($conn, $_POST['cutName']);
$precio_corte = floatval($_POST['cutPrice']);
$categoria_corte = intval($_POST['cutCategory']); 
$id_corte = intval($_POST['cutId']); 


$id_check_query = "SELECT * FROM servicios WHERE id = ?";
$stmt_check = $conn->prepare($id_check_query);
$stmt_check->bind_param("i", $id_corte);
$stmt_check->execute();
$result_check = $stmt_check->get_result();

if ($result_check->num_rows === 0) {
    echo "<script>
    document.addEventListener('DOMContentLoaded', function() {
        Swal.fire({
            title: 'Error',
            text: 'El servicio que intentas actualizar no existe.',
            icon: 'error',
            background: '#000',
            color: '#fff',
            confirmButtonText: 'Aceptar'
        }).then(() => {
            window.location.href = '../html/editaroeliminar.php'; // Redirigir a la página de edición
        });
    });
    </script>";
    exit;
}


$foto_corte = $_FILES['cutPhoto'];
$upload_dir = '../../recursos/'; 

$foto_nombre = null; 

if ($foto_corte['error'] == UPLOAD_ERR_OK) {
    $foto_nombre = $foto_corte['name'];
    $foto_tmp = $foto_corte['tmp_name'];
    if (!move_uploaded_file($foto_tmp, $upload_dir . $foto_nombre)) {
        $foto_nombre = null; // No se pudo mover el archivo
    }
}


$query = "UPDATE servicios SET nombre = ?, precio = ?, id_categoria = ?" . ($foto_nombre ? ", imagen = ?" : "") . " WHERE id = ?";
$stmt = $conn->prepare($query);

if ($foto_nombre) {
    $stmt->bind_param("siisi", $nombre_corte, $precio_corte, $categoria_corte, $foto_nombre, $id_corte);
} else {
    $stmt->bind_param("siii", $nombre_corte, $precio_corte, $categoria_corte, $id_corte);
}

if ($stmt->execute()) {
    echo "<script>
    document.addEventListener('DOMContentLoaded', function() {
        Swal.fire({
            title: '¡Actualización exitosa!',
            text: 'El corte se ha actualizado correctamente.',
            icon: 'success',
            background: '#000',
            color: '#fff',
            confirmButtonText: 'Aceptar'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = '../html/editaroeliminar.php'; // Redirigir a la página de edición
            }
        });
    });
    </script>";
} else {
    echo "<script>
    document.addEventListener('DOMContentLoaded', function() {
        Swal.fire({
            title: 'Error',
            text: 'No se pudo actualizar el corte. Intenta nuevamente.',
            icon: 'error',
            background: '#000',
            color: '#fff',
            confirmButtonText: 'Aceptar'
        });
    });
    </script>";
}

$stmt->close();
$conn->close();
?>
