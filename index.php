<?php
session_start();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Maoui Fragance - Inicio</title>
    <link rel="stylesheet" href="css/estilos.css">
</head>
<body>

<header>
    <h1>Maoui Fragance</h1>
    <p>Exclusividad y elegancia en cada fragancia </p>
</header>

<nav>
    <a href="index.php">Inicio</a>
    <?php if (isset($_SESSION['usuario_id'])): ?>
        <a href="panel.php">Mi Panel</a>
        <?php if (($_SESSION['usuario_rol'] ?? '') === 'admin'): ?>
            <a href="admin_usuarios.php">Panel Admin</a>
        <?php endif; ?>
        <a href="cerrar_sesion.php">Cerrar Sesión</a>
    <?php else: ?>
        <a href="login.php">Iniciar Sesión</a>
        <a href="registro.php">Registrarse</a>
    <?php endif; ?>
</nav>

<main style="text-align: center;">
    <h2>Bienvenidos al sistema de Maoui Fragance</h2>
    <p>Gestioná tus cuentas de cliente, consultá nuestros productos y accedé a los paneles de control exclusivos de forma segura.</p>
    
    <?php if (!isset($_SESSION['usuario_id'])): ?>
        <p style="margin-top: 30px;">
            <a href="login.php" style="background: var(--ciruela); color: #fff; padding: 10px 20px; text-decoration: none; border-radius: 4px; margin-right: 10px;">Iniciar Sesión</a>
            <a href="registro.php" style="background: var(--dorado); color: #fff; padding: 10px 20px; text-decoration: none; border-radius: 4px;">Crear Cuenta</a>
        </p>
    <?php endif; ?>
</main>

<footer>
    <p>Maoui Fragance</p>
</footer>

</body>
</html>