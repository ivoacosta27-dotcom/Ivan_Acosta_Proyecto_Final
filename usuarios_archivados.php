<?php
require_once 'conexion.php';
session_start();

if (!isset($_SESSION['usuario_id']) || ($_SESSION['usuario_rol'] ?? '') !== 'admin') {
    http_response_code(403);
    exit('Acceso denegado.');
}

// Token CSRF para el formulario de eliminación
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Mensajes posibles tras eliminar (lista fija, nunca se imprime el valor recibido)
$mensajes = [
    'eliminado'   => ['exito', 'Usuario eliminado definitivamente.'],
    'noeliminado' => ['error', 'No se pudo eliminar: el usuario no está archivado, es administrador o no existe.'],
    'propio'      => ['error', 'No podés eliminar tu propia cuenta.'],
    'invalido'    => ['error', 'Identificador inválido.'],
    'error'       => ['error', 'Ocurrió un error al eliminar el usuario.'],
];
$estado = $_GET['estado'] ?? '';
$aviso = $mensajes[$estado] ?? null;

$sql = "SELECT id, nombre, apellido, dni, email, rol, archivado_en FROM usuarios WHERE archivado_en IS NOT NULL ORDER BY archivado_en DESC";
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

    <?php if ($aviso): ?>
        <div class="<?= $aviso[0] ?>"><?= htmlspecialchars($aviso[1], ENT_QUOTES, 'UTF-8') ?></div>
    <?php endif; ?>

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
                        <?php if ($u['rol'] !== 'admin'): ?>
                        <form action="eliminar_usuario.php" method="POST" style="display:inline;" onsubmit="return confirm('¿Eliminar definitivamente a este usuario? Esta acción NO se puede deshacer.');">
                            <input type="hidden" name="id" value="<?= $u['id'] ?>">
                            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token'], ENT_QUOTES, 'UTF-8') ?>">
                            <button type="submit" style="background: #7a1f1f; color: #fff; border: none; padding: 4px 8px; border-radius: 3px; cursor: pointer; font-size: 0.9rem; width: auto; margin-top:0;">Eliminar</button>
                        </form>
                        <?php else: ?>
                        <span style="font-size: 0.85rem; color: #6d6573;">Admin: no eliminable</span>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</main>

</body>
</html>