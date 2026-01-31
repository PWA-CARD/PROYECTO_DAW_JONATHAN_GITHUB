<?php
session_start();
require_once 'validacion.php';
requiereLogin();

require_once 'conectar_db.php';
require_once 'Usuario.php';
require_once 'GestorUsuarios.php';

$conexion = conectar();
$gestorUsuarios = new GestorUsuarios($conexion);

$rol = rolUsuario();
$dniActual = dniUsuario();

// búsqueda, paginación y orden
$pagina = isset($_GET['p']) ? max(1, (int)$_GET['p']) : 1;
$porPagina = 5;
$buscar = $_GET['buscar'] ?? '';
$orden = $_GET['orden'] ?? 'nombre';

//cambio de rol (solo administrador no a sí mismo)
if ($rol === 'admin' && isset($_POST['accion']) && $_POST['accion'] === 'cambiarRol') {
    $dni =$_POST['dni'] ?? '';
    $nuevoRol = $_POST['rol'] ?? '';
    if ($dni !== $dniActual && in_array($nuevoRol, ['admin', 'editor', 'registrado'])) {
        $u = $gestorUsuarios->buscarPorDni($dni);

        if ($u) {
            $datos = [
                ':dni' => $u->getDni(),
                ':nombre' => $u->getNombre(),
                ':direccion' => $u->getDireccion(),
                ':localidad' => $u->getLocalidad(),
                ':provincia' => $u->getProvincia(),
                ':telefono' => $u->getTelefono(),
                ':email' => $u->getEmail(),
                ':rol' => $nuevoRol
            ];
            $gestorUsuarios->actualizar($datos);
        }
    }
}

// Eliminar usuario no a sí mismo
if (isset($_POST['accion']) && $_POST['accion'] === 'eliminar') {
    $dni = $_POST['dni'] ?? '';
    if ($dni !== $dniActual) {
        // administrador elimina a cualquier y usuario solo a sí mismo
        if ($rol === 'admin' || ($rol !== 'admin' && $dni === $dniActual)) {
            $gestorUsuarios->eliminar($dni);
            if ($dni === $dniActual) {
                // se cierra sesión al borrarse a si mismo
                session_unset();
                session_destroy();
                header("Location: index.php");
                exit;
            }
        }
    }
}

if ($rol === 'admin'){
    list($usuarios, $total) = $gestorUsuarios->listar($pagina, $porPagina, $buscar, $orden);

}else {
    // solo se ven a si mismos editor y usuario
    $u = $gestorUsuarios->buscarPorDni($dniActual);
    $usuarios = $u ? [$u] : [];
    $total = 1;
}
$totalPaginas = max(1, ceil($total / $porPagina));
?>
<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <title>Usuarios</title>
    </head>
    <body>
        <h2>Gestión de usuarios</h2>
        <p><a href="index.php">Volver al menú</a></p>

        <?php if ($rol === 'admin'): ?>
            <form method="get">
                Buscar: <input type="text" name="buscar" value="<?= htmlspecialchars($buscar) ?>">
                Ordenar por: 
                    <select name="orden">
                        <option value="nombre" <?= $orden === 'nombre' ? 'selected' : ''; ?>>Nombre</option>
                        <option value="dni" <?= $orden === 'dni' ? 'selected' : ''; ?>>DNI</option>
                        <option value="localidad" <?= $orden === 'localidad' ? 'selected' : ''; ?>>Localidad</option>
                        <option value="provincia" <?= $orden === 'provincia' ? 'selected' : ''; ?>>Provincia</option>
                        <input type="submit" value="Filtrar">
            </form>
        <?php endif; ?>

        <table border="1">
            <tr>
                <th>DNI</th>
                <th>Nombre</th>
                <th>Dirección</th>
                <th>Localidad</th>
                <th>Provincia</th>
                <th>Teléfono</th>
                <th>Email</th>
                <th>Rol</th>
                <th>Acciones</th>
            </tr>
            <?php foreach ($usuarios as $u): ?>
                <tr>
                    <td><?= htmlspecialchars($u->getDni()) ?></td>
                    <td><?= htmlspecialchars($u->getNombre()) ?></td>
                    <td><?= htmlspecialchars($u->getDireccion()) ?></td>
                    <td><?= htmlspecialchars($u->getLocalidad()) ?></td>
                    <td><?= htmlspecialchars($u->getProvincia()) ?></td>
                    <td><?= htmlspecialchars($u->getTelefono()) ?></td>
                    <td><?= htmlspecialchars($u->getEmail()) ?></td>
                    <td>
                        <?php if ($rol === 'admin' && $u->getDni() !== $dniActual): ?>
                            <form method="post" style="display:inline;">
                                <input type="hidden" name="accion" value="cambiarRol">
                                <input type="hidden" name="dni" value="<?= htmlspecialchars($u->getDni()) ?>">
                                <select name="rol" onchange="this.form.submit()">
                                    <option value="admin" <?= $u->getRol() === 'admin' ? 'selected' : ''; ?>>admin</option>
                                    <option value="editor" <?= $u->getRol() === 'editor' ? 'selected' : ''; ?>>editor</option>
                                    <option value="editor" <?= $u->getRol() === 'editor' ? 'selected' : ''; ?>>editor</option>
                                    <option value="registrado" <?= $u->getRol() === 'registrado' ? 'selected' : ''; ?>>registrado</option>
                                </select>
                            </form>
                            <?php else: ?>
                                <?= htmlspecialchars($u->getRol())?>
                        <?php endif; ?>
                    </td>
                    <td>
                        <a href="usuarioEditar.php?dni=<?= urlencode($u->getDni()) ?>">Editar</a>
                        <?php if ($u->getDni() !== $dniActual || $rol !== 'admin'): ?>
                            <form method="post" style="display:inline;" onsubmit="return confirm('¿Seguro que quieres eliminar?');">
                                <input type="hidden" name="accion" value="eliminar">
                                <input type="hidden" name="dni" value="<?= htmlspecialchars($u->getDni()) ?>">
                                <input type="submit" value="Eliminar">
                            </form>
                        <?php endif; ?>

                    </td>
                </tr>
                <?php endforeach; ?>
        </table>
        <?php if ($rol === 'admin'): ?>
            <p>Página:
                <?php for ($i = 1; $i <= $totalPaginas; $i++): ?>
                    <?php if ($i == $pagina): ?>
                        <strong><?= $i ?></strong>
                        <?php else: ?>
                            <a href="?p=<?= $i ?>&buscar=<?= urlencode($buscar) ?>&orden=<?= urlencode($orden) ?>"><?= $i ?></a>
                        <?php endif; ?>
                    <?php endfor; ?>

            </p>
        <?php endif; ?>
    </body>
</html>
