<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: index.html");
    exit();
}

include 'php/db_config.php'; // Conexión a la base de datos

if (isset($_GET['id'])) {
    $usuario_id = $_GET['id'];
    $query = "SELECT * FROM users WHERE id = $usuario_id";
    $result = mysqli_query($conn, $query);
    $usuario = mysqli_fetch_assoc($result);

    if (!$usuario) {
        echo "Usuario no encontrado.";
        exit();
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre = $_POST['nombre'];
    $correo = $_POST['correo'];
    $usuario_nombre = $_POST['usuario'];
    $password = $_POST['password'] ? password_hash($_POST['password'], PASSWORD_BCRYPT) : $usuario['password'];
    $role = $_POST['role'];

    $query = "UPDATE users SET nombre = '$nombre', correo = '$correo', usuario = '$usuario_nombre', password = '$password', role = '$role' WHERE id = $usuario_id";

    if (mysqli_query($conn, $query)) {
        header("Location: gestion_usuarios.php");
        exit();
    } else {
        echo "Error al actualizar el usuario: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Usuario</title>
</head>
<body>
    <h1>Editar Usuario</h1>
    <form action="edit_usuario.php?id=<?php echo $usuario['id']; ?>" method="POST">
        <label for="nombre">Nombre</label>
        <input type="text" id="nombre" name="nombre" value="<?php echo $usuario['nombre']; ?>" required>

        <label for="correo">Correo</label>
        <input type="email" id="correo" name="correo" value="<?php echo $usuario['correo']; ?>" required>

        <label for="usuario">Nombre de usuario</label>
        <input type="text" id="usuario" name="usuario" value="<?php echo $usuario['usuario']; ?>" required>

        <label for="password">Contraseña</label>
        <input type="password" id="password" name="password">

        <label for="role">Rol</label>
        <select id="role" name="role" required>
            <option value="admin" <?php echo $usuario['role'] == 'admin' ? 'selected' : ''; ?>>Administrador</option>
            <option value="empleado" <?php echo $usuario['role'] == 'empleado' ? 'selected' : ''; ?>>Empleado</option>
        </select>

        <input type="submit" value="Actualizar Usuario">
    </form>
</body>
</html>
