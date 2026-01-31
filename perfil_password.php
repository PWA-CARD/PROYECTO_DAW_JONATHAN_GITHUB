<?php
// perfil_password.php
session_start();

if (!isset($_SESSION['usuario_id'])) {
    header("Location: index.php?error=Debes iniciar sesión.");
    exit;
}

require_once 'conexion.php';
require_once 'GestorUsuarios.php';

$id   = intval($_POST['id'] ?? 0);
$p1   = trim($_POST['password1'] ?? '');
$p2   = trim($_POST['password2'] ?? '');

if ($id !== $_SESSION['usuario_id']) {
    header("Location: autorizacion.php?error=No puedes cambiar la contraseña de otro usuario.");
    exit;
}

if ($p1 === '' || $p2 === '') {
    header("Location: perfil.php?error=Rellena las dos contraseñas.");
    exit;
}

if ($p1 !== $p2) {
    header("Location: perfil.php?error=Las contraseñas no coinciden.");
    exit;
}

$con = obtenerConexion();
$gestor = new GestorUsuarios($con);

$gestor->actualizarPassword($id, $p1);

header("Location: perfil.php?info=Contraseña actualizada correctamente.");
exit;
