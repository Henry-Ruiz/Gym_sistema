<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: index.html");
    exit();
}

include 'php/db_config.php'; // Conexión a la base de datos

// Obtener el ID de la venta a editar
$venta_id = $_GET['id'];

// Obtener los detalles de la venta
$query_venta = "SELECT * FROM ventas WHERE id = '$venta_id'";
$venta_result = mysqli_query($conn, $query_venta);
$venta = mysqli_fetch_assoc($venta_result);

// Obtener los productos
$query_productos = "SELECT * FROM productos";
$productos_result = mysqli_query($conn, $query_productos);

// Obtener los clientes
$query_clientes = "SELECT * FROM clientes";
$clientes_result = mysqli_query($conn, $query_clientes);

// Si se ha enviado el formulario para actualizar
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $cliente_id = $_POST['cliente_id'];
    $comprador_nombre = $_POST['comprador_nombre'];
    $productos = $_POST['producto'];
    $cantidades = $_POST['cantidad'];
    $tipo_pago = $_POST['tipo_pago'];

    // Calcular el total de la venta
    $total_venta = 0;
    foreach ($productos as $index => $producto_id) {
        $query_precio = "SELECT precio FROM productos WHERE id = '$producto_id'";
        $precio_result = mysqli_query($conn, $query_precio);
        $producto = mysqli_fetch_assoc($precio_result);
        $precio = $producto['precio'];
        $cantidad = $cantidades[$index];
        $total_producto = $precio * $cantidad;
        $total_venta += $total_producto;
    }

    // Si no hay cliente seleccionado, se guarda el nombre del comprador
    if (empty($cliente_id)) {
        $cliente_id = NULL;
    }

    // Actualizar la venta en la base de datos
    $query_update = "UPDATE ventas SET cliente_id = '$cliente_id', comprador_nombre = '$comprador_nombre', total = '$total_venta', tipo_pago = '$tipo_pago' WHERE id = '$venta_id'";
    mysqli_query($conn, $query_update);

    // Actualizar los detalles de la venta
    mysqli_query($conn, "DELETE FROM detalle_venta WHERE venta_id = '$venta_id'");
    foreach ($productos as $index => $producto_id) {
        $cantidad = $cantidades[$index];
        $query_precio = "SELECT precio FROM productos WHERE id = '$producto_id'";
        $precio_result = mysqli_query($conn, $query_precio);
        $producto = mysqli_fetch_assoc($precio_result);
        $precio = $producto['precio'];
        $total_producto = $precio * $cantidad;

        $query_detalle = "INSERT INTO detalle_venta (venta_id, producto_id, cantidad, precio, total) 
                          VALUES ('$venta_id', '$producto_id', '$cantidad', '$precio', '$total_producto')";
        mysqli_query($conn, $query_detalle);
    }

    header("Location: gestion_ventas.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Venta</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="navbar">
        <div class="logo">CorpusLine Gym</div>
        <div class="menu">
            <a href="gestion_ventas.php">Gestión de Ventas</a>
            <a href="logout.php">Cerrar sesión</a>
        </div>
    </div>

    <div class="content">
        <h1>Editar Venta</h1>
        <form method="POST">
            <label for="cliente_id">Cliente</label>
            <select id="cliente_id" name="cliente_id">
                <option value="">Sin Cliente</option>
                <?php while ($cliente = mysqli_fetch_assoc($clientes_result)) { ?>
                    <option value="<?= $cliente['id'] ?>" <?= ($cliente['id'] == $venta['cliente_id']) ? 'selected' : '' ?>><?= $cliente['nombre'] ?></option>
                <?php } ?>
            </select>

            <label for="comprador_nombre">Nombre del Comprador</label>
            <input type="text" id="comprador_nombre" name="comprador_nombre" value="<?= $venta['comprador_nombre'] ?>" />

            <label for="producto">Productos</label>
            <div id="productos">
                <?php while ($producto = mysqli_fetch_assoc($productos_result)) { ?>
                    <div class="producto-item">
                        <input type="checkbox" name="producto[]" value="<?= $producto['id'] ?>" />
                        <?= $producto['nombre'] ?> - $<?= $producto['precio'] ?>
                        <input type="number" name="cantidad[]" min="1" value="1" required />
                    </div>
                <?php } ?>
            </div>

            <label for="tipo_pago">Tipo de Pago</label>
            <select id="tipo_pago" name="tipo_pago" required>
                <option value="efectivo" <?= ($venta['tipo_pago'] == 'efectivo') ? 'selected' : '' ?>>Efectivo</option>
                <option value="tarjeta" <?= ($venta['tipo_pago'] == 'tarjeta') ? 'selected' : '' ?>>Tarjeta</option>
            </select>

            <input type="submit" class="btn-submit" value="Actualizar Venta">
        </form>
    </div>
</body>
</html>
