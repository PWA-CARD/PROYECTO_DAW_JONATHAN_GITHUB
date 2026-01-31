<?php
// conexion.php
// Configuración de acceso a la BD
const DB_HOST = 'localhost';
const DB_NAME = 'gimnasio_proyecto';
const DB_USER = 'root';
const DB_PASS = ''; // pon aquí tu contraseña si la tienes

function obtenerConexion(): PDO
{
    $dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4';

    try {
        $pdo = new PDO($dsn, DB_USER, DB_PASS, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]);
        return $pdo;
    } catch (PDOException $e) {
        // En producción no se muestra el mensaje real, pero para el proyecto está bien así
        die('Error de conexión a la base de datos: ' . $e->getMessage());
    }
}
