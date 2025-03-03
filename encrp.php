<?php
include('conexion.php');

// Consulta para obtener los usuarios
$sql = "SELECT codigo_usu, password FROM Usuario";
$resultado = $conn->query($sql);

// Iterar sobre los usuarios
while ($fila = $resultado->fetch_assoc()) {
    $codigo_usu = $fila['codigo_usu'];
    $password_sin_encriptar = $fila['password'];

    // Encriptar la contraseña usando bcrypt
    $password_encriptada = password_hash($password_sin_encriptar, PASSWORD_BCRYPT);

    // Actualizar la contraseña en la base de datos
    $sql_update = "UPDATE Usuario SET password = ? WHERE codigo_usu = ?";
    $stmt = $conn->prepare($sql_update);
    $stmt->bind_param("si", $password_encriptada, $codigo_usu);
    $stmt->execute();
}

echo "Contraseñas encriptadas correctamente.";
$conn->close();
?>
