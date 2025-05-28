<?php

$dbHost = getenv('DB_HOST');
$dbName = "prueba";
$dbUser = getenv('DB_USER');
$dbPass = getenv('DB_PASSWORD');

if (!$dbHost || !$dbUser || $dbPass === false) {
    throw new \RuntimeException('Faltan variables de entorno para la conexión a la base de datos.');
}

$dsn = "mysql:host={$dbHost};dbname={$dbName};charset=utf8mb4";

try {
    // Opciones recomendadas para PDO
    $options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,   // Excepciones en errores
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,         // Fetch como array asociativo
        PDO::ATTR_EMULATE_PREPARES   => false,                    // Desactivar emulación de prepares
    ];

    // Crear la conexión PDO
    $pdo = new PDO($dsn, $dbUser, $dbPass, $options);
    echo "Conexión exitosa a la base de datos '{$dbName}' en '{$dbHost}'.";
} catch (PDOException $e) {
    // Manejo de error de conexión
    error_log('Error de conexión PDO: ' . $e->getMessage());
    echo "Error al conectar con la base de datos.";
    exit;
}
