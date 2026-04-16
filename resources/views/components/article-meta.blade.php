
     
        <div style="display: flex; gap:10px; flex-direction:column;">
            <input type="text" id="article-mode" hidden>
            <input type="text" id="article-id" hidden>
            <input type="text" id="article-title" placeholder="Title">
            <div style="display: flex; flex-direction:row; gap:10px;" id="category-topic-verison-container">
              
                <div style="display: flex; gap:10px; width:300px;" id="article-category-container">
                    <input type="text" id="article-original-category" hidden>
                    <select id="article-category" class="article-category-update">
                        <option id="article-originally-selected-category" selected></option>
                    </select>
                </div>
              
                <div style="display: flex; flex-direction:row;width:100%;padding-top:10px;" >
                    <input type="text" id="article-tags-selected" hidden>
                    <span>Tags: </span>
                    <div id="article-tags-list">

                    </div>
                </div>

            </div>
          
            
            

        </div>
