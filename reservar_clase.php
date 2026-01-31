<?php
// reservar_clase.php
session_start();

if (!isset($_SESSION['usuario_id'])) {
    header("Location: index.php?error=Debes iniciar sesión.");
    exit;
}

if ($_SESSION['usuario_rol'] !== 'cliente') {
    header("Location: autorizacion.php?error=Solo los clientes pueden reservar clases.");
    exit;
}

require_once 'conexion.php';

$con = obtenerConexion();

function obtenerIdCliente(PDO $con, int $idUsuario): int
{
    $stmt = $con->prepare("SELECT idReferencia, nombre, email FROM usuario WHERE id = :id");
    $stmt->execute([':id' => $idUsuario]);
    $usuario = $stmt->fetch();

    if (!$usuario) {
        die("Usuario no encontrado.");
    }

    if (!empty($usuario['idReferencia'])) {
        return (int)$usuario['idReferencia'];
    }

    $nombre = $usuario['nombre'] ?: 'Cliente sin nombre';
    $email  = $usuario['email'] ?? null;
    $telefono = '000000000';
    $fechaNac = '2000-01-01';

    $stmt = $con->prepare("
        INSERT INTO cliente (nombre, telefono, fechaNacimiento, eMail)
        VALUES (:nombre, :telefono, :fechaNacimiento, :email)
    ");
    $stmt->execute([
        ':nombre'         => $nombre,
        ':telefono'       => $telefono,
        ':fechaNacimiento'=> $fechaNac,
        ':email'          => $email
    ]);

    $idCliente = (int)$con->lastInsertId();

    $stmt = $con->prepare("UPDATE usuario SET idReferencia = :idCliente WHERE id = :idUsuario");
    $stmt->execute([
        ':idCliente' => $idCliente,
        ':idUsuario' => $idUsuario
    ]);

    return $idCliente;
}

$idUsuario = (int)$_SESSION['usuario_id'];
$idCliente = obtenerIdCliente($con, $idUsuario);

$idClase = isset($_POST['idClase']) ? (int)$_POST['idClase'] : 0;

if ($idClase <= 0) {
    header("Location: reservas.php?error=Clase no válida.");
    exit;
}

// 1) Comprobar que la clase existe y obtener capacidad
$stmt = $con->prepare("SELECT capacidad FROM clase WHERE idClase = :idClase");
$stmt->execute([':idClase' => $idClase]);
$clase = $stmt->fetch();

if (!$clase) {
    header("Location: reservas.php?error=La clase no existe.");
    exit;
}

// 2) Comprobar si ya está inscrito
$stmt = $con->prepare("
    SELECT 1 
    FROM inscripcion 
    WHERE idCliente = :idCliente 
      AND idClase = :idClase
");
$stmt->execute([
    ':idCliente' => $idCliente,
    ':idClase'   => $idClase
]);
$yaInscrito = $stmt->fetchColumn();

if ($yaInscrito) {
    header("Location: reservas.php?error=Ya estás inscrito en esta clase.");
    exit;
}

// 3) Comprobar aforo (inscritos actuales)
$stmt = $con->prepare("
    SELECT COUNT(*) 
    FROM inscripcion 
    WHERE idClase = :idClase
");
$stmt->execute([':idClase' => $idClase]);
$inscritos = (int)$stmt->fetchColumn();

$capacidad = (int)$clase['capacidad'];

if ($inscritos >= $capacidad) {
    header("Location: reservas.php?error=La clase está completa.");
    exit;
}

// 4) Insertar inscripción
$hoy = date('Y-m-d');

$stmt = $con->prepare("
    INSERT INTO inscripcion (fechaInscripcion, idCliente, idClase)
    VALUES (:fecha, :idCliente, :idClase)
");
$stmt->execute([
    ':fecha'     => $hoy,
    ':idCliente' => $idCliente,
    ':idClase'   => $idClase
]);

header("Location: reservas.php?info=Reserva realizada correctamente.");
exit;
