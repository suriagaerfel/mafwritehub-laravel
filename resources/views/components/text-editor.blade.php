<div id="modal" class="modal">
  <div class="modal-content">
    <span class="modal-close" onclick="closeModal()">&times;</span>
    <h4 id="modalTitle"></h4>
    <div id="modalBody"></div>
    <div class="modal-footer">
      <button onclick="confirmModal()">OK</button>
      <button onclick="closeModal()">Cancel</button>
    </div>
  </div>
</div>


<div id="toolbar">
  <!-- Text Styles -->
  <button onclick="format('bold')"><b>B</b></button>
  <button onclick="format('italic')"><i>I</i></button>
  <button onclick="format('underline')"><u>U</u></button>
  <button onclick="format('superscript')">x⁺</button>
  <button onclick="format('subscript')">x₋</button>
  <!-- Alignment -->
  <button onclick="format('justifyLeft')">⯇</button>
  <button onclick="format('justifyCenter')">⯈⯇</button>
  <button onclick="format('justifyRight')">⯈</button>
  <button onclick="format('justifyFull')">≋</button>
  <!-- Headings / Paragraph -->
  <select onchange="formatBlock(this.value)">
    <option value="p">Paragraph</option>
    <option value="h1">H1</option>
    <option value="h2">H2</option>
    <option value="h3">H3</option>
    <option value="h4">H4</option>
    <option value="h5">H5</option>
    <option value="h6">H6</option>
  </select>
  <!-- Lists -->
  <button onclick="format('insertUnorderedList')">• List</button>
  <button onclick="format('insertOrderedList')">1. List</button>
  <!-- Insert -->
  <button onclick="insertTable()">Table</button>
  <button onclick="insertLink()">🔗 Link</button>
  <button onclick="insertImage()">🖼️ Image</button>
  <button onclick="insertVideo()">🎥 Video</button>
  <input type="file" id="fileInput" onchange="uploadFile(event)" hidden>
</div>
<div id="editor" contenteditable="true"></div>
