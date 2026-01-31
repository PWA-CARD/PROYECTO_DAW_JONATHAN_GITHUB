<?php
// validacion.php
session_start();

require_once 'conexion.php';
require_once 'GestorUsuarios.php';

// Si no viene por POST, lo mandamos al login
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit;
}

$email    = trim($_POST['email'] ?? '');
$password = trim($_POST['password'] ?? '');

// Validación muy básica de campos
if ($email === '' || $password === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    header('Location: index.php?error=1');
    exit;
}

// Conexión y gestor
$con = obtenerConexion();
$gestor = new GestorUsuarios($con);

// Intentamos validar login
$usuario = $gestor->validarLogin($email, $password);

if (!$usuario) {
    // Email no existe o contraseña incorrecta
    header('Location: index.php?error=2');
    exit;
}

// Login correcto: guardamos datos mínimos en sesión
$_SESSION['usuario_id']   = $usuario->getId();
$_SESSION['usuario_email']= $usuario->getEmail();
$_SESSION['usuario_nombre']= $usuario->getNombre();
$_SESSION['usuario_rol']  = $usuario->getRol();
$_SESSION['usuario_avatar'] = $usuario->getAvatar();

// Si marcó “Recordar”, podrías poner una cookie. De momento lo dejamos así.
// Redirigimos a la zona privada (autorizacion.php será nuestro “panel”)
header('Location: autorizacion.php');
exit;
