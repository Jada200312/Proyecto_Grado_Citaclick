<?php
session_start();

if (!isset($_SESSION['nombre_usuario'])) {
    header("Location: ../index.php");
    exit(); 
}

include('../../back/conexion.php');

$nombre_usuario = $_SESSION['nombre_usuario'];
$query = "SELECT id FROM peluquerias WHERE nombre_usuario = '$nombre_usuario'";
$result = mysqli_query($conn, $query);
$row = mysqli_fetch_assoc($result);
$id_peluqueria = $row['id'];

$sql = "SELECT servicios.id, servicios.nombre, servicios.precio, servicios.imagen, categoria.nombreCategoria 
        FROM servicios 
        JOIN categoria ON servicios.id_categoria = categoria.id
        WHERE servicios.id_peluqueria = $id_peluqueria";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar o Eliminar</title>
    <link rel="stylesheet" href="../css/editar.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../css/utilidades.css">
    <link rel="stylesheet" href="../css/styles_ganancias.css">
    <link rel="icon" href="../recursos/log.png" type="image/x-icon">
    <link rel="icon" href="../recursos/log.png" type="image/png">
    <script src="../../JS/filter_button.js"></script>
    <style>
    .hidden {
        display: none;
    }
</style>

</head>
<body>
    <header>
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
                <a href="editaroeliminar.php" class="navbar_link active">Editar o Eliminar</a>
                <a href="../../back/cerrar_sesion.php" class="navbar_link">Cerrar Sesión</a>
            </nav>
        </div>
    </header>

    <main>
        <section class="haircut_section">
            <div class="filters">
                <img src="../recursos/img/logocc.jpg" alt="">
        <button class="filter_button active" data-filter="all">Todos</button>
        <button data-filter="Corte de cabello" class="filter_button">Corte de cabello</button>
        <button data-filter="Corte de barba" class="filter_button">Corte de barba</button>
            </div>

            <div class="haircut_container">
                <?php while($row = $result->fetch_assoc()): ?>
                    <div class="haircut_card" data-category="<?= $row['nombreCategoria']; ?>">
                        <img src="../../recursos/<?= $row['imagen']; ?>" alt="<?= $row['nombre']; ?>">
                        <div class="haircut_info">
                            <h3><?= $row['nombre']; ?></h3>
                            <p>$<?= $row['precio']; ?></p><br>
                            <div class="button-container">
                                <button class="openModalBtn" id="openModalBtn"data-id="<?= $row['id']; ?>" data-name="<?= $row['nombre']; ?>" data-price="<?= $row['precio']; ?>" data-category="<?= $row['nombreCategoria']; ?>">Editar</button>
                                <a href="../back/eliminar_corte.php?id=<?= $row['id']; ?>" class="filterbutton_one" style="text-decoration: none;">Eliminar</a><br>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        </section>
    </main>    

    <div id="modal" class="modal">
        <div class="modal-content">
            <h2>Editar Corte</h2>
            <form id="editForm" action="../back/editar_corte.php" method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="cutName">Editar Nombre del Corte</label>
                    <input type="text" id="cutName" name="cutName" required>
                </div>
                <div class="form-group">
                    <label for="cutPrice">Editar Precio del Corte</label>
                    <input type="number" id="cutPrice" name="cutPrice" required>
                </div>
                <div class="form-group">
                    <label for="cutCategory">Editar categoría</label>
                    <select id="cutCategory" name="cutCategory" required>
                        <option value="1">Corte de cabello</option>
                        <option value="2">Corte de barba</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="cutPhoto">Editar foto del corte</label>
                    <div class="file-input-wrapper">
                        <button type="button" id="fileInputBtn">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"></path></svg>
                        </button>
                        <span id="fileName">No se ha seleccionado archivo</span>
                        <input type="file" id="cutPhoto" name="cutPhoto" accept="image/*" style="display: none;">
                    </div>
                </div>
                <input type="hidden" id="cutId" name="cutId"> <!-- Campo oculto para almacenar el ID -->
                <button type="submit" class="submit-btn">Actualizar</button>
            </form>
        </div>
    </div>    

    <footer>
        <div class="footer-container">
            <div class="footer_social">
                <h4>SOCIAL</h4>
                <ul>
                    <div class="social">
                        <img src="../recursos/2.png" alt="">
                        <li><a href=""><span>Facebook</span></a></li>
                    </div>
                    <div class="social">
                        <img src="../recursos/3.png" alt="">
                        <li><a href=""><span>Instagram</span></a></li>
                    </div>
                    <div class="social">
                        <img src="../recursos/1.png" alt="">
                        <li><a href=""><span>X</span></a></li>
                    </div>
                </ul>
            </div>
            <div class="footer_us">
                <h4>Cita<span>Click</span></h4>
                <p>Es el puente entre la inspiración y la acción</p>
            </div>
            <div class="footer_logo">
                <img src="../recursos/logocco.png" alt="Logo">
            </div>
            <div class="footer_copy">
                <p>&copy; Copyright CITA CLICK 2024  |  
                    <a href="">Terms of Use</a> | 
                    <a href="">Privacy</a> 
                </p>
            </div>
        </div>
    </footer>
    
</body>
<script src="../js/menu_admin.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('modal');
    const editForm = document.getElementById('editForm');
    const fileInput = document.getElementById('cutPhoto');
    const fileInputBtn = document.getElementById('fileInputBtn');
    const fileName = document.getElementById('fileName');

    document.querySelectorAll('.openModalBtn').forEach(button => {
        button.addEventListener('click', function() {
            const id = this.getAttribute('data-id');
            const name = this.getAttribute('data-name');
            const price = this.getAttribute('data-price');
            const category = this.getAttribute('data-category');

            // Rellenar el formulario con los datos del servicio seleccionado
            document.getElementById('cutName').value = name;
            document.getElementById('cutPrice').value = price;
            document.getElementById('cutCategory').value = category; 
            document.getElementById('cutId').value = id;

            // Mostrar modal y desactivar scroll
            modal.style.display = 'block';
            document.body.classList.add('no-scroll');  // Quitar scroll
        });
    });

    window.addEventListener('click', function(event) {
        if (event.target === modal) {
            modal.style.display = 'none';
            document.body.classList.remove('no-scroll');  // Restaurar scroll
        }
    });

    fileInputBtn.addEventListener('click', function() {
        fileInput.click();
    });

    fileInput.addEventListener('change', function() {
        fileName.textContent = this.files[0] ? this.files[0].name : 'No se ha seleccionado archivo';
    });
});


</script>
</html>
