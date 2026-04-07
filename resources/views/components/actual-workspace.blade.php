<div  class="actual-workspace" style="padding-top: 10px;"> 
    <input type="text" id="content_id" placeholder="Content ID..."hidden>
     <input type="text" id="content-action" placeholder="Content action..."hidden>
    <input type="text" id="mode" placeholder="Mode..."hidden>
    <input type="text" id="version" placeholder="Version..." hidden>
    <input type="text" id="status" placeholder="Status..." hidden>
    <input type="text" id="image-link" placeholder="Image..." hidden>
    <input type="text" id="image-status" placeholder="Image status..." hidden>

    <div class="workspace-info-and-buttons">
            
    
            <div  class="workspace-buttons">               
    
                    <a class="link-tag-button buttons" id="update-image-button">
                        <img src="<?php echo asset('/images/image.svg');?>" title="Add Content Image">
                    </a>
                  

                    <a class="link-tag-button buttons" id="show-image-button" style="display: none;">
                        <img src="<?php echo asset('/images/image.svg');?>" class="icons " title="Show Content Image" >
                    </a>
                  
             
                    
                    <a class="link-tag-button buttons" id="view-content-button">
                        <img src="<?php echo asset('/images/view.svg');?>" class="icons " title="Preview">
                    </a>

                    <?php if($pageName == 'Workspace - Writer' || $pageName == 'Workspace - Editor') {?>
                    <a class="link-tag-button buttons" id="content-message-button">
                        <img src="<?php echo asset('/images/message.svg');?>" class="icons " title="Comment">
                    </a>
                    <?php } ?>


                     <a class="link-tag-button buttons" id="new-button">
                        <img src="<?php echo asset('/images/new.svg');?>" class="icons " title="New">
                    </a>


                   
             
            </div>


    </div>
  

<?php if ($pageName == 'Workspace - Developer') {?>
    <div id="tool-files-list">
        <input type="text" id="tool-link" hidden>
        <table class="list-table" id="tool-files-list">
            <thead>
                <tr>
                    <th>Timestamp</th>
                    <th>File</th>
                    <th>Action</th>
                </tr>
            </thead>

            <tbody id="tool-files-list-body">
            
            </tbody>

        </table>
          
        <div id="tool-file-upload-message" class="alert alert-danger"></div>
        <div id="tool-file-upload-container">
            <input id="tool-file-upload" type="file">
            <button id="tool-file-upload-submit-button" class="link-tag-button"> UPLOAD FILE</button>  
        </div>
    </div>
    <br>
    <br>
    <?php } ?>
            
    <div id="inputs-container">
                <input type="text" id="original-content" placeholder="Original content..." hidden>
                <div id="workspace-inputs-message" class="alert alert-danger"></div>
                
               
                <div id="workspace-inputs">
                    <div style="display: flex; flex-direction:column;gap:10px; margin-bottom:15px;">

                        <div style="display: flex; gap:10px;justify-content:center;" >

                            <input id="title" type="text" placeholder="Title">

                            <?php if ($pageName == 'Workspace - Teacher' || $pageName == 'School Workspace - Researches') {?>
                            <div id="content-upload-container" style="margin-top:-20px;">
                                <input id="content" type="file">  
                                <a class="link-tag-button buttons" id="show-content-button">
                                    <img src="<?php echo asset('/images/document.png');?>" class="icons " >
                                </a>
                                
                            </div>
                            <?php } ?>

                        </div>

                         <?php if ($pageName == 'School Workspace - Researches') {?>
                            <div style="display: flex; gap:10px;">
                                <input id="date" type="date" placeholder="Date...">
                                <input id="proponents" type="text" placeholder="Proponents...">
                            </div>

                        <?php } ?>

                        <div style="display: flex; gap:10px;">
                            <input type="text" id="original-category" placeholder="Original category" hidden>
                            <select id="category">
                                <option value="Select Category" selected hidden>Select Category</option>
                            </select>
                       
                        
                           <input type="text" id="tags" placeholder="@tags" hidden>

                            <input type="text" id="original-access-type" placeholder="Original access type" hidden>
                            <select id="access-type">
                                

                            </select>

                            <?php if ($pageName == 'Workspace - Teacher') {?>
                            <input type="number" id="amount" placeholder="Amount" min="1" placeholder="amount" style="display: none; width:5px;">
                            <?php } ?>

                            
                            <input type="text" id="shared-with" placeholder="Shared with..." hidden>

                            <div style="display: flex; gap:10px; padding:auto;">
                                <a class="link-tag-button buttons" id="show-tags-container-button">
                                    <img src="<?php echo asset('/images/tag.png');?>" class="icons " >
                                </a>
                                <a class="link-tag-button buttons" id="show-shared-with-container-button">
                                    <img src="<?php echo asset('/images/share.svg');?>" class="icons " >
                                </a>
                            </div>

                        </div>

                        

                         <div id="tags-container" style="display: none;">
                            <div id="show-tags-used" style="width:100%;">
                                    
                            </div>
                        <br>
                            <div style="display: flex; flex-direction:column; gap:10px;">
                                <div id="show-tags-available" style="width:100%;">

                                </div>
                                <div style="display: flex; gap:10px;">
                                    <input type="search" id="searched-tag" placeholder="Type to search a tag...">
                                    <small>or</small>
                                    <input type="type" id="new-tag" placeholder="Add a tag...">
                                    <button style="width: fit-content;" id="new-tag-button">Add</button>
                                </div>

                            </div>
                        </div>


                         <div id="shared-with-container" style="display: none;">
                            <div id="show-users-shared" style="width:100%;">
                                    
                            </div>
                        <br>
                            <div style="display: flex; flex-direction:column; gap:10px;">
                                <div id="show-users-not-shared" style="width:100%;">

                                </div>
                                <div style="display: flex; gap:10px;">
                                    <input type="search" id="searched-user" placeholder="Search a user...">
                                </div>

                            </div>
                        </div>


                       

                       

                    </div>
                     <?php if ($pageName != 'Workspace - Writer' && $pageName != 'Workspace - Editor') {?>
                    <textarea id="description" type="text" placeholder="Description" style="width: 100%;min-height: 200px;resize:none; "></textarea>
                    <?php } ?>

                     <?php if ($pageName == 'Workspace - Writer' || $pageName == 'Workspace - Editor') {?>
                    <div id="eskquip-editor" contenteditable="true"></div>
                    </div>

                    <?php } ?>

                    <button id="save-content-button"></button>
                
                    
              

                </div>
                

               


    
 
    </div>
    
   


    <div  class="workspace-actions-container" style="display:flex;gap:10px; justify-content:space-between;">
        
        <div id="content-notes" style="display: flex; gap:40px;">
                    <small id="content-published"></small>
                    <small id="content-last-update"></small>     
        </div>
        <div id="content-versions">

        </div>

        <div class="workspace-action-buttons">    
                <button class="link-tag-button" id="to-unpublish-content-button">Unpublish</button>
                <button class="link-tag-button" id="to-publish-content-button">Publish</button>

            

                <?php if ($pageName == 'Workspace - Writer') {?>
                        <button class="link-tag-button" id="review-content-button">Get Review</button>
                <?php } ?>

                <?php if ($pageName == 'Workspace - Editor') {?>
                        <button class="link-tag-button" id="take-content-button">Take</button>
                        <button class="link-tag-button" id="leave-content-button">Leave</button>
                        <button class="link-tag-button" id="comment-button">Comment</button>
                        <button class="link-tag-button" id="approve-content-button">Approve</button>
                <?php } ?>

                <button class="link-tag-button" id="delete-content-button">Delete</button>      
        </div>

        
     
    </div>
 

      



</div>

