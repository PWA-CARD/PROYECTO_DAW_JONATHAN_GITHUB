<?php
$tituloPagina = 'Centro de Musculación y Acondicionamiento Físico';
require_once 'includes/header.php';
?>

<!-- HERO -->
<section class="hero text-center">
  <div class="container">
    <h1 class="fw-bold">Bienvenido al Centro de Musculación y Acondicionamiento Físico</h1>
    <p class="lead mt-3">
      Tu centro de musculación y acondicionamiento físico.  
      Reserva clases, gestiona tu perfil y disfruta de una experiencia fitness profesional.
    </p>

    <a href="<?= !empty($_SESSION['usuario_id']) ? 'autorizacion.php' : 'index.php' ?>"
       class="btn btn-primary btn-lg mt-3">
      Empezar ahora
    </a>
  </div>
</section>

<!-- CAROUSEL -->
<div id="carouselExample" class="carousel slide mt-5">
  <div class="carousel-inner">
    <div class="carousel-item active">
      <img src="img/banner1.webp" class="d-block w-100" alt="Banner 1">
    </div>
    <div class="carousel-item">
      <img src="img/banner2.webp" class="d-block w-100" alt="Banner 2">
    </div>
    <div class="carousel-item">
      <img src="img/banner3.webp" class="d-block w-100" alt="Banner 3">
    </div>
  </div>

  <button class="carousel-control-prev" type="button" data-bs-target="#carouselExample" data-bs-slide="prev">
    <span class="carousel-control-prev-icon"></span>
  </button>
  <button class="carousel-control-next" type="button" data-bs-target="#carouselExample" data-bs-slide="next">
    <span class="carousel-control-next-icon"></span>
  </button>
</div>

<!-- CARDS -->
<div class="container mt-5">
  <h2 class="text-center mb-4">Nuestros servicios</h2>

  <div class="row g-4">
    <div class="col-md-4">
      <div class="card card-hover shadow-sm">
        <img src="img/pesa.webp" class="card-img-top" alt="Musculación">
        <div class="card-body">
          <h5 class="card-title">Musculación</h5>
          <p class="card-text">Accede a rutinas personalizadas y entrena con nuestros profesionales.</p>
        </div>
      </div>
    </div>

    <div class="col-md-4">
      <div class="card card-hover shadow-sm">
        <img src="img/yoga.webp" class="card-img-top" alt="Clases dirigidas">
        <div class="card-body">
          <h5 class="card-title">Clases dirigidas</h5>
          <p class="card-text">Reserva clases de cardio, spinning, yoga y mucho más.</p>
        </div>
      </div>
    </div>

    <div class="col-md-4">
      <div class="card card-hover shadow-sm">
        <img src="img/boxeo.jpg" class="card-img-top" alt="Entrenamiento funcional">
        <div class="card-body">
          <h5 class="card-title">Entrenamiento funcional</h5>
          <p class="card-text">Mejora tu movilidad, equilibrio y fuerza con sesiones especializadas.</p>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- FOOTER -->
<footer class="bg-dark text-light mt-5 py-4">
  <div class="container text-center">
    <img src="img/logo.jpg" width="50" class="mb-2" alt="Logo">
    <p class="mb-1">© 2025 Centro de Musculación y Acondicionamiento Físico | Todos los derechos reservados</p>
    <div class="d-flex justify-content-center gap-3">
      <a href="#" class="text-light"><i class="bi bi-facebook"></i></a>
      <a href="#" class="text-light"><i class="bi bi-instagram"></i></a>
      <a href="#" class="text-light"><i class="bi bi-twitter"></i></a>
    </div>
  </div>
</footer>

<?php require_once 'includes/footer.php'; ?>
