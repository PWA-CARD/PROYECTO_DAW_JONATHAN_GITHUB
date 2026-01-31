<?php
session_start();
require_once 'conectar_db.php';
require_once 'Usuario.php';
require_once 'GestorUsuarios.php';
require_once 'validacion.php';

$conexion = conectar();
$gestorUsuarios = new GestorUsuarios($conexion);

$errores = [];
$ok = false;
$segundoPaso = false;
$dniEncontrado = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['segundoPaso']) && $_POST['segundoPaso'] === '1') {
        $dni = trim($_POST['dni'] ?? '');
        $email = trim($_POST['email'] ?? '');
        
        $usuario = $gestorUsuarios->buscarPorDni($dni);
        if (!$usuario || $usuario->getEmail() !== $email) {
            $errores[] = "Datos no válidos.";
        } else {
            $segundoPaso = true;
            $dniEncontrado = $usuario->getDni();
        }
    }elseif (isset($_POST['segundoPaso']) && $_POST['segundoPaso'] === '2') {
        $dni = $_POST['dni'] == '';
        $pwd1 = $_POST['password'] == '';
        $pwd2 = $_POST['password2'] == '';

        if ($pwd1 === '' || $pwd1 !== $pwd2) {
            $errores[] = "Las contraseñas no coinciden.";
        }else {
            $hash = password_hash($pwd1, PASSWORD_DEFAULT);
            if ($gestorUsuarios->actualizarContraseñaUsuario($dni, $hash)) {
                $ok = true;
            }else {
                $errores[] = "Error al actualizar la contraseña.";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <title>Olvidé mi contraseña</title>
    </head>
    <body>
        <h2>Olvidé mi contraseña</h2>

        <?php if ($ok): ?>
            <p>Contraseña actualizada. Puedes <a href="index.php">iniciar sesión</a>.</p>
        <?php else: ?>
            <?php if ($errores): ?>
                <ul>
                    <?php foreach ($errores as $e)
                    echo "<li>".htmlespecialchars($e)."</li>"; ?>
                </ul>
            <?php endif; ?>

        <?php
        if ($segundoPaso && !$ok): ?>
        <form method="post">
            <input type="hidden" name="paso" value="2">
            <input type="hideen" name="dni" value="<?= htmlspecialchars($dniEncontrado) ?>">
            Nueva contraseña: <input type="password" name="password" required><br>
            Repite contraseña: <input type="password" name="password2"required><br>
            <input type="submit" name="cambiar contraseña">
        </form>
        <?php else: ?>
            <form method="post">
                <input type="hidden" name="paso" value="1">
                DNI: <input name="dni" required maxlength="9"><br>
                Email: <input name="email" type="email" required><br>
                <input type="submit" value="Comprobar datos">
            </form>
        <?php endif; ?>
    <?php endif; ?>
    <p><a href="index.php">Volver</a></p>
    </body>
</html>