<?php
// if ($_SERVER["REQUEST_METHOD"] == "POST") {
//     file_put_contents("content.html", $_POST['content']);
// }
$data = $_POST['content'] ?? '';

if ($data !== '') {
    file_put_contents("content.html", $data);
    echo "OK";
} else {
    echo "EMPTY";
}


?>

