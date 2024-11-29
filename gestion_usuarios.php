<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: index.html");
    exit();
}

include 'php/db_config.php'; // Conexión a la base de datos

// Obtener los usuarios
$query = "SELECT * FROM users";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Usuarios</title>
    <link rel="stylesheet" href="styles.css"> <!-- Archivo de estilo externo -->
</head>
<body>
    <div class="navbar">
        <div class="logo">CorpusLine Gym</div>
        <div class="menu">
            <a href="gestion_productos.php">Gestión de Productos</a>
            <a href="gestion_clientes.php">Gestión de Clientes</a>
            <a href="gestion_empleados.php">Gestión de Empleados</a>
            <a href="gestion_usuarios.php">Gestión de Usuarios</a>
            <a href="logout.php">Cerrar sesión</a>
        </div>
    </div>

    <div class="content">
        <h1>Gestión de Usuarios</h1>

        <!-- Formulario para añadir un nuevo usuario -->
        <h2>Añadir Usuario</h2>
        <form action="add_usuario.php" method="POST">
            <label for="nombre">Nombre</label>
            <input type="text" id="nombre" name="nombre" required>

            <label for="correo">Correo</label>
            <input type="email" id="correo" name="correo" required>

            <label for="usuario">Nombre de usuario</label>
            <input type="text" id="usuario" name="usuario" required>

            <label for="password">Contraseña</label>
            <input type="password" id="password" name="password" required>

            <label for="role">Rol</label>
            <select id="role" name="role" required>
                <option value="admin">Administrador</option>
                <option value="empleado">Empleado</option>
            </select>

            <input type="submit" class="btn-submit" value="Añadir Usuario">
        </form>

        <!-- Mostrar los usuarios -->
        <h2>Usuarios Registrados</h2>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Correo</th>
                    <th>Usuario</th>
                    <th>Rol</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if (mysqli_num_rows($result) > 0) {
                    while ($usuario = mysqli_fetch_assoc($result)) {
                        echo "<tr>";
                        echo "<td>" . $usuario['id'] . "</td>";
                        echo "<td>" . $usuario['nombre'] . "</td>";
                        echo "<td>" . $usuario['correo'] . "</td>";
                        echo "<td>" . $usuario['usuario'] . "</td>";
                        echo "<td>" . $usuario['role'] . "</td>";
                        echo "<td>
                            <a href='edit_usuario.php?id=" . $usuario['id'] . "'>Editar</a> | 
                            <a href='delete_usuario.php?id=" . $usuario['id'] . "'>Eliminar</a>
                        </td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='6'>No hay usuarios registrados</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>
