<?php

session_start(); // Inicia la sesión

// Verifica si el usuario está logueado
if (!isset($_SESSION['nombre_usuario'])) {
    header("Location: login.html");
    exit();
}

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="../recursos/log.png">
<link rel="stylesheet" href="../css/agregar.css">
<link rel="stylesheet" href="../css/utilidades.css">


    <title>CITA CLICK</title>
        
    <link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">
<link rel="stylesheet" href="../css/utilidades.css">
<link rel="icon" href="../recursos/log.png" type="image/x-icon">
<link rel="icon" href="../recursos/log.png" type="image/png">

<script src="../js/funciones.js"></script> 
</head>
<body>
    
    <header></header>
    <div class="navbar_container">
        <div class="navbar_icon">
            <img src="../recursos/home.png" alt="Home Icon">
            <h1>CITA<span>CLICK</span></h1>
        </div>
        <div class="menu_toggle" id="menuToggle">
            <img src="../recursos/menu.png" alt="Menu">
        </div>
        <nav class="navbar_menu" id="navbarMenu">
            <a href="index.php" class="navbar_link ">Inicio</a>
            <a href="agregar.html" class="navbar_link active">Agregar Corte</a>
            <a href="../../back/cerrar_sesion.php" class="navbar_link">Cerrar Sesión</a>
            
        </nav>
    </div>
</header>



<div class="contenedor-principal">
    <!-- Div para la imagen -->
    <div class="contenedor-imagen">
        <img src="../recursos/barber.webp" alt="Barbería">
    </div>

    <!-- Div para el formulario -->
    <div class="contenedor-formulario">
<form action="../back/guardar_corte.php" method="POST" enctype="multipart/form-data">
    <label for="nombre-corte">Inserta Nombre del Corte</label>
    <input type="text" id="nombre-corte" name="nombre_corte" placeholder="Nombre del Corte" required>

    <label for="precio-corte">Inserta Precio del Corte</label>
    <input type="number" id="precio-corte" name="precio_corte" placeholder="Precio del Corte" required>

    <label for="categoria-corte">Selecciona una categoría</label>
    <select id="categoria-corte" name="categoria_corte" required>
        <option value="1">Corte de Cabello</option>
        <option value="2">Corte de Barba</option>
    </select>

    <label for="foto-corte">Inserta una foto del corte</label>
    <input type="file" name="foto_corte" id="foto-corte" required>

    <button type="submit">Aceptar</button>
</form>

    </div>
</div>
</div>
<footer>
    <div class="footer-container">
        <div class="footer_social">
            <h4>SOCIAL</h4>
            <ul>
                <div class="social">
                    <img src="../recursos/2.png" alt="Facebook Icon">
                    <li><a href=""><span>Facebook</span></a></li>
                </div>
                <div class="social">
                    <img src="../recursos/3.png" alt="Instagram Icon">
                    <li><a href=""><span>Instagram</span></a></li>
                </div>
                <div class="social">
                    <img src="../recursos/1.png" alt="X Icon">
                    <li><a href=""><span>X</span></a></li>
                </div>
            </ul>
        </div>

        <div class="footer_us">
            <h4>Cita<span>Click</span></h4>
            <p>Es el puente entre la inspiración y la acción</p>
        </div>

        <div class="footer_logo">
            <img src="../recursos/logocco.png" alt="Logo CitaClick">
        </div>

        <div class="footer_copy">
            <p>&copy; Copyright CITA CLICK 2024  |  
                <a href="">Terms of Use</a> | 
                <a href="">Privacy</a> 
            </p>
        </div>
    </div>
</footer>
        </body><script src="../js/menu_admin.js"></script>
        
        </html>