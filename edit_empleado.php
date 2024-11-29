<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: index.html");
    exit();
}

include 'php/db_config.php'; // Conexión a la base de datos

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Obtener los datos del empleado
    $query = "SELECT * FROM empleados WHERE id = $id";
    $result = mysqli_query($conn, $query);
    $empleado = mysqli_fetch_assoc($result);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Recoger los datos del formulario
    $nombre = $_POST['nombre'];
    $correo = $_POST['correo'];
    $telefono = $_POST['telefono'];
    $puesto = $_POST['puesto'];
    $salario = $_POST['salario'];
    $fecha_inicio = $_POST['fecha_inicio'];
    $fecha_fin = $_POST['fecha_fin'];

    // Actualizar los datos del empleado
    $query = "UPDATE empleados SET nombre = '$nombre', correo = '$correo', telefono = '$telefono', puesto = '$puesto', salario = '$salario', fecha_inicio = '$fecha_inicio', fecha_fin = '$fecha_fin' WHERE id = $id";
    
    if (mysqli_query($conn, $query)) {
        header("Location: gestion_empleados.php");
        exit();
    } else {
        echo "Error al actualizar el empleado.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Empleado</title>
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
        <h1>Editar Empleado</h1>
        <form method="POST" class="form-cliente">
            <label for="nombre">Nombre</label>
            <input type="text" id="nombre" name="nombre" value="<?php echo $empleado['nombre']; ?>" required>

            <label for="correo">Correo</label>
            <input type="email" id="correo" name="correo" value="<?php echo $empleado['correo']; ?>" required>

            <label for="telefono">Teléfono</label>
            <input type="text" id="telefono" name="telefono" value="<?php echo $empleado['telefono']; ?>">

            <label for="puesto">Puesto</label>
            <input type="text" id="puesto" name="puesto" value="<?php echo $empleado['puesto']; ?>">

            <label for="salario">Salario</label>
            <input type="number" id="salario" name="salario" value="<?php echo $empleado['salario']; ?>" step="0.01">

            <label for="fecha_inicio">Fecha de Inicio</label>
            <input type="date" id="fecha_inicio" name="fecha_inicio" value="<?php echo $empleado['fecha_inicio']; ?>" required>

            <label for="fecha_fin">Fecha de Fin</label>
            <input type="date" id="fecha_fin" name="fecha_fin" value="<?php echo $empleado['fecha_fin']; ?>">

            <input type="submit" class="btn-submit" value="Actualizar Empleado">
        </form>
    </div>
</body>
</html>
