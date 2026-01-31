<?php
session_start();
require_once 'conectar_db.php';
require_once 'Usuario.php';
require_once 'validacion.php';
require_once 'GestorUsuarios.php';

$conexion = conectar();
$gestorUsuarios = new GestorUsuarios($conexion);

$errores = [];

// LOGIN
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $dni = $_POST['dni'] ?? '';
    $contraseña = $_POST['password'] ?? '';

    if ($dni === '' || $contraseña === '') {
        $errores[] = "DNI y contraseña obligatorios.";
    } else {
        // USAMOS el método que te dije antes
        $fila = $gestorUsuarios->obtenerFilaPorDni($dni);

        if (!$fila) {
            // 1) NO EXISTE EL DNI EN LA TABLA
            $errores[] = "DNI no encontrado en la base de datos.";
        } else {
            // DEBUG OPCIONAL: ver qué trae la BD
            /*
            echo '<pre>';
            var_dump($fila);
            echo '</pre>';
            exit;
            */

            // 2) EXISTE EL DNI, MIRAMOS CONTRASEÑA
            if (!password_verify($contraseña, $fila['password'])) {
                $errores[] = "Contraseña incorrecta.";
            } else {
                // LOGIN OK
                $_SESSION['usuario'] = [
                    'dni'    => $fila['dni'],
                    'nombre' => $fila['nombre'],
                    'rol'    => $fila['rol']
                ];
                header("Location: index.php");
                exit;
            }
        }
    }
}


?>
<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <title>Centro de Musculación y Acondicionamiento Físico - Login Usuarios</title>
    </head>
    <body>
        <h2>Gestión de Usuarios Gimnasio</h2>

        <?php if (!usuarioLogueado()) : ?>

            <h2>Acceso</h2>

            <?php if ($errores) : ?>
                <ul>
                    <?php foreach ($errores as $e) {
                        echo "<li>" . htmlspecialchars($e) . "</li>";
                    } ?>
                </ul>
            <?php endif; ?>

            <form method="post" action="">
                DNI: <input type="text" name="dni" placeholder="DNI"><br>
                Contraseña: <input type="password" name="password" placeholder="Contraseña"><br>
                <input type="submit" value="Acceder">
            </form>

            <a href="registro.php">Registrarte</a> |
            <a href="olvide.php">Olvidé mi contraseña</a>

        <?php else: ?>

            <?php $usuarioSesion = $_SESSION['usuario'] ?? null; ?>

            <p>
                Hola,
                <?= htmlspecialchars($usuarioSesion['nombre'] ?? '') ?>
                (<?= htmlspecialchars($usuarioSesion['rol'] ?? '') ?>)
            </p>

            <h2>Menú</h2>
            <ul>
                <li><a href="usuarios.php">Gestión de usuarios</a></li>
                <!-- <li><a href="articulos.php">Gestión de artículos</a></li>-->
                <li><a href="logout.php">Cerrar sesión</a></li>
            </ul>

        <?php endif; ?>
    </body>
</html>
