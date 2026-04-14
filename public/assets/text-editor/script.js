let isCode = false;
let currentAction = "";

/* =========================
   BASIC EDITING
========================= */

// function format(cmd) {
//     document.execCommand(cmd, false, null);
// }

function format(cmd) {
    let editor = document.getElementById("editor");
    editor.focus(); // 🔥 FIX

    document.execCommand(cmd, false, null);
}

function setColor(color) {
    document.execCommand("foreColor", false, color);
}

function setBlock(tag) {
    if (!tag) return;
    document.execCommand("formatBlock", false, tag);
}

function insertList(type) {
    let editor = document.getElementById("editor");
    editor.focus();

    // If nothing selected, insert placeholder
    let selection = window.getSelection();
    if (selection.toString().length === 0) {
        document.execCommand("insertHTML", false, "<p>List item</p>");
    }

    document.execCommand(type, false, null);
}

/* =========================
   DARK MODE
========================= */

function toggleDark() {
    document.body.classList.toggle("dark");
}

/* =========================
   CODE MODE
========================= */

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

/* =========================
   AUTO SAVE
========================= */

// setInterval(() => {
//     let content = isCode
//         ? document.getElementById("codeArea").value
//         : document.getElementById("editor").innerHTML;

//     fetch("autosave.php", {
//         method: "POST",
//         headers: { "Content-Type": "application/x-www-form-urlencoded" },
//         body: "content=" + encodeURIComponent(content),
//     });
// }, 3000);

/* =========================
   MODAL SYSTEM
========================= */

function openActionModal(type) {
    currentAction = type;

    let body = document.getElementById("modalBody");
    let title = document.getElementById("modalTitle");

    body.innerHTML = "";

    if (type === "image") {
        title.innerText = "Insert Image";
        body.innerHTML = `
            <input id="imgUrl" placeholder="Image URL"
            style="width:100%; padding:8px;">
        `;
    }

    if (type === "video") {
        title.innerText = "Insert Video";
        body.innerHTML = `
            <input id="videoUrl" placeholder="YouTube or Video URL"
            style="width:100%; padding:8px;">
        `;
    }

    if (type === "table") {
        title.innerText = "Insert Table";
        body.innerHTML = `
            <input id="rows" type="number" placeholder="Rows"
            style="width:48%; padding:8px;">
            <input id="cols" type="number" placeholder="Columns"
            style="width:48%; padding:8px;">
        `;
    }

    document.getElementById("actionModal").style.display = "block";
}

function closeActionModal() {
    document.getElementById("actionModal").style.display = "none";
}

function runAction() {
    let editor = document.getElementById("editor");

    /* IMAGE */
    if (currentAction === "image") {
        let url = document.getElementById("imgUrl").value;
        if (url) {
            document.execCommand("insertImage", false, url);
        }
    }

    /* VIDEO */
    if (currentAction === "video") {
        let url = document.getElementById("videoUrl").value;
        if (!url) return;

        let embed = "";

        if (url.includes("youtube.com") || url.includes("youtu.be")) {
            let id = url.split("v=")[1] || url.split("/").pop();

            embed = `
            <div class="video-wrapper">
                <iframe
                    src="https://www.youtube.com/embed/${id}"
                    frameborder="0"
                    allowfullscreen>
                </iframe>
            </div>`;
        } else {
            embed = `
            <video controls width="100%">
                <source src="${url}">
            </video>`;
        }

        document.execCommand("insertHTML", false, embed);
    }

    /* TABLE */
    if (currentAction === "table") {
        let rows = document.getElementById("rows").value;
        let cols = document.getElementById("cols").value;

        if (!rows || !cols) return;

        let table =
            "<table border='1' style='border-collapse:collapse;width:100%'>";

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

    closeActionModal();
}

/* =========================
   IMAGE UPLOAD (PHP)
========================= */

function uploadImage(input) {
    let file = input.files[0];
    if (!file) return;

    let formData = new FormData();
    formData.append("image", file);

    fetch("upload.php", {
        method: "POST",
        body: formData,
    })
        .then((res) => res.text())
        .then((url) => {
            if (url.includes("uploads/")) {
                document.execCommand("insertImage", false, url);
            } else {
                alert("Upload failed: " + url);
            }
        });
}

/* =========================
   INSERT HELPERS
========================= */

function insertImageURL() {
    openActionModal("image");
}

function insertVideo() {
    openActionModal("video");
}

function insertTable() {
    openActionModal("table");
}

/* =========================
   MODAL META (ARTICLE INFO)
========================= */

function openModal() {
    document.getElementById("modal").style.display = "block";
}

function closeModal() {
    document.getElementById("modal").style.display = "none";
}

function saveMeta() {
    let title = document.getElementById("title").value;
    let author = document.getElementById("author").value;
    let tags = document.getElementById("tags").value;

    fetch("meta.php", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: `title=${encodeURIComponent(title)}&author=${encodeURIComponent(author)}&tags=${encodeURIComponent(tags)}`,
    });

    closeModal();
}
