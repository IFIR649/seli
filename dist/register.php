<?php
// Configuración de conexión a la base de datos
$host = 'localhost';
$db = 'universidad';
$user = 'root';
$password = '';

$conn = new mysqli($host, $user, $password, $db);

if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

// Recibir datos del formulario
$nombre = $_POST['nombre'];
$paterno = $_POST['paterno'];
$materno = $_POST['materno'];
$correo = $_POST['correo'];
$contrasena = $_POST['contrasena'];

// Verificar si el correo ya está registrado
$sql = "SELECT * FROM estudiante WHERE Correo = ?";
$solicitud = $conn->prepare($sql);
$solicitud->bind_param("s", $correo);
$solicitud->execute();
$resultado = $solicitud->get_result();

if ($resultado->num_rows > 0) {
    echo "<script>
        alert('El correo ya está en uso.');
        window.location.href = 'index.html';
    </script>";
} else {
    // Insertar nuevo estudiante en la base de datos
    $sql = "INSERT INTO estudiante (Nombre, ApellidoPaterno, ApellidoMaterno, Correo, Contrasena) VALUES (?, ?, ?, ?, ?)";
    $solicitud = $conn->prepare($sql);
    $solicitud->bind_param("sssss", $nombre, $paterno, $materno, $correo, $contrasena);

    if ($solicitud->execute()) {
        header("Location: index.html");
    } else {
        echo "<p>Error al registrar. Intente nuevamente.</p>";
    }
}

$solicitud->close();
$conn->close();
?>