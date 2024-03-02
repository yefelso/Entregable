<?php
    // Supongamos que $idProductoAgregado es el id del producto que se añadió al carrito
    session_start();
    $_SESSION['producto_seleccionado'] = $idProductoAgregado;
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
    // Conexi�n a la base de datos (ajusta las credenciales seg�n tu configuraci�n)
    $servername = "localhost";
    $username = "root";
    $password = "";
    $database = "happy";

    $conn = new mysqli($servername, $username, $password, $database);

    if ($conn->connect_error) {
        die("Conexi�n fallida: " . $conn->connect_error);
    }

    // Consulta para obtener productos desde la base de datos
    $sql = "SELECT id_producto, nombre, descripcion, precio, imagen FROM productos";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo '<div class="product-card">';
            echo '<div class="image-container">'; // Nuevo contenedor para la imagen
            echo '<img src="' . $row["imagen"] . '" alt="' . $row["nombre"] . '">';
            echo '</div>';
            echo '<div class="product-info">';
            echo '<h2>' . $row["nombre"] . '</h2>';
            echo '<p>' . $row["descripcion"] . '</p>';
            echo '<p class="price">Precio: s/' . $row["precio"] . '</p>';
            echo '<a href="compra.php"><button>Añadir al Carrito</button></a>';
            echo '</div>';
            echo '</div>';
        }
    } else {
        echo "No hay productos disponibles.";
    }

    // Cerrar la conexi�n
    $conn->close();
    ?>

</div>

</body>
</html>
