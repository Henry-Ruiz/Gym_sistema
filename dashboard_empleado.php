<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'empleado') {
    header("Location: index.html");
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Empleado</title>
    <link rel="stylesheet" href="styles.css"> <!-- Archivo de estilo externo -->
    <style>
        /* Estilo para centrar la imagen */
        .image-container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 60vh; /* Ajusta la altura según sea necesario */
        }
        .image-container img {
            max-width: 100%; /* Ajusta el tamaño de la imagen */
            height: auto;
        }
    </style>
</head>
<body>
    <div class="navbar">
        <div class="logo">CorpusLine Gym</div>
        <div class="menu">
            <a href="gestion_productos.php">Gestión de Productos</a>
            <a href="gestion_ventas.php">Gestión de Ventas</a>
            <a href="gestion_clientes.php">Gestión de Clientes</a>
            <a href="reporte_stock.php">Reporte de Stock</a>
            <a href="logout.php">Cerrar sesión</a>
        </div>
    </div>
    <div class="content">
        <h1>Bienvenido, Empleado</h1>
        <!-- Contenedor para la imagen -->
        <div class="image-container">
            <img src="logo2.png" alt="Dashboard Empleado">
        </div>
    </div>
</body>
</html>
