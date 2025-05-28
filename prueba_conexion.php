<?php
// Recuperar variables de entorno
$dbHost = getenv('DB_HOST');         // e.g. mysql-cefire01.mysql.database.azure.com
$dbName = getenv('DB_NAME');         // e.g. mi_basedatos
$dbUser = getenv('DB_USER');         // e.g. adminuser@mysql-cefire01
$dbPass = getenv('DB_PASS');         // tu contraseña

if (!$dbHost || !$dbName || !$dbUser || $dbPass === false) {
    throw new \RuntimeException('Faltan variables de entorno para la conexión a la base de datos.');
}

// DSN con charset utf8mb4
$dsn = "mysql:host={$dbHost};dbname={$dbName};charset=utf8mb4";

try {
    $options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,   // Excepciones en errores
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,         // Fetch como array asociativo
        PDO::ATTR_EMULATE_PREPARES   => false,                    // Desactivar emulación de prepares

        // Asegurar la conexión TLS hacia Azure Database for MySQL
        PDO::MYSQL_ATTR_SSL_CA        => '/etc/ssl/certs/BaltimoreCyberTrustRoot.crt.pem',
        PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT => true,
    ];

    // Crear la conexión PDO
    $pdo = new PDO($dsn, $dbUser, $dbPass, $options);

    // Ejemplo: consulta sencilla
    $stmt = $pdo->query('SELECT NOW() AS fecha_actual;');
    $fila = $stmt->fetch();
    echo "Conectado correctamente. Hora del servidor: " . $fila['fecha_actual'];
} catch (PDOException $e) {
    echo "Error de conexión PDO: " . $e->getMessage());
    echo "Error al conectar con la base de datos: " . htmlspecialchars($e->getMessage());
    exit;
}
