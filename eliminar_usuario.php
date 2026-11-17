<?php
require_once 'conexion.php';
session_start();

// 1) Solo administradores
if (!isset($_SESSION['usuario_id']) || ($_SESSION['usuario_rol'] ?? '') !== 'admin') {
    http_response_code(403);
    exit('Acceso denegado.');
}

// 2) Solo por POST: nunca se borra con un enlace
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: usuarios_archivados.php');
    exit;
}

// 3) Token CSRF: comprueba que el pedido salió de nuestro propio formulario
$token = $_POST['csrf_token'] ?? '';
if (!isset($_SESSION['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $token)) {
    http_response_code(403);
    exit('Solicitud no válida.');
}

// 4) Validar el ID
$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
if (!$id || $id < 1) {
    header('Location: usuarios_archivados.php?estado=invalido');
    exit;
}

// 5) Un admin no puede borrarse a sí mismo
if ($id === (int) $_SESSION['usuario_id']) {
    header('Location: usuarios_archivados.php?estado=propio');
    exit;
}

try {
    // Solo se borran usuarios ya archivados y que no sean administradores
    $sql = "DELETE FROM usuarios
            WHERE id = :id AND archivado_en IS NOT NULL AND rol <> 'admin'";
    $stmt = $conexion->prepare($sql);
    $stmt->execute([':id' => $id]);

    $estado = ($stmt->rowCount() === 1) ? 'eliminado' : 'noeliminado';
} catch (PDOException $e) {
    error_log($e->getMessage());
    $estado = 'error';
}

header('Location: usuarios_archivados.php?estado=' . $estado);
exit;
?>
