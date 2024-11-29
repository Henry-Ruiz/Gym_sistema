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
    $descripcion = $_POST['descripcion'];
    $precio = $_POST['precio'];
    $stock = $_POST['stock'];

    // Subir la imagen
    $imagen = $_FILES['imagen']['name'];
    $imagen_tmp = $_FILES['imagen']['tmp_name'];

    // Carpeta de destino
    $imagen_destino = 'imagenes/' . $imagen;

    // Intentar mover la imagen a la carpeta 'imagenes'
    if (move_uploaded_file($imagen_tmp, $imagen_destino)) {
        // Insertar el producto en la base de datos
        $query = "INSERT INTO productos (nombre, descripcion, precio, stock, imagen) 
                  VALUES ('$nombre', '$descripcion', '$precio', '$stock', '$imagen')";
        
        if (mysqli_query($conn, $query)) {
            header("Location: gestion_productos.php");
            exit();
        } else {
            echo "Error al añadir el producto.";
        }
    } else {
        echo "Error al subir la imagen.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Añadir Producto</title>
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
        <h1>Añadir Producto</h1>
        <form method="POST" enctype="multipart/form-data">
            <label for="nombre">Nombre</label>
            <input type="text" id="nombre" name="nombre" required>

            <label for="descripcion">Descripción</label>
            <textarea id="descripcion" name="descripcion"></textarea>

            <label for="precio">Precio</label>
            <input type="number" id="precio" name="precio" step="0.01" required>

            <label for="stock">Stock</label>
            <input type="number" id="stock" name="stock" required>

            <label for="imagen">Imagen</label>
            <input type="file" id="imagen" name="imagen" accept="image/*" required>

            <input type="submit" class="btn-submit" value="Añadir Producto">
        </form>
    </div>

</body>
</html>
