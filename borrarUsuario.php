<?php
// borrarUsuario.php
session_start();

if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_rol'] !== 'admin') {
    header("Location: index.php?error=Acceso denegado");
    exit;
}

require_once 'conexion.php';
require_once 'GestorUsuarios.php';

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Evitamos borrar al propio admin conectado para no liarla
if ($id === $_SESSION['usuario_id']) {
    header("Location: usuarios.php?error=No puedes borrarte a ti mismo.");
    exit;
}

$con = obtenerConexion();

// Borrar usuario
$stmt = $con->prepare("DELETE FROM usuario WHERE id = :id");
$stmt->execute([':id' => $id]);

header("Location: usuarios.php?info=Usuario borrado correctamente");
exit;
