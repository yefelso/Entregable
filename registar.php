<?php
// Verificar si se reciben datos del formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Conectar a la base de datos (ajusta los detalles según tu configuración)
    $servername = "localhost";
    $username = "root"; // Asegúrate de proporcionar el nombre de usuario correcto
    $password = "";
    $dbname = "happy";

    $conn = new mysqli($servername, $username, $password, $dbname);

    // Verificar la conexión
    if ($conn->connect_error) {
        die("Conexión fallida: " . $conn->connect_error);
    }

    // Obtener datos del formulario
    $correo = $_POST["correo"];
    $contrasena = $_POST["contrasena"];

    // Insertar datos en la base de datos (ajusta la consulta según tu esquema)
    $sql = "INSERT INTO usuarios (correo, contrasena) VALUES ('$correo', '$contrasena')";

    if ($conn->query($sql) === TRUE) {
        echo "Registro exitoso";
    } else {
        echo "Error al registrar: " . $conn->error;
    }

    // Cerrar la conexión
    $conn->close();
}
?>
