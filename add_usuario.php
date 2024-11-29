<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: index.html");
    exit();
}

include 'php/db_config.php'; // Conexión a la base de datos

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre = $_POST['nombre'];
    $correo = $_POST['correo'];
    $usuario = $_POST['usuario'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT); // Seguridad con hash
    $role = $_POST['role'];

    // Insertar usuario en la base de datos
    $query = "INSERT INTO users (nombre, correo, usuario, password, role) 
              VALUES ('$nombre', '$correo', '$usuario', '$password', '$role')";

    if (mysqli_query($conn, $query)) {
        header("Location: gestion_usuarios.php");
        exit();
    } else {
        echo "Error al añadir el usuario: " . mysqli_error($conn);
    }
}
?>
