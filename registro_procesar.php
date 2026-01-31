<?php
// registro_procesar.php
session_start();

require_once 'conexion.php';
require_once 'GestorUsuarios.php';

// Solo aceptar POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: registro.php");
    exit;
}

$nombre   = trim($_POST['nombre'] ?? '');
$email    = trim($_POST['email'] ?? '');
$password = trim($_POST['password'] ?? '');
$rol      = trim($_POST['rol'] ?? 'cliente');

// Validaciones básicas
if ($nombre === '' || $email === '' || $password === '') {
    header("Location: registro.php?error=Rellena todos los campos.");
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    header("Location: registro.php?error=Email no válido.");
    exit;
}

$con = obtenerConexion();
$gestor = new GestorUsuarios($con);

// Comprobar si existe el email
if ($gestor->obtenerPorEmail($email)) {
    header("Location: registro.php?error=El email ya está registrado.");
    exit;
}

// Registrar usuario
$gestor->registrarUsuario([
    'email' => $email,
    'password' => $password,
    'nombre' => $nombre,
    'rol' => $rol,
    'idReferencia' => null
]);

// Mensaje de éxito
header("Location: registro.php?info=Usuario registrado correctamente. Ya puedes iniciar sesión.");
exit;
