<?php
// includes/header.php
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title><?= isset($tituloPagina) ? htmlspecialchars($tituloPagina) : 'Gimnasio'; ?></title>
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

  <!-- CSS del prototipo -->
  <link rel="stylesheet" href="/gimnasio/css/styles.css">

  <style>
    /*Evita que tape navbar*//
    body { margin:0; background:#e5e5e5; }
    .navbar { position: sticky; top: 0; z-index: 2000; }  
  </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container">
    <a class="navbar-brand d-flex align-items-center" href="/gimnasio/inicio.php">
      <img src="/gimnasio/img/logo.jpg" width="40" class="me-2" alt="Logo">
      <strong>Centro de Musculación y Acondicionamiento Físico</strong>
    </a>

    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ms-auto">
        <li class="nav-item"><a class="nav-link" href="/gimnasio/inicio.php">Inicio</a></li>
        <li class="nav-item"><a class="nav-link" href="/gimnasio/reservas.php">Reservas</a></li>

        <?php if (!empty($_SESSION['usuario_id']) && in_array(($_SESSION['usuario_rol'] ?? ''), ['admin','editor'], true)): ?>
          <li class="nav-item"><a class="nav-link" href="/gimnasio/admin/index.php">Admin</a></li>
        <?php endif; ?>

        <?php if (!empty($_SESSION['usuario_id'])): ?>
          <li class="nav-item"><a class="nav-link" href="/gimnasio/logout.php">Salir</a></li>
        <?php else: ?>
          <li class="nav-item"><a class="nav-link" href="/gimnasio/index.php">Iniciar sesión</a></li>
        <?php endif; ?>
      </ul>
    </div>
  </div>
</nav>
