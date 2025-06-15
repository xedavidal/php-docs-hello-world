<?php
require 'vendor/autoload.php';

use MicrosoftAzure\Storage\File\FileRestProxy;
use MicrosoftAzure\Storage\Common\Exceptions\ServiceException;
use MicrosoftAzure\Storage\File\Models\ListDirectoriesAndFilesOptions;
use MicrosoftAzure\Storage\File\Models\DeleteFileOptions;

// Configuración
$connectionString = getenv("AZURE_STORAGE_CONNECTION_STRING");
$shareName = "comprimidos";       // <--- Cambia esto por tu Azure File Share
$directoryName = "";              // "" para trabajar en la raíz

$fileClient = FileRestProxy::createFileService($connectionString);

// 🔽 Eliminar archivo si se solicita
if (isset($_GET["delete"])) {
    $fileToDelete = $_GET["delete"];
    try {
        $fileClient->deleteFile($shareName, $directoryName, $fileToDelete);
        echo "<p style='color:green;'>Archivo $fileToDelete eliminado correctamente.</p>";
    } catch (ServiceException $e) {
        echo "<p style='color:red;'>Error al eliminar: " . $e->getMessage() . "</p>";
    }
}

// ⬆️ Subida de archivo ZIP
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_FILES["zipfile"])) {
    $uploadedFile = $_FILES["zipfile"];
    if ($uploadedFile["type"] !== "application/zip") {
        echo "<p style='color:red;'>Solo se permiten archivos ZIP.</p>";
    } else {
        $fileName = basename($uploadedFile["name"]);
        $fileContent = file_get_contents($uploadedFile["tmp_name"]);
        $fileSize = filesize($uploadedFile["tmp_name"]);

        try {
            $fileClient->createFile($shareName, $directoryName, $fileName, $fileSize);
            $fileClient->createFileRangeFromContent($shareName, $directoryName, $fileName, $fileContent, 0, $fileSize - 1);
            echo "<p style='color:green;'>Archivo $fileName subido correctamente.</p>";
        } catch (ServiceException $e) {
            echo "<p style='color:red;'>Error al subir: " . $e->getMessage() . "</p>";
        }
    }
}

// 📂 Listado de archivos
try {
    $options = new ListDirectoriesAndFilesOptions();
    $result = $fileClient->listDirectoriesAndFiles($shareName, $directoryName, $options);
    $files = $result->getFiles();
} catch (ServiceException $e) {
    die("Error al listar archivos: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Gestión de ZIPs en Azure Files</title>
</head>
<body>
    <h1>Archivos en Azure Files</h1>
    <ul>
        <?php foreach ($files as $file): ?>
            <li>
                <?= htmlspecialchars($file->getName()) ?>
                [<a href="?delete=<?= urlencode($file->getName()) ?>" onclick="return confirm('¿Eliminar este archivo?')">Eliminar</a>]
            </li>
        <?php endforeach; ?>
    </ul>

    <h2>Subir archivo ZIP</h2>
    <form method="POST" enctype="multipart/form-data">
        <input type="file" name="zipfile" accept=".zip" required>
        <button type="submit">Subir</button>
    </form>
</body>
</html>
