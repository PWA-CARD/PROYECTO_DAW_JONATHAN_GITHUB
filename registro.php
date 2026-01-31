<?php
// registro.php
session_start();

$tituloPagina = 'Registro - Gimnasio';

require_once 'includes/header.php';

$errores = $_GET['error'] ?? '';
$info = $_GET['info'] ?? '';
?>

<div class="phone-frame shadow-sm">

  <div class="top-text">Crear cuenta</div>
  <div class="screen-title">Registro de usuario</div>

  <?php if ($errores): ?>
    <div class="alert alert-danger py-1 small">
      <?= htmlspecialchars($errores) ?>
    </div>
  <?php endif; ?>

  <?php if ($info): ?>
    <div class="alert alert-success py-1 small">
      <?= htmlspecialchars($info) ?>
    </div>
  <?php endif; ?>

  <div class="card form-card mt-2">
    <div class="card-body">

      <form action="registro_procesar.php" method="post">

        <div class="mb-3">
          <label class="form-label mb-1">Nombre</label>
          <input type="text" class="form-control form-control-sm" name="nombre" required>
        </div>

        <div class="mb-3">
          <label class="form-label mb-1">Email</label>
          <input type="email" class="form-control form-control-sm" name="email" required>
        </div>

        <div class="mb-3">
          <label class="form-label mb-1">Contraseña</label>
          <input type="password" class="form-control form-control-sm" name="password" required>
        </div>

        <div class="mb-3">
          <label class="form-label mb-1">Rol</label>
          <select name="rol" class="form-select form-select-sm" required>
            <option value="cliente">Cliente</option>
            <option value="entrenador">Entrenador</option>
            <option value="recepcionista">Recepcionista</option>
            <option value="admin">Administrador</option>
          </select>
        </div>

        <button type="submit" class="btn btn-register w-100 py-2">
          Crear cuenta
        </button>

        <div class="mt-3 text-center" style="font-size:0.8rem;">
          <a href="index.php">Volver al login</a>
        </div>

      </form>

    </div>
  </div>

</div>

<?php require_once 'includes/footer.php'; ?>
