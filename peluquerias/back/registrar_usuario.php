<?php

include 'conexion.php';


$nombres = $_POST['nombres'];
$apellidos = $_POST['apellidos'];
$nombre_usuario = $_POST['nombre_usuario'];
$correo_celular = $_POST['correo_celular'];
$contrasena_registro = $_POST['contrasena_registro'];

// Hash de la contraseña para almacenarla encriptada
$hashed_password = password_hash($contrasena_registro, PASSWORD_DEFAULT);


$sql = "INSERT INTO usuarios (nombre_usuario, contraseña, nombres, apellidos, celular) VALUES (?, ?, ?, ?, ?)";

// Preparp la declaración
$stmt = $conn->prepare($sql);

// Verifico si la preparación de la declaración fue exitosa
if ($stmt === false) {
    die("Error al preparar la consulta: " . $conn->error);
}

// Vinculo los parámetros
$stmt->bind_param("sssss", $nombre_usuario, $hashed_password, $nombres, $apellidos, $correo_celular);

// Ejecuto la declaración
if ($stmt->execute()) {
    echo "<script>
    alert('Has cerrado sesión correctamente.');
    window.location.href = '../access.php';
</script>";
} else {
    echo "Error al registrar: " . $stmt->error;
}

// Cierro la declaración y la conexión
$stmt->close();
$conn->close();
?>
