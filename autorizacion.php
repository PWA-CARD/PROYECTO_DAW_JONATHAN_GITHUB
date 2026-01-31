<?php
// autorizacion.php
session_start();

if (!isset($_SESSION['usuario_id'])) {
    header("Location: index.php?error=Debes iniciar sesión.");
    exit;
}

$rol = $_SESSION['usuario_rol'] ?? 'cliente';

// Admin / editor -> panel admin
if ($rol === 'admin' || $rol === 'editor') {
    header("Location: admin/index.php");
    exit;
}

// Cliente -> dashboard cliente
header("Location: dashboard.php");
exit;
