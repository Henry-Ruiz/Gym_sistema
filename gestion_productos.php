<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: index.html");
    exit();
}

include 'php/db_config.php'; // Conexión a la base de datos

// Buscar productos si se proporciona un nombre
$search = "";
if (isset($_POST['search'])) {
    $search = $_POST['search'];
    $query = "SELECT * FROM productos WHERE nombre LIKE '%$search%'";
} else {
    $query = "SELECT * FROM productos";
}

$result = mysqli_query($conn, $query);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Productos</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="navbar">
        <div class="logo">CorpusLine Gym</div>
        <div class="menu">
            <a href="dashboard_admin.php">Inicio</a>
            <a href="gestion_ventas.php">Gestión de Ventas</a>
            <a href="gestion_clientes.php">Gestión de Clientes</a>
            <?php if ($_SESSION['role'] == 'admin') { ?>
                <a href="gestion_empleados.php">Gestión de Empleados</a>
                <a href="gestion_usuarios.php">Gestión de Usuarios</a>
                <a href="add_producto.php">Añadir Producto</a>
            <?php } ?>
            <a href="logout.php">Cerrar sesión</a>
        </div>
    </div>
    <div class="content">
        <h1>Gestión de Productos</h1>

        <!-- Barra de búsqueda -->
        <form method="POST" class="search-bar">
            <input type="text" name="search" value="<?php echo $search; ?>" placeholder="Buscar por nombre">
            <button type="submit">Buscar</button>
        </form>

        <!-- Tabla de productos -->
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Descripción</th>
                    <th>Precio</th>
                    <th>Stock</th>
                    <th>Imagen</th>
                    <?php if ($_SESSION['role'] == 'admin') { ?>
                        <th>Acciones</th>
                    <?php } ?>
                </tr>
            </thead>
            <tbody>
                <?php while ($producto = mysqli_fetch_assoc($result)) { ?>
                    <tr>
                        <td><?php echo $producto['id']; ?></td>
                        <td><?php echo $producto['nombre']; ?></td>
                        <td><?php echo $producto['descripcion']; ?></td>
                        <td><?php echo $producto['precio']; ?></td>
                        <td><?php echo $producto['stock']; ?></td>
                        <td><img src="uploads/<?php echo $producto['imagen']; ?>" alt="<?php echo $producto['nombre']; ?>" width="50"></td>
                        <?php if ($_SESSION['role'] == 'admin') { ?>
                            <td>
                                <a href="edit_producto.php?id=<?php echo $producto['id']; ?>">Editar</a> |
                                <a href="delete_producto.php?id=<?php echo $producto['id']; ?>">Eliminar</a>
                            </td>
                        <?php } ?>
                    </tr>
                <?php } ?>
            </tbody>
        </table>

        <?php if ($_SESSION['role'] == 'admin') { ?>
            <a href="add_producto.php" class="btn-submit">Añadir Producto</a>
        <?php } ?>
    </div>
</body>
</html>
