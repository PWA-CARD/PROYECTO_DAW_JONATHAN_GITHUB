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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $dni = trim($_POST['dni'] ?? '');
    $nombre = trim($_POST['nombre'] ?? '');
    $direccion = trim($_POST['direccion'] ?? '');
    $localidad = trim($_POST['localidad'] ?? '');
    $provincia = trim($_POST['provincia'] ?? '');
    $telefono = trim($_POST['telefono'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');
    $password2 = trim($_POST['password2'] ?? '');

    // validaciones
    if (!dniValido($dni))
        $errores[] = "DNI no válido.";
    if ($gestorUsuarios->dniExiste($dni))
        $errores[] = "DNI en uso.";
    if ($nombre === '' || $direccion === '' || $localidad === '' || $provincia === '')
        $errores[] = "Obligatorios.";
    if (!telefonoValido($telefono))
        $errores[] = "Teléfono debe tener 9 dígitos.";
    if (!emailValido($email))
        $errores[] = "Correo no válido.";
    if (strlen($password) < 4)
        $errores[] = "Contraseña corta.";
    if ($password !== $password2)
        $errores[] = "Las contraseñas no coinciden.";

    if (!$errores) {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $datos = [
            ':dni' => $dni,
            ':nombre' => $nombre,
            ':direccion' => $direccion,
            ':localidad' => $localidad,
            ':provincia' => $provincia,
            ':telefono' => $telefono,
            ':email' => $email,
            ':password' => $hash,
            ':rol' => 'registrado'
        ];
        if ($gestorUsuarios->registrarUsuario($datos)) {
            $ok = true;
        }else {
            $errores[] = "Error de usuario.";
        }
    }

}
?>
<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <title>Registro</title>
    </head>
    <body>
        <h2>Registro de usuario</h2>

        <?php if ($ok): ?>
            <p>Usuario creado correctamente. Puedes <a href="index.php"> iniciar sesión</a>.</p>
        <?php else: ?>
            <?php if ($errores): ?>
                <ul>
                    <?php foreach ($errores as $e)
                        echo "<li>" . htmlspecialchars($e) . "</li>"; 
                    ?>
                </ul>
            <?php endif; ?>

            <form method="post">
                DNI: <input name="dni" maxlength="9" required><br>
                Nombre: <input name="nombre" maxlength="50" required><br>
                Dirección: <input name="direccion" maxlength="100" required><br>
                Localidad: <input name="localidad" maxlength="50" required><br>
                Provincia: <input name="provincia" maxlength="50" required><br>
                Teléfono: <input name="telefono" maxlength="9" required><br>
                Email: <input name="email" type="email" maxlength="100" required><br>
                Contraseña: <input name="password" type="password" required><br>
                Repite contraseña: <input name="password2" type="password" required><br>
                <input type="submit" value="Registrarse">
            </form>
            <?php endif; ?>
            <p><a href="index.php">Volver</a></p>
            
    </body>
    </html>