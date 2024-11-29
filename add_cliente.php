<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: index.html");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    include 'php/db_config.php'; // Conexión a la base de datos

    $nombre = $_POST['nombre'];
    $correo = $_POST['correo'];
    $telefono = $_POST['telefono'];
    $membresia = $_POST['membresia'];
    $descripcion = $_POST['descripcion'];
    $fecha_inicio = $_POST['fecha_inicio'];
    $fecha_fin = $_POST['fecha_fin'];

    $query = "INSERT INTO clientes (nombre, correo, telefono, membresia, descripcion, fecha_inicio, fecha_fin)
              VALUES ('$nombre', '$correo', '$telefono', '$membresia', '$descripcion', '$fecha_inicio', '$fecha_fin')";
    
    if (mysqli_query($conn, $query)) {
        echo "Cliente añadido exitosamente.";
    } else {
        echo "Error al añadir cliente: " . mysqli_error($conn);
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Añadir Cliente</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="navbar">
        <div class="logo">CorpusLine Gym</div>
        <div class="menu">
            <a href="dashboard_admin.php">Inicio</a>
            <a href="gestion_clientes.php">Gestión de Clientes</a>
            <a href="logout.php">Cerrar sesión</a>
        </div>
    </div>
    <div class="content">
        <h1>Añadir Cliente</h1>
        <form action="add_cliente.php" method="POST">
            <label for="nombre">Nombre:</label>
            <input type="text" id="nombre" name="nombre" required>
            <label for="correo">Correo:</label>
            <input type="email" id="correo" name="correo" required>
            <label for="telefono">Teléfono:</label>
            <input type="text" id="telefono" name="telefono" required>
            <label for="membresia">Membresía:</label>
            <input type="text" id="membresia" name="membresia" required>
            <label for="descripcion">Descripción:</label>
            <textarea id="descripcion" name="descripcion"></textarea>
            <label for="fecha_inicio">Fecha de Inicio:</label>
            <input type="date" id="fecha_inicio" name="fecha_inicio" required>
            <label for="fecha_fin">Fecha de Fin:</label>
            <input type="date" id="fecha_fin" name="fecha_fin" required>
            <button type="submit">Añadir Cliente</button>
        </form>
    </div>
</body>
</html>
