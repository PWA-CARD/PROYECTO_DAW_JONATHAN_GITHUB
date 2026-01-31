<?php
// usuarios.php
session_start();

// Comprobar login
if (!isset($_SESSION['usuario_id'])) {
    header("Location: index.php?error=Debes iniciar sesión.");
    exit;
}

// Comprobar rol admin
if ($_SESSION['usuario_rol'] !== 'admin') {
    header("Location: autorizacion.php?error=No tienes permiso para ver esta página.");
    exit;
}

require_once 'conexion.php';
require_once 'GestorUsuarios.php';

$tituloPagina = 'Usuarios - Gimnasio';
require_once 'includes/header.php';

// Obtener usuarios
$con = obtenerConexion();
$gestor = new GestorUsuarios($con);
$listaUsuarios = $gestor->obtenerTodos();
?>

<div class="phone-frame shadow-sm">

  <div class="top-text">Panel admin</div>
  <div class="screen-title">Listado de usuarios</div>

  <div class="card form-card mt-2">
    <div class="card-body" style="max-height: 480px; overflow-y:auto;">

      <?php if (empty($listaUsuarios)): ?>
        <p class="small mb-0">No hay usuarios registrados.</p>
      <?php else: ?>
        <ul class="list-group list-group-flush">
          <?php foreach ($listaUsuarios as $u): ?>
            <li class="list-group-item px-0">
              <div class="d-flex justify-content-between">
                <div>
                  <strong><?= htmlspecialchars($u->getNombre()) ?></strong><br>
                  <span style="font-size:0.8rem;">
                    <?= htmlspecialchars($u->getEmail()) ?><br>
                    Rol: <?= htmlspecialchars($u->getRol()) ?>
                  </span>
                </div>
                <div class="d-flex align-items-center gap-1">
                    <a href="editarUsuario.php?id=<?= $u->getId() ?>"
                    class="btn btn-sm btn-primary"
                    style="font-size: 0.7rem; padding: 2px 6px;">
                    Editar
                    </a>

                    <a href="borrarUsuario.php?id=<?= $u->getId() ?>"
                    onclick="return confirm('¿Seguro que deseas borrar este usuario?');"
                    class="btn btn-sm btn-danger"
                    style="font-size: 0.7rem; padding: 2px 6px;">
                    Borrar
                    </a>
                </div>

              </div>
            </li>
          <?php endforeach; ?>
        </ul>
      <?php endif; ?>

      <div class="mt-3 text-center" style="font-size:0.8rem;">
        <a href="autorizacion.php">Volver al panel</a>
      </div>

    </div>
  </div>

</div>

<?php require_once 'includes/footer.php'; ?>
