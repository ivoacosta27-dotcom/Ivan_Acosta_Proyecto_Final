<?php
session_start();
$logueado = isset($_SESSION['usuario_id']);
$esAdmin  = ($_SESSION['usuario_rol'] ?? '') === 'admin';
$nombre   = htmlspecialchars($_SESSION['usuario_nombre'] ?? '', ENT_QUOTES, 'UTF-8');
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Maoui Fragance - Perfumería</title>
    <link rel="stylesheet" href="css/estilos.css">
</head>
<body>

<header>
    <h1>Maoui Fragance</h1>
    <p>Exclusividad y elegancia en cada fragancia</p>
</header>

<nav>
    <a href="index.php">Inicio</a>
    <a href="#familias">Familias olfativas</a>
    <a href="#destacados">Destacados</a>
    <a href="#contacto">Contacto</a>
    <?php if ($logueado): ?>
        <a href="panel.php">Mi Panel</a>
        <?php if ($esAdmin): ?>
            <a href="admin_usuarios.php">Panel Admin</a>
        <?php endif; ?>
        <a href="cerrar_sesion.php">Cerrar Sesión</a>
    <?php else: ?>
        <a href="login.php">Iniciar Sesión</a>
        <a href="registro.php">Registrarse</a>
    <?php endif; ?>
</nav>

<main class="inicio">

    <!-- Portada -->
    <section class="hero">
        <div class="hero-texto">
            <h2><?= $logueado ? "Hola, $nombre" : 'El perfume que cuenta tu historia' ?></h2>
            <p>
                En Maoui Fragance seleccionamos fragancias para cada momento del día:
                notas florales, frescas, amaderadas y orientales, elegidas con cuidado.
                Creá tu cuenta para guardar tus datos y seguir de cerca tu perfil de cliente.
            </p>
            <p>
                <?php if ($logueado): ?>
                    <a class="btn" href="panel.php">Ir a mi panel</a>
                    <?php if ($esAdmin): ?>
                        <a class="btn btn-dorado" href="admin_usuarios.php">Administrar usuarios</a>
                    <?php endif; ?>
                <?php else: ?>
                    <a class="btn" href="registro.php">Crear cuenta</a>
                    <a class="btn btn-dorado" href="login.php">Iniciar sesión</a>
                <?php endif; ?>
            </p>
        </div>
        <img src="img/hero.svg" alt="Frasco de perfume Maoui Fragance">
    </section>

    <!-- Familias olfativas -->
    <section id="familias">
        <h2 class="titulo">Familias olfativas</h2>
        <p class="subtitulo">Cuatro caminos para encontrar el aroma que va con vos.</p>
        <div class="tarjetas cuatro">
            <article class="tarjeta centrada">
                <img src="img/floral.svg" alt="Ícono de flor">
                <h3>Florales</h3>
                <p>Rosa, jazmín y peonía. Suaves, románticos y de día.</p>
            </article>
            <article class="tarjeta centrada">
                <img src="img/citrica.svg" alt="Ícono de cítrico">
                <h3>Cítricas</h3>
                <p>Limón, bergamota y mandarina. Frescura para el verano.</p>
            </article>
            <article class="tarjeta centrada">
                <img src="img/amaderada.svg" alt="Ícono de hoja">
                <h3>Amaderadas</h3>
                <p>Cedro, vetiver y sándalo. Profundas y elegantes.</p>
            </article>
            <article class="tarjeta centrada">
                <img src="img/oriental.svg" alt="Ícono de luna">
                <h3>Orientales</h3>
                <p>Vainilla, ámbar y especias. Intensas para la noche.</p>
            </article>
        </div>
    </section>

    <!-- Destacados -->
    <section id="destacados">
        <h2 class="titulo">Fragancias destacadas</h2>
        <p class="subtitulo">Las más elegidas por nuestros clientes.</p>
        <div class="tarjetas tres">
            <article class="tarjeta">
                <img src="img/frasco-rosa.svg" alt="Frasco rosa">
                <div class="tarjeta-cuerpo">
                    <h3>Rosa Velada</h3>
                    <p class="notas">Notas: rosa, peonía, almizcle blanco.</p>
                    <p>Floral y delicado, ideal para el día y para regalar.</p>
                </div>
            </article>
            <article class="tarjeta">
                <img src="img/frasco-ambar.svg" alt="Frasco ámbar">
                <div class="tarjeta-cuerpo">
                    <h3>Ámbar Nocturno</h3>
                    <p class="notas">Notas: ámbar, vainilla, canela.</p>
                    <p>Cálido e intenso, pensado para las noches de invierno.</p>
                </div>
            </article>
            <article class="tarjeta">
                <img src="img/frasco-azul.svg" alt="Frasco azul">
                <div class="tarjeta-cuerpo">
                    <h3>Brisa Marina</h3>
                    <p class="notas">Notas: bergamota, sal marina, cedro.</p>
                    <p>Fresco y limpio, para todos los días.</p>
                </div>
            </article>
        </div>
    </section>

    <!-- Por qué elegirnos -->
    <section class="franja">
        <h2 class="titulo">Por qué elegir Maoui</h2>
        <div class="tarjetas tres">
            <div>
                <h3>Productos originales</h3>
                <p>Trabajamos solo con fragancias auténticas, con garantía de procedencia.</p>
            </div>
            <div>
                <h3>Asesoramiento personal</h3>
                <p>Te ayudamos a elegir según tu estilo, la ocasión y la época del año.</p>
            </div>
            <div>
                <h3>Tu cuenta, segura</h3>
                <p>Tus datos se guardan con contraseña protegida y solo vos podés verlos.</p>
            </div>
        </div>
    </section>

    <!-- Cómo funciona la cuenta -->
    <section>
        <h2 class="titulo">Cómo funciona tu cuenta</h2>
        <div class="tarjetas tres">
            <div class="paso"><span>1</span><h3>Registrate</h3><p>Completá tus datos: nombre, apellido, DNI, correo y contraseña.</p></div>
            <div class="paso"><span>2</span><h3>Iniciá sesión</h3><p>Ingresá con tu correo y contraseña para acceder a tu panel.</p></div>
            <div class="paso"><span>3</span><h3>Consultá tus datos</h3><p>Desde tu panel personal ves la información de tu cuenta.</p></div>
        </div>
    </section>

</main>

<footer id="contacto">
    <div class="pie">
        <div>
            <h3>Maoui Fragance</h3>
            <p>Perfumería de autor.</p>
        </div>
        <div>
            <h3>Contacto</h3>
            <p>Almagro, Buenos Aires<br>info@maouifragance.com</p>
        </div>
        <div>
            <h3>Horarios</h3>
            <p>Lunes a sábado<br>10:00 a 19:00</p>
        </div>
    </div>
    <p class="copy">&copy; <?= date('Y') ?> Maoui Fragance. Proyecto escolar.</p>
</footer>

</body>
</html>
