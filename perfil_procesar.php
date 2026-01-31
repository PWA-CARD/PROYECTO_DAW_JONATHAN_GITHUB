<?php
// perfil_procesar.php
session_start();

if (!isset($_SESSION['usuario_id'])) {
    header("Location: index.php?error=Debes iniciar sesión.");
    exit;
}

require_once 'conexion.php';
require_once 'GestorUsuarios.php';

$id     = intval($_POST['id'] ?? 0);
$nombre = trim($_POST['nombre'] ?? '');
$email  = trim($_POST['email'] ?? '');

// Seguridad: solo puede editar su propio perfil
if ($id !== $_SESSION['usuario_id']) {
    header("Location: autorizacion.php?error=No puedes editar otro usuario.");
    exit;
}

// Validaciones
if ($nombre === '' || $email === '') {
    header("Location: perfil.php?error=Rellena todos los campos.");
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    header("Location: perfil.php?error=Email no válido.");
    exit;
}

$con = obtenerConexion();
$gestor = new GestorUsuarios($con);

// Actualizar usuario
$gestor->actualizarUsuario([
    'id'     => $id,
    'nombre' => $nombre,
    'email'  => $email,
    'rol'    => $_SESSION['usuario_rol'] // no se cambia aquí
]);

// Actualizar sesión
$_SESSION['usuario_nombre'] = $nombre;
$_SESSION['usuario_email']  = $email;

header("Location: perfil.php?info=Datos actualizados correctamente.");
exit;
