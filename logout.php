<?php
// logout.php
session_start();

// Elimina todas las variables de sesión
$_SESSION = [];

// Destruye la sesión
session_destroy();

// Redirige al login con mensaje
header("Location: index.php?info=logout");
exit;
