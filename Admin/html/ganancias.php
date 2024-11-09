<?php
session_start(); // Inicia la sesión

// Verifica si el usuario está logueado
if (!isset($_SESSION['nombre_usuario'])) {
    header("Location: login.html");
    exit();
}
include ('../../back/conexion.php');

$nombre_usuario = $_SESSION['nombre_usuario'];
$query = "SELECT id FROM peluquerias WHERE nombre_usuario = '$nombre_usuario'";
$result = mysqli_query($conn, $query);
$row = mysqli_fetch_assoc($result);
$id_peluqueria = $row['id'];

// Consulta para las ganancias diarias
$query = "SELECT DATE(fechaReserva) AS dia, SUM(servicios.precio) AS total_dia
          FROM reservas
          JOIN servicios ON reservas.servicio = servicios.id
          where reservas.id_peluqueria = $id_peluqueria
          GROUP BY DATE(fechaReserva)
          ORDER BY dia";
$result = mysqli_query($conn, $query);

$fechas = [];
$ganancias = [];

while ($row = mysqli_fetch_assoc($result)) {
    $fechas[] = $row['dia'];
    $ganancias[] = $row['total_dia'];
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/styles_ganancias.css">
    <title>CITA CLICK</title>
        
    <link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="../css/utilidades.css">
    <link rel="icon" href="../recursos/log.png" type="image/x-icon">
    <link rel="icon" href="../recursos/log.png" type="image/png">
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
            <a href="index.php">Inicio</a>
            <a href="ganancias.php" class="navbar_link active">Ganancias Diarias</a>
            <a href="../../back/cerrar_sesion.php">Cerrar Sesión</a>
        </nav>
    </div>
    
    <div class="filters">
        <img src="../recursos/img/logocc.jpg" alt="">
        <button class="filter_button active" data-filter="all">Diario</button>
        <button onclick="window.location.href='gananciassemanales.php';" class="filter_button">Semanal</button>
        <button onclick="window.location.href='gananciasmensuales.php';" class="filter_button">Mensual</button>
    </div>

    <div class="container">
        <canvas id="gananciasChart"></canvas>
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
    <script src="../js/menu_admin.js"></script>
<script>
  

    // Gráfica de Ganancias Diarias
    const ctx = document.getElementById('gananciasChart').getContext('2d');
    const gananciasChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: <?php echo json_encode($fechas); ?>,
            datasets: [{
    label: 'Ganancias Diarias',
    data: <?php echo json_encode($ganancias); ?>,
    borderColor: '#FF6F00', // Color del borde
    backgroundColor: 'rgba(224, 224, 224, 0.05)', // Color del fondo con transparencia
    pointBackgroundColor: '#FF6F00', // Color de los puntos
    pointBorderColor: '#fff', // Color del borde de los puntos
    borderWidth: 2,
    tension: 0.4, // Curvatura de la línea
    fill: true // Rellenar el área bajo la línea
}]

        },
        options: {
            responsive: true,
            maintainAspectRatio: false, // Mantener la relación de aspecto
            plugins: {
                legend: {
                    display: true,
                    position: 'top', // Posición de la leyenda
                    labels: {
                        font: {
                            size: 14,
                            family: 'Poppins, sans-serif', // Fuente
                            weight: 'bold'
                        },
                        color: '#333' // Color del texto
                    }
                },
                tooltip: {
                    backgroundColor: 'rgba(255, 255, 255, 0.9)', // Color de fondo del tooltip
                    titleColor: '#333', // Color del título del tooltip
                    bodyColor: '#333', // Color del cuerpo del tooltip
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        color: 'white', // Color de las etiquetas del eje Y
                        font: {
                            size: 12,
                            family: 'Poppins, sans-serif' // Fuente
                        }
                    },
                    grid: {
                        color: 'rgba(0, 0, 0, 0.1)', // Color de la cuadrícula
                        borderColor: 'rgba(0, 0, 0, 0.2)', // Color del borde del eje Y
                    }
                },
                x: {
                    ticks: {
                        color: 'white', // Color de las etiquetas del eje X
                        font: {
                            size: 12,
                            family: 'Poppins, sans-serif' // Fuente
                        }
                    },
                    grid: {
                        color: 'rgba(0, 0, 0, 0.1)', // Color de la cuadrícula
                        borderColor: 'rgba(0, 0, 0, 0.2)', // Color del borde del eje X
                    }
                }
            }
        }
    });
</script>

</body>
</html>
