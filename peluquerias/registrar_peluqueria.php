<?php

include('../back/conexion.php');

//Datos para las peluquerías
$nombre = $_POST['nombre'];
$nombre_usuario = $_POST['nombre_usuario'];
$contrasena = password_hash($_POST['contrasena'], PASSWORD_DEFAULT); // Encriptar contraseña
$direccion = $_POST['direccion'];
$ciudad = $_POST['ciudad'];
$celular = $_POST['celular'];
$fecha_registro = date("Y-m-d");
$fecha_vencimiento = date("Y-m-d", strtotime('+1 year'));
$estado = 1; // Por defecto activo

// Datos del horario
$horaInicio = $_POST['horaInicio'];
$horaFin = $_POST['horaFin'];
$intervalo_tiempo = $_POST['intervalo_tiempo'];

// Manejo la ruta de la imagen
$imagen = $_FILES['imagen'];
$directorio_imagen = "uploads/";
$nombre_imagen = basename($imagen["name"]);
$ruta_imagen = $directorio_imagen . $nombre_imagen;


if (move_uploaded_file($imagen["tmp_name"], $ruta_imagen)) {
    // Inserto el horario 
    $sql_horario = "INSERT INTO horarios (horaInicio, horaFin, intervalo_tiempo)
                    VALUES ('$horaInicio', '$horaFin', '$intervalo_tiempo')";

    if ($conn->query($sql_horario) === TRUE) {
        // Obtengo el ID del horario recién creado
        $id_horario = $conn->insert_id;

        // Inserto los datos de la peluquería
        $sql_peluqueria = "INSERT INTO peluquerias (nombre, nombre_usuario, contraseña, direccion, ciudad, id_horario, celular, fecha_registro, fecha_vencimiento, estado, imagen)
                           VALUES ('$nombre', '$nombre_usuario', '$contrasena', '$direccion', '$ciudad', '$id_horario', '$celular', '$fecha_registro', '$fecha_vencimiento', '$estado', '$ruta_imagen')";

        if ($conn->query($sql_peluqueria) === TRUE) {
            echo "Registro exitoso de la peluquería.";
        } else {
            echo "Error: " . $sql_peluqueria . "<br>" . $conn->error;
        }
    } else {
        echo "Error al crear el horario: " . $conn->error;
    }
} else {
    echo "Hubo un error al subir la imagen.";
}

$conn->close();
?>
