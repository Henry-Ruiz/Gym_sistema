<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: index.html");
    exit();
}

include 'php/db_config.php'; // Conexión a la base de datos

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Recoger los datos del formulario
    $comprador_nombre = $_POST['comprador_nombre']; // Nombre del comprador
    $total = $_POST['total'];
    $tipo_pago = $_POST['tipo_pago'];
    $fecha = date('Y-m-d H:i:s'); // Fecha actual de la venta

    // Insertar en la base de datos
    $query = "INSERT INTO ventas (comprador_nombre, total, tipo_pago, fecha) 
              VALUES ('$comprador_nombre', '$total', '$tipo_pago', '$fecha')";
    
    if (mysqli_query($conn, $query)) {
        $venta_id = mysqli_insert_id($conn); // Obtener el ID de la venta insertada

        // Insertar los productos de la venta (detalles de la venta)
        foreach ($_POST['productos'] as $producto_id => $cantidad) {
            // Consultar el precio de cada producto
            $query_producto = "SELECT precio FROM productos WHERE id = '$producto_id'";
            $producto_result = mysqli_query($conn, $query_producto);
            $producto = mysqli_fetch_assoc($producto_result);

            $precio = $producto['precio'];
            $total_producto = $precio * $cantidad;

            // Insertar detalle de la venta
            $query_detalle = "INSERT INTO detalle_venta (venta_id, producto_id, cantidad, precio, total) 
                              VALUES ('$venta_id', '$producto_id', '$cantidad', '$precio', '$total_producto')";
            mysqli_query($conn, $query_detalle);
        }

        header("Location: gestion_ventas.php");
        exit();
    } else {
        echo "Error al realizar la venta.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Añadir Venta</title>
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
        <h1>Añadir Venta</h1>
        <form method="POST">
            <label for="comprador_nombre">Nombre del Comprador</label>
            <input type="text" id="comprador_nombre" name="comprador_nombre" required>

            <label for="productos">Productos</label>
            <select id="productos" name="productos[1]" required>
                <?php
                // Cargar productos de la base de datos
                $query_productos = "SELECT id, nombre FROM productos";
                $productos_result = mysqli_query($conn, $query_productos);
                while ($producto = mysqli_fetch_assoc($productos_result)) {
                    echo "<option value='{$producto['id']}'>{$producto['nombre']}</option>";
                }
                ?>
            </select>
            <input type="number" name="productos[1]" value="1" min="1">

            <label for="tipo_pago">Tipo de Pago</label>
            <select id="tipo_pago" name="tipo_pago" required>
                <option value="Efectivo">Efectivo</option>
                <option value="Tarjeta">Tarjeta</option>
            </select>

            <label for="total">Total</label>
            <input type="number" id="total" name="total" step="0.01" required>

            <input type="submit" class="btn-submit" value="Añadir Venta">
        </form>
    </div>
</body>
</html>
