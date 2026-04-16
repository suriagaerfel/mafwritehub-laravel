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
  <button onclick="format('bold')"><i class="bi bi-type-bold"></i></button>
  <button onclick="format('italic')"><i class="bi bi-type-italic"></i></button>
  <button onclick="format('underline')"><i class="bi bi-type-underline"></i></button>
  <button onclick="format('superscript')"><i class="bi bi-superscript"></i></button>
  <button onclick="format('subscript')"><i class="bi bi-subscript"></i></button>
  <!-- Alignment -->
  <button onclick="format('justifyLeft')"><i class="bi bi-justify-left"></i></button>
  <button onclick="format('justifyCenter')"><i class="bi bi-text-center"></i></button>
  <button onclick="format('justifyRight')"><i class="bi bi-justify-right"></i></button>
  <button onclick="format('justifyFull')"><i class="bi bi-justify"></i></button>


  
  <!-- Headings / Paragraph -->
  <select onchange="formatBlock(this.value)" style="margin-top: 12px;" id="text-tag">
    <option value="p">P</option>
    <option value="h1">H1</option>
    <option value="h2">H2</option>
    <option value="h3">H3</option>
    <option value="h4">H4</option>
    <option value="h5">H5</option>
    <option value="h6">H6</option>
  </select>
  <!-- Lists -->
  <button onclick="format('insertUnorderedList')"><i class="bi bi-list-ul"></i></button>
  <button onclick="format('insertOrderedList')"><i class="bi bi-list-ol"></i></button>
  <!-- Insert -->
  <button onclick="insertTable()"><i class="bi bi-table"></i></button>
  <button onclick="insertLink()"><i class="bi bi-link"></i></button>
  <button onclick="insertImage()"><i class="bi bi-card-image"></i></button>
  <button onclick="insertVideo()"><i class="bi bi-play-btn-fill"></i></button>
 <input type="file" id="fileInput" onchange="uploadFile(event)" hidden>

</div>

<div id="editor" contenteditable="true" style="border:none;outline:none; overflow:scroll; scrollbar-width:none; scrollbar-height:none;"></div>


