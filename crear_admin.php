<?php
require_once 'conexion.php';

$mensaje = '';

try {
    $emailAdmin = 'admin@maouifragance.com';
    
    // Verificar si ya existe un admin
    $stmt = $conexion->prepare("SELECT id FROM usuarios WHERE email = :email LIMIT 1");
    $stmt->execute([':email' => $emailAdmin]);
    
    if ($stmt->fetch()) {
        $mensaje = 'El usuario administrador ya existe en la base de datos.';
    } else {
        $passwordHash = password_hash('Admin1234', PASSWORD_DEFAULT);
        
        $sql = "INSERT INTO usuarios (nombre, apellido, dni, email, password_hash, rol) 
                VALUES ('Administrador', 'General', '00000000', :email, :password_hash, 'admin')";
        $stmt = $conexion->prepare($sql);
        $stmt->execute([
            ':email' => $emailAdmin,
            ':password_hash' => $passwordHash
        ]);
        
        $mensaje = '¡Administrador creado con éxito! Email: admin@maouifragance.com / Contraseña: Admin1234';
    }
} catch (PDOException $e) {
    $mensaje = 'Error al crear el administrador: ' . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Crear Admin - Maoui Fragance</title>
    <link rel="stylesheet" href="css/estilos.css">
</head>
<body>
<main style="text-align: center; margin-top: 50px;">
    <h2>Instalador de Administrador</h2>
    <p><?= htmlspecialchars($mensaje, ENT_QUOTES, 'UTF-8') ?></p>
    <p style="margin-top: 20px;"><a href="login.php" style="color: var(--ciruela); font-weight: bold;">Ir a Iniciar Sesión</a></p>
</main>
</body>
</html>