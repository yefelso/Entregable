<?php
// Inicia la sesión
session_start();

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

    // Verificar las credenciales en la base de datos
    $sql = "SELECT * FROM usuarios WHERE correo = '$correo' AND contrasena = '$contrasena'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Credenciales válidas, obtener datos del usuario
        $row = $result->fetch_assoc();
        
        // Iniciar la sesión y almacenar datos del usuario
        $_SESSION["usuario_id"] = $row["id"];
        $_SESSION["usuario_nombre"] = $row["nombre"];

        // Redirigir a la página de bienvenida
        header("Location: compra.php");
        exit();
    } else {
        // Credenciales inválidas
        echo "Credenciales inválidas";
        header("Location: loguin.html");
    }

    // Cerrar la conexión
    $conn->close();
}
?>
