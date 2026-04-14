let savedRange = null;

function saveSelection() {
    const sel = window.getSelection();
    if (sel.rangeCount > 0) {
        savedRange = sel.getRangeAt(0);
    }
}

function restoreSelection() {
    if (savedRange) {
        const sel = window.getSelection();
        sel.removeAllRanges();
        sel.addRange(savedRange);
    }
}

function format(command, value = null) {
    document.getElementById("editor").focus();
    document.execCommand(command, false, value);
}

function formatBlock(tag) {
    document.execCommand("formatBlock", false, `<${tag}>`);
}

function insertTable() {
    const rows = prompt("Number of rows:", 2);
    const cols = prompt("Number of columns:", 2);
    if (!rows || !cols) return;
    let table =
        "<table border='1' style='border-collapse:collapse;width:100%;'>";
    for (let i = 0; i < rows; i++) {
        table += "<tr>";
        for (let j = 0; j < cols; j++) table += "<td>&nbsp;</td>";
        table += "</tr>";
    }
    table += "</table>";
    pasteHtmlAtCaret(table);
}

function insertImage() {
    const editor = document.getElementById("editor");
    editor.focus();
    saveSelection();

    const modalHtml = `
    <div class="modal-row">
      <label class="modal-radio">
        <input type="radio" name="imgType" value="upload" checked> Upload from computer
      </label>
      <input id="imgFile" type="file" accept="image/*" onchange="handleImgFileChange(event)" hidden>
      <button onclick="document.getElementById('imgFile').click()">Choose File</button>
    </div>
    <div class="modal-row">
      <label class="modal-radio">
        <input type="radio" name="imgType" value="url"> Enter URL
      </label>
      <input type="text" id="imgUrl" class="modal-input" placeholder="https://example.com/image.jpg">
    </div>
  `;
    showModal("Insert Image", modalHtml, () => {
        const upload = document.querySelector(
            "input[name='imgType'][value='upload']:checked",
        );
        const url = document.querySelector(
            "input[name='imgType'][value='url']:checked",
        );
        const file = document.getElementById("imgFile").files[0];
        const textUrl = document.getElementById("imgUrl").value.trim();

        if (upload && file) {
            const reader = new FileReader();
            reader.onload = (e) => {
                const imgHTML = `<img src="${e.target.result}" style="max-width:100%;display:block;margin:6px 0;">`;
                const editor = document.getElementById("editor");
                editor.focus();
                restoreSelection();
                pasteHtmlAtCaret(imgHTML);
            };
            reader.readAsDataURL(file);
        } else if (url && textUrl && textUrl.startsWith("http")) {
            const editor = document.getElementById("editor");
            editor.focus();
            restoreSelection();

            const imgHTML = `<img src="${textUrl}" style="max-width:100%;display:block;margin:6px 0;">`;
            if (document.queryCommandSupported("insertHTML")) {
                document.execCommand("insertHTML", false, imgHTML);
            } else {
                pasteHtmlAtCaret(imgHTML);
            }
        } else {
            alert("Please select a file or enter a valid URL.");
        }
    });
}

function handleImgFileChange(e) {
    const file = e.target.files[0];
    if (file) {
        document.querySelector(
            "input[name='imgType'][value='upload']",
        ).checked = true;
    }
}

function insertVideo() {
    const editor = document.getElementById("editor");
    editor.focus();
    saveSelection();

    const modalHtml = `
    <div class="modal-row">
      <label class="modal-radio">
        <input type="radio" name="videoType" value="upload" checked> Upload MP4
      </label>
      <input id="videoFile" type="file" accept="video/mp4" onchange="handleVideoFileChange(event)" hidden>
      <button onclick="document.getElementById('videoFile').click()">Choose File</button>
    </div>
    <div class="modal-row">
      <label class="modal-radio">
        <input type="radio" name="videoType" value="url"> Enter URL (MP4)
      </label>
      <input type="text" id="videoUrl" class="modal-input" placeholder="https://example.com/video.mp4">
    </div>
  `;
    showModal("Insert Video", modalHtml, () => {
        const upload = document.querySelector(
            "input[name='videoType'][value='upload']:checked",
        );
        const url = document.querySelector(
            "input[name='videoType'][value='url']:checked",
        );
        const file = document.getElementById("videoFile").files[0];
        const textUrl = document.getElementById("videoUrl").value.trim();

        if (upload && file) {
            const reader = new FileReader();
            reader.onload = (e) => {
                const videoHTML = `<div style="margin:8px 0;text-align:center;"><video controls style="max-width:100%;height:auto;"><source src="${e.target.result}" type="video/mp4"></video></div>`;
                const editor = document.getElementById("editor");
                editor.focus();
                restoreSelection();
                pasteHtmlAtCaret(videoHTML);
            };
            reader.readAsDataURL(file);
        } else if (url && textUrl && textUrl.startsWith("http")) {
            const editor = document.getElementById("editor");
            editor.focus();
            restoreSelection();

            const videoHTML = `<div style="margin:8px 0;text-align:center;"><video controls style="max-width:100%;height:auto;"><source src="${textUrl}" type="video/mp4"></video></div>`;
            if (document.queryCommandSupported("insertHTML")) {
                document.execCommand("insertHTML", false, videoHTML);
            } else {
                pasteHtmlAtCaret(videoHTML);
            }
        } else {
            alert("Please select a file or enter a valid URL.");
        }
    });
}

function handleVideoFileChange(e) {
    const file = e.target.files[0];
    if (file) {
        document.querySelector(
            "input[name='videoType'][value='upload']",
        ).checked = true;
    }
}

function uploadFile(event) {
    const file = event.target.files[0];
    if (!file) return;
    const reader = new FileReader();
    reader.onload = (e) => {
        const imgHTML = `<img src="${e.target.result}" style="max-width:100%;display:block;margin:6px 0;">`;
        const editor = document.getElementById("editor");
        editor.focus();
        restoreSelection();
        pasteHtmlAtCaret(imgHTML);
    };
    reader.readAsDataURL(file);
}

function pasteHtmlAtCaret(html) {
    const editor = document.getElementById("editor");
    editor.focus();
    const sel = window.getSelection();
    if (!sel.rangeCount) return;

    const range = sel.getRangeAt(0);
    range.deleteContents();
    const el = document.createElement("div");
    el.innerHTML = html;
    const frag = document.createDocumentFragment();
    while (el.firstChild) frag.appendChild(el.firstChild);

    range.insertNode(frag);

    range.setStartAfter(frag.lastChild || frag.firstChild || frag);
    sel.removeAllRanges();
    sel.addRange(range);
}

function showModal(title, html, onConfirm) {
    document.getElementById("modalTitle").textContent = title;
    document.getElementById("modalBody").innerHTML = html;
    window.currentModalConfirm = onConfirm;
    document.getElementById("modal").style.display = "flex";
    document.getElementById("editor").focus();
}

function closeModal() {
    document.getElementById("modal").style.display = "none";
    window.currentModalConfirm = null;
}

function confirmModal() {
    if (window.currentModalConfirm) {
        window.currentModalConfirm();
    }
    closeModal();
}

function insertLink() {
    const editor = document.getElementById("editor");
    editor.focus();
    saveSelection();

    const selection = window.getSelection();
    const text = selection.toString().trim();

    const modalHtml = `
    <div class="modal-row">
      <label>Link text</label>
      <input type="text" id="linkText" class="modal-input" value="${text || ""}">
    </div>
    <div class="modal-row">
      <label>URL</label>
      <input type="text" id="linkUrl" class="modal-input" value="${text || "https://"}" placeholder="https://">
    </div>
  `;
    showModal("Insert Link", modalHtml, () => {
        const txt = document.getElementById("linkText").value.trim();
        const url = document.getElementById("linkUrl").value.trim();

        if (!url || !url.startsWith("http")) {
            alert("Please enter a valid URL.");
            return;
        }

        const linkText = txt || url;
        const linkHTML = `<a href="${url}" target="_blank">${linkText}</a>`;

        const editor = document.getElementById("editor");
        editor.focus();
        restoreSelection();
        pasteHtmlAtCaret(linkHTML);
    });
}

document.getElementById("editor").addEventListener("click", function (e) {
    const link = e.target.closest("a");
    if (!link) return;

    e.preventDefault();
    const text = link.textContent;
    const url = link.href;

    const modalHtml = `
    <div class="modal-row">
      <label>Link text</label>
      <input type="text" id="linkText" class="modal-input" value="${text}">
    </div>
    <div class="modal-row">
      <label>URL</label>
      <input type="text" id="linkUrl" class="modal-input" value="${url}" placeholder="https://">
    </div>
  `;

    showModal("Edit Link", modalHtml, () => {
        const txt = document.getElementById("linkText").value.trim();
        const u = document.getElementById("linkUrl").value.trim();

        if (!u || !u.startsWith("http")) {
            alert("Please enter a valid URL.");
            return;
        }

        link.textContent = txt || u;
        link.href = u;
    });
});
