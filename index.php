<?php
// index.php
session_start();

// Si ya está logueado, lo mandamos directamente a la zona privada
if (isset($_SESSION['usuario_id'])) {
    header('Location: autorizacion.php');
    exit;
}

$tituloPagina = 'Login - Gimnasio';
require_once 'includes/header.php';

// Gestión sencilla de mensajes (error / info)
$mensajeError = '';
$mensajeInfo  = '';

if (!empty($_GET['error'])) {
    if ($_GET['error'] == 1) {
        $mensajeError = 'Debes introducir un email y una contraseña válidos.';
    } elseif ($_GET['error'] == 2) {
        $mensajeError = 'Credenciales incorrectas. Revisa email y contraseña.';
    }
}

if (!empty($_GET['info']) && $_GET['info'] == 'logout') {
    $mensajeInfo = 'Has cerrado sesión correctamente.';
}
?>

<div class="container mt-3">
  <nav aria-label="breadcrumb">
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="inicio.php">Inicio</a></li>
      <li class="breadcrumb-item active" aria-current="page">Iniciar sesión</li>
    </ol>
  </nav>
</div>

<div class="container mt-5" style="max-width: 420px;">
  <!-- Mensajes -->
  <?php if ($mensajeError): ?>
    <div class="alert alert-danger">
      <?= htmlspecialchars($mensajeError) ?>
    </div>
  <?php endif; ?>

  <?php if ($mensajeInfo): ?>
    <div class="alert alert-success">
      <?= htmlspecialchars($mensajeInfo) ?>
    </div>
  <?php endif; ?>

  <div class="card shadow-sm">
    <div class="card-body">
      <h3 class="text-center mb-4">Acceso al gimnasio</h3>

      <form action="validacion.php" method="post">
        <div class="mb-3">
          <label for="email" class="form-label">Correo electrónico</label>
          <input
            type="email"
            class="form-control"
            id="email"
            name="email"
            placeholder="tuemail@correo.com"
            required
          >
        </div>

        <div class="mb-3">
          <label for="password" class="form-label">Contraseña</label>
          <input
            type="password"
            class="form-control"
            id="password"
            name="password"
            placeholder="••••••••"
            required
          >
        </div>

        <div class="form-check mb-3">
          <input class="form-check-input" type="checkbox" value="1" id="remember" name="remember">
          <label class="form-check-label" for="remember">
            Recordar
          </label>
          <div class="form-text">Mantener la sesión iniciada</div>
        </div>

        <button type="submit" class="btn btn-primary w-100">Entrar</button>

        <div class="mt-3 text-center" style="font-size: 0.9rem;">
          <a href="registro.php">Crear cuenta</a> ·
          <a href="olvide.php">He olvidado la contraseña</a>
        </div>
      </form>
    </div>
  </div>
</div>

<footer class="bg-dark text-light mt-5 py-4">
  <div class="container text-center">
    <img src="img/logo.png" class="footer-logo mb-2" alt="Logo">
    <p class="mb-1">© 2025 Centro de Musculación y Acondicionamiento Físico | Todos los derechos reservados</p>
    <div class="d-flex justify-content-center gap-3">
      <a href="#" class="text-light"><i class="bi bi-facebook"></i></a>
      <a href="#" class="text-light"><i class="bi bi-instagram"></i></a>
      <a href="#" class="text-light"><i class="bi bi-twitter"></i></a>
    </div>
  </div>
</footer>


<?php
require_once 'includes/footer.php';
