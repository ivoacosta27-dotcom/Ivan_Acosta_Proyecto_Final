<?php
require_once 'conexion.php';
session_start();

if (!isset($_SESSION['usuario_id']) || ($_SESSION['usuario_rol'] ?? '') !== 'admin') {
    http_response_code(403);
    exit('Acceso denegado.');
}

$error = '';
$mensaje = '';

// Validar ID por GET
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id) {
    header('Location: admin_usuarios.php');
    exit;
}

// Procesar actualización por POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = trim($_POST['nombre'] ?? '');
    $apellido = trim($_POST['apellido'] ?? '');
    $dni = trim($_POST['dni'] ?? '');
    $email = trim($_POST['email'] ?? '');

    if ($nombre === '' || $apellido === '' || $dni === '' || $email === '') {
        $error = 'Todos los campos son obligatorios.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'El correo electrónico no es válido.';
    } else {
        try {
            $sql = "UPDATE usuarios SET nombre = :nombre, apellido = :apellido, dni = :dni, email = :email 
                    WHERE id = :id AND archivado_en IS NULL";
            $stmt = $conexion->prepare($sql);
            $stmt->execute([
                ':nombre' => $nombre,
                ':apellido' => $apellido,
                ':dni' => $dni,
                ':email' => $email,
                ':id' => $id
            ]);
            $mensaje = 'Usuario actualizado correctamente.';
        } catch (PDOException $e) {
            if ($e->getCode() === '23000') {
                $error = 'El DNI o el correo electrónico ya pertenecen a otro usuario.';
            } else {
                $error = 'Error al actualizar los datos.';
            }
        }
    }
}

// Consultar datos actuales del usuario
$stmt = $conexion->prepare("SELECT id, nombre, apellido, dni, email FROM usuarios WHERE id = :id AND archivado_en IS NULL LIMIT 1");
$stmt->execute([':id' => $id]);
$usuario = $stmt->fetch();

if (!$usuario) {
    header('Location: admin_usuarios.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Usuario - Maoui Fragance</title>
    <link rel="stylesheet" href="css/estilos.css">
</head>
<body>

<header>
    <h1>Maoui Fragance</h1>
    <p>Modificar Usuario</p>
</header>

<nav>
    <a href="admin_usuarios.php">Volver al Panel Admin</a>
    <a href="cerrar_sesion.php">Cerrar Sesión</a>
</nav>

<main>
    <h2>Editar Usuario: <?= htmlspecialchars($usuario['nombre'], ENT_QUOTES, 'UTF-8') ?></h2>

    <?php if ($error !== ''): ?>
        <div class="error"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></div>
    <?php endif; ?>

    <?php if ($mensaje !== ''): ?>
        <div class="exito"><?= htmlspecialchars($mensaje, ENT_QUOTES, 'UTF-8') ?></div>
    <?php endif; ?>

    <form action="editar_usuario.php?id=<?= $usuario['id'] ?>" method="POST">
        <label for="nombre">Nombre:</label>
        <input type="text" id="nombre" name="nombre" value="<?= htmlspecialchars($usuario['nombre'], ENT_QUOTES, 'UTF-8') ?>" required>

        <label for="apellido">Apellido:</label>
        <input type="text" id="apellido" name="apellido" value="<?= htmlspecialchars($usuario['apellido'], ENT_QUOTES, 'UTF-8') ?>" required>

        <label for="dni">DNI:</label>
        <input type="text" id="dni" name="dni" value="<?= htmlspecialchars($usuario['dni'], ENT_QUOTES, 'UTF-8') ?>" required>

        <label for="email">Correo electrónico:</label>
        <input type="email" id="email" name="email" value="<?= htmlspecialchars($usuario['email'], ENT_QUOTES, 'UTF-8') ?>" required>

        <button type="submit">Guardar Cambios</button>
    </form>
</main>

</body>
</html>