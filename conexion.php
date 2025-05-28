<?php
// Recuperar variables de entorno
$dbHost = getenv('DB_HOST');         // e.g. mysql-cefire01.mysql.database.azure.com
$dbName = getenv('DB_NAME');         // e.g. mi_basedatos
$dbUser = getenv('DB_USER');         // e.g. adminuser@mysql-cefire01
$dbPass = getenv('DB_PASS');         // tu contraseña

echo $dbHost . " " . $dbName . " " . $dbUser . " " . $dbPass;

