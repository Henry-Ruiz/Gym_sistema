<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: index.html");
    exit();
}

include 'php/db_config.php'; // Conexión a la base de datos

// Obtener la ID de la venta
$venta_id = $_GET['id'];

// Obtener los detalles de la venta
$query_venta = "SELECT v.id, v.comprador_nombre, v.total, v.tipo_pago, v.fecha, c.nombre AS cliente_nombre
                FROM ventas v
                LEFT JOIN clientes c ON v.cliente_id = c.id
                WHERE v.id = '$venta_id'";

$venta_result = mysqli_query($conn, $query_venta);
$venta = mysqli_fetch_assoc($venta_result);

// Obtener los detalles de los productos vendidos
$query_detalle = "SELECT p.nombre, dv.cantidad, dv.precio, dv.total
                  FROM detalle_venta dv
                  LEFT JOIN productos p ON dv.producto_id = p.id
                  WHERE dv.venta_id = '$venta_id'";

$detalles_result = mysqli_query($conn, $query_detalle);

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalles de Venta</title>
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
        <h1>Detalles de Venta</h1>

        <p><strong>ID de Venta:</strong> <?= $venta['id'] ?></p>
        <p><strong>Cliente/Comprador:</strong> <?= $venta['cliente_nombre'] ? $venta['cliente_nombre'] : $venta['comprador_nombre'] ?></p>
        <p><strong>Total:</strong> $<?= number_format($venta['total'], 2) ?></p>
        <p><strong>Tipo de Pago:</strong> <?= $venta['tipo_pago'] ?></p>
        <p><strong>Fecha:</strong> <?= date("d/m/Y H:i", strtotime($venta['fecha'])) ?></p>

        <h2>Productos Vendidos</h2>
        <table>
            <thead>
                <tr>
                    <th>Producto</th>
                    <th>Cantidad</th>
                    <th>Precio Unitario</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($detalle = mysqli_fetch_assoc($detalles_result)) { ?>
                    <tr>
                        <td><?= $detalle['nombre'] ?></td>
                        <td><?= $detalle['cantidad'] ?></td>
                        <td>$<?= number_format($detalle['precio'], 2) ?></td>
                        <td>$<?= number_format($detalle['total'], 2) ?></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</body>
</html>
