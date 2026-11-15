<?php
require_once 'conexion.php';
session_start();

if (!isset($_SESSION['usuario_id']) || ($_SESSION['usuario_rol'] ?? '') !== 'admin') {
    http_response_code(403);
    exit('Acceso denegado.');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);

    if ($id) {
        $sql = "UPDATE usuarios SET archivado_en = CURRENT_TIMESTAMP WHERE id = :id AND archivado_en IS NULL";
        $stmt = $conexion->prepare($sql);
        $stmt->execute([':id' => $id]);
    }
}

header('Location: admin_usuarios.php');
exit;
?>