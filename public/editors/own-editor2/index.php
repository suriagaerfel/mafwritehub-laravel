<?php
$content = file_exists("content.html") ? file_get_contents("content.html") : "";
$meta = file_exists("meta.json") ? json_decode(file_get_contents("meta.json"), true) : [];
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Advanced Offline Editor</title>

<style>
/* =========================
   BASE
========================= */

html, body {
    margin: 0;
    padding: 0;
    width: 100%;
    overflow-x: hidden;
    font-family: Arial, sans-serif;
    background: #f5f5f5;
}

.dark {
    background: #1e1e1e;
    color: #ddd;
}

#editor iframe,
#editor video {
    /* width: 100%;
    max-width: 100%;
    height: auto; */
    width: 320px;
    /* max-width: 100%; */
    /* height: 350px; */
    display: block;
}

.video-wrapper {
    position: relative;
    width: 100%;
    padding-bottom: 56.25%; /* 16:9 ratio */
    height: 0;
}

.video-wrapper iframe {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
}

/* =========================
   TOOLBAR
========================= */

.toolbar {
    display: flex;
    flex-wrap: wrap;
    gap: 8px; /* equal spacing */
    padding: 10px;
    background: #ddd;
    align-items: center;
}

.dark .toolbar {
    background: #333;
}

/* Buttons, selects, inputs */
.toolbar button,
.toolbar select,
.toolbar input {
    flex: 0 0 auto;
    width: fit-content;
    min-width: 40px;
    padding: 6px 10px;
    font-size: 14px;
    border: 1px solid #ccc;
    border-radius: 4px;
    background: #fff;
    cursor: pointer;
    text-align: center;
}

.dark .toolbar button,
.dark .toolbar select,
.dark .toolbar input {
    background: #444;
    color: #fff;
    border: 1px solid #666;
}

/* =========================
   FILE UPLOAD ICON BUTTON
========================= */

.icon-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    flex: 0 0 auto;
    width: fit-content;
    min-width: 40px;
    padding: 6px 10px;
    font-size: 16px;
    border: 1px solid #ccc;
    border-radius: 4px;
    background: #fff;
    cursor: pointer;
}

.dark .icon-btn {
    background: #444;
    color: #fff;
    border: 1px solid #666;
}

/* =========================
   EDITOR AREA
========================= */

#editor, #codeArea {
    width: 100%;
    max-width: 100%;
    box-sizing: border-box;
    padding: 15px;
    min-height: 300px;
    font-size: 16px;
    border-top: 1px solid #ccc;
    outline: none;
}

.dark #editor {
    background: #1e1e1e;
    color: #ddd;
    border-color: #444;
}

#codeArea {
    display: none;
    font-family: monospace;
}

/* =========================
   TABLE STYLE
========================= */

#editor table {
    width: 100%;
    border-collapse: collapse;
}

#editor td, #editor th {
    border: 1px solid #999;
    padding: 6px;
}

/* =========================
   STATUS BAR
========================= */

.status {
    padding: 6px 10px;
    font-size: 12px;
    background: #eee;
}

.dark .status {
    background: #2a2a2a;
}

/* =========================
   MODAL
========================= */

#modal {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0,0,0,0.6);
}

#modal div {
    background: #fff;
    padding: 20px;
    width: 90%;
    max-width: 400px;
    margin: 60px auto;
    border-radius: 6px;
}

.dark #modal div {
    background: #2a2a2a;
    color: #fff;
}

/* =========================
   MOBILE (FIXED + CLEAN)
========================= */

@media (max-width: 600px) {

    .toolbar {
        display: flex;
        flex-wrap: wrap;
        gap: 8px; /* equal spacing */
        justify-content: flex-start;
    }

    .toolbar button,
    .toolbar select,
    .toolbar input,
    .icon-btn {
        flex: 0 0 auto;
        width: fit-content;
        min-width: 40px;
        padding: 8px;
        font-size: 12px;
    }

    #editor, #codeArea {
        min-height: 220px;
        font-size: 14px;
        padding: 10px;
    }

    #modal div {
        margin: 40px auto;
    }
}

/* =========================
   TABLET
========================= */

@media (min-width: 601px) and (max-width: 1024px) {
    #editor {
        font-size: 15px;
    }
}

/* =========================
   DESKTOP
========================= */

@media (min-width: 1025px) {
    #editor {
        width: 100%;
    }
}
</style>

</head>

<div id="actionModal" style="display:none; position:fixed; top:0; left:0;
width:100%; height:100%; background:rgba(0,0,0,0.6);">

    <div style="background:#fff; width:90%; max-width:420px; margin:80px auto;
    padding:20px; border-radius:8px;">

        <h3 id="modalTitle">Action</h3>

        <div id="modalBody"></div>

        <br>

        <button onclick="runAction()">Insert</button>
        <button onclick="closeActionModal()">Cancel</button>
    </div>
</div>
<body>

<div class="toolbar">
    <button onclick="format('bold')">🅱️</button>
    <button onclick="format('italic')">𝘐</button>
    <button onclick="format('underline')">U̲</button>
    <button onclick="insertImageURL()">🖼️</button>
    <button onclick="insertVideo()">🎬</button>
    <button onclick="insertTable()">📊</button>
    <button onclick="openModal()">📝</button>

    <select onchange="setBlock(this.value)">
        <option value="">Format</option>
        <option value="p">Paragraph</option>
        <option value="h2">Heading 2</option>
        <option value="h3">Heading 3</option>
        <option value="h4">Heading 4</option>
        <option value="h5">Heading 5</option>
        <option value="h6">Heading 6</option>
    </select>

    <input type="color" onchange="setColor(this.value)">

    <label class="icon-btn">
    📁
        <input type="file" onchange="uploadImage(this)" hidden>
    </label>

    <button onclick="toggleDark()">🌙</button>
    <button onclick="toggleCode()">💻</button>
</div>

<div id="editor" contenteditable="true"><?= $content ?></div>
<textarea id="codeArea"></textarea>

<div id="modal" style="display:none; position:fixed; top:0; left:0; 
width:100%; height:100%; background:rgba(0,0,0,0.5);">

    <div style="background:#fff; padding:20px; width:300px; margin:100px auto;">
        <h3>Article Info</h3>

        <input id="title" value="<?= $meta['title'] ?? '' ?>" placeholder="Title">
        <input id="author" value="<?= $meta['author'] ?? '' ?>" placeholder="Author">
        <input id="tags" value="<?= $meta['tags'] ?? '' ?>" placeholder="Tags">

        <button onclick="saveMeta()">Save</button>
        <button onclick="closeModal()">Close</button>
    </div>
</div>

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
// setInterval(() => {
//     let content = isCode 
//         ? document.getElementById("codeArea").value
//         : document.getElementById("editor").innerHTML;

//     fetch("autosave.php", {
//         method: "POST",
//         headers: {"Content-Type": "application/x-www-form-urlencoded"},
//         // body: "content=" + encodeURIComponent(content)
//         body: new URLSearchParams({
//     content: content
// })
//     })
//     .then(() => {
//         document.getElementById("status").innerText = "Auto-saved " + new Date().toLocaleTimeString();
//     });
// }, 3000);

setInterval(() => {
    let content = isCode
        ? document.getElementById("codeArea").value
        : document.getElementById("editor").innerHTML;

    fetch("autosave.php", {
        method: "POST",
        body: new URLSearchParams({ content })
    })
    .then(res => res.text())
    .then(res => {
        console.log("autosave:", res);
        document.getElementById("status").innerText =
            "Saved " + new Date().toLocaleTimeString();
    });

}, 3000);

// Insert image via URL
function insertImageURL() {
    let url = prompt("Enter image URL:");
    if (url) {
        document.execCommand("insertImage", false, url);
    }
}

// Insert video
function insertVideo() {
    let url = prompt("Enter video URL (YouTube or file):");
    if (!url) return;

    let embed = "";

    if (url.includes("youtube.com") || url.includes("youtu.be")) {
        let id = url.split("v=")[1] || url.split("/").pop();
        embed = `<iframe width="400" height="250"
            src="https://www.youtube.com/embed/${id}"
            frameborder="0" allowfullscreen></iframe>`;
    } else {
        embed = `<video controls width="400">
                    <source src="${url}">
                 </video>`;
    }

    document.execCommand("insertHTML", false, embed);
}

// Insert table
function insertTable() {
    let rows = prompt("Rows?");
    let cols = prompt("Columns?");

    if (!rows || !cols) return;

    let table = "<table border='1' style='border-collapse:collapse; width:100%'>";

    for (let i = 0; i < rows; i++) {
        table += "<tr>";
        for (let j = 0; j < cols; j++) {
            table += "<td style='padding:5px'>Cell</td>";
        }
        table += "</tr>";
    }

    table += "</table>";

    document.execCommand("insertHTML", false, table);
}

// Modal controls
function openModal() {
    document.getElementById("modal").style.display = "block";
}

function closeModal() {
    document.getElementById("modal").style.display = "none";
}

// Save metadata
function saveMeta() {
    let title = document.getElementById("title").value;
    let author = document.getElementById("author").value;
    let tags = document.getElementById("tags").value;

    fetch("meta.php", {
        method: "POST",
        headers: {"Content-Type": "application/x-www-form-urlencoded"},
        body: `title=${encodeURIComponent(title)}&author=${encodeURIComponent(author)}&tags=${encodeURIComponent(tags)}`
    });

    closeModal();
}

function setBlock(tag) {
    if (!tag) return;

    document.execCommand("formatBlock", false, tag);
}
// let isCode = false;
// let currentAction = "";

// /* =========================
//    BASIC EDITING
// ========================= */

// function format(cmd) {
//     document.execCommand(cmd, false, null);
// }

// function setColor(color) {
//     document.execCommand("foreColor", false, color);
// }

// function setBlock(tag) {
//     if (!tag) return;
//     document.execCommand("formatBlock", false, tag);
// }

// /* =========================
//    DARK MODE
// ========================= */

// function toggleDark() {
//     document.body.classList.toggle("dark");
// }

// /* =========================
//    CODE MODE
// ========================= */

// function toggleCode() {
//     let editor = document.getElementById("editor");
//     let code = document.getElementById("codeArea");

//     if (!isCode) {
//         code.value = editor.innerHTML;
//         editor.style.display = "none";
//         code.style.display = "block";
//     } else {
//         editor.innerHTML = code.value;
//         editor.style.display = "block";
//         code.style.display = "none";
//     }

//     isCode = !isCode;
// }

// /* =========================
//    AUTO SAVE
// ========================= */

// setInterval(() => {
//     let content = isCode
//         ? document.getElementById("codeArea").value
//         : document.getElementById("editor").innerHTML;

//     fetch("autosave.php", {
//         method: "POST",
//         headers: {"Content-Type": "application/x-www-form-urlencoded"},
//         body: "content=" + encodeURIComponent(content)
//     });

// }, 3000);

// /* =========================
//    MODAL SYSTEM
// ========================= */

// function openActionModal(type) {
//     currentAction = type;

//     let body = document.getElementById("modalBody");
//     let title = document.getElementById("modalTitle");

//     body.innerHTML = "";

//     if (type === "image") {
//         title.innerText = "Insert Image";
//         body.innerHTML = `
//             <input id="imgUrl" placeholder="Image URL"
//             style="width:100%; padding:8px;">
//         `;
//     }

//     if (type === "video") {
//         title.innerText = "Insert Video";
//         body.innerHTML = `
//             <input id="videoUrl" placeholder="YouTube or Video URL"
//             style="width:100%; padding:8px;">
//         `;
//     }

//     if (type === "table") {
//         title.innerText = "Insert Table";
//         body.innerHTML = `
//             <input id="rows" type="number" placeholder="Rows"
//             style="width:48%; padding:8px;">
//             <input id="cols" type="number" placeholder="Columns"
//             style="width:48%; padding:8px;">
//         `;
//     }

//     document.getElementById("actionModal").style.display = "block";
// }

// function closeActionModal() {
//     document.getElementById("actionModal").style.display = "none";
// }

// function runAction() {

//     let editor = document.getElementById("editor");

//     /* IMAGE */
//     if (currentAction === "image") {
//         let url = document.getElementById("imgUrl").value;
//         if (url) {
//             document.execCommand("insertImage", false, url);
//         }
//     }

//     /* VIDEO */
//     if (currentAction === "video") {
//         let url = document.getElementById("videoUrl").value;
//         if (!url) return;

//         let embed = "";

//         if (url.includes("youtube.com") || url.includes("youtu.be")) {
//             let id = url.split("v=")[1] || url.split("/").pop();

//             embed = `
//             <div class="video-wrapper">
//                 <iframe
//                     src="https://www.youtube.com/embed/${id}"
//                     frameborder="0"
//                     allowfullscreen>
//                 </iframe>
//             </div>`;
//         } else {
//             embed = `
//             <video controls width="100%">
//                 <source src="${url}">
//             </video>`;
//         }

//         document.execCommand("insertHTML", false, embed);
//     }

//     /* TABLE */
//     if (currentAction === "table") {
//         let rows = document.getElementById("rows").value;
//         let cols = document.getElementById("cols").value;

//         if (!rows || !cols) return;

//         let table = "<table border='1' style='border-collapse:collapse;width:100%'>";

//         for (let i = 0; i < rows; i++) {
//             table += "<tr>";
//             for (let j = 0; j < cols; j++) {
//                 table += "<td style='padding:5px'>Cell</td>";
//             }
//             table += "</tr>";
//         }

//         table += "</table>";

//         document.execCommand("insertHTML", false, table);
//     }

//     closeActionModal();
// }

// /* =========================
//    IMAGE UPLOAD (PHP)
// ========================= */

// function uploadImage(input) {
//     let file = input.files[0];
//     if (!file) return;

//     let formData = new FormData();
//     formData.append("image", file);

//     fetch("upload.php", {
//         method: "POST",
//         body: formData
//     })
//     .then(res => res.text())
//     .then(url => {
//         if (url.includes("uploads/")) {
//             document.execCommand("insertImage", false, url);
//         } else {
//             alert("Upload failed: " + url);
//         }
//     });
// }

// /* =========================
//    INSERT HELPERS
// ========================= */

// function insertImageURL() {
//     openActionModal("image");
// }

// function insertVideo() {
//     openActionModal("video");
// }

// function insertTable() {
//     openActionModal("table");
// }

// /* =========================
//    MODAL META (ARTICLE INFO)
// ========================= */

// function openModal() {
//     document.getElementById("modal").style.display = "block";
// }

// function closeModal() {
//     document.getElementById("modal").style.display = "none";
// }

// function saveMeta() {
//     let title = document.getElementById("title").value;
//     let author = document.getElementById("author").value;
//     let tags = document.getElementById("tags").value;

//     fetch("meta.php", {
//         method: "POST",
//         headers: {"Content-Type": "application/x-www-form-urlencoded"},
//         body: `title=${encodeURIComponent(title)}&author=${encodeURIComponent(author)}&tags=${encodeURIComponent(tags)}`
//     });

//     closeModal();
// }
</script>

</body>
</html>