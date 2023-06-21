<?php
// Obtén los datos enviados por el formulario
$usuario = filter_input(INPUT_POST, 'usuario', FILTER_SANITIZE_STRING);
$password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);

// Realiza la conexión a la base de datos MySQL
$servername = "127.0.0.1";
$username = "root";
$password_mysql = "";
$dbname = "REA";

$conn = new mysqli($servername, $username, $password_mysql, $dbname);

// Verifica si hay errores en la conexión
if ($conn->connect_error) {
    die("Error en la conexión a la base de datos: " . $conn->connect_error);
}

// Escapa los datos para prevenir inyección SQL
$usuario = $conn->real_escape_string($usuario);

// Consulta en la base de datos para verificar el usuario
$query = "SELECT password FROM usuarios WHERE usuario = '$usuario'";

$result = $conn->query($query);

if ($result) {
    if ($result->num_rows > 0) {
        // Usuario encontrado en la base de datos
        $row = $result->fetch_assoc();
        $hashed_password = $row['password'];

        // Verifica la contraseña ingresada con el hash almacenado en la base de datos
        if (password_verify($password, $hashed_password)) {
            // Contraseña correcta, inicio de sesión exitoso
            echo "Inicio de sesión exitoso";
        } else {
            // Contraseña incorrecta
            echo "Contraseña incorrecta";
        }
    } else {
        // Usuario no encontrado en la base de datos
        echo "El usuario no existe";
    }
} else {
    // Error al ejecutar la consulta
    echo "Error en la consulta: " . $conn->error;
}

// Cierra la conexión a la base de datos
$conn->close();
?>

