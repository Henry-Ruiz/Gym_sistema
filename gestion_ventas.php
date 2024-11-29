<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: index.html");
    exit();
}

include 'php/db_config.php'; // Conexión a la base de datos

// Obtener las ventas
$query = "SELECT * FROM ventas";
$result = mysqli_query($conn, $query);

// Obtener los productos disponibles
$query_productos = "SELECT * FROM productos";
$result_productos = mysqli_query($conn, $query_productos);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Ventas</title>
    <link rel="stylesheet" href="styles.css"> <!-- Archivo de estilo externo -->
</head>
<body>
    <div class="navbar">
        <div class="logo">CorpusLine Gym</div>
        <div class="menu">
            <a href="gestion_productos.php">Gestión de Productos</a>
            <a href="gestion_clientes.php">Gestión de Clientes</a>
            <?php if ($_SESSION['role'] == 'admin') { ?>
                <a href="gestion_empleados.php">Gestión de Empleados</a>
                <a href="gestion_usuarios.php">Gestión de Usuarios</a>
            <?php } ?>
            <a href="logout.php">Cerrar sesión</a>
        </div>
    </div>

    <div class="content">
        <h1>Gestión de Ventas</h1>

        <?php if ($_SESSION['role'] == 'admin') { ?>
            <!-- Formulario para añadir una venta -->
            <h2>Añadir Venta</h2>
            <form action="add_venta.php" method="POST">
                <label for="comprador_nombre">Nombre del comprador</label>
                <input type="text" id="comprador_nombre" name="comprador_nombre" required>

                <label for="total">Total</label>
                <input type="number" id="total" name="total" step="0.01" required>

                <label for="tipo_pago">Tipo de Pago</label>
                <select id="tipo_pago" name="tipo_pago" required>
                    <option value="Efectivo">Efectivo</option>
                    <option value="Tarjeta">Tarjeta</option>
                </select>

                <label for="fecha">Fecha</label>
                <input type="datetime-local" id="fecha" name="fecha" required>

                <label for="producto">Producto</label>
                <select id="producto" name="producto" required>
                    <?php
                    if (mysqli_num_rows($result_productos) > 0) {
                        while ($producto = mysqli_fetch_assoc($result_productos)) {
                            echo "<option value='" . $producto['id'] . "'>" . $producto['nombre'] . "</option>";
                        }
                    } else {
                        echo "<option value=''>No hay productos disponibles</option>";
                    }
                    ?>
                </select>

                <input type="submit" class="btn-submit" value="Añadir Venta">
            </form>
        <?php } ?>

        <!-- Mostrar las ventas -->
        <h2>Ventas Registradas</h2>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Comprador</th>
                    <th>Total</th>
                    <th>Tipo de Pago</th>
                    <th>Fecha</th>
                    <?php if ($_SESSION['role'] == 'admin') { ?>
                        <th>Acciones</th>
                    <?php } ?>
                </tr>
            </thead>
            <tbody>
                <?php
                if (mysqli_num_rows($result) > 0) {
                    while ($venta = mysqli_fetch_assoc($result)) {
                        echo "<tr>";
                        echo "<td>" . $venta['id'] . "</td>";
                        echo "<td>" . $venta['comprador_nombre'] . "</td>";
                        echo "<td>" . $venta['total'] . "</td>";
                        echo "<td>" . $venta['tipo_pago'] . "</td>";
                        echo "<td>" . $venta['fecha'] . "</td>";
                        if ($_SESSION['role'] == 'admin') {
                            echo "<td>
                                <a href='edit_venta.php?id=" . $venta['id'] . "'>Editar</a> | 
                                <a href='delete_venta.php?id=" . $venta['id'] . "'>Eliminar</a> | 
                                <a href='factura_venta.php?id=" . $venta['id'] . "'>Generar Factura</a>
                            </td>";
                        } else {
                            echo "<td>No tiene permisos para gestionar</td>";
                        }
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='6'>No hay ventas registradas</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>
