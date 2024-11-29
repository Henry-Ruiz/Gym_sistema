<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: index.html");
    exit();
}

include 'php/db_config.php'; // Conexión a la base de datos

// Obtener el ID de la venta a eliminar
$venta_id = $_GET['id'];

// Eliminar los detalles de la venta
mysqli_query($conn, "DELETE FROM detalle_venta WHERE venta_id = '$venta_id'");

// Eliminar la venta
mysqli_query($conn, "DELETE FROM ventas WHERE id = '$venta_id'");

header("Location: gestion_ventas.php");
exit();
