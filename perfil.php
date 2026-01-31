<?php
// perfil.php
session_start();

if (!isset($_SESSION['usuario_id'])) {
    header("Location: index.php?error=Debes iniciar sesión.");
    exit;
}

require_once 'conexion.php';
require_once 'GestorUsuarios.php';

$con = obtenerConexion();
$gestor = new GestorUsuarios($con);

$usuario = $gestor->obtenerPorEmail($_SESSION['usuario_email']);

if (!$usuario) {
    header("Location: autorizacion.php?error=No se pudo cargar tu perfil.");
    exit;
}

$tituloPagina = 'Mi perfil';
require_once 'includes/header.php';

$err  = $_GET['error'] ?? '';
$info = $_GET['info'] ?? '';

$avatar = $usuario->getAvatar() ?: 'https://via.placeholder.com/80x80.png?text=User';
?>

<div class="phone-frame shadow-sm">

    <div class="top-text">Perfil</div>
    <div class="screen-title">Mi perfil</div>

    <!-- Avatar superior -->
    <div class="d-flex align-items-center mb-3">
        <div class="avatar">
            <img src="<?= htmlspecialchars($avatar) ?>" alt="Avatar">
        </div>
        <div>
            <h6 class="mb-1"><?= htmlspecialchars($usuario->getNombre()) ?></h6>
            <p class="user-subtitle mb-0">
                Rol: <?= htmlspecialchars($usuario->getRol()) ?><br>
                <?= htmlspecialchars($usuario->getEmail()) ?>
            </p>
        </div>
    </div>

    <?php if ($err): ?>
        <div class="alert alert-danger py-1 small"><?= htmlspecialchars($err) ?></div>
    <?php endif; ?>

    <?php if ($info): ?>
        <div class="alert alert-success py-1 small"><?= htmlspecialchars($info) ?></div>
    <?php endif; ?>

    <!-- Card datos básicos -->
    <div class="card form-card mt-2">
        <div class="card-body">
            <h6 class="mb-2">Datos personales</h6>

            <form action="perfil_procesar.php" method="post">
                <input type="hidden" name="id" value="<?= $usuario->getId() ?>">

                <div class="mb-3">
                    <label class="form-label mb-1">Nombre</label>
                    <input
                        type="text"
                        class="form-control form-control-sm"
                        name="nombre"
                        value="<?= htmlspecialchars($usuario->getNombre()) ?>"
                        required>
                </div>

                <div class="mb-3">
                    <label class="form-label mb-1">Email</label>
                    <input
                        type="email"
                        class="form-control form-control-sm"
                        name="email"
                        value="<?= htmlspecialchars($usuario->getEmail()) ?>"
                        required>
                </div>

                <div class="mb-3">
                    <label class="form-label mb-1">Rol</label>
                    <input
                        type="text"
                        class="form-control form-control-sm"
                        value="<?= htmlspecialchars($usuario->getRol()) ?>"
                        disabled>
                </div>

                <button type="submit" class="btn btn-register w-100 py-2">
                    Guardar datos
                </button>

                <div class="mt-3 text-center" style="font-size:0.8rem;">
                    <a href="autorizacion.php">Volver</a>
                </div>
            </form>
        </div>
    </div>

    <!-- Card cambio de contraseña -->
    <div class="card form-card mt-2">
        <div class="card-body">
            <h6 class="mb-2">Cambiar contraseña</h6>

            <form action="perfil_password.php" method="post">
                <input type="hidden" name="id" value="<?= $usuario->getId() ?>">

                <div class="mb-3">
                    <label class="form-label mb-1">Nueva contraseña</label>
                    <input
                        type="password"
                        class="form-control form-control-sm"
                        name="password1"
                        required>
                </div>

                <div class="mb-3">
                    <label class="form-label mb-1">Repite la contraseña</label>
                    <input
                        type="password"
                        class="form-control form-control-sm"
                        name="password2"
                        required>
                </div>

                <button type="submit" class="btn btn-register w-100 py-2">
                    Cambiar contraseña
                </button>
            </form>
        </div>
    </div>

    <!-- Card cambio de avatar -->
    <div class="card form-card mt-2">
        <div class="card-body">
            <h6 class="mb-2">Cambiar avatar</h6>

            <form action="perfil_avatar.php" method="post" enctype="multipart/form-data">
                <input type="hidden" name="id" value="<?= $usuario->getId() ?>">

                <div class="mb-3">
                    <label class="form-label mb-1">Selecciona una imagen (JPG/PNG)</label>
                    <input
                        type="file"
                        class="form-control form-control-sm"
                        name="avatar"
                        accept="image/*"
                        required>
                </div>

                <button type="submit" class="btn btn-register w-100 py-2">
                    Subir avatar
                </button>
            </form>
        </div>
    </div>

</div>

<?php require_once 'includes/footer.php'; ?>
