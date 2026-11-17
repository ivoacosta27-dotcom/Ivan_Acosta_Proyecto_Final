<?php
session_start();

// Limpiar todas las variables de sesión
$_SESSION = [];

// Borrar también la cookie de sesión del navegador
if (ini_get('session.use_cookies')) {
    $p = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000, $p['path'], $p['domain'], $p['secure'], $p['httponly']);
}

// Destruir la sesión
session_destroy();

// Redirigir al inicio de sesión
header('Location: login.php');
exit;
?>
