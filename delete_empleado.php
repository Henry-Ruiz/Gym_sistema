<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: index.html");
    exit();
}

include 'php/db_config.php'; // Conexión a la base de datos

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Eliminar empleado
    $query = "DELETE FROM empleados WHERE id = $id";
    
    if (mysqli_query($conn, $query)) {
        header("Location: gestion_empleados.php");
        exit();
    } else {
        echo "Error al eliminar el empleado.";
    }
}
?>
