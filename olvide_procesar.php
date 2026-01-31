<?php
// olvide_procesar.php
session_start();

require_once 'conexion.php';
require_once 'GestorUsuarios.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: olvide.php");
    exit;
}

$email     = trim($_POST['email'] ?? '');
$pass1     = trim($_POST['password1'] ?? '');
$pass2     = trim($_POST['password2'] ?? '');

if ($email === '' || $pass1 === '' || $pass2 === '') {
    header("Location: olvide.php?error=Rellena todos los campos.");
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    header("Location: olvide.php?error=Email no válido.");
    exit;
}

if ($pass1 !== $pass2) {
    header("Location: olvide.php?error=Las contraseñas no coinciden.");
    exit;
}

$con = obtenerConexion();
$gestor = new GestorUsuarios($con);

// Buscar usuario por email
$usuario = $gestor->obtenerPorEmail($email);

if (!$usuario) {
    // Para el proyecto puedes indicar que no existe claramente:
    header("Location: olvide.php?error=No existe ningún usuario con ese email.");
    exit;
}

// Actualizar contraseña
$gestor->actualizarPassword($usuario->getId(), $pass1);

// Redirigir al login con mensaje
header("Location: index.php?info=Contraseña actualizada, ya puedes iniciar sesión.");
exit;
