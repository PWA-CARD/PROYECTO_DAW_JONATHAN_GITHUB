<?php
// olvide.php
session_start();

$tituloPagina = 'Recuperar contraseña';
require_once 'includes/header.php';

$err  = $_GET['error'] ?? '';
$info = $_GET['info'] ?? '';
?>

<div class="phone-frame shadow-sm">

  <div class="top-text">Recuperar acceso</div>
  <div class="screen-title">Olvidé mi contraseña</div>

  <?php if ($err): ?>
    <div class="alert alert-danger py-1 small">
      <?= htmlspecialchars($err) ?>
    </div>
  <?php endif; ?>

  <?php if ($info): ?>
    <div class="alert alert-success py-1 small">
      <?= htmlspecialchars($info) ?>
    </div>
  <?php endif; ?>

  <div class="card form-card mt-2">
    <div class="card-body">

      <p class="small">
        Introduce tu email y una nueva contraseña para restablecer el acceso.
      </p>

      <form action="olvide_procesar.php" method="post">

        <div class="mb-3">
          <label class="form-label mb-1">Email</label>
          <input 
            type="email"
            class="form-control form-control-sm"
            name="email"
            required
          >
        </div>

        <div class="mb-3">
          <label class="form-label mb-1">Nueva contraseña</label>
          <input 
            type="password"
            class="form-control form-control-sm"
            name="password1"
            required
          >
        </div>

        <div class="mb-3">
          <label class="form-label mb-1">Repite la contraseña</label>
          <input 
            type="password"
            class="form-control form-control-sm"
            name="password2"
            required
          >
        </div>

        <button type="submit" class="btn btn-register w-100 py-2">
          Restablecer contraseña
        </button>

        <div class="mt-3 text-center" style="font-size:0.8rem;">
          <a href="index.php">Volver al login</a>
        </div>

      </form>

    </div>
  </div>

</div>

<?php require_once 'includes/footer.php'; ?>
