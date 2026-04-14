<?php

header("Content-Type: text/plain");

if (!isset($_FILES["image"])) {
    http_response_code(400);
    exit("No file received");
}

$uploadDir = __DIR__ . "/uploads/";

if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0777, true);
}

$fileName = time() . "_" . basename($_FILES["image"]["name"]);
$targetFile = $uploadDir . $fileName;

if (move_uploaded_file($_FILES["image"]["tmp_name"], $targetFile)) {
    // IMPORTANT: return RELATIVE URL for browser
    echo "uploads/" . $fileName;
} else {
    http_response_code(500);
    echo "Upload failed";
}