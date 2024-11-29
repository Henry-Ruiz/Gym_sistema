<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: index.html");
    exit();
}

include 'php/db_config.php'; // Conexión a la base de datos

if (isset($_GET['id'])) {
    $usuario_id = $_GET['id'];

    // Eliminar el usuario
    $query = "DELETE FROM users WHERE id = $usuario_id";
    if (mysqli_query($conn, $query)) {
        header("Location: gestion_usuarios.php");
        exit();
    } else {
        echo "Error al eliminar el usuario: " . mysqli_error($conn);
    }
} else {
    echo "No se ha especificado un ID de usuario.";
}
?>
