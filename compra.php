<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar Pago</title>
    <link rel="stylesheet" href="compra.css"> <!-- Enlace a tu archivo de estilos CSS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> <!-- Incluir jQuery -->
    <script src="https://www.gstatic.com/firebasejs/9.2.0/firebase-app.js"></script>
    <script src="https://www.gstatic.com/firebasejs/9.2.0/firebase-database.js"></script>
    <script src="https://unpkg.com/chat21-web-sdk/dist/chat21.js"></script>
</head>
<body>

    <!-- Encabezado con dos enlaces -->
    <header>
        <nav>
            <ul>
                <li><a href="loguin.html">Inicio</a></li>
                <li><a href="#">Otro Enlace</a></li>
            </ul>
        </nav>
    </header>

    <div id="registrar-pago-container">

        <h2>Comprar</h2>

        <form action="registrarcompra.php" method="post" id="compraForm">
            <!-- Permitir que el usuario ingrese manualmente el ID -->
            <label for="id_usuario">ID de Usuario:</label>
            <input type="text" id="id_usuario" name="id_usuario" required>
            <br>

            <label for="id_producto">Seleccionar Producto:</label>
            <select id="id_producto" name="id_producto" required>
                <!-- Obtener y mostrar opciones de productos desde la base de datos -->
                <?php
                    // Código PHP para mostrar opciones de productos (ajustar según tu configuración)
                    $servername = "localhost";
                    $username = "root";
                    $password = "";
                    $dbname = "happy";

                    $conn = new mysqli($servername, $username, $password, $dbname);

                    if ($conn->connect_error) {
                        die("Conexión fallida: " . $conn->connect_error);
                    }

                    $sql = "SELECT id_producto, nombre, precio FROM productos";
                    $result = $conn->query($sql);

                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<option value='" . $row['id_producto'] . "' data-precio='" . $row['precio'] . "'>" . $row['nombre'] . "</option>";
                        }
                    } else {
                        echo "<option value=''>No se encontraron productos</option>";
                    }

                    $conn->close();
                ?>
            </select>
            <br>

            <label for="cantidad">Cantidad:</label>
            <input type="number" id="cantidad" name="cantidad" min="1" value="1" required>
            <br>

            <label for="monto">Monto Total:</label>
            <input type="text" id="monto" name="monto" readonly>
            <br>

            <label for="metodo_pago">Método de Pago:</label>
            <select id="metodo_pago" name="metodo_pago" required>
                <option value="efectivo">Efectivo</option>
                <option value="tarjeta">Tarjeta</option>
                <option value="yape">Yape</option>
            </select>
            <br>

            <input type="submit" value="Confirmar Compra">
            <!-- Agregamos un nuevo botón para mostrar el chat -->
            <input type="button" value="Mostrar Chat" onclick="mostrarChat()">
        </form>

        <p><a href="loguin.html">Volver al Inicio</a></p> <!-- Enlace para volver a la página de inicio -->

    </div>

    <div id="chat-container">
        <div id="chat-header">
            <h3>Chat en línea</h3>
            <button onclick="toggleChat()">Cerrar</button>
        </div>
        <div id="chat-messages"></div>
        <div id="chat-input">
            <input type="text" id="message-input" placeholder="Escribe tu mensaje">
            <button onclick="sendMessage()">Enviar</button>
        </div>
    </div>
    <script>
        $(document).ready(function() {
            $('#id_producto, #cantidad').change(function() {
                var precio = parseFloat($('#id_producto option:selected').data('precio'));
                var cantidad = parseInt($('#cantidad').val());
                var montoTotal = precio * cantidad;

                $('#monto').val(montoTotal.toFixed(2));
            });
        });

        function mostrarChat() {
            toggleChat();
        }

        function toggleChat() {
            var chatContainer = document.getElementById("chat-container");
            chatContainer.classList.toggle("chat-open");
        }

        function sendMessage() {
            var messageInput = document.getElementById("message-input");
            var message = messageInput.value;

            Chat21.sendMessage("ID_DEL_USUARIO", message);

            messageInput.value = "";
        }

        Chat21.addMessageListener(function (message) {
            var chatMessages = document.getElementById("chat-messages");
            var newMessage = document.createElement("div");
            newMessage.textContent = message.text;
            chatMessages.appendChild(newMessage);
        });
    </script>

   <!-- Código de instalación Cliengo para software.com --> <script type="text/javascript">(function () { var ldk = document.createElement('script'); ldk.type = 'text/javascript'; ldk.async = true; ldk.src = 'https://s.cliengo.com/weboptimizer/65e35e4c77aaf4003255a45f/65e35e4e77aaf4003255a462.js?platform=dashboard'; var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ldk, s); })(); </script>

</body>
</html>
