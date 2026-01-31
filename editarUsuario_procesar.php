<?php
// editarUsuario_procesar.php
session_start();

if (!isset($_SESSION['usuario_id'])) {
    header("Location: index.php?error=Debes iniciar sesión.");
    exit;
}

require_once 'conexion.php';
require_once 'GestorUsuarios.php';

$id       = intval($_POST['id'] ?? 0);
$nombre   = trim($_POST['nombre'] ?? '');
$email    = trim($_POST['email'] ?? '');
$rol      = trim($_POST['rol'] ?? '');

// Seguridad:
if ($_SESSION['usuario_rol'] !== 'admin' && $_SESSION['usuario_id'] !== $id) {
    header("Location: autorizacion.php?error=No puedes editar este usuario.");
    exit;
}

if ($nombre === '' || $email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    header("Location: editarUsuario.php?id=$id&error=Datos no válidos.");
    exit;
}

$con = obtenerConexion();
$gestor = new GestorUsuarios($con);

// Actualizamos
$gestor->actualizarUsuario([
    'id'     => $id,
    'nombre' => $nombre,
    'email'  => $email,
    'rol'    => $rol
]);

header("Location: editarUsuario.php?id=$id&info=Cambios guardados correctamente.");
exit;
