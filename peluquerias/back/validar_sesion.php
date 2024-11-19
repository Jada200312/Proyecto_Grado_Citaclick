<?php

include 'conexion.php';


session_start();


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $nombre_usuario = $_POST['username'];
    $contrasena = $_POST['password'];

    
    $sql = "SELECT * FROM usuarios WHERE nombre_usuario = ?";
    $stmt = $conn->prepare($sql);

    
    if ($stmt === false) {
        die("Error al preparar la consulta: " . $conn->error);
    }

    // Vinculo el parámetro
    $stmt->bind_param("s", $nombre_usuario);

    // Ejecuto la consulta
    $stmt->execute();

    // Obtengoel resultado de la consulta
    $result = $stmt->get_result();

    // Verifico si se encontró un usuario con el nombre proporcionado
    if ($result->num_rows > 0) {
        // Obtengo la fila del resultado como un array asociativo
        $row = $result->fetch_assoc();

        // Verifico la contraseña
        if (password_verify($contrasena, $row['contraseña'])) {
            // La contraseña es correcta, inicia sesión y redirige
            $_SESSION['nombre_usuario'] = $nombre_usuario;
echo "<script>
    alert('Inicio de sesión exitoso. Bienvenido $nombre_usuario');
    window.location.href = '../index.php';
</script>";
        } else {
            echo "Contraseña incorrecta";
        }
    } else {
        echo "Nombre de usuario no encontrado";
    }

    // Cierro la declaración
    $stmt->close();
} else {
    echo "No se recibieron datos por el formulario";
}

// Cierro la conexión
$conn->close();
?>
