

    <?php //---------------------------FOR DELETING CONTENT-------------------------------------?>

    <div class="modal website-modal website-modal-wrapper" id="modal-confirm-delete">
        <div class="website-modal-content" style="width: 300px;">
            <p>Are you sure you want to delete?</p>
            <p id="delete-message"></p>
            <input type="text" id="delete-type">
            <input type="text" id="delete-id">

            <hr>
            <div style="display: flex; flex-direction:row"> 
                <a class="link-tag-button" id="delete-confirmed-button">Yes</a>
                <a class="link-tag-button" id="close-modal-confirm-delete">No</a>
            </div>
        
        </div>
    </div>



    <?php //---------------------------FOR UPLOADING IMAGE-------------------------------------?>

    <div class="modal website-modal website-modal-wrapper" id="modal-upload-image">
        <div class="website-modal-content">
                <div class="close-modal-container">
                    <a class="close" id="close-modal-upload-image">&times;</a>
                </div>
                    <small class='modal-replace-image-warning'>
                        Select an image with a JPEG or JPG format. 
                        <br>The existing image will be deleted after the update.
                    </small> 
                
                    <hr>

                    <div id="modal-upload-image-message" class="alert alert-danger"></div>
                    <input type="text" id="upload-type" hidden>
                    <input type="text" id="upload-action-file" hidden>
                    <input type="text" id="content-hidden-id" hidden>

                    <input type="file" id="upload-image" class="attachments">

                    <button id="upload-button"></button>
        

                </form>

        </div>
    </div>



<?php //---------------------------FOR ARTICLES SEARCH-------------------------------------?>

    <div class="modal website-modal website-modal-wrapper" id="modal-searched-articles" >
        <div class="website-modal-content" style="width: 50%; height:fit-content; min-height:50vh; background-color:white; " >

            <!-- <div id="searched-articles-list">
            </div> -->
         
            
        </div>
    </div>


















    








    <?php //---------------------------FOR SHOWING CONTENT IMAGE-------------------------------------?>





    <div class="modal website-modal website-modal-wrapper" id="modal-show-image">
        <div class="website-modal-content" id="modal-content-show-image">
                <div class="close-modal-container" style="width: 100%;">
                    <a class="close" id="close-modal-show-image">&times;</a>
                </div>
                    
                
                <hr>
                <img src="" style="width: 100%;" id="article-image-shown">
                
                <span class="change-featured-image link-tag-button">Change Featured Image</span>
                

                

        </div>
    </div>





   



    <?php //-----------------FOR LOGIN----------------------------- ?>

    <div class="modal website-modal website-modal-wrapper" id="modal-login">
        <div class="website-modal-content">
            <div class="close-modal-container">
                 <a class="close close-without-null-redirection">&times;</a>
            </div>
            <div id="login-form">            
                <h5 class="form-title">Login</h5>

                <div id="login-message" class="alert"></div>
            
                <div class="input-containers">
                    <input type="text" placeholder="Email Address/Username" id="login-email-username">
                </div>

                <div class="input-containers">
                    <input type="password" placeholder="Password" id="login-password">
                </div>

                <div>
                    <button id="login-submit-button">Submit</button>
                </div>
                <br>
                <span style="font-size: 10pt;">Forgot your password?</span>
                <span id="get-reset-password-otp-button"class='link' style="font-size: 10pt;">Reset now</span>

          </div>
        </div>

    </div>





    <?php //-----------------FOR GET PASSWORD RESET OTP----------------------------- ?>

    <div class="modal website-modal website-modal-wrapper" id="modal-get-password-reset-otp">
        <div class="website-modal-content">
            <div class="close-modal-container">
                    <a class="close close-without-null-redirection">&times;</a>
            </div>

            <div id="get-password-reset-otp-form">
                <h5 class="form-title">Provide Details</h5>
                <div id="get-otp-message" class="alert" style="display: none;"></div>
                <input type="text" placeholder="Email address o username" id="get-password-reset-otp-email-username">
                <button id="get-password-reset-otp-submit-button">Get Password Reset OTP</button>
                <br>
                <br>
                <span class="login-button link" style="font-size: 10pt;">Back to login</span>
                    
            </div>
                
        </div>

    </div>

      <?php //-----------------FOR ENTERING PASSWORD RESET OTP----------------------------- ?>

    <div class="modal website-modal website-modal-wrapper" id="modal-otp-for-reset-password">
        <div class="website-modal-content">
            <div class="close-modal-container">
                    <a class="close close-without-null-redirection">&times;</a>
            </div>

            <div id="enter-password-reset-otp-form">
               
                <h5 class="form-title">Enter OTP</h5>
                <small>An OTP has been sent to your email address. Please enter it here to proceed.</small>
                 <div id="otp-message" class="alert" style="display: none;"></div>
                <input type="text" id="password-reset-email-username-otp" hidden>
                <input type="text" placeholder="000000" id="password-reset-otp">
                <button id="check-password-reset-otp-submit-button">Proceed</button>
                <br>
                <br>
                <span class="login-button link" style="font-size: 10pt;">Back to login</span>
                    
            </div>
                
        </div>

    </div>


      <?php //-----------------FOR PASSWORD RESET----------------------------- ?>

    <div class="modal website-modal website-modal-wrapper" id="modal-reset-password">
        <div class="website-modal-content" style="height:fit-content">
            <div class="close-modal-container">
                    <a class="close close-without-null-redirection">&times;</a>
            </div>

            <div id="modal-reset-password-form">
                <div id="otp-message" class="alert" style="display: none;"></div>
                <h5 class="form-title">Password Reset</h5>
                <div id="password-reset-message" class="alert" style="display: none;"></div>
                <input type="text" id="password-reset-email-username-proceed">
                <input type="password" placeholder='Type your password' id="new-password">
                <input type="password" placeholder="Retype your password" id="new-password-retyped">
                <button id="reset-password-submit-button">Reset</button>
                <br>
                <br>
                <span class="login-button link" style="font-size: 10pt;">Back to login</span>
                    
            </div>
                
        </div>

    </div>







     <?php //-----------------FOR ADDING/EDITING ARTICLES----------------------------- ?>

    <div class="modal website-modal website-modal-wrapper" id="modal-add-edit-article">
        <div class="website-modal-content" style="width: 90%; height:90vh; margin:auto; overflow:scroll;" id="modal-add-edit-article-content">
           
            <div class="close-modal-container">
                 <a class="close close-without-null-redirection">&times;</a>
            </div>
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


            
                
            

           

            
            

        
                
        </div>

    </div>



    <?php //-----------------FOR ARTICLE META----------------------------- ?>

    <div class="modal website-modal website-modal-wrapper" id="modal-article-meta">
        <div class="website-modal-content" style="width: 90%; height:90vh; margin:auto; overflow:scroll;" id="modal-add-edit-article-content">
           
            <div class="close-modal-container">
                 <a class="close close-without-null-redirection">&times;</a>
            </div>

            <div id="add-edit-article-container">
                <div id="article-message" class="alert alert-danger"></div>
                <div id="article-head-details" style="display: flex; gap:10px; flex-direction:column;">
                    <input type="text" id="article-mode" hidden>
                    <input type="text" id="article-id" hidden>
                    <input type="text" id="article-title" placeholder="Title">
                    <div style="display: flex; flex-direction:column; gap:10px;">
                        <div style="display: flex; flex-direction:column; width:100%;">
                            <div style="display: flex; gap:10px;" class="article-category-update">
                                <input type="text" id="article-original-category" hidden>
                                <select id="article-category" class="article-category-update">
                                   <option id="article-originally-selected-category" selected></option>
                                </select>
                                <img src="<?php echo $publicFolder.'/assets/images/minus.png'?>" class="icon article-category-update" id="article-category-delete-submit-button" style="display: none;">
                            </div>
                            <div style="display: flex; gap:10px;" class="article-category-add">
                                <input type="text" placeholder="Add category..." class="new article-category-add" id="article-category-add-input">
                                <div>
                                    <img src="<?php echo $publicFolder.'/assets/images/plus.png'?>" class="icon new article-category-add" id="article-category-add-submit-button">
                                    <img src="<?php echo $publicFolder.'/assets/images/close.png'?>" class="icon new article-category-add" id="article-category-add-close">
                                </div>
                            </div>
                        </div>
                        <div style="display: flex; flex-direction:column;width:100%;">
                            <div style="display: flex; gap:10px;">
                                <input type="text" id="article-original-topic" hidden>
                                <select id="article-topic" class="article-topic-update">   
                                    <option id="article-originally-selected-topic" selected></option>  
                                </select>
                                <img src="<?php echo $publicFolder.'/assets/images/minus.png'?>" class="icon article-topic-update" id="article-topic-delete-submit-button" style="display: none;">
                            </div>

                            <div style="display: flex; gap:10px;" class="article-topic-add">
                                <input type="text" placeholder="Add topic..." class="new article-topic-add" id="article-topic-add-input">
                                <div>
                                    <img src="<?php echo $publicFolder.'/assets/images/plus.png'?>" class="icon new article-topic-add" id="article-topic-add-submit-button">
                                    <img src="<?php echo $publicFolder.'/assets/images/close.png'?>" class="icon new article-topic-add" id="article-topic-add-close">
                                </div>
                    
                            </div>
                        </div>

                        <div style="display: flex; flex-direction:column;width:100%;" id="article-version-container">
                            <div style="display: flex; gap:10px;">
                                <select id="article-version" class="article-version-update">     
                                    <option id="article-originally-selected-version" selected></option>  
                                </select>
                            </div>
                            
                        </div>

                    </div>
                   

                </div>
                

            </div>

            
            

        
                
        </div>

    </div>




    <?php //-----------------FOR ADDING/EDITING USERS----------------------------- ?>

    <div class="modal website-modal website-modal-wrapper" id="modal-add-edit-user">
        <div class="website-modal-content" style="width: 40%; height:fit-content; margin:auto;">
           
            <div class="close-modal-container">
                 <a class="close close-without-null-redirection">&times;</a>
            </div>

            <div id="user-message" class="alert alert-danger">
                
            </div>
            <div id="add-edit-user-container">
                <div id="user-names" style="display: flex;  gap:10px;" class="other-user-details">
                    <input type="text" id="user-first-name" placeholder="First Name">
                    <input type="text" id="user-last-name" placeholder="Last Name">
                    <input type="text" id="user-name" hidden>
                 </div>

                 <div id="user-credentials" style="display: flex;gap:10px;" class="other-user-details">
                    <input type="text" id="user-email-address" placeholder="Email Address">
                    <input type="text" id="user-username" placeholder="Username">
                 </div>

                 <div style="display: flex;gap:10px;" class="other-user-details">
                    <select id="user-type">
                        <option selected>Guest Writer</option>
                    </select>
                    <select id="user-status">
                        <option selected>Activate</option>
                        <option>Deactivate</option>
                    </select>
                 </div>

                 <div id="user-buttons" style="display: flex;gap:10px;">
                    <input type="text" id="current-user-id" hidden>
                    <button id="user-add-update-submit-button"></button>
                    <button id="user-delete-button">Delete</button>
                 </div>

                 
                

            </div>
           

        
                
        </div>

    </div>







    <?php //-----------------FOR EDITING PROFILE----------------------------- ?>

    <div class="modal website-modal website-modal-wrapper" id="modal-edit-profile">
        <div class="website-modal-content" style="width: 40%; height:fit-content; margin:auto;">
           
            <div class="close-modal-container">
                 <a class="close close-without-null-redirection">&times;</a>
            </div>

            <div id="user-message" class="alert alert-danger">
                
            </div>

             <textarea id="profile-description-edit" placeholder="Description" class="profile-edit" style="resize: none; height:100px;"><?php echo $registrantDescription;?></textarea>
            
            <input id="profile-first-name-edit" placeholder="First Name" class="profile-edit" value="<?php echo $firstName;?>">

            <input id="profile-middle-name-edit" placeholder="Middle Name" class="profile-edit" value="<?php echo $middleName;?>">

            <input id="profile-last-name-edit" placeholder="Last Name" class="profile-edit" value="<?php echo $lastName;?>">

            <input id="profile-username-edit" placeholder="Username" class="profile-edit" value="<?php echo $username;?>">

            <input id="profile-email-address-edit" placeholder="Email Address" class="profile-edit" value="<?php echo $emailAddress;?>">

            <select id="profile-account-type-edit" class="profile-edit">
                <option value="" selected hidden>Select Type</option>

                <?php if ($type=='Owner') {?>
                <option value="Owner" <?php if ($type=='Owner'){echo 'selected';}?>>Owner</option>
                <?php } ?>

                <option value="Guest Writer" <?php if ($type=='Guest Writer'){echo 'selected';}?>>Guest Writer</option>
            </select>
            <button id="profile-submit-button">
                Update
            </button>
        
                
        </div>

    </div>










 
        
    










