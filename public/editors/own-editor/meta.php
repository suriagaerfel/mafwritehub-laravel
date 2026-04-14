<?php
$data = [
 "title" => $_POST["title"],
 "author" => $_POST["author"],
 "tags" => $_POST["tags"]
];

file_put_contents("meta.json", json_encode($data));