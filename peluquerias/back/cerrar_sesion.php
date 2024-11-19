<?php

session_start();

// Limpia todas las variables de sesión
$_SESSION = array();

// Destruye la sesión
session_destroy();

// Muestra una alerta personalizada usando JavaScript antes de redirigir
echo "<script>
    alert('Has cerrado sesión correctamente.');
    window.location.href = '../index.php';
</script>";
exit();
?>
