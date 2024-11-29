<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: index.html");
    exit();
}

include 'php/db_config.php'; // Conexión a la base de datos

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Recoger los datos del formulario
    $nombre = $_POST['nombre'];
    $correo = $_POST['correo'];
    $telefono = $_POST['telefono'];
    $puesto = $_POST['puesto'];
    $salario = $_POST['salario'];
    $fecha_inicio = $_POST['fecha_inicio'];
    $fecha_fin = $_POST['fecha_fin'];

    // Insertar en la base de datos
    $query = "INSERT INTO empleados (nombre, correo, telefono, puesto, salario, fecha_inicio, fecha_fin) 
              VALUES ('$nombre', '$correo', '$telefono', '$puesto', '$salario', '$fecha_inicio', '$fecha_fin')";
    
    if (mysqli_query($conn, $query)) {
        header("Location: gestion_empleados.php");
        exit();
    } else {
        echo "Error al añadir el empleado.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Añadir Empleado</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="navbar">
        <div class="logo">CorpusLine Gym</div>
        <div class="menu">
            <a href="gestion_empleados.php">Gestión de Empleados</a>
            <a href="logout.php">Cerrar sesión</a>
        </div>
    </div>
    <div class="content">
        <h1>Añadir Empleado</h1>
        <form method="POST" class="form-cliente">
            <label for="nombre">Nombre</label>
            <input type="text" id="nombre" name="nombre" required>

            <label for="correo">Correo</label>
            <input type="email" id="correo" name="correo" required>

            <label for="telefono">Teléfono</label>
            <input type="text" id="telefono" name="telefono">

            <label for="puesto">Puesto</label>
            <input type="text" id="puesto" name="puesto">

            <label for="salario">Salario</label>
            <input type="number" id="salario" name="salario" step="0.01">

            <label for="fecha_inicio">Fecha de Inicio</label>
            <input type="date" id="fecha_inicio" name="fecha_inicio" required>

            <label for="fecha_fin">Fecha de Fin</label>
            <input type="date" id="fecha_fin" name="fecha_fin">

            <input type="submit" class="btn-submit" value="Añadir Empleado">
        </form>
    </div>
</body>
</html>
