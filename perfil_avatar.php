<?php
// perfil_avatar.php
session_start();

if (!isset($_SESSION['usuario_id'])) {
    header("Location: index.php?error=Debes iniciar sesión.");
    exit;
}

require_once 'conexion.php';
require_once 'GestorUsuarios.php';

$id = intval($_POST['id'] ?? 0);

if ($id !== $_SESSION['usuario_id']) {
    header("Location: autorizacion.php?error=No puedes cambiar el avatar de otro usuario.");
    exit;
}

if (!isset($_FILES['avatar']) || $_FILES['avatar']['error'] !== UPLOAD_ERR_OK) {
    header("Location: perfil.php?error=Error al subir la imagen.");
    exit;
}

$archivo = $_FILES['avatar'];

$ext = strtolower(pathinfo($archivo['name'], PATHINFO_EXTENSION));
$extensionesPermitidas = ['jpg', 'jpeg', 'png', 'gif'];

if (!in_array($ext, $extensionesPermitidas)) {
    header("Location: perfil.php?error=Formato de imagen no válido.");
    exit;
}

$carpeta = __DIR__ . '/avatars';
if (!is_dir($carpeta)) {
    mkdir($carpeta, 0777, true);
}

$nombreFichero = 'avatar_' . $id . '_' . time() . '.' . $ext;
$rutaFisica = $carpeta . '/' . $nombreFichero;
$rutaRelativa = 'avatars/' . $nombreFichero;

if (!move_uploaded_file($archivo['tmp_name'], $rutaFisica)) {
    header("Location: perfil.php?error=No se pudo guardar la imagen.");
    exit;
}

$con = obtenerConexion();
$gestor = new GestorUsuarios($con);

$gestor->actualizarAvatar($id, $rutaRelativa);

// actualizar en sesión para que se vea en otras pantallas
$_SESSION['usuario_avatar'] = $rutaRelativa;

header("Location: perfil.php?info=Avatar actualizado correctamente.");
exit;
