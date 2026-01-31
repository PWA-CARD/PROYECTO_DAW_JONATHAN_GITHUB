<?php
session_start();

if (!isset($_SESSION['usuario_id'])) {
  header("Location: index.php?error=Debes iniciar sesión.");
  exit;
}

if ($_SESSION['usuario_rol'] !== 'cliente') {
  header("Location: autorizacion.php?error=Solo los clientes pueden gestionar reservas.");
  exit;
}

require_once 'conexion.php';
$con = obtenerConexion();

function obtenerIdCliente(PDO $con, int $idUsuario): int
{
  $stmt = $con->prepare("SELECT idReferencia, nombre, email FROM usuario WHERE id = :id");
  $stmt->execute([':id' => $idUsuario]);
  $u = $stmt->fetch();

  if (!$u) die("Usuario no encontrado.");

  if (!empty($u['idReferencia'])) return (int)$u['idReferencia'];

  // crea cliente mínimo si no existe
  $stmt = $con->prepare("
    INSERT INTO cliente (nombre, telefono, fechaNacimiento, eMail)
    VALUES (:nombre, '000000000', '2000-01-01', :email)
  ");
  $stmt->execute([
    ':nombre' => $u['nombre'] ?: 'Cliente sin nombre',
    ':email'  => $u['email'] ?? null
  ]);

  $idCliente = (int)$con->lastInsertId();

  $stmt = $con->prepare("UPDATE usuario SET idReferencia = :idCliente WHERE id = :idUsuario");
  $stmt->execute([':idCliente' => $idCliente, ':idUsuario' => $idUsuario]);

  return $idCliente;
}

$idUsuario = (int)$_SESSION['usuario_id'];
$idCliente = obtenerIdCliente($con, $idUsuario);

$idClase = (int)($_POST['idClase'] ?? 0);
if ($idClase <= 0) {
  header("Location: reservas.php?error=Clase no válida.");
  exit;
}

$stmt = $con->prepare("DELETE FROM inscripcion WHERE idCliente = :idCliente AND idClase = :idClase");
$stmt->execute([':idCliente' => $idCliente, ':idClase' => $idClase]);

header("Location: reservas.php?info=Reserva cancelada correctamente.");
exit;
