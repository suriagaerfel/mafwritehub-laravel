<?php
$content = file_exists("content.html") ? file_get_contents("content.html") : "";
$meta = file_exists("meta.json") ? json_decode(file_get_contents("meta.json"), true) : [];
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Offline Editor</title>

<link rel="stylesheet" href="style.css">
</head>

<body>

<div class="toolbar">

    <button onclick="format('bold')">🅱️</button>
    <button onclick="format('italic')">𝘐</button>
    <button onclick="format('underline')">U̲</button>

    <button onclick="openActionModal('image')">🖼️</button>
    <button onclick="openActionModal('video')">🎬</button>
    <button onclick="openActionModal('table')">📊</button>

    <button onclick="openModal()">📝</button>

    <select onchange="setBlock(this.value)">
        <option value="">Format</option>
        <option value="p">Paragraph</option>
        <option value="h2">H2</option>
        <option value="h3">H3</option>
        <option value="h4">H4</option>
        <option value="h5">H5</option>
        <option value="h6">H6</option>
    </select>

    <input type="color" onchange="setColor(this.value)">

    <label class="icon-btn">📁
        <input type="file" onchange="uploadImage(this)" hidden>
    </label>

    <button onclick="toggleDark()">🌙</button>
    <button onclick="toggleCode()">💻</button>

</div>

<div id="editor" contenteditable="true"><?= $content ?></div>
<textarea id="codeArea"></textarea>

<div class="status" id="status">Auto-save enabled</div>

<?php include "modals.html"; ?>

<script src="script.js"></script>

</body>
</html>