


<x-main>
    @include ('components/head')
    @include ('components/header')

 
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

        <!-- <input id="title" placeholder="Title"><br><br>
        <input id="author" placeholder="Author"><br><br>
        <input id="tags" placeholder="Tags"><br><br> -->

        <input id="title" value="<?= $meta['title'] ?? '' ?>" placeholder="Title">
        <input id="author" value="<?= $meta['author'] ?? '' ?>" placeholder="Author">
        <input id="tags" value="<?= $meta['tags'] ?? '' ?>" placeholder="Tags">

        <button onclick="saveMeta()">Save</button>
        <button onclick="closeModal()">Close</button>
    </div>
</div>

<div class="status" id="status">Auto-save enabled</div>

 @include('components/footer-scripts')

</body>
</x-main>