<?php
session_start();

if (!isset($_SESSION['usuario_id'])) {
  header("Location: index.php?error=Debes iniciar sesión.");
  exit;
}

if (($_SESSION['usuario_rol'] ?? '') !== 'cliente') {
  header("Location: autorizacion.php");
  exit;
}

$tituloPagina = "Panel - Centro de Musculación";
require_once 'includes/header.php';

$nombre = $_SESSION['usuario_nombre'] ?? 'Usuario';
$email  = $_SESSION['usuario_email'] ?? '';
$avatar = $_SESSION['usuario_avatar'] ?? 'img/logo.png';
?>

<div class="container mt-3">
  <nav aria-label="breadcrumb">
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="inicio.php">Inicio</a></li>
      <li class="breadcrumb-item active" aria-current="page">Panel</li>
    </ol>
  </nav>
</div>

<div class="container mt-4" style="max-width: 900px;">
  <div class="d-flex align-items-center mb-4">
    <img src="<?= htmlspecialchars($avatar) ?>" alt="Avatar" class="rounded-circle me-3" style="width:56px;height:56px;object-fit:cover;">
    <div>
      <h2 class="mb-0">Hola, <?= htmlspecialchars($nombre) ?></h2>
      <div class="text-muted"><?= htmlspecialchars($email) ?></div>
    </div>
  </div>

  <div class="row g-4">
    <div class="col-md-6">
      <div class="card card-hover shadow-sm h-100">
        <div class="card-body">
          <h5 class="card-title">Reservas</h5>
          <p class="card-text">Reserva clases, consulta disponibilidad y gestiona tus inscripciones.</p>
          <a href="reservas.php" class="btn btn-primary">Ir a reservas</a>
        </div>
      </div>
    </div>

    <div class="col-md-6">
      <div class="card card-hover shadow-sm h-100">
        <div class="card-body">
          <h5 class="card-title">Mi perfil</h5>
          <p class="card-text">Edita tus datos personales y tu avatar.</p>
          <a href="perfil.php" class="btn btn-outline-primary">Ver perfil</a>
        </div>
      </div>
    </div>

    <div class="col-md-6">
      <div class="card card-hover shadow-sm h-100">
        <div class="card-body">
          <h5 class="card-title">Ayuda</h5>
          <p class="card-text">Accede a información básica y contacto.</p>
          <a href="inicio.php#servicios" class="btn btn-outline-secondary">Ver servicios</a>
        </div>
      </div>
    </div>

    <div class="col-md-6">
      <div class="card card-hover shadow-sm h-100">
        <div class="card-body">
          <h5 class="card-title">Cerrar sesión</h5>
          <p class="card-text">Cierra tu sesión de forma segura.</p>
          <a href="logout.php" class="btn btn-danger">Salir</a>
        </div>
      </div>
    </div>
  </div>
</div>

<footer class="bg-dark text-light mt-5 py-4">
  <div class="container text-center">
    <img src="img/logo.jpg" class="footer-logo mb-2" alt="Logo">
    <p class="mb-1">© 2025 Centro de Musculación y Acondicionamiento Físico | Todos los derechos reservados</p>
    <div class="d-flex justify-content-center gap-3">
      <a href="#" class="text-light"><i class="bi bi-facebook"></i></a>
      <a href="#" class="text-light"><i class="bi bi-instagram"></i></a>
      <a href="#" class="text-light"><i class="bi bi-twitter"></i></a>
    </div>
  </div>
</footer>

<?php require_once 'includes/footer.php'; ?>
