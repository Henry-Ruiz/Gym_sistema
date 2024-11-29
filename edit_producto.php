<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: index.html");
    exit();
}

include 'php/db_config.php'; // Conexión a la base de datos

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Obtener los datos del producto
    $query = "SELECT * FROM productos WHERE id = $id";
    $result = mysqli_query($conn, $query);
    $producto = mysqli_fetch_assoc($result);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Recoger los datos del formulario
    $nombre = $_POST['nombre'];
    $descripcion = $_POST['descripcion'];
    $precio = $_POST['precio'];
    $stock = $_POST['stock'];

    // Subir nueva imagen si se selecciona
    if ($_FILES['imagen']['name']) {
        $imagen = $_FILES['imagen']['name'];
        $imagen_tmp = $_FILES['imagen']['tmp_name'];
        $imagen_destino = 'uploads/' . $imagen;

        if (move_uploaded_file($imagen_tmp, $imagen_destino)) {
            // Actualizar producto con nueva imagen
            $query = "UPDATE productos SET nombre = '$nombre', descripcion = '$descripcion', precio = '$precio', stock = '$stock', imagen = '$imagen' WHERE id = $id";
        } else {
            echo "Error al subir la imagen.";
        }
    } else {
        // Actualizar producto sin cambiar imagen
        $query = "UPDATE productos SET nombre = '$nombre', descripcion = '$descripcion', precio = '$precio', stock = '$stock' WHERE id = $id";
    }

    if (mysqli_query($conn, $query)) {
        header("Location: gestion_productos.php");
        exit();
    } else {
        echo "Error al actualizar el producto.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Producto</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="navbar">
        <div class="logo">CorpusLine Gym</div>
        <div class="menu">
            <a href="gestion_productos.php">Gestión de Productos</a>
            <a href="logout.php">Cerrar sesión</a>
        </div>
    </div>
    <div class="content">
        <h1>Editar Producto</h1>
        <form method="POST" enctype="multipart/form-data">
            <label for="nombre">Nombre</label>
            <input type="text" id="nombre" name="nombre" value="<?php echo $producto['nombre']; ?>" required>

            <label for="descripcion">Descripción</label>
            <textarea id="descripcion" name="descripcion"><?php echo $producto['descripcion']; ?></textarea>

            <label for="precio">Precio</label>
            <input type="number" id="precio" name="precio" value="<?php echo $producto['precio']; ?>" step="0.01" required>

            <label for="stock">Stock</label>
            <input type="number" id="stock" name="stock" value="<?php echo $producto['stock']; ?>" required>

            <label for="imagen">Imagen</label>
            <input type="file" id="imagen" name="imagen" accept="image/*">

            <input type="submit" class="btn-submit" value="Actualizar Producto">
        </form>
    </div>
</body>
</html>
