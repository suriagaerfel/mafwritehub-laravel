<?php
if (!isset($_FILES["image"])) exit;

$dir = __DIR__ . "/uploads/";
if (!is_dir($dir)) mkdir($dir, 0777, true);

$name = time() . "_" . $_FILES["image"]["name"];
$path = $dir . $name;

move_uploaded_file($_FILES["image"]["tmp_name"], $path);

echo "uploads/" . $name;