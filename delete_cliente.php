<?php
session_start();

// Verificar si el usuario está autenticado y tiene el rol de admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: index.html");
    exit();
}

include 'php/db_config.php'; // Conexión a la base de datos

// Verificar si se recibió el ID del cliente a eliminar
if (isset($_GET['id'])) {
    $cliente_id = $_GET['id'];

    // Eliminar cliente de la base de datos
    $query = "DELETE FROM clientes WHERE id = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "i", $cliente_id);

    if (mysqli_stmt_execute($stmt)) {
        // Redirigir a la página de gestión de clientes con mensaje de éxito
        header("Location: gestion_clientes.php?message=Cliente eliminado exitosamente");
        exit();
    } else {
        // En caso de error, redirigir con mensaje de error
        header("Location: gestion_clientes.php?message=Error al eliminar el cliente");
        exit();
    }
} else {
    // Si no se recibe el ID, redirigir a la página de gestión de clientes
    header("Location: gestion_clientes.php");
    exit();
}
?>
