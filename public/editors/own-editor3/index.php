<?php
$content = file_exists("content.html") ? file_get_contents("content.html") : "";
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Advanced Offline Editor</title>

<style>
body {
    font-family: Arial;
    margin: 0;
    background: #f5f5f5;
    color: #000;
}

.dark {
    background: #1e1e1e;
    color: #ddd;
}

.toolbar {
    background: #ddd;
    padding: 10px;
    display: flex;
    flex-wrap: wrap;
    gap: 5px;
}

.dark .toolbar {
    background: #333;
}

.toolbar button, select, input[type=color] {
    padding: 5px;
    cursor: pointer;
}

#editor {
    padding: 15px;
    min-height: 300px;
    outline: none;
    border-top: 1px solid #ccc;
}

.dark #editor {
    background: #1e1e1e;
    color: #ddd;
}

#codeArea {
    display: none;
    width: 100%;
    height: 300px;
    font-family: monospace;
}

.status {
    padding: 5px 10px;
    font-size: 12px;
}
</style>

</head>
<body>

<div class="toolbar">
    <button onclick="format('bold')">🅱️</button>
    <button onclick="format('italic')">𝘐</button>
    <button onclick="format('underline')">U̲</button>

    <select onchange="setFontSize(this.value)">
        <option value="">Font Size</option>
        <option value="1">Small</option>
        <option value="3">Normal</option>
        <option value="5">Large</option>
        <option value="7">Huge</option>
    </select>

    <input type="color" onchange="setColor(this.value)">

    <input type="file" onchange="uploadImage(this)">

    <button onclick="toggleDark()">🌙</button>
    <button onclick="toggleCode()">💻</button>
</div>

<div id="editor" contenteditable="true"><?= $content ?></div>
<textarea id="codeArea"></textarea>

<div class="status" id="status">Auto-save enabled</div>

<script>
let isCode = false;

// Formatting
function format(cmd) {
    document.execCommand(cmd, false, null);
}

// Font size
function setFontSize(size) {
    document.execCommand("fontSize", false, size);
}

// Text color
function setColor(color) {
    document.execCommand("foreColor", false, color);
}

// Image upload
function uploadImage(input) {
    let file = input.files[0];
    let formData = new FormData();
    formData.append("image", file);

    fetch("upload.php", {
        method: "POST",
        body: formData
    })
    .then(res => res.text())
    .then(url => {
        document.execCommand("insertImage", false, url);
    });
}

// Dark mode
function toggleDark() {
    document.body.classList.toggle("dark");
}

// Code mode
function toggleCode() {
    let editor = document.getElementById("editor");
    let code = document.getElementById("codeArea");

    if (!isCode) {
        code.value = editor.innerHTML;
        editor.style.display = "none";
        code.style.display = "block";
    } else {
        editor.innerHTML = code.value;
        editor.style.display = "block";
        code.style.display = "none";
    }
    isCode = !isCode;
}

// Auto-save every 3 seconds
setInterval(() => {
    let content = isCode 
        ? document.getElementById("codeArea").value
        : document.getElementById("editor").innerHTML;

    fetch("autosave.php", {
        method: "POST",
        headers: {"Content-Type": "application/x-www-form-urlencoded"},
        body: "content=" + encodeURIComponent(content)
    })
    .then(() => {
        document.getElementById("status").innerText = "Auto-saved " + new Date().toLocaleTimeString();
    });
}, 3000);
</script>

</body>
</html>