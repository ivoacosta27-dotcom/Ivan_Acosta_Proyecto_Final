<?php
require_once 'conexion.php';
session_start();

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($email === '' || $password === '') {
        $error = 'Por favor, completá todos los campos.';
    } else {
        // Consultamos el usuario activo por correo
        $sql = "SELECT id, nombre, apellido, password_hash, rol FROM usuarios 
                WHERE email = :email AND archivado_en IS NULL LIMIT 1";
        $stmt = $conexion->prepare($sql);
        $stmt->execute([':email' => $email]);
        $usuario = $stmt->fetch();

        // Verificamos si el usuario existe y si la contraseña coincide con el hash
        if ($usuario && password_verify($password, $usuario['password_hash'])) {
            // Regenerar ID de sesión por seguridad
            session_regenerate_id(true);

            $_SESSION['usuario_id'] = $usuario['id'];
            $_SESSION['usuario_nombre'] = $usuario['nombre'];
            $_SESSION['usuario_rol'] = $usuario['rol'];

            // Redirigir según el rol
            if ($usuario['rol'] === 'admin') {
                header('Location: admin_usuarios.php');
            } else {
                header('Location: panel.php');
            }
            exit;
        } else {
            $error = 'Correo o contraseña incorrectos.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión - Maoui Fragance</title>
    <link rel="stylesheet" href="css/estilos.css">
</head>
<body>

<header>
    <h1>Maoui Fragance</h1>
    <p>Acceso al sistema</p>
</header>

<nav>
    <a href="index.php">Inicio</a>
    <a href="login.php">Iniciar Sesión</a>
    <a href="registro.php">Registrarse</a>
</nav>

<main>
    <h2>Iniciar Sesión</h2>

    <?php if ($error !== ''): ?>
        <div class="error"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></div>
    <?php endif; ?>

    <form action="login.php" method="POST">
        <label for="email">Correo electrónico:</label>
        <input type="email" id="email" name="email" required>

        <label for="password">Contraseña:</label>
        <input type="password" id="password" name="password" required>

        <button type="submit">Ingresar</button>
    </form>
</main>

</body>
</html>