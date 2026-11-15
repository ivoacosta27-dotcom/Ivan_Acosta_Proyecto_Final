<?php
require_once 'conexion.php';

$mensaje = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = trim($_POST['nombre'] ?? '');
    $apellido = trim($_POST['apellido'] ?? '');
    $dni = trim($_POST['dni'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($nombre === '' || $apellido === '' || $dni === '' || $email === '' || $password === '') {
        $error = 'Todos los campos son obligatorios.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'El correo electrónico no es válido.';
    } else {
        try {
            $passwordHash = password_hash($password, PASSWORD_DEFAULT);
            
            $sql = "INSERT INTO usuarios (nombre, apellido, dni, email, password_hash, rol) 
                    VALUES (:nombre, :apellido, :dni, :email, :password_hash, 'usuario')";
            
            $stmt = $conexion->prepare($sql);
            $stmt->execute([
                ':nombre' => $nombre,
                ':apellido' => $apellido,
                ':dni' => $dni,
                ':email' => $email,
                ':password_hash' => $passwordHash
            ]);

            $mensaje = '¡Cuenta creada con éxito! Ya podés iniciar sesión.';
        } catch (PDOException $e) {
            // El código 23000 de SQLSTATE indica violación de restricción UNIQUE (DNI o email duplicado)
            if ($e->getCode() === '23000') {
                $error = 'El DNI o el correo electrónico ya se encuentran registrados.';
            } else {
                $error = 'Ocurrió un error inesperado al registrar los datos.';
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro - Maoui Fragance</title>
    <link rel="stylesheet" href="css/estilos.css">
</head>
<body>

<header>
    <h1>Maoui Fragance</h1>
    <p>Sistema de Gestión de Usuarios</p>
</header>

<nav>
    <a href="index.php">Inicio</a>
    <a href="login.php">Iniciar Sesión</a>
    <a href="registro.php">Registrarse</a>
</nav>

<main>
    <h2>Crear una cuenta</h2>

    <?php if ($error !== ''): ?>
        <div class="error"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></div>
    <?php endif; ?>

    <?php if ($mensaje !== ''): ?>
        <div class="exito"><?= htmlspecialchars($mensaje, ENT_QUOTES, 'UTF-8') ?></div>
    <?php endif; ?>

    <form action="registro.php" method="POST">
        <label for="nombre">Nombre:</label>
        <input type="text" id="nombre" name="nombre" required>

        <label for="apellido">Apellido:</label>
        <input type="text" id="apellido" name="apellido" required>

        <label for="dni">DNI:</label>
        <input type="text" id="dni" name="dni" required>

        <label for="email">Correo electrónico:</label>
        <input type="email" id="email" name="email" required>

        <label for="password">Contraseña:</label>
        <input type="password" id="password" name="password" required>

        <button type="submit">Registrarse</button>
    </form>
</main>

</body>
</html>