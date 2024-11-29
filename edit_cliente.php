<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: index.html");
    exit();
}

include 'php/db_config.php'; // Conexión a la base de datos

// Verificar si se ha recibido un ID válido
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: gestion_clientes.php");
    exit();
}

$id = intval($_GET['id']);
$query = "SELECT * FROM clientes WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('i', $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    header("Location: gestion_clientes.php");
    exit();
}

$cliente = $result->fetch_assoc();

// Procesar la actualización del cliente
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'];
    $correo = $_POST['correo'];
    $telefono = $_POST['telefono'];
    $membresia = $_POST['membresia'];
    $descripcion = $_POST['descripcion'];
    $fecha_inicio = $_POST['fecha_inicio'];
    $fecha_fin = $_POST['fecha_fin'];

    $updateQuery = "UPDATE clientes SET nombre = ?, correo = ?, telefono = ?, membresia = ?, descripcion = ?, fecha_inicio = ?, fecha_fin = ? WHERE id = ?";
    $updateStmt = $conn->prepare($updateQuery);
    $updateStmt->bind_param('sssssssi', $nombre, $correo, $telefono, $membresia, $descripcion, $fecha_inicio, $fecha_fin, $id);

    if ($updateStmt->execute()) {
        header("Location: gestion_clientes.php?msg=Cliente actualizado correctamente");
        exit();
    } else {
        $error = "Error al actualizar el cliente. Por favor, inténtelo de nuevo.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Cliente</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="navbar">
        <div class="logo">CorpusLine Gym</div>
        <div class="menu">
            <a href="gestion_clientes.php">Ver Clientes</a>
            <a href="add_cliente.php">Añadir Cliente</a>
            <a href="dashboard_admin.php">Dashboard</a>
            <a href="logout.php">Cerrar sesión</a>
        </div>
    </div>
    <div class="content">
        <h1>Editar Cliente</h1>
        <?php if (isset($error)) { echo "<div class='error'>$error</div>"; } ?>
        <form method="POST" class="form-cliente">
            <label for="nombre">Nombre:</label>
            <input type="text" name="nombre" id="nombre" value="<?php echo htmlspecialchars($cliente['nombre']); ?>" required>

            <label for="correo">Correo:</label>
            <input type="email" name="correo" id="correo" value="<?php echo htmlspecialchars($cliente['correo']); ?>" required>

            <label for="telefono">Teléfono:</label>
            <input type="text" name="telefono" id="telefono" value="<?php echo htmlspecialchars($cliente['telefono']); ?>" required>

            <label for="membresia">Membresía:</label>
            <input type="text" name="membresia" id="membresia" value="<?php echo htmlspecialchars($cliente['membresia']); ?>" required>

            <label for="descripcion">Descripción:</label>
            <textarea name="descripcion" id="descripcion" required><?php echo htmlspecialchars($cliente['descripcion']); ?></textarea>

            <label for="fecha_inicio">Fecha Inicio:</label>
            <input type="date" name="fecha_inicio" id="fecha_inicio" value="<?php echo htmlspecialchars($cliente['fecha_inicio']); ?>" required>

            <label for="fecha_fin">Fecha Fin:</label>
            <input type="date" name="fecha_fin" id="fecha_fin" value="<?php echo htmlspecialchars($cliente['fecha_fin']); ?>" required>

            <button type="submit" class="btn-submit">Guardar Cambios</button>
        </form>
    </div>
</body>
</html>
