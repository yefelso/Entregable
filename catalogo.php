<?php
// Inicializar la sesión
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['id_producto'])) {
        $idProductoAgregado = intval($_POST['id_producto']);
        $_SESSION['producto_seleccionado'] = $idProductoAgregado;
        header('Location: compra.php');  // Redirige a la página de compra después de seleccionar un producto
        exit();
    } else {
        echo "Error: No se seleccionó ningún producto.";
    }
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tienda de Productos</title>
    <link rel="stylesheet" href="catalogo.css">
</head>
<body>

<div class="container">

<?php
// Conexión a la base de datos (ajusta las credenciales según tu configuración)
$servername = "localhost";
$username = "root";
$password = "";
$database = "happy";

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Consulta para obtener productos desde la base de datos
$sql = "SELECT id_producto, nombre, descripcion, precio, imagen FROM productos";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        // Mostrar información del producto
        echo '<div class="product-card">';
        echo '<div class="image-container">';
        echo '<img src="' . $row["imagen"] . '" alt="' . $row["nombre"] . '">';
        echo '</div>';
        echo '<div class="product-info">';
        echo '<h2>' . $row["nombre"] . '</h2>';
        echo '<p>' . $row["descripcion"] . '</p>';
        echo '<p class="price">Precio: s/' . $row["precio"] . '</p>';

        // Formulario para agregar al carrito
        echo '<form action="compra.php" method="post">';
        echo '<input type="hidden" name="id_producto" value="' . $row['id_producto'] . '">';
        echo '<button type="submit">Añadir al Carrito</button>';
        echo '</form>';

        echo '</div>';
        echo '</div>';
    }
} else {
    echo "No hay productos disponibles.";
}

// Cerrar la conexión
$conn->close();
?>

</div>

</body>
</html>
