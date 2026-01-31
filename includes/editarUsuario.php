<?php
session_start();
require_once 'validacion.php';
requiereLogin();

require_once 'conectar_db.php';
require_once 'Usuario.php';
require_once 'GestorUsuarios.php';

$conexion = conectar();
$gestorUsuarios = new GestorUsuarios($conexion);

$rol = rol_usuario();
$dniActual = dni_usuario();

$errores = [];
$ok = false;

$dni = $_GET['dni'] ?? $_POST['dni'] ?? '';

if ($dni === '') {
    header("Location: usuarios.php");
    exit;
}

// Permisos: administrador puede editar a cualquiera, usuarios solo a sí mismos
if ($rol !== 'admin' && $dni !== $dniActual) {
    die("No tienes permiso.");
}

$usuario = $gestorUsuarios->buscarPorDni($dni);
if (!$usuario) {
    die("Usuario no encontrado.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre    = trim($_POST['nombre'] ?? '');
    $direccion = trim($_POST['direccion'] ?? '');
    $localidad = trim($_POST['localidad'] ?? '');
    $provincia = trim($_POST['provincia'] ?? '');
    $telefono  = trim($_POST['telefono'] ?? '');
    $email     = trim($_POST['email'] ?? '');
    $rolNuevo  = $usuario->getRol();

    if ($rol === 'admin') {
        $rolPost = $_POST['rol'] ?? $rolNuevo;
        if (in_array($rolPost, ['admin','editor','registrado'])) {
            $rolNuevo = $rolPost;
        }
    }

    if ($nombre === '' || $direccion === '' || $localidad === '' || $provincia === '') 
        $errores[] = "Campos obligatorios.";
    if (!telefono_valido($telefono)) 
        $errores[] = "Teléfono no válido.";
    if (!email_valido($email)) 
        $errores[] = "Email no válido.";

    if (!$errores) {
        $datos = [
            ':dni'       => $dni,
            ':nombre'    => $nombre,
            ':direccion' => $direccion,
            ':localidad' => $localidad,
            ':provincia' => $provincia,
            ':telefono'  => $telefono,
            ':email'     => $email,
            ':rol'       => $rolNuevo
        ];
        if ($gestorUsuarios->actualizar($datos)) {
            $ok = true;
            // si actualiza su propio nombre o rol, refrescar sesión
            if ($dni === $dniActual) {
                $_SESSION['usuario']['nombre'] = $nombre;
                $_SESSION['usuario']['rol'] = $rolNuevo;
            }
        } else {
            $errores[] = "Error al actualizar.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar usuario</title>
</head>
<body>
<h2>Editar usuario</h2>

<?php if ($ok): ?>
    <p>Datos actualizados correctamente.</p>
    <p><a href="usuarios.php">Volver</a></p>
<?php else: ?>

    <?php if ($errores): ?>
        <ul>
        <?php foreach ($errores as $e) echo "<li>".htmlspecialchars($e)."</li>"; ?>
        </ul>
    <?php endif; ?>

    <form method="post">
        <input type="hidden" name="dni" value="<?= htmlspecialchars($usuario->getDni()) ?>">
        DNI: <strong><?= htmlspecialchars($usuario->getDni()) ?></strong><br>
        Nombre: <input name="nombre" value="<?= htmlspecialchars($usuario->getNombre()) ?>" required><br>
        Dirección: <input name="direccion" value="<?= htmlspecialchars($usuario->getDireccion()) ?>" required><br>
        Localidad: <input name="localidad" value="<?= htmlspecialchars($usuario->getLocalidad()) ?>" required><br>
        Provincia: <input name="provincia" value="<?= htmlspecialchars($usuario->getProvincia()) ?>" required><br>
        Teléfono: <input name="telefono" value="<?= htmlspecialchars($usuario->getTelefono()) ?>" required><br>
        Email: <input name="email" type="email" value="<?= htmlspecialchars($usuario->getEmail()) ?>" required><br>
        <?php if ($rol === 'admin'): ?>
            Rol:
            <select name="rol">
                <option value="admin"      <?= $usuario->getRol() === 'admin' ? 'selected' : ''; ?>>admin</option>
                <option value="editor"     <?= $usuario->getRol() === 'editor' ? 'selected' : ''; ?>>editor</option>
                <option value="registrado" <?= $usuario->getRol() === 'registrado' ? 'selected' : ''; ?>>registrado</option>
            </select><br>
        <?php endif; ?>
        <input type="submit" value="Guardar">
    </form>

    <p><a href="usuarios.php">Volver</a></p>
<?php endif; ?>

</body>
</html>
