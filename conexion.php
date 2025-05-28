<?php
// Recuperar variables de entorno
$dbHost = getenv('DB_HOST');
$dbName = "prueba";         
$dbUser = getenv('DB_USER');
$dbPass = getenv('DB_PASS');

echo $dbHost . " " . $dbName . " " . $dbUser . " " . $dbPass;

