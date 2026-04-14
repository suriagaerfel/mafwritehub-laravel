let isCode = false;
let currentAction = "";

/* FORMAT */
function format(cmd) {
    document.execCommand(cmd, false, null);
}

function setColor(color) {
    document.execCommand("foreColor", false, color);
}

function setBlock(tag) {
    if (tag) document.execCommand("formatBlock", false, tag);
}

/* DARK */
function toggleDark() {
    document.body.classList.toggle("dark");
}

/* CODE */
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

/* AUTO SAVE */
setInterval(() => {
    let content = isCode
        ? document.getElementById("codeArea").value
        : document.getElementById("editor").innerHTML;

    fetch("autosave.php", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: "content=" + encodeURIComponent(content),
    });
}, 3000);

/* MODAL ACTION */
function openActionModal(type) {
    currentAction = type;

    let body = document.getElementById("modalBody");
    let title = document.getElementById("modalTitle");

    body.innerHTML = "";

    if (type === "image") {
        title.innerText = "Image URL";
        body.innerHTML = `<input id="imgUrl" style="width:100%;padding:8px;">`;
    }

    if (type === "video") {
        title.innerText = "Video URL";
        body.innerHTML = `<input id="videoUrl" style="width:100%;padding:8px;">`;
    }

    if (type === "table") {
        title.innerText = "Table";
        body.innerHTML = `
            <input id="rows" type="number" placeholder="Rows" style="width:48%">
            <input id="cols" type="number" placeholder="Cols" style="width:48%">
        `;
    }

    document.getElementById("actionModal").style.display = "block";
}

function closeActionModal() {
    document.getElementById("actionModal").style.display = "none";
}

function runAction() {
    let editor = document.getElementById("editor");

    if (currentAction === "image") {
        let url = document.getElementById("imgUrl").value;
        document.execCommand("insertImage", false, url);
    }

    if (currentAction === "video") {
        let url = document.getElementById("videoUrl").value;
        let embed = "";

        if (url.includes("youtube")) {
            let id = url.split("v=")[1] || url.split("/").pop();
            embed = `<div class="video-wrapper">
                        <iframe src="https://www.youtube.com/embed/${id}"></iframe>
                     </div>`;
        } else {
            embed = `<video controls width="100%"><source src="${url}"></video>`;
        }

        document.execCommand("insertHTML", false, embed);
    }

    if (currentAction === "table") {
        let r = document.getElementById("rows").value;
        let c = document.getElementById("cols").value;

        let table =
            "<table border='1' style='width:100%;border-collapse:collapse'>";

        for (let i = 0; i < r; i++) {
            table += "<tr>";
            for (let j = 0; j < c; j++) {
                table += "<td>Cell</td>";
            }
            table += "</tr>";
        }

        table += "</table>";

        document.execCommand("insertHTML", false, table);
    }

    closeActionModal();
}

/* UPLOAD */
function uploadImage(input) {
    let file = input.files[0];
    let form = new FormData();
    form.append("image", file);

    fetch("upload.php", { method: "POST", body: form })
        .then((res) => res.text())
        .then((url) => {
            document.execCommand("insertImage", false, url);
        });
}

/* META MODAL */
function openModal() {
    document.getElementById("modal").style.display = "block";
}

function closeModal() {
    document.getElementById("modal").style.display = "none";
}

function saveMeta() {
    let t = document.getElementById("title").value;
    let a = document.getElementById("author").value;
    let g = document.getElementById("tags").value;

    fetch("meta.php", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: `title=${t}&author=${a}&tags=${g}`,
    });

    closeModal();
}
