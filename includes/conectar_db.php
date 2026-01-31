<?php
function conectar() {
    $host = "localhost";
    $dbname = "tienda";
    $usuario = "root";
    $clave = "";

    try {
        $dsn = "mysql:host=$host;dbname=$dbname;charset=utf8";
        $pdo = new PDO($dsn, $usuario, $clave);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo;
    }catch (PDOException $e) {
        die("Error de conexión a la base de datos: " . $e->getMessage());
    }
}