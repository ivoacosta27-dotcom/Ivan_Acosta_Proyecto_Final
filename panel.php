<?php
require_once 'conexion.php';
session_start();

// Control de acceso: si no hay sesión iniciada, redirigir al login
if (!isset($_SESSION['usuario_id'])) {
    header('Location: login.php');
    exit;
}

// Consultar los datos actualizados del usuario logueado
$sql = "SELECT nombre, apellido, dni, email, rol, creado_en FROM usuarios WHERE id = :id LIMIT 1";
$stmt = $conexion->prepare($sql);
$stmt->execute([':id' => $_SESSION['usuario_id']]);
$usuario = $stmt->fetch();

if (!$usuario) {
    // Si por alguna razón el usuario ya no existe en la base
    header('Location: cerrar_sesion.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Usuario - Maoui Fragance</title>
    <link rel="stylesheet" href="css/estilos.css">
</head>
<body>

<header>
    <h1>Maoui Fragance</h1>
    <p>Panel Personal</p>
</header>

<nav>
    <a href="index.php">Inicio</a>
    <a href="panel.php">Mi Panel</a>
    <?php if ($usuario['rol'] === 'admin'): ?>
        <a href="admin_usuarios.php">Panel Admin</a>
    <?php endif; ?>
    <a href="cerrar_sesion.php">Cerrar Sesión</a>
</nav>

<main>
    <h2>Bienvenido/a, <?= htmlspecialchars($usuario['nombre'], ENT_QUOTES, 'UTF-8') ?></h2>
    <p>Este es tu panel personal en Maoui Fragance. Aquí podés ver los datos registrados de tu cuenta:</p>

    <table style="margin-top: 20px;">
        <tr>
            <th>Nombre:</th>
            <td><?= htmlspecialchars($usuario['nombre'], ENT_QUOTES, 'UTF-8') ?></td>
        </tr>
        <tr>
            <th>Apellido:</th>
            <td><?= htmlspecialchars($usuario['apellido'], ENT_QUOTES, 'UTF-8') ?></td>
        </tr>
        <tr>
            <th>DNI:</th>
            <td><?= htmlspecialchars($usuario['dni'], ENT_QUOTES, 'UTF-8') ?></td>
        </tr>
        <tr>
            <th>Correo electrónico:</th>
            <td><?= htmlspecialchars($usuario['email'], ENT_QUOTES, 'UTF-8') ?></td>
        </tr>
        <tr>
            <th>Rol en el sistema:</th>
            <td><?= htmlspecialchars($usuario['rol'], ENT_QUOTES, 'UTF-8') ?></td>
        </tr>
        <tr>
            <th>Fecha de registro:</th>
            <td><?= htmlspecialchars($usuario['creado_en'], ENT_QUOTES, 'UTF-8') ?></td>
        </tr>
    </table>

    <p style="margin-top: 25px;">
        <a href="cerrar_sesion.php" style="background: #a84f4f; color: #fff; padding: 10px 15px; text-decoration: none; border-radius: 4px;">Cerrar Sesión</a>
    </p>
</main>

</body>
</html>