<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: index.html");
    exit();
}

include 'php/db_config.php'; // Conexión a la base de datos

// Buscar empleados si se proporciona un correo
$search = "";
if (isset($_POST['search'])) {
    $search = $_POST['search'];
    $query = "SELECT * FROM empleados WHERE correo LIKE '%$search%'";
} else {
    $query = "SELECT * FROM empleados";
}

$result = mysqli_query($conn, $query);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Empleados</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="navbar">
        <div class="logo">CorpusLine Gym</div>
        <div class="menu">
            <a href="dashboard_admin.php">Inicio</a>
            <a href="gestion_productos.php">Gestión de Productos</a>
            <a href="gestion_ventas.php">Gestión de Ventas</a>
            <a href="gestion_clientes.php">Gestión de Clientes</a>
            <a href="gestion_empleados.php">Gestión de Empleados</a>
            <a href="logout.php">Cerrar sesión</a>
        </div>
    </div>
    <div class="content">
        <h1>Gestión de Empleados</h1>

        <!-- Barra de búsqueda -->
        <form method="POST" class="search-bar">
            <input type="text" name="search" value="<?php echo $search; ?>" placeholder="Buscar por correo">
            <button type="submit">Buscar</button>
        </form>

        <!-- Tabla de empleados -->
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Correo</th>
                    <th>Teléfono</th>
                    <th>Puesto</th>
                    <th>Salario</th>
                    <th>Fecha de Inicio</th>
                    <th>Fecha de Fin</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($empleado = mysqli_fetch_assoc($result)) { ?>
                    <tr>
                        <td><?php echo $empleado['id']; ?></td>
                        <td><?php echo $empleado['nombre']; ?></td>
                        <td><?php echo $empleado['correo']; ?></td>
                        <td><?php echo $empleado['telefono']; ?></td>
                        <td><?php echo $empleado['puesto']; ?></td>
                        <td><?php echo $empleado['salario']; ?></td>
                        <td><?php echo $empleado['fecha_inicio']; ?></td>
                        <td><?php echo $empleado['fecha_fin']; ?></td>
                        <td>
                            <a href="edit_empleado.php?id=<?php echo $empleado['id']; ?>">Editar</a>
                            <a href="delete_empleado.php?id=<?php echo $empleado['id']; ?>">Eliminar</a>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
        <a href="add_empleado.php" class="btn-submit">Añadir Empleado</a>
    </div>
</body>
</html>
