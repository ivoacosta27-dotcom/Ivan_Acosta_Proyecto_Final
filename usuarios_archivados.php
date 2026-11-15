<?php
require_once 'conexion.php';
session_start();

if (!isset($_SESSION['usuario_id']) || ($_SESSION['usuario_rol'] ?? '') !== 'admin') {
    http_response_code(403);
    exit('Acceso denegado.');
}

$sql = "SELECT id, nombre, apellido, dni, email, archivado_en FROM usuarios WHERE archivado_en IS NOT NULL ORDER BY archivado_en DESC";
$stmt = $conexion->query($sql);
$archivados = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Usuarios Archivados - Maoui Fragance</title>
    <link rel="stylesheet" href="css/estilos.css">
</head>
<body>

<header>
    <h1>Maoui Fragance</h1>
    <p>Cuentas Archivadas</p>
</header>

<nav>
    <a href="admin_usuarios.php">Volver al Panel Admin</a>
    <a href="cerrar_sesion.php">Cerrar Sesión</a>
</nav>

<main style="max-width: 900px;">
    <h2>Usuarios Archivados</h2>

    <?php if (empty($archivados)): ?>
        <p>No hay usuarios archivados en este momento.</p>
    <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre y Apellido</th>
                    <th>DNI</th>
                    <th>Email</th>
                    <th>Archivado el</th>
                    <th>Acción</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($archivados as $u): ?>
                <tr>
                    <td><?= htmlspecialchars($u['id'], ENT_QUOTES, 'UTF-8') ?></td>
                    <td><?= htmlspecialchars($u['nombre'] . ' ' . $u['apellido'], ENT_QUOTES, 'UTF-8') ?></td>
                    <td><?= htmlspecialchars($u['dni'], ENT_QUOTES, 'UTF-8') ?></td>
                    <td><?= htmlspecialchars($u['email'], ENT_QUOTES, 'UTF-8') ?></td>
                    <td><?= htmlspecialchars($u['archivado_en'], ENT_QUOTES, 'UTF-8') ?></td>
                    <td>
                        <form action="reactivar_usuario.php" method="POST" style="display:inline;">
                            <input type="hidden" name="id" value="<?= $u['id'] ?>">
                            <button type="submit" style="background: #28a745; color: #fff; border: none; padding: 4px 8px; border-radius: 3px; cursor: pointer; font-size: 0.9rem; width: auto; margin-top:0;">Reactivar</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</main>

</body>
</html>