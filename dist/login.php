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
$username = $_POST['username'];
$password = $_POST['password'];

// Consultas para cada tabla
$tablas = ['docente', 'estudiante', 'personal', 'servicios'];
$logueado = false;

foreach ($tablas as $tabla) {
    $sql = "SELECT * FROM $tabla WHERE Correo = ? AND Contrasena = ?";
    $solicitud = $conn->prepare($sql);
    $solicitud->bind_param("ss", $username, $password);
    $solicitud->execute();
    $resultado = $solicitud->get_result();

    if ($resultado->num_rows > 0) {
        session_start();
        $_SESSION['usuario'] = $resultado->fetch_assoc();
        $_SESSION['tabla'] = $tabla;
        $logueado = true;
        break;
    }
    $solicitud->close();
}

$conn->close();

if ($logueado) {
    header("Location: menu/index.html"); // Cambia esto a la página principal tras el login 
    exit();
} else {
    echo "<script>
    alert('Usuario o contraseña incorrectos, intente de nuevo.');
    window.location.href = 'index.html';
</script>";
}
?>
