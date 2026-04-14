            <div style="display: inline;">
                <div id="actionModal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.6);">

                    <div style="background:#fff; width:90%; max-width:420px; margin:80px auto;
                    padding:20px; border-radius:8px;">

                        <h3 id="modalTitle">Action</h3>

                        <div id="modalBody"></div>

                        <br>

                        <button onclick="runAction()">Insert</button>
                        <button onclick="closeActionModal()">Cancel</button>
                    </div>
                </div>

                <div class="toolbar">
                    <button onclick="format('bold')">𝗕</button>
                    <button onclick="format('italic')">𝘐</button>
                    <button onclick="format('underline')">U̲</button>

                    <!-- LIST -->
                    <button onclick="insertList('insertUnorderedList')">• List</button>
                    <button onclick="insertList('insertOrderedList')">1. List</button>

                    <!-- ALIGN -->
                    <button onclick="format('justifyLeft')">⬅️</button>
                    <button onclick="format('justifyCenter')">⬆️</button>
                    <button onclick="format('justifyRight')">➡️</button>
                    <button onclick="format('justifyFull')">☰</button>

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

                    <button onclick="insertImageURL()">Image</button>
                    <button onclick="insertVideo()">Video</button>
                    <button onclick="insertTable()">Table</button>
                    <button onclick="openModal()">Details</button>

                    <button onclick="toggleDark()">🌙</button>
                    <button onclick="toggleCode()">💻</button>

                    <div  id="dashboard-add-edit-article-buttons">
                    <span id="article-save-button" class="link-tag-button">Save</span>
                    <span id="article-publish-button" class="link-tag-button">Publish</span>
                    <span id="article-unpublish-button" class="link-tag-button">Unpublish</span>
                    <span id="article-delete-button" class="link-tag-button">Delete</span>
                    <span id="article-image-button" class="link-tag-button">Article Image</span>
                    <a id="article-view-button" class="link-tag-button">View</a>
                </div>
                </div>
           
                
             </div>

            <div id="editor" contenteditable="true"></div>
            <textarea id="codeArea"></textarea>

            <div id="modal" style="display:none; position:fixed; top:0; left:0; 
            width:100%; height:100%; background:rgba(0,0,0,0.5);">

                <div style="background:#fff; padding:20px; width:300px; margin:100px auto;">
                    <h3>Article Info</h3>

                    <input id="title" value="" placeholder="Title">
                    <input id="author" value="" placeholder="Author">
                    <input id="tags" value=" placeholder="Tags">

                    <button onclick="saveMeta()">Save</button>
                    <button onclick="closeModal()">Close</button>
                </div>
            </div>