<?php
// editarUsuario.php
session_start();

// Debes estar logueado
if (!isset($_SESSION['usuario_id'])) {
    header("Location: index.php?error=Debes iniciar sesión.");
    exit;
}

require_once 'conexion.php';
require_once 'GestorUsuarios.php';

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Seguridad:
// ADMIN → puede editar a cualquiera
// Usuario normal → solo puede editar su propia cuenta
if ($_SESSION['usuario_rol'] !== 'admin' && $_SESSION['usuario_id'] !== $id) {
    header("Location: autorizacion.php?error=No tienes permiso para editar este usuario.");
    exit;
}

$con = obtenerConexion();
$gestor = new GestorUsuarios($con);
$usuario = null;

// Obtener usuario por email no sirve aquí → creamos método rápido:
$stmt = $con->prepare("SELECT * FROM usuario WHERE id = :id");
$stmt->execute([':id' => $id]);
$fila = $stmt->fetch();

if (!$fila) {
    header("Location: usuarios.php?error=Usuario no encontrado.");
    exit;
}

$usuario = $fila;

$tituloPagina = "Editar usuario";
require_once 'includes/header.php';

// Mensajes
$err = $_GET['error'] ?? '';
$info = $_GET['info'] ?? '';
?>

<div class="phone-frame shadow-sm">

  <div class="top-text">Editar</div>
  <div class="screen-title">Editar usuario</div>

  <?php if ($err): ?>
    <div class="alert alert-danger py-1 small"><?= htmlspecialchars($err) ?></div>
  <?php endif; ?>

  <?php if ($info): ?>
    <div class="alert alert-success py-1 small"><?= htmlspecialchars($info) ?></div>
  <?php endif; ?>

  <div class="card form-card mt-2">
    <div class="card-body">

      <form action="editarUsuario_procesar.php" method="post">

        <input type="hidden" name="id" value="<?= $usuario['id'] ?>">

        <div class="mb-3">
          <label class="form-label mb-1">Nombre</label>
          <input 
            type="text"
            class="form-control form-control-sm"
            name="nombre"
            value="<?= htmlspecialchars($usuario['nombre']) ?>"
            required
          >
        </div>

        <div class="mb-3">
          <label class="form-label mb-1">Email</label>
          <input 
            type="email"
            class="form-control form-control-sm"
            name="email"
            value="<?= htmlspecialchars($usuario['email']) ?>"
            required
          >
        </div>

        <div class="mb-3">
          <label class="form-label mb-1">Rol</label>
          <select name="rol" class="form-select form-select-sm" 
            <?= $_SESSION['usuario_rol'] !== 'admin' ? 'disabled' : '' ?>>
            
            <option value="cliente"        <?= $usuario['rol']=='cliente' ? 'selected' : '' ?>>Cliente</option>
            <option value="entrenador"     <?= $usuario['rol']=='entrenador' ? 'selected' : '' ?>>Entrenador</option>
            <option value="recepcionista"  <?= $usuario['rol']=='recepcionista' ? 'selected' : '' ?>>Recepcionista</option>
            <option value="admin"          <?= $usuario['rol']=='admin' ? 'selected' : '' ?>>Administrador</option>

          </select>

          <?php if ($_SESSION['usuario_rol'] !== 'admin'): ?>
            <input type="hidden" name="rol" value="<?= htmlspecialchars($usuario['rol']) ?>">
          <?php endif; ?>

        </div>

        <button type="submit" class="btn btn-register w-100 py-2">Guardar cambios</button>

        <div class="mt-3 text-center" style="font-size:0.8rem;">
          <a href="usuarios.php">Volver</a>
        </div>

      </form>

    </div>
  </div>

</div>

<?php require_once 'includes/footer.php'; ?>
