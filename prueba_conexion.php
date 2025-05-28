<?php
// Recuperar variables de entorno
$dbHost = getenv('DB_HOST');         // e.g. mysql-cefire01.mysql.database.azure.com
$dbName = getenv('DB_NAME');         // e.g. mi_basedatos
$dbUser = getenv('DB_USER');         // e.g. adminuser@mysql-cefire01
$dbPass = getenv('DB_PASS');         // tu contraseña

if (!$dbHost || !$dbName || !$dbUser || $dbPass === false) {
    throw new \RuntimeException('Faltan variables de entorno para la conexión a la base de datos.');
}

//Establishes the connection
$conn = mysqli_init();
mysqli_real_connect($conn, $dbHost, $dbUser, $dbPass, $dbName, 3306);
if (mysqli_connect_errno($conn)) {f
    die('Failed to connect to MySQL: '.mysqli_connect_error());
}
