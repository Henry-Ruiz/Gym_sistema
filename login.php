<?php
session_start();
require_once('php/db_config.php');  // Asegúrate de que la conexión esté bien

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $usuario = $_POST['usuario'];
    $password = $_POST['password'];

    // Consulta SQL para obtener el usuario desde la base de datos
    $stmt = $conn->prepare("SELECT * FROM users WHERE usuario = ?");
    $stmt->bind_param("s", $usuario);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        
        // Compara las contraseñas directamente sin hashing
        if ($password == $user['password']) {
            // Iniciar sesión
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['role'] = $user['role'];

            // Redirigir al dashboard correspondiente
            if ($user['role'] == 'admin') {
                header("Location: dashboard_admin.php");
            } else {
                header("Location: dashboard_empleado.php");
            }
            exit();
        } else {
            echo "Contraseña incorrecta";
        }
    } else {
        echo "Usuario no encontrado";
    }
}
?>
