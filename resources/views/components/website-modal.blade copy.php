<?php //---------------------------FOR INPUTS MESSAGE-------------------------------------?>

<div class="modal website-modal website-modal-wrapper" id="modal-inputs-message" style="z-index:1000000;">
    <div class="website-modal-content" style="width: 350px; margin-top:-200px; ">
        <div id="inputs-message">
           
        </div>
        <div style="display: flex; justify-content:center;">        
            <span class="link-tag-button" id="close-inputs-message-modal">OK</span>
        </div>
    
    </div>
</div>


<?php if ($registrantCode){?>
    <?php //---------------------------FOR CONFIRM LOGOUT-------------------------------------?>

    <div class="modal website-modal website-modal-wrapper" id="modal-confirm-logout" style="z-index:1000000;">
        <div class="website-modal-content" style="width: fit-content; margin-top:-200px; ">
            <div>
            <small>Are you sure you want to logout?</small>
            </div>
            <div style="display: flex; justify-content:middle; "> 
                <span id="confirmed-logout-button" class="link-tag-button active">Yes</span>       
                <span class="close-modal link-tag-button">Cancel</span>
            </div>
        
        </div>
    </div>


    <?php //---------------------------FOR WEBSITE UPDATES-------------------------------------?>

    <div class="modal website-modal website-modal-wrapper" id="modal-updates">
        <div class="website-modal-content" style="width: 400px;height:90%; margin-right:5px;">
            <div style="display: flex; justify-content:space-between;gap:5px;" id="update-filter">
                <div>
                    <strong>UPDATES</strong>
                </div>
            
                <div style="display: flex; flex-direction:row; justify-content:space-between !important; ">
                    <div style="margin-top:-10px; ">
                        <input type="search" style="height: 30px; " placeholder="Search update..." id="searched-update">
                    </div>
                    <?php if ($websiteManagerMessageManagerRegistration || $websiteManagerSuperManagerRegistration) {?>
                    <div style="margin-top: -8px; width:fit-content;">
                        <span class="link-tag-button" id="new-update">Add</span>
                    </div>   
                    <?php } ?>
                    <div class="close-button-container" style="display: flex; width:50px;">
                        <span class="close-modal" style="font-size: 12px;">✖</span> 
                    </div>
                </div>
                <hr>
            </div>
            
            <div id="updates-result" style="overflow:scroll; scrollbar-width:none;scrollbar-height:none;">
            
            </div>
            <div id="update-item" style="display: none; overflow:scroll; scrollbar-width:none;scrollbar-height:none;">
                <div id="update-item-actions" style="position: sticky;top:0;background-color:white;">
                    <span id="back-to-updates-result">
                        <img src="<?php echo $publicFolder.'/assets/images/return.png'?>">
                    </span>
                    <div id="edit-update-actions" style="display: none;">
                        <span id="to-publish-update-button" class="link-tag-button">Publish</span>
                        <span id="to-unpublish-update-button" class="link-tag-button">Unpublish</span>
                        <span id="to-duplicate-update-button" class="link-tag-button">Duplicate</span>
                        <span id="to-delete-update-button" class="link-tag-button">Delete</span>

                    </div>
                </div>
                <input type="text" id="update-id" placeholder="Update Id.." hidden>
                <input type="text" id="update-mode" placeholder="Update mode.." hidden>
                <select  id="update-type">
                    <option value="" selected hidden>Update Type</option>
                    <option value="Registration">Registration</option>
                    <option value="Subscription">Subscription</option>
                    <option value="Maintenance">Maintenance</option>
                </select>
                <input type="text" id="update-title" placeholder="Title...">
                <textarea id="update-content" placeholder="Content...." style="width: 100%;
                height:250px; resize:none;"></textarea>
                <button id="submit-update-button"></button>
            </div>
            <div id="update-read" style="display: none; overflow:scroll; scrollbar-width:none; scrollbar-height:none;">
                <div id="hide-update-read" style="position: sticky; top:0; background-color:white;">
                    <span>
                        <img src="<?php echo $publicFolder.'/assets/images/return.png'?>" style='width:25px;'>
                    </span>
                </div>
                <div id="update-read-details" style="display: none;">
                    <div style="display: flex; flex-direction:column;">
                        <strong id="update-read-title"></strong>
                        <small id="update-read-timestamp"></small>
                        <small id="update-read-viewers" style="display: none;"></small>
                        <div id="update-read-content" style="margin-top:10px; font-size:10pt;"></div>
                    </div>
                    
                </div>

            </div>
    
        </div>
    </div>



    <?php //---------------------------FOR CONFIRM DELETE UPDATE-------------------------------------?>

    <div class="modal website-modal website-modal-wrapper" id="modal-confirm-delete-update" style="z-index: 90000000;">
        <div class="website-modal-content">
            <small>Are you sure you want to delete?</small>

            <input type="text" id="delete-update-id" hidden>

            <hr>
            <div style="display: flex; flex-direction:row"> 
                <span class="link-tag-button" id="confirmed-delete-update-button">Yes</span>          
                <span class="link-tag-button" id="close-confirm-delete-update-modal-button">No</span>
            </div>
        
        </div>
    </div>






    <?php //---------------------------FOR SHOWING ALL CATEGORIES-------------------------------------?>


    <div class="modal website-modal website-modal-wrapper" id="modal-all-categories">
        <div class="website-modal-content" style="width: 350px; height:fit-content;">
            <div class="close-button-container">
                <span class="close-modal">✖</span>
            </div>
        <div id='delete-category-message'></div>
        <div id="all-categories-result">

        </div>
            
            


        </div>
    </div>





    <?php //---------------------------FOR SUBSCRIPTION-------------------------------------?>

    <div class="modal website-modal website-modal-wrapper" id="modal-subscription">
        <div class="website-modal-content" style="width: 350px; height:fit-content; min-height:400px;">
            <div class="close-button-container">
                <span class="close-modal">✖</span>
            </div>

            <div>

            <select id="subscription-type">
                    <option value="" selected hidden>Subscription Type</option>
                    <?php if ($type == 'Personal'){?>
                    <option value="Tools">Tools</option>
                    <?php if ($teacherRegistration){?>
                    <option value="Seller" id="seller-subscription">Seller</option>
                    <?php } ?>
                    <?php } ?>
                    <?php if ($type=='School') {?>
                    <option value="Shelf">Shelf</option>
                    <?php } ?>
            </select>

            </div>
            

            <div  id="modal-subscription-inputs">

                <div style="display: flex; gap:5px;">
                <input type="number" id="subscription-duration" placeholder="Duration" style="width: 30% !important;">
    
                <select id="subscription-payment-option" style="width: 70% !important;">
                        <option value="" hidden>Your Payment Option</option>
                        <option value="GCASH">GCASH</option>
                        <option value="Other">Other</option>
                </select>
                </div>
                <input type="text" placeholder="Your Reference Number"  id="subscription-reference-number">
                <label for="proofOfPayment">Proof of Payment [JPEG or JPG format]</label>
                <input type="file" id="subscription-proof-of-payment">

            
                <input id="subscription-total" hidden>
                <strong id="subscription-summary"></strong>
                <button id="submit-subscription-button">Submit</button>
            
    
            </div>
            <div id="modal-subscription-notes">
                <div style="display: flex;justify-content:center;" id="subscription-message-container">
                    <small id="subscription-message"></small>
                </div>
            
                <div id="modal-subscriptions-list">

                </div>
                <div id="modal-subscription-other-details" style="display: none;">
                    <div>
                        <span id="hide-subscription-other-details-button">
                            <img src="<?php echo $publicFolder.'/assets/images/return.png'?>" style="width: 25px;">
                        </span>
                    </div>
                
                    <div style="display: flex; flex-direction:column;">
                        <small id="subscription-details-timestamp"></small>
                        <small id="subscription-details-type"></small>
                        <small id="subscription-details-duration"></small>
                        <small id="subscription-details-total"></small>
                        <small id="subscription-details-payment-option"></small>
                        <small id="subscription-details-reference-code"></small>
                        <small id="subscription-details-status"></small>
                        <small id="subscription-details-activated"></small>
                        <small id="subscription-details-expired"></small>
                        <small id="subscription-details-remaining-days"></small>
                        <a id="subscription-details-payment-proof" target="_blank" class="link-tag-button" style="margin-left:-5px;margin-top:5px;">PROOF OF PAYMENT</a>
                    </div>

                </div>

                <div id="modal-subscription-payment-proof-view-container" style="display: none;">
                    <div>
                        <span id="show-subscription-other-details-button">
                            <img src="<?php echo $publicFolder.'/assets/images/return.png'?>" style="width: 25px;">
                        </span>
                    </div>
                
                    <div style="display: flex; flex-direction:column;">
                    <img id="modal-subscription-payment-proof-view">
                    </div>

                </div>

            </div>

            
        
        </div>
        

            
    
    </div>

    <?php //---------------------------FOR OTHER REGISTRATION-------------------------------------?>

    <div class="modal website-modal website-modal-wrapper" id="modal-other-registration">
        <div class="website-modal-content"> 
            <div class="close-button-container">
                <span class="close-modal">✖</span>
            </div>
            <div>
                <input type="text" id="regtype-hidden" hidden>
            </div>
                        
            <div id="modal-other-registration-navigation">
                        <?php if ($type=='Personal') {?>
                        <small>Register as:</small>
                        <?php } ?>

                        <?php if ($type=='School') {?>
                        <small>Register for:</small>
                        <?php } ?>

                        <div id="regtype-buttons">

                    
                        <?php if ($type=='Personal') {?>

                            <button class="link-tag-button" id="regtype-teacher-button">Teacher</button>

                            <button class="link-tag-button" id="regtype-writer-button">Writer</button>
                            
                            <button class="link-tag-button" id="regtype-editor-button">Editor</button>
                        
                            <button class="link-tag-button" id="regtype-developer-button">Developer</button>
                    

                        <?php } ?>


                        <?php if ($type=='School') {?>
                            <button class="link-tag-button" id="regtype-researches-button">Researches</button>
                        <?php } ?>

                        </div>
            

            </div>
            
            
            <small id="other-registration-message" style="display: none;margin-top:-15px;">Message</small>
            
            
            <div id="modal-other-registration-inputs-container">
                <div>
                    <a class="link-tag-button" id="download-agreement-form-link">Download Agreement Form</a>
                </div>

                <div id="other-registration-license-certification-container">
                <label for='license-certification'>License or Certification</label>
                <input type="file" id="license-certification">
                </div>

                <div id="other-registration-sample-container">
                <label for='sample'>Sample</label>
                <input type="text" id="sample">
                </div>
            
                <label for="agreement" class="other-registration-label" id="agreement-label">Agreement [PDF format]</label>
                <input type="file" title="Agreement Form" id="agreement" >
    
                <button id="register-button">Submit</button>
            </div>

            <div id="modal-other-registration-submitted-documents-container">
                    <small>Submissions: </small>
                    
                    <a class="link-tag-button" id="submitted-license-certification" target="_blank">License or Certification</a>
                                            
                    <a class="link-tag-button" id="submitted-sample" target="_blank">Sample (s)</a>
                
                    <a class="link-tag-button" id="submitted-agreement" target="_blank">Agreement</a>
            </div>
                    
                    
        </div>

    
    </div>

<?php } ?>




<?php if ($pageName=='Workspace - Teacher' || $pageName == 'School Workspace - Researches') {?>
<?php //---------------------------FOR PUBLISHING CONTENT-------------------------------------?>

<div class="modal website-modal website-modal-wrapper" id="modal-confirm-publish">
    <div class="website-modal-content" style="width: 300px;">
        <p>You can't replace the attached document anymore once published.</p>
        <p>Are you sure you want to publish?</p>
        <hr>
        <div style="display: flex; flex-direction:row"> 
            <span class="link-tag-button" id="confirmed-publish-button">Yes</span>          
            <span class="link-tag-button close-modal">No</span>
        </div>
    
    </div>
</div>
<?php } ?>




<?php if ($pageName=='Workspace - Website Manager') {?>
<?php //---------------------------FOR CONFIRM UPDATE SUPER MANAGER REGISTRATION-------------------------------------?>

<div class="modal website-modal website-modal-wrapper" id="modal-confirm-super-manager-registration">
    <div class="website-modal-content" style="width: 300px;">
        <small>Are you sure you wan to update?</small>
        <small>If yes, please provide your password to make sure it is not done by accident.</small>
        <div>
            <input type="text" id="confirm-super-manager-registration-record-id" hidden>
            <input type="text" id="confirm-super-manager-registration-website-manager-type" hidden>
            <input type="text" id="confirm-super-manager-registration-action" hidden>
            <input type="password" id="active-super-manager-password" placeholder="Type your password...">
        </div>
       
        <div style="display: flex; flex-direction:row;justify-content:center;"> 
            <span class="link-tag-button" id="confirmed-update-super-manager-registration-button">Continue</span>          
            <span class="link-tag-button close-modal">Close</span>
        </div>
    
    </div>
</div>



<?php //---------------------------FOR DELETING RECORD-------------------------------------?>

<div class="modal website-modal website-modal-wrapper" id="modal-confirm-delete-record">
    <div class="website-modal-content">
        <p>Are you sure you want to delete?</p>

        <input type="text" id="delete-record-id"hidden>
        <input type="text" id="delete-record-category" hidden>

        <hr>
        <div style="display: flex; flex-direction:row"> 
            <span class="link-tag-button" id="confirmed-delete-record-button">Yes</span>          
            <span class="link-tag-button close-modal">No</span>
        </div>
    
    </div>
</div>



<?php //---------------------------FOR ADDING/UPDATING PROMOTIONS-------------------------------------?>
<div class="modal website-modal website-modal-wrapper" id="modal-promotion">
    <div class="website-modal-content" style="height:fit-content;overflow:scroll; scrollbar-height:none;scrollbar-width:none;" id="modal-promotion-content">
        <div class="close-button-container">
            <span class="close-modal">✖</span>
        </div>
        <div id="promotion-message" class="alert alert-danger" style="display: none;"></div>
        <div id="modal-promotion-inputs-container">
            <div id="group-1">
                <input type="text" placeholder="Id" id="promotion-id" hidden>
                <input type="text" placeholder="Name/Company" id="promotion-name-company">
                <input type="text" placeholder="Title" id="promotion-title">
            
                <div>
                    <select id="promotion-type">
                        <option value="" hidden>Type</option>
                        <option value="Products">Products</option>
                        <option value="Services">Services</option>
                    </select>
                    <input type="text" placeholder="Topic (s)" id="promotion-topics">     
                </div>

                <textarea placeholder="Description" id="promotion-description"></textarea>
            </div>
    
            <div id="group-2">       
                <div id="promotion-days-amount">
                    <input type="number" placeholder="Number of Days" id="promotion-duration">
                    <input type="text" placeholder="Amount" id="promotion-amount">
                </div>

                <div>
                    <input type="text" placeholder="Link" id="promotion-link"> 
                </div>

                <div id="promotion-attachments">
                    <label for="promotion-image">Image</label>
                    <div style="display: flex;">
                        <input type="file" id="promotion-image-edit">
                        <input type="text" id="promotion-image-link" hidden>
                        <span class="link-tag-button" id="show-promotion-image-button"style="display: none;margin-top:10px;">
                            <img src="<?php echo $publicFolder.'/assets/images/image.svg'?>"  style="width: 30px;" >
                        </span>
                    </div>
                    <label for="promotion-agreement">Agreement</label>
                    <div style="display: flex;">
                        <input type="file" id="promotion-agreement">
                        <input type="text" id="promotion-agreement-link" hidden>
                        <span class="link-tag-button" id="show-promotion-agreement-button" style="display: none;margin-top:10px;">
                            <img src="<?php echo $publicFolder.'/assets/images/document.png'?>"  style="width: 30px;">
                        </span>
                    </div>
                </div>

                
            </div>

        </div>

        <button id="promotion-submit-button">Add</button>
       
    </div>
</div>





<?php //---------------------------FOR SHOWING PROMOTION IMAGE-------------------------------------?>


<div class="modal website-modal website-modal-wrapper" id="modal-promotion-image">
    <div class="website-modal-content" style="width: fit-content;">
       <div class="close-button-container" >
            <span id="close-modal-promotion-image" style="cursor: pointer;">✖</span>
        </div>
    
        <img id="promotion-image-view" style="width: 400px;">
    
    </div>
</div>



<?php //---------------------------FOR SHOWING PROMOTION AGREEMENT-------------------------------------?>

<div class="modal website-modal website-modal-wrapper" id="modal-promotion-agreement">
    <div class="website-modal-content" style="width: 90%; height:fit-content">
       <div class="close-button-container" >
            <span id="close-modal-promotion-agreement" style="cursor: pointer;">✖</span>
        </div>
    
        <iframe id="promotion-agreement-view" style="width:100%; height:70vh;"></iframe>
          
    </div>
</div>




<?php //---------------------------FOR SHOWING SUBSCRIPTION PROOF OF PAYMENT-------------------------------------?>


<div class="modal website-modal website-modal-wrapper" id="modal-subscription-proof-of-payment">
    <div class="website-modal-content" style="width: fit-content;">
       <div class="close-button-container" >
            <span id="close-modal-subscription-proof-of-payment" style="cursor: pointer;">✖</span>
        </div>
    
        <img id="proof-of-payment-view" style="width: 400px;">
    
    </div>
</div>




<?php //---------------------------FOR SHOWING OTHER REGISTRATION LICENSE/CERTIFICATION-------------------------------------?>

<div class="modal website-modal website-modal-wrapper" id="modal-other-registration-license-certification">
    <div class="website-modal-content" style="width: 90%; height:fit-content">
       <div class="close-button-container" >
            <span id="close-modal-other-registration-license-certification" style="cursor: pointer;">✖</span>
        </div>
    
        <iframe id="other-registration-license-certification-view" style="width:100%; height:70vh;"></iframe>
          
    </div>
</div>


<?php //---------------------------FOR SHOWING OTHER REGISTRATION AGREEMENT-------------------------------------?>

<div class="modal website-modal website-modal-wrapper" id="modal-other-registration-agreement">
    <div class="website-modal-content" style="width: 90%; height:fit-content">
       <div class="close-button-container" >
            <span id="close-modal-other-registration-agreement" style="cursor: pointer;">✖</span>
        </div>
    
        <iframe id="other-registration-agreement-view" style="width:100%; height:70vh;"></iframe>
          
    </div>
</div>






<?php } ?>






<?php if ($pageName == 'My Account') {?>




    <?php //-----------------FOR SHOWING WORKSPACE LIST----------------------------- ?>

    <div class="modal website-modal website-modal-wrapper" id="modal-workspace-list">
    <div class="website-modal-content"> 
            <div class="close-button-container">
                <span class="close-modal">✖</span>
            </div>
            
            <div  class="workspace-list">

            <?php if ($type=='Personal') {?>
        
                <?php if($teacherRegistration) { ?>
                        <a href="<?php echo $publicFolder.'/workspace/teacher'?>" class="link-tag-button" target='_blank'>Teacher</a>
                <?php }?>

                <?php if($writerRegistration) { ?>
                        <a href="<?php echo $publicFolder.'/workspace/writer'?>" class="link-tag-button" target='_blank'>Writer</a>
                <?php }?>

                <?php if($editorRegistration) { ?>
                        <a href="<?php echo $publicFolder.'/workspace/editor'?>" class="link-tag-button" target='_blank'>Editor</a>
                <?php }?>

                <?php if($developerRegistration) { ?>
                        <a href="<?php echo $publicFolder.'/workspace/developer'?>" class="link-tag-button" target='_blank'>Developer</a>
                <?php }?>

                <?php if($websiteManagerRegistration) { ?>
                        <a href="<?php echo $publicFolder.'/workspace/website-manager'?>" class="link-tag-button" target='_blank'>Website Manager</a>
                <?php }?>

                <?php } ?>

                
                <?php if ($type=='School') {?>
                <a href="<?php echo $publicFolder.'/workspace/researches'?>" class="link-tag-button" target='_blank'>Researches</a>
                <?php } ?>
            </div>
        </div>
    </div>
<?php } ?>
















<?php if (str_contains($currentURL,'/workspace/')){?>

    <?php //---------------------------FOR DELETING CONTENT-------------------------------------?>

    <div class="modal website-modal website-modal-wrapper" id="modal-confirm-delete-content">
        <div class="website-modal-content">
            <p>Are you sure you want to delete?</p>

            <input type="text" id="delete-content-id" hidden>
            <input type="text" id="delete-sub-content-id" hidden>

            <hr>
            <div style="display: flex; flex-direction:row"> 
                <span class="link-tag-button" id="confirmed-delete-content-button">Yes</span>          
                <span class="link-tag-button close-modal">No</span>
            </div>
        
        </div>
    </div>


    <?php //---------------------------FOR SHOWING CONTENT IMAGE-------------------------------------?>


    <div class="modal website-modal website-modal-wrapper" id="modal-show-image">
        <div class="website-modal-content">
        <div class="close-button-container">
                <span class="close-modal">✖</span>
            </div>
        
        
            <img id="image">
        
            <hr>

            <a class="link-tag-button" id="change-image-button"></a> 
        


        </div>
    </div>



    <?php //---------------------------FOR SHOWING CONTENT-------------------------------------?>

    <div class="modal website-modal website-modal-wrapper" id="modal-show-content">
        <div class="website-modal-content" style="width: fit-content; height:fit-content;">
            <div class="close-button-container">
                <span class="close-modal">✖</span>
            </div>
        
            <div id="replace-content-message" class="alert alert-success" style="display: none;"></div>

            <iframe src="" frameborder="0" id="content-iframe" style="width:80vw; height:70vh;"></iframe> 

            <div id="replace-content-container" style="display: none; width: 350px;" class="replace-active">
                <input type="file" id="new-content">
            </div>

            

            <div style="display: flex;" class="replace-active">
                <span id="replace-content-submit-button" class="link-tag-button" style="display: none;">REPLACE</span> 
                <span class="link-tag-button" id="retain-content-button" style="display: none;">RETAIN</span> 
            </div> 
        
            <span class="link-tag-button" id="replace-content-button">REPLACE</span>
            


        </div>
    </div>



    <?php //---------------------------FOR COMMENTS-------------------------------------?>

    <div class="modal website-modal website-modal-wrapper" id="modal-comment">
        <div class="website-modal-content" style="width: 400px;height:500px;display:flex;flex-direction:column;justify-content:space-between;">
            <div class="close-button-container">
                <span class="close-modal" style="font-size: 10px;">CLOSE</span> 
            </div>
            <div id="comments-result" style="height: 400px; max-height:430px; overflow:scroll;scrollbar-width: none; scrollbar-height: none; display:flex;flex-direction:column-reverse;gap:20px; overflow-y: auto;position:sticky;top:0; ">
            
            </div>
            <div style="width: 100%;">
                <textarea id="content-comment" style="resize: none;height:70px;width:100%;" placeholder="What's on your mind?"></textarea>
                <button id="submit-comment">Send</button>
            </div>
    
        </div>
    </div>

    <?php if ($pageName == 'Workspace - Teacher') {?>

    <?php //---------------------------FOR PURCHASES-------------------------------------?>

    <div class="modal website-modal website-modal-wrapper" id="modal-purchases">
        <div class="website-modal-content" style="width: fit-content; height:90%;">
            <div class="close-button-container">
                <span class="close-modal">✖</span>
            </div>
            <input type="text" id="filtered-status" hidden>
            <div id="purchases-status-filter" style="margin-bottom: 15px;">
                <small class="link-tag-button" id="purchase-status-all">All</small>
                <small class="link-tag-button" id="purchase-status-pending">Pending</small>
                <small class="link-tag-button" id="purchase-status-approved">Approved</small>
                <small class="link-tag-button" id="purchase-status-rejected">Rejected</small>
                <small class="link-tag-button" id="purchase-status-revoked">Revoked</small>
                <small class="link-tag-button" id="purchase-status-kept">Kept</small>
            </div> 
        
            <div id="purchases-list" style="width: 400px;" >
                
            </div>

                
        </div>
    </div>


    <?php //---------------------------FOR SELLER DETAILS-------------------------------------?>

    <div class="modal website-modal website-modal-wrapper" id="modal-seller-details">
        <div class="website-modal-content" style="width: fit-content; height:fit-content;">
            <div class="close-button-container">
                <span class="close-modal">✖</span>
            </div>
            <div id="purchases-status-filter" style="width:350px; margin-bottom: 15px;" >
                <div id="update-my-seller-details-message" class="alert alert-danger" style="display: none;"></div>
                <input type="text" id="my-payment-channel" placeholder="My payment channel...">
                <input type="text" id="my-account-name" placeholder="My account name...">
                <input type="text" id="my-account-number" placeholder="My account number...">
                <input type="text" id="my-review-schedules" placeholder="My review schedule...">
                <button id="update-my-seller-details-submit-button">Update Details</button>
            </div>          
        </div>
    </div>

    <?php } ?>





<?php } ?>




<?php if ($pageName=='My Account' || str_contains($currentURL,'/workspace/')) {?>
<?php //---------------------------FOR UPLOADING IMAGE-------------------------------------?>

<div class="modal website-modal website-modal-wrapper" id="modal-upload-image">
    <div class="website-modal-content" style="width: 300px; display:flex;flex-direction:column; gap:10px;">
        <div class="close-button-container">
             <span class="close-modal">✖</span>
        </div>
       

            <small class='modal-replace-image-warning'>
                Select an image with a JPEG or JPG format. 
                <br>The existing image will be deleted after the update.
                <?php if ($pageName != 'My Account') {?>
                <br>You can extract an image from your PDF document <a href="https://www.freeconvert.com/pdf-to-jpg" target="_blank"><strong style="font-size:10pt;">here</strong></a>.
                <?php } ?>

            </small> 
            
            <div id="modal-upload-image-message" class="alert alert-danger" style="margin-bottom:10px;"></div>
                      
            
            <input type="text" id="upload-type" hidden>
            <input type="text" id="upload-action-file" hidden>

            <input type="file" id="upload-image" class="attachment">

            <button id="upload-button"></button>

    </div>
</div>
<?php } ?>














































<?php //---------------------------FOR PURCHASING-------------------------------------?>

<div class="modal website-modal website-modal-wrapper" id="modal-purchase">
    <div class="website-modal-content" style="width: 400px;">
         <div class="close-button-container">
            <span class="close-modal">✖</span>
        </div> 
            <input type="text" id="purchase-processing-file" hidden>
            <small id="show-seller-details-button" class="link-tag-button">Show Seller's Details</small>
            <small id="show-purchase-details-button" class="link-tag-button" style="display: none;">Show Purchase Details</small>
            <div id="purchase-purchase-details">
        
                <p>
                    <strong>Title:</strong>
                    <span id="purchase-content-title"></span>
                </p>
                <br>
                <p>
                    <strong>Type:</strong>
                    <span id="purchase-content-type"></span>
                </p>
                <br>
                 <p style="display: none;">
                    <strong>Id:</strong>
                    <span id="purchase-content-id"></span>
                </p>
              
                <p style="display: none;">
                    <strong>Seller Id:</strong>
                    <span id="purchase-content-seller-id"></span>
                </p>
              

                <p>
                    <strong>Seller:</strong>
                    <span id="purchase-content-seller-name"></span>
                </p>
                <br>

                 <p>
                    <strong>Seller Code:</strong>
                    <span id="purchase-content-seller-code"></span>
                </p>
                <br>

                <p>
                    <strong>Amount:</strong>
                    <span id="purchase-content-amount"></span>
                </p>
                <br> 
                <p>
                    <strong>Send to:</strong>
                    <span id="purchase-content-send-to"></span>
                </p>
                <br>   
                <p>
                    <strong>Purchase Review:</strong>
                    <span id="purchase-content-review"></span>
                </p>
                <br>  
                <div id="purchase-inputs">
                    <select id="purchase-content-payment-channel">
                        <option value="" hidden selected>My Payment Channel</option>
                        <option value="GCash">GCash</option>
                        <option value="PayMaya">PayMaya</option>
                    </select>
                    <input type="text" placeholder="Reference Number" id="purchase-content-reference-number">
                    <input type="file" id="purchase-content-payment-proof">
                    <button id="purchase-submit-button">Purchase</button>
                </div>
                <div id="purchase-message">

                </div>
            </div>

            <div id="purchase-seller-details" style="display: none;">
                <p>
                    <strong>Seller:</strong>
                    <span id="seller-name-shown"></span>
                </p>
             <br>
                <strong>*Seller Subscription</strong>
                <hr>
                <p>
                    <strong>Status:</strong>
                    <span id="subscription-status-shown"></span>
                </p>
                <br>

                <p>
                    <strong>Start:</strong>
                    <span id="subscription-start-shown"></span>
                </p>
                <br>

                <p>
                    <strong>Expiry:</strong>
                    <span id="subscription-expiry-shown"></span>
                </p>
                <br>

                <p>
                    <strong>Remaining Days:</strong>
                    <span id="subscription-remaining-days-shown"></span>
                </p>
                <br>

                 <p>
                    <strong>Active:</strong>
                    <span id="subscription-active-shown"></span>
                </p>
                
                
                
            </div>
            <div id="login-purchase-notice" style="display: none;">
                <p>Please <a href="<?php echo $publicFolder.'/login/';?>">login</a> to purchase.</p>
            </div>

            

     
       
    </div>
    
    </div>
</div>





































 
        
    










