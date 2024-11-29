<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: index.html");
    exit();
}

include 'php/db_config.php'; // Conexión a la base de datos

// Mostrar mensaje de éxito o error
if (isset($_GET['message'])) {
    echo '<div class="success">' . htmlspecialchars($_GET['message']) . '</div>';
}

// Obtener todos los clientes
$query = "SELECT * FROM clientes";
$result = mysqli_query($conn, $query);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Clientes</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="navbar">
        <div class="logo">CorpusLine Gym</div>
        <div class="menu">
            <a href="dashboard_admin.php">Dashboard</a>
            <?php if ($_SESSION['role'] == 'admin') { ?>
                <a href="add_cliente.php">Añadir Cliente</a>
            <?php } ?>
            <a href="logout.php">Cerrar sesión</a>
        </div>
    </div>
    <div class="content">
        <h1>Gestión de Clientes</h1>
        <div class="search-bar">
            <form action="gestion_clientes.php" method="GET">
                <input type="text" name="search_email" placeholder="Buscar por correo" />
                <button type="submit">Buscar</button>
            </form>
        </div>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Correo</th>
                    <th>Teléfono</th>
                    <th>Membresía</th>
                    <th>Descripción</th>
                    <th>Fecha Inicio</th>
                    <th>Fecha Fin</th>
                    <?php if ($_SESSION['role'] == 'admin') { ?>
                        <th>Acciones</th>
                    <?php } ?>
                </tr>
            </thead>
            <tbody>
                <?php while ($cliente = mysqli_fetch_assoc($result)) { ?>
                    <tr>
                        <td><?php echo $cliente['id']; ?></td>
                        <td><?php echo $cliente['nombre']; ?></td>
                        <td><?php echo $cliente['correo']; ?></td>
                        <td><?php echo $cliente['telefono']; ?></td>
                        <td><?php echo $cliente['membresia']; ?></td>
                        <td><?php echo $cliente['descripcion']; ?></td>
                        <td><?php echo $cliente['fecha_inicio']; ?></td>
                        <td><?php echo $cliente['fecha_fin']; ?></td>
                        <?php if ($_SESSION['role'] == 'admin') { ?>
                            <td>
                                <a href="edit_cliente.php?id=<?php echo $cliente['id']; ?>">Editar</a> |
                                <a href="delete_cliente.php?id=<?php echo $cliente['id']; ?>" onclick="return confirm('¿Estás seguro de que quieres eliminar este cliente?');">Eliminar</a>
                            </td>
                        <?php } ?>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</body>
</html>
