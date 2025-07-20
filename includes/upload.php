<?php
require_once 'config.php';

// Handle file upload
function handleFileUpload($file)
{
    if ($file['error'] !== UPLOAD_ERR_OK) {
        return null;
    }

    $targetDir = UPLOAD_DIR;
    if (!file_exists($targetDir)) {
        mkdir($targetDir, 0777, true);
    }

    $fileName = uniqid() . '_' . basename($file["name"]);
    $targetFilePath = $targetDir . $fileName;

    if (move_uploaded_file($file["tmp_name"], $targetFilePath)) {
        return 'assets/uploads/' . $fileName;
    }
    return null;
}
