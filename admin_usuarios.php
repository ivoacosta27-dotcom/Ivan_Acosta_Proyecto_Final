<?php
require_once 'conexion.php';
session_start();

// Control de acceso: solo administradores
if (!isset($_SESSION['usuario_id']) || ($_SESSION['usuario_rol'] ?? '') !== 'admin') {
    http_response_code(403);
    exit('Acceso denegado. No tenés permisos de administrador.');
}

$busqueda = trim($_GET['buscar'] ?? '');

// Consulta para listar usuarios activos con filtro de búsqueda opcional
$sql = "SELECT id, nombre, apellido, dni, email, rol, creado_en FROM usuarios WHERE archivado_en IS NULL";
$parametros = [];

if ($busqueda !== '') {
    $sql .= " AND (nombre LIKE :busqueda OR apellido LIKE :busqueda OR dni LIKE :busqueda OR email LIKE :busqueda)";
    $parametros[':busqueda'] = '%' . $busqueda . '%';
}

$sql .= " ORDER BY id DESC";
$stmt = $conexion->prepare($sql);
$stmt->execute($parametros);
$usuarios = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Administrador - Maoui Fragance</title>
    <link rel="stylesheet" href="css/estilos.css">
</head>
<body>

<header>
    <h1>Maoui Fragance</h1>
    <p>Panel de Administración de Usuarios</p>
</header>

<nav>
    <a href="index.php">Inicio</a>
    <a href="admin_usuarios.php">Usuarios Activos</a>
    <a href="usuarios_archivados.php">Ver Archivados</a>
    <a href="cerrar_sesion.php">Cerrar Sesión</a>
</nav>

<main style="max-width: 900px;">
    <h2>Gestión de Usuarios Activos</h2>
    <p>Administrador: <strong><?= htmlspecialchars($_SESSION['usuario_nombre'] ?? '', ENT_QUOTES, 'UTF-8') ?></strong></p>

    <!-- Formulario de búsqueda -->
    <form method="GET" action="admin_usuarios.php" style="margin-bottom: 20px; display: flex; gap: 10px;">
        <input type="text" name="buscar" placeholder="Buscar por nombre, apellido, DNI o correo..." value="<?= htmlspecialchars($busqueda, ENT_QUOTES, 'UTF-8') ?>" style="margin-top: 0;">
        <button type="submit" style="margin-top: 0; width: auto; padding: 10px 20px;">Buscar</button>
    </form>

    <?php if (empty($usuarios)): ?>
        <p>No se encontraron usuarios activos.</p>
    <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre y Apellido</th>
                    <th>DNI</th>
                    <th>Email</th>
                    <th>Rol</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($usuarios as $u): ?>
                <tr>
                    <td><?= htmlspecialchars($u['id'], ENT_QUOTES, 'UTF-8') ?></td>
                    <td><?= htmlspecialchars($u['nombre'] . ' ' . $u['apellido'], ENT_QUOTES, 'UTF-8') ?></td>
                    <td><?= htmlspecialchars($u['dni'], ENT_QUOTES, 'UTF-8') ?></td>
                    <td><?= htmlspecialchars($u['email'], ENT_QUOTES, 'UTF-8') ?></td>
                    <td><?= htmlspecialchars($u['rol'], ENT_QUOTES, 'UTF-8') ?></td>
                    <td>
                        <a href="editar_usuario.php?id=<?= $u['id'] ?>" style="color: var(--ciruela); font-weight: bold; margin-right: 10px; text-decoration: none;">Editar</a>
                        <form action="archivar_usuario.php" method="POST" style="display:inline;" onsubmit="return confirm('¿Estás seguro de archivar a este usuario?');">
                            <input type="hidden" name="id" value="<?= $u['id'] ?>">
                            <button type="submit" style="background: #a84f4f; color: #fff; border: none; padding: 4px 8px; border-radius: 3px; cursor: pointer; font-size: 0.9rem; width: auto; margin-top:0;">Archivar</button>
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