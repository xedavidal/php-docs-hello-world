<?php
// Recuperar variables de entorno
$dbHost = getenv('DB_HOST');
$dbName = "prueba";         
$dbUser = getenv('DB_USER');
$dbPass = getenv('DB_PASSWORD');

if (!$dbHost || !$dbName || !$dbUser || $dbPass === false) {
    throw new \RuntimeException('Faltan variables de entorno para la conexión a la base de datos.');
}

// DSN con charset utf8mb4
$dsn = "mysql:host={$dbHost};dbname={$dbName};charset=utf8mb4";

try {
    $options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,

        // Asegurar la conexión TLS:
        PDO::MYSQL_ATTR_SSL_CA        => '/home/site/certs/BaltimoreCyberTrustRoot.crt.pem',
        // Activamos la validación de certificado SSL
        PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT => true,
    ];

    $pdo = new PDO($dsn, $dbUser, $dbPass, $options);
    echo "Conexión SSL establecida correctamente contra la bbdd.";
} catch (PDOException $e) {
    error_log('Error PDO SSL: ' . $e->getMessage());
    echo "Error de conexión SSL: " . htmlspecialchars($e->getMessage());
}
