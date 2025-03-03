<?php
// Incluir el archivo de conexión a la base de datos
include('conexion.php');

// Inicializar una variable para mensajes de error
$error_message = '';

// Verificar si se ha enviado el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener los datos del formulario
    $email = $_POST['email'];
    $password = $_POST['password']; 
    // Preparar una consulta SQL para buscar al usuario en la base de datos
    $stmt = $conn->prepare("SELECT * FROM usuario WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    // Verificar si se encontró al usuario
    if ($result->num_rows > 0) {

        $user = $result->fetch_assoc();
        $passworden = $user['password'];
        if(password_verify($password, $passworden)){
            session_start();
            $_SESSION['email'] = $email;
            $_SESSION['rol'] = $user['rol']; // Almacena el rol del usuario en la sesión
            $_SESSION['codigo_usu'] = $user['codigo_usu']; // Almacena el código de usuario en la sesión

        // Redirigir según el rol
            if (($_SESSION['rol'] == 1)) { // Suponiendo que el ID del rol admin es 1
                header("Location: admin/menup.php");
                exit();
            } else if (($_SESSION['rol'] == 2)){
                header("Location: instructores/menuins.php"); // Redirige a 1.php sin parámetros
                exit();
            } else {
                header("Location: index.php");
            }
        }else {
            $error_message = "Credenciales inválidas. Inténtalo de nuevo.";
        } 
    }
        

    // Cerrar la declaración y la conexión
    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="estilos/despegable.css">
    <link rel="stylesheet" href="estilos/styles.css">
    <head>
    <title>Geston Fichas </title>
    <link rel="icon" href="imagenes/sena-logo.svg" type="image/x-icon">
</head>

</head>
<body>
<header>
        <div class="header-bar">
            <div class="logo-container">
                <img src="imagenes/sena-logo.svg" alt="Logo SENA" type="image/x-icon">
            </div>
        </div>
    </header>
    <div class="login-container">
        <h1>Ingresa</h1>
        <form method="POST" action="">
            <input type="email" name="email" placeholder="Correo Electrónico" required>
            <input type="password" name="password" placeholder="Contraseña" required>
            <input type="hidden" name="codigo_usu" value="12345"> <!-- Aquí va el valor del campo oculto -->
            <button type="submit">Ingresar</button>
            <?php
            if (!empty($error_message)) {
                echo "<p class='error-message'>$error_message</p>";
            }
            ?>
            <a href="#">Recuperar contraseña</a>
        </form>
    </div>
    <div class="menu">
        <ion-icon name="menu-outline"></ion-icon>
        <ion-icon name="close-outline"></ion-icon>
    </div>
    <div class="barra-lateral">
        <div class="modo-oscuro">
            <div class="info">
                <ion-icon name="moon-outline"></ion-icon>
                <span>Dark Mode</span>
            </div>
            <div class="switch">
                <div class="base">
                    <div class="circulo"></div>
                </div>  
            </div>
        </div>
    </div>
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
    <script src="script/js.js"></script>
</body>
</html>
