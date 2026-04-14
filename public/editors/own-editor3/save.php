<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $content = $_POST['content'];

    // Save to file
    file_put_contents("content.html", $content);

    echo "Content saved successfully!<br><br>";
    echo "<a href='index.php'>Go back</a>";
}
?>