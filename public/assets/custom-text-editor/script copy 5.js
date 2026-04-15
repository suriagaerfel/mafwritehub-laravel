let savedRange = null;

let isResizingCol = false;
let isResizingRow = false;

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
    const editor = document.getElementById("editor");
    editor.focus();

    restoreSelection();

    document.execCommand(command, false, value);

    saveSelection();
}

function formatBlock(tag) {
    document.execCommand("formatBlock", false, `<${tag}>`);
}

// Helper: wrap HTML in a resizable div
function wrapResizable(html, width) {
    const w = width?.trim() || "100%";
    return `<div contenteditable="false" class="resizable" style="max-width:${w};">${html}</div>`;
}

// **NEW: Table insertion via modal**
function insertTable() {
    const editor = document.getElementById("editor");
    editor.focus();
    saveSelection();

    const modalHtml = `
    <div class="modal-row">
        <label>Rows</label>
        <input type="number" id="tableRows" class="modal-input" value="2" min="1" max="20">
    </div>
    <div class="modal-row">
        <label>Columns</label>
        <input type="number" id="tableCols" class="modal-input" value="2" min="1" max="20">
    </div>
    <div class="modal-row">
        <label>Width (e.g. 100%, 600px)</label>
        <input type="text" id="tableWidth" class="modal-input" placeholder="100%">
    </div>
    `;

    showModal("Insert Table", modalHtml, () => {
        const rows = parseInt(document.getElementById("tableRows").value);
        const cols = parseInt(document.getElementById("tableCols").value);
        const width =
            document.getElementById("tableWidth").value.trim() || "100%";

        if (!rows || !cols || rows > 20 || cols > 20) {
            alert("Please enter valid rows (1-20) and columns (1-20).");
            return;
        }

        let table =
            "<table style='border-collapse:collapse;width:100%;table-layout:fixed;'>";
        for (let i = 0; i < rows; i++) {
            table += "<tr>";
            for (let j = 0; j < cols; j++) {
                table += `
                <td>
                    <div class="cell-content" contenteditable="true"></div>
                    <div class="col-resizer"></div>
                    <div class="row-resizer"></div>
                </td>`;
            }
            table += "</tr>";
        }
        table += "</table>";

        const wrappedTable = wrapResizable(table, width);
        editor.focus();
        restoreSelection();
        pasteHtmlAtCaret(wrappedTable);
    });
}

// **Updated image insertion with better resizable wrapper**
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
    <div class="modal-row">
        <label>Width (e.g. 300px, 70%)</label>
        <input type="text" id="imgWidth" class="modal-input" placeholder="100%">
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
        const width =
            document.getElementById("imgWidth").value.trim() || "100%";

        const buildImg = (src) => {
            return `<img src="${src}" alt="Image" style="max-width:100%;height:auto;display:block;">`;
        };

        if (upload && file) {
            const reader = new FileReader();
            reader.onload = (e) => {
                const wrapped = wrapResizable(buildImg(e.target.result), width);
                editor.focus();
                restoreSelection();
                pasteHtmlAtCaret(wrapped);
            };
            reader.readAsDataURL(file);
        } else if (url && textUrl && textUrl.startsWith("http")) {
            const img = new Image();
            img.onload = () => {
                const wrapped = wrapResizable(
                    `<img src="${textUrl}" alt="Image" style="max-width:100%;height:auto;display:block;">`,
                    width,
                );
                editor.focus();
                restoreSelection();
                pasteHtmlAtCaret(wrapped);
            };

            img.onerror = () => {
                alert(
                    "Image failed to load. Check the URL or server permissions.",
                );
            };

            img.src = textUrl;
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
            <input type="radio" name="videoType" value="url"> Enter MP4 URL
        </label>
        <input type="text" id="videoUrl" class="modal-input" placeholder="https://example.com/video.mp4">
    </div>
    <div class="modal-row">
        <label class="modal-radio">
            <input type="radio" name="videoType" value="youtube"> YouTube URL
        </label>
        <input type="text" id="youtubeUrl" class="modal-input" placeholder="https://www.youtube.com/watch?v=...">
    </div>
    <div class="modal-row">
        <label>Width (e.g. 100%, 400px)</label>
        <input type="text" id="videoWidth" class="modal-input" placeholder="100%">
    </div>
    `;
    showModal("Insert Video", modalHtml, () => {
        const upload = document.querySelector(
            "input[name='videoType'][value='upload']:checked",
        );
        const mp4Url = document.querySelector(
            "input[name='videoType'][value='url']:checked",
        );
        const youtubeChoice = document.querySelector(
            "input[name='videoType'][value='youtube']:checked",
        );
        const file = document.getElementById("videoFile").files[0];
        const mp4TextUrl = document.getElementById("videoUrl").value.trim();
        const youtubeRaw = document.getElementById("youtubeUrl").value.trim();
        const width =
            document.getElementById("videoWidth").value.trim() || "100%";

        const buildWrapped = (inner) => wrapResizable(inner, width);

        if (upload && file) {
            const reader = new FileReader();
            reader.onload = (e) => {
                const inner = `
                <div class="video-wrapper">
                    <video controls>
                        <source src="${e.target.result}" type="video/mp4">
                    </video>
                </div>`;
                const wrapped = buildWrapped(inner);
                const editor = document.getElementById("editor");
                editor.focus();
                restoreSelection();
                pasteHtmlAtCaret(wrapped);
            };
            reader.readAsDataURL(file);
        } else if (
            mp4Url &&
            mp4TextUrl &&
            mp4TextUrl.startsWith("http") &&
            mp4TextUrl.includes(".mp4")
        ) {
            const editor = document.getElementById("editor");
            editor.focus();
            restoreSelection();
            const inner = `
            <div class="video-wrapper">
                <video controls>
                    <source src="${mp4TextUrl}" type="video/mp4">
                </video>
            </div>`;
            const wrapped = buildWrapped(inner);
            if (document.queryCommandSupported("insertHTML")) {
                document.execCommand("insertHTML", false, wrapped);
            } else {
                pasteHtmlAtCaret(wrapped);
            }
        } else if (
            youtubeChoice &&
            youtubeRaw &&
            youtubeRaw.trim().length > 0
        ) {
            const editor = document.getElementById("editor");
            editor.focus();
            restoreSelection();
            const id = extractYouTubeId(youtubeRaw);
            if (!id) {
                alert("Invalid YouTube URL.");
                return;
            }
            const inner = `
            <div class="video-wrapper">
                <div class="iframe-overlay"></div>
                <iframe src="https://www.youtube.com/embed/${id}" frameborder="0" allowfullscreen></iframe>
            </div>`;
            const wrapped = buildWrapped(inner);
            if (document.queryCommandSupported("insertHTML")) {
                document.execCommand("insertHTML", false, wrapped);
            } else {
                pasteHtmlAtCaret(wrapped);
            }
        } else {
            alert("Please select a file, enter an MP4 URL, or a YouTube URL.");
        }
    });
}

function extractYouTubeId(url) {
    const regExp =
        /^.*(youtu.be\/|v\/|u\/\w\/|embed\/|watch\?v=|\&v=)([^#&?]*).*/;
    const match = url.match(regExp);
    return match && match[2].length === 11 ? match[2] : null;
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
        const imgHTML = `<div contenteditable="false" class="resizable" style="max-width:100%;"><img src="${e.target.result}" style="max-width:100%;height:auto;display:block;"></div>`;
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

// **NEW: Mouse resize functionality**
function initResize() {
    document.addEventListener("mousedown", function (e) {
        const resizable = e.target.closest(".resizable");
        if (
            e.target.closest(".col-resizer") ||
            e.target.closest(".row-resizer") ||
            e.target.closest(".cell-content") ||
            isResizingCol ||
            isResizingRow
        ) {
            return;
        }

        const target = e.target.closest(".resizable");
        if (!target) return;
    });
}

// Initialize resize on load
if (document.readyState === "loading") {
    document.addEventListener("DOMContentLoaded", initResize);
} else {
    initResize();
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

document.addEventListener("dblclick", function (e) {
    const wrapper = e.target.closest(".video-wrapper");
    if (!wrapper) return;

    const overlay = wrapper.querySelector(".iframe-overlay");
    if (overlay) {
        overlay.style.display = "none"; // allow interaction
    }
});

document.addEventListener("click", function (e) {
    const all = document.querySelectorAll(".resizable");
    all.forEach((el) => el.classList.remove("selected"));

    const selected = e.target.closest(".resizable");
    if (selected) {
        selected.classList.add("selected");
    }
});

function wrapResizable(html, width) {
    const w = width?.trim() || "100%";
    return `
    <div contenteditable="false" class="resizable" style="max-width:${w};">
        ${html}
        <div class="resize-handle"></div>
    </div>`;
}

function onMouseMove(e) {
    const dx = e.clientX - startX;
    const newWidth = Math.max(100, startWidth + dx);

    const aspectRatio = startWidth / startHeight;
    const newHeight = newWidth / aspectRatio;

    resizable.style.width = newWidth + "px";
    resizable.style.height = newHeight + "px";
    resizable.style.maxWidth = newWidth + "px";
}

document.addEventListener("mousedown", function (e) {
    const target = e.target.closest(".resizable");

    if (!target || e.target.classList.contains("resize-handle")) return;

    let startX = e.clientX;
    let startY = e.clientY;

    const rect = target.getBoundingClientRect();
    const offsetX = startX - rect.left;
    const offsetY = startY - rect.top;

    function onMove(e) {
        target.style.position = "absolute";
        target.style.left = e.pageX - offsetX + "px";
        target.style.top = e.pageY - offsetY + "px";
    }

    function onUp() {
        document.removeEventListener("mousemove", onMove);
        document.removeEventListener("mouseup", onUp);
    }

    document.addEventListener("mousemove", onMove);
    document.addEventListener("mouseup", onUp);
});

document.addEventListener("mousedown", function (e) {
    // COLUMN RESIZE

    if (e.target.classList.contains("col-resizer")) {
        isResizingCol = true;
        e.preventDefault();
        e.stopPropagation();

        const td = e.target.parentElement;
        const startX = e.clientX;
        const startWidth = td.offsetWidth;

        function onMove(e) {
            const newWidth = Math.max(50, startWidth + (e.clientX - startX));
            td.style.width = newWidth + "px";
        }

        function onUp() {
            isResizingCol = false;
            document.removeEventListener("mousemove", onMove);
            document.removeEventListener("mouseup", onUp);
        }

        document.addEventListener("mousemove", onMove);
        document.addEventListener("mouseup", onUp);
    }
    // ROW RESIZE
    // if (e.target.classList.contains("row-resizer")) {
    //     e.preventDefault();

    //     const td = e.target.parentElement;
    //     const tr = td.parentElement;
    //     const startY = e.clientY;
    //     const startHeight = tr.offsetHeight;

    //     function onMove(e) {
    //         const newHeight = Math.max(30, startHeight + (e.clientY - startY));
    //         tr.style.height = newHeight + "px";
    //     }

    //     function onUp() {
    //         document.removeEventListener("mousemove", onMove);
    //         document.removeEventListener("mouseup", onUp);
    //     }

    //     document.addEventListener("mousemove", onMove);
    //     document.addEventListener("mouseup", onUp);
    // }

    // ROW RESIZE
    // if (e.target.classList.contains("row-resizer")) {
    //     isResizingTable = true;
    //     e.preventDefault();

    //     const td = e.target.parentElement;
    //     const startX = e.clientX;
    //     const startWidth = td.offsetWidth;

    //     function onMove(e) {
    //         const newWidth = Math.max(50, startWidth + (e.clientX - startX));
    //         td.style.width = newWidth + "px";
    //     }

    //     function onUp() {
    //         isResizingTable = false;
    //         document.removeEventListener("mousemove", onMove);
    //         document.removeEventListener("mouseup", onUp);
    //     }

    //     document.addEventListener("mousemove", onMove);
    //     document.addEventListener("mouseup", onUp);
    // }

    // ROW RESIZE
    if (e.target.classList.contains("row-resizer")) {
        isResizingRow = true;
        e.preventDefault();
        e.stopPropagation();

        const td = e.target.parentElement;
        const tr = td.parentElement;

        const startY = e.clientY;
        const startHeight = tr.offsetHeight;

        function onMove(e) {
            const newHeight = Math.max(30, startHeight + (e.clientY - startY));

            tr.querySelectorAll("td").forEach((cell) => {
                cell.style.height = newHeight + "px";
            });
        }

        function onUp() {
            isResizingRow = false;
            document.removeEventListener("mousemove", onMove);
            document.removeEventListener("mouseup", onUp);
        }

        document.addEventListener("mousemove", onMove);
        document.addEventListener("mouseup", onUp);
    }
});

function addRow(td) {
    const tr = td.parentElement;
    const table = tr.parentElement;

    const newRow = tr.cloneNode(true);

    // Clear content
    newRow.querySelectorAll(".cell-content").forEach((cell) => {
        cell.innerHTML = "";
    });

    table.appendChild(newRow);
}

function addColumn(td) {
    const table = td.closest("table");
    const colIndex = Array.from(td.parentElement.children).indexOf(td);

    table.querySelectorAll("tr").forEach((tr) => {
        const newCell = tr.children[colIndex].cloneNode(true);
        newCell.querySelector(".cell-content").innerHTML = "";
        tr.appendChild(newCell);
    });
}

document.addEventListener("dblclick", function (e) {
    const td = e.target.closest("td");
    if (!td) return;

    if (e.offsetX > td.offsetWidth - 10) {
        addColumn(td); // right edge → add column
    } else if (e.offsetY > td.offsetHeight - 10) {
        addRow(td); // bottom edge → add row
    }
});

document.addEventListener("click", function (e) {
    const cell = e.target.closest(".cell-content");
    if (!cell) return;

    setTimeout(() => {
        cell.focus();
    }, 0);
});

document.addEventListener("mouseup", saveSelection);
document.addEventListener("keyup", saveSelection);
