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
    $id_usuario = $_POST["id_usuario"];
    $id_producto = $_POST["id_producto"];
    $monto = $_POST["monto"];
    $metodo_pago = $_POST["metodo_pago"];

    // Insertar datos en la base de datos
    $sql = "INSERT INTO pago (id_usuario, id_producto, monto, metodo_pago) 
            VALUES ($id_usuario, $id_producto, $monto, '$metodo_pago')";

    if ($conn->query($sql) === TRUE) {
        echo "Pago registrado exitosamente";
    } else {
        echo "Error al registrar el pago: " . $conn->error;
    }

    $conn->close();
}
?>
