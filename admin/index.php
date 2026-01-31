<?php
// admin/index.php
session_start();

if (!isset($_SESSION['usuario_id'])) {
  header("Location: ../index.php?error=Debes iniciar sesión.");
  exit;
}

$rol = $_SESSION['usuario_rol'] ?? '';
if ($rol !== 'admin' && $rol !== 'editor') {
  header("Location: ../dashboard.php");
  exit;
}

$tituloPagina = "Panel de administración";
require_once __DIR__ . '/../includes/header.php';

$nombre = $_SESSION['usuario_nombre'] ?? 'Admin';
$email  = $_SESSION['usuario_email'] ?? '';
?>

<div class="container mt-3">
  <nav aria-label="breadcrumb">
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="../inicio.php">Inicio</a></li>
      <li class="breadcrumb-item active" aria-current="page">Administración</li>
    </ol>
  </nav>
</div>

<div class="container mt-4" style="max-width: 900px;">
  <h2 class="mb-2">Panel de administración</h2>
  <p class="text-muted mb-4">
    <?= htmlspecialchars($nombre) ?> · <?= htmlspecialchars($email) ?>
  </p>

  <div class="row g-4">
  <div class="col-md-6">
    <div class="card card-hover shadow-sm h-100">
      <div class="card-body">
        <h5 class="card-title">Gestión de usuarios</h5>
        <p class="card-text">Altas, bajas, edición y control de roles.</p>
        <a href="../usuarios.php" class="btn btn-primary">Abrir usuarios</a>
      </div>
    </div>
  </div>

  <div class="col-md-6">
    <div class="card card-hover shadow-sm h-100">
      <div class="card-body">
        <h5 class="card-title">Inicio</h5>
        <p class="card-text">Volver a la página principal pública.</p>
        <a href="../inicio.php" class="btn btn-outline-secondary">Ir a inicio</a>
      </div>
    </div>
  </div>

  <div class="col-md-6">
    <div class="card card-hover shadow-sm h-100">
      <div class="card-body">
        <h5 class="card-title">Reservas</h5>
            <p class="card-text">Acceder al módulo de reservas.</p>
            <a href="../reservas.php" class="btn btn-outline-primary">Ir a reservas</a>

      </div>
    </div>
  </div>

  <div class="col-md-6">
    <div class="card card-hover shadow-sm h-100">
      <div class="card-body">
        <h5 class="card-title">Cerrar sesión</h5>
        <p class="card-text">Salir de forma segura.</p>
        <a href="../logout.php" class="btn btn-danger">Salir</a>
      </div>
    </div>
  </div>
</div>

</div>

<footer class="bg-dark text-light mt-5 py-4">
  <div class="container text-center">
    <img src="/gimnasio/img/logo.jpg"
     alt="Logo"
     style="width:50px;height:auto;display:inline-block;">

    <p class="mb-1">© 2025 Centro de Musculación y Acondicionamiento Físico | Todos los derechos reservados</p>
    <div class="d-flex justify-content-center gap-3">
      <a href="#" class="text-light"><i class="bi bi-facebook"></i></a>
      <a href="#" class="text-light"><i class="bi bi-instagram"></i></a>
      <a href="#" class="text-light"><i class="bi bi-twitter"></i></a>
    </div>
  </div>
</footer>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
