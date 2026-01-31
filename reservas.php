<?php
// reservas.php
session_start();

if (!isset($_SESSION['usuario_id'])) {
    header("Location: index.php?error=Debes iniciar sesión.");
    exit;
}

// Permitimos acceder a cliente/admin/editor.
// (Cliente reserva. Admin/editor solo ven.)
if (!in_array($_SESSION['usuario_rol'] ?? '', ['cliente', 'admin', 'editor'], true)) {
    header("Location: autorizacion.php?error=No tienes permiso para acceder a reservas.");
    exit;
}

require_once 'conexion.php';
$con = obtenerConexion();

/**
 * Devuelve el idCliente asociado al usuario logueado.
 * Si no existe, crea un registro en cliente y lo asocia a usuario.idReferencia.
 */
function obtenerIdCliente(PDO $con, int $idUsuario): int
{
    $stmt = $con->prepare("SELECT idReferencia, nombre, email FROM usuario WHERE id = :id");
    $stmt->execute([':id' => $idUsuario]);
    $usuario = $stmt->fetch();

    if (!$usuario) {
        die("Usuario no encontrado.");
    }

    if (!empty($usuario['idReferencia'])) {
        return (int)$usuario['idReferencia'];
    }

    // No tiene cliente asociado: creamos uno mínimo
    $nombre = $usuario['nombre'] ?: 'Cliente sin nombre';
    $email  = $usuario['email'] ?? null;

    // Valores por defecto (campos NOT NULL)
    $telefono = '000000000';
    $fechaNac = '2000-01-01';

    $stmt = $con->prepare("
        INSERT INTO cliente (nombre, telefono, fechaNacimiento, eMail)
        VALUES (:nombre, :telefono, :fechaNacimiento, :email)
    ");
    $stmt->execute([
        ':nombre'          => $nombre,
        ':telefono'        => $telefono,
        ':fechaNacimiento' => $fechaNac,
        ':email'           => $email
    ]);

    $idCliente = (int)$con->lastInsertId();

    $stmt = $con->prepare("UPDATE usuario SET idReferencia = :idCliente WHERE id = :idUsuario");
    $stmt->execute([
        ':idCliente' => $idCliente,
        ':idUsuario' => $idUsuario
    ]);

    return $idCliente;
}

$idUsuario = (int)$_SESSION['usuario_id'];
$idCliente = obtenerIdCliente($con, $idUsuario);

$tituloPagina = 'Reservar clases';
require_once 'includes/header.php';

$err  = $_GET['error'] ?? '';
$info = $_GET['info'] ?? '';

// Obtenemos las clases con aforo e inscripciones
$sql = "
    SELECT 
        c.idClase,
        c.nombreClase,
        c.duracion,
        c.capacidad,
        e.nombre AS nombreEntrenador,
        COUNT(i.idCliente) AS inscritos,
        MAX(CASE WHEN i.idCliente = :idCliente THEN 1 ELSE 0 END) AS ya_inscrito
    FROM clase c
    JOIN entrenador e ON c.idEmpleado = e.idEmpleado
    LEFT JOIN inscripcion i ON c.idClase = i.idClase
    GROUP BY c.idClase, c.nombreClase, c.duracion, c.capacidad, e.nombre
    ORDER BY c.nombreClase
";
$stmt = $con->prepare($sql);
$stmt->execute([':idCliente' => $idCliente]);
$clases = $stmt->fetchAll();

?>

<!-- Migas de pan -->
<div class="container mt-3">
  <nav aria-label="breadcrumb">
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="inicio.php">Inicio</a></li>
      <li class="breadcrumb-item active" aria-current="page">Reservar clases</li>
    </ol>
  </nav>
</div>

<div class="container mt-4" style="max-width: 900px;">
  <h2 class="mb-3">Reservar clases dirigidas</h2>
  <p class="text-muted">
    Selecciona la clase que quieras reservar. Verás aforo, inscritos y estado.
  </p>

  <?php if ($err): ?>
    <div class="alert alert-danger"><?= htmlspecialchars($err) ?></div>
  <?php endif; ?>

  <?php if ($info): ?>
    <div class="alert alert-success"><?= htmlspecialchars($info) ?></div>
  <?php endif; ?>

  <div class="card shadow-sm">
    <div class="card-body" style="max-height: 480px; overflow-y:auto;">

      <?php if (empty($clases)): ?>
        <p class="mb-0">No hay clases disponibles.</p>
      <?php else: ?>
        <div class="row g-2 mb-3">
          <div class="col-md-6">
            <input id="filtroTexto" class="form-control" placeholder="Buscar por clase o entrenador...">
          </div>
          <div class="col-md-3">
            <select id="filtroEstado" class="form-select">
              <option value="">Todos</option>
              <option value="disponible">Disponible</option>
              <option value="apuntado">Apuntado</option>
              <option value="completa">Completa</option>
            </select>
          </div>
          <div class="col-md-3">
            <button id="limpiarFiltros" class="btn btn-outline-secondary w-100">Limpiar</button>
          </div>
        </div>

        <ul class="list-group list-group-flush">
          <?php foreach ($clases as $c): ?>
            <?php
              $plazasLibres = (int)$c['capacidad'] - (int)$c['inscritos'];
              $completa     = $plazasLibres <= 0;
              $yaInscrito   = (int)$c['ya_inscrito'] === 1;
              $esCliente    = (($_SESSION['usuario_rol'] ?? '') === 'cliente');
            ?>

            <li class="list-group-item"
                data-nombre="<?= htmlspecialchars($c['nombreClase']) ?>"
                data-entrenador="<?= htmlspecialchars($c['nombreEntrenador']) ?>"
                data-estado="<?=
                  $esCliente
                    ? ($yaInscrito ? 'apuntado' : ($completa ? 'completa' : 'disponible'))
                    : 'admin'
                ?>">

              <div class="d-flex justify-content-between align-items-center">
                <div>
                  <strong><?= htmlspecialchars($c['nombreClase']) ?></strong><br>
                  <span class="text-muted" style="font-size:0.9rem;">
                    Duración: <?= (int)$c['duracion'] ?> min<br>
                    Entrenador: <?= htmlspecialchars($c['nombreEntrenador']) ?><br>
                    Aforo: <?= (int)$c['capacidad'] ?> · Inscritos: <?= (int)$c['inscritos'] ?><br>
                    Plazas libres: <?= max(0, $plazasLibres) ?>
                  </span>
                </div>

                <div class="ms-2 text-end">

                  <?php if (!$esCliente): ?>
                    <span class="badge bg-secondary" style="font-size:0.75rem;">Vista admin</span>

                  <?php else: ?>

                    <?php if ($yaInscrito): ?>
                      <span class="badge bg-success" style="font-size:0.75rem;">Apuntado</span>

                      <form action="cancelar_reserva.php" method="post" style="display:inline;">
                        <input type="hidden" name="idClase" value="<?= (int)$c['idClase'] ?>">
                        <button type="submit"
                                class="btn btn-sm btn-outline-danger ms-1"
                                style="font-size:0.8rem;">
                          Cancelar
                        </button>
                      </form>

                    <?php elseif ($completa): ?>
                      <span class="badge bg-secondary" style="font-size:0.75rem;">Completa</span>

                    <?php else: ?>
                      <form action="reservar_clase.php" method="post" style="display:inline;">
                        <input type="hidden" name="idClase" value="<?= (int)$c['idClase'] ?>">
                        <button type="submit"
                                class="btn btn-sm btn-primary"
                                style="font-size:0.8rem;">
                          Reservar
                        </button>
                      </form>
                    <?php endif; ?>

                  <?php endif; ?>

                </div>
              </div>
            </li>

          <?php endforeach; ?>
        </ul>
      <?php endif; ?>

      <div class="mt-3 text-center" style="font-size:0.9rem;">
        <a href="autorizacion.php">Volver</a>
      </div>

    </div>
  </div>
</div>

<footer class="bg-dark text-light mt-5 py-4">
  <div class="container text-center">
    <img src="img/logo.jpg" class="footer-logo mb-2" alt="Logo" style="width:50px;height:auto;">
    <p class="mb-1">© 2025 Centro de Musculación y Acondicionamiento Físico | Todos los derechos reservados</p>
    <div class="d-flex justify-content-center gap-3">
      <a href="#" class="text-light"><i class="bi bi-facebook"></i></a>
      <a href="#" class="text-light"><i class="bi bi-instagram"></i></a>
      <a href="#" class="text-light"><i class="bi bi-twitter"></i></a>
    </div>
  </div>
</footer>

<script src="js/reservas.js"></script>

<?php require_once 'includes/footer.php'; ?>
