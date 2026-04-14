<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    file_put_contents("content.html", $_POST['content']);
}
?>