<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: index.html");
    exit();
}

include 'php/db_config.php'; // Conexión a la base de datos
require_once 'fpdf/fpdf.php';

// Obtener la venta y detalles
$venta_id = $_GET['id'];
$query_venta = "SELECT * FROM ventas WHERE id = '$venta_id'";
$venta_result = mysqli_query($conn, $query_venta);
$venta = mysqli_fetch_assoc($venta_result);

$query_detalle = "SELECT * FROM detalle_venta WHERE venta_id = '$venta_id'";
$detalle_result = mysqli_query($conn, $query_detalle);

// Crear PDF
$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial', 'B', 16);

$pdf->Cell(200, 10, "Factura de Venta #$venta_id", 0, 1, 'C');
$pdf->Ln(10);

$pdf->SetFont('Arial', '', 12);
$pdf->Cell(100, 10, "Fecha: " . $venta['fecha']);
$pdf->Ln(10);

$pdf->Cell(100, 10, "Cliente: " . ($venta['cliente_id'] ? 'Registrado' : $venta['comprador_nombre']));
$pdf->Ln(10);

$pdf->Cell(100, 10, "Tipo de Pago: " . $venta['tipo_pago']);
$pdf->Ln(10);

$pdf->Cell(100, 10, "Productos:");
$pdf->Ln(10);

$pdf->SetFont('Arial', '', 10);
while ($detalle = mysqli_fetch_assoc($detalle_result)) {
    $query_producto = "SELECT * FROM productos WHERE id = '" . $detalle['producto_id'] . "'";
    $producto_result = mysqli_query($conn, $query_producto);
    $producto = mysqli_fetch_assoc($producto_result);
    
    $pdf->Cell(100, 10, $producto['nombre'] . " x " . $detalle['cantidad'] . " - $" . $detalle['total']);
    $pdf->Ln(6);
}

$pdf->Ln(10);
$pdf->Cell(100, 10, "Total: $" . $venta['total']);
$pdf->Output();
