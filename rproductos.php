<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Conectar a la base de datos (ajustar los detalles según tu configuración)
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "happy";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Conexión fallida: " . $conn->connect_error);
    }

    // Obtener datos del formulario
    $nombre = $_POST["nombre"];
    $descripcion = $_POST["descripcion"];
    $precio = $_POST["precio"];
    $stock = $_POST["stock"];

    // Manejar la imagen (guardar la ruta o los datos binarios en la base de datos según tu elección)
    $imagen = $_FILES["imagen"]["name"];
    $imagen_temp = $_FILES["imagen"]["tmp_name"];
    $imagen_ruta = "uploads/" . $imagen; // Carpeta "uploads" debe existir y tener permisos de escritura

    move_uploaded_file($imagen_temp, $imagen_ruta);

    // Insertar datos en la base de datos
    $sql = "INSERT INTO productos (nombre, descripcion, precio, imagen, stock) 
            VALUES ('$nombre', '$descripcion', $precio, '$imagen_ruta', $stock)";

    if ($conn->query($sql) === TRUE) {
        echo "Producto registrado exitosamente";
    } else {
        echo "Error al registrar el producto: " . $conn->error;
    }

    $conn->close();
}
?>
