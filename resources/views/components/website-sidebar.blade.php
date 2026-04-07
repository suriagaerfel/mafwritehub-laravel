
<div id="website-sidebar" style="display: none;">
   <input type="search" id="searched-promotion" placeholder="Search your ad by code...">
 
   <div id="promotions-list">

   </div>
   
    <div style="display: inline-block; justify-content:left;">
        <?php if ($registrantCode) {?>
            <span id="show-my-subscription-button" class="link-tag-button">Subscriptions</span>
            <span id="show-other-registration-button" class="link-tag-button">Other Registrations</span>
        <?php } ?>

        <?php if (!$registrantCode) {?>
            <a class="link-tag-button login" href="<?php echo $publicFolder.'/login';?>">Login</a>
            <a class="link-tag-button login" href="<?php echo $publicFolder.'/create-account';?>">Create Account</a>
        <?php } ?>

       
          
    </div>

    <?php if ($registrantCode) {?>
        <?php if ($websiteManagerSuperManagerRegistration) {?>
            <?php if (str_contains($currentURL, '/articles') || str_contains($currentURL,'/teacher-files') || str_contains($currentURL,'/researches') || str_contains($currentURL,'/tools')) {?>
            
                    <div style="display: flex; gap:10px; margin-top:-20px;">
                        <input type="text" id="new-category" placeholder="Type and press ENTER to add category...">
                        <small class="link-tag-button" id="show-all-categories">SHOW ALL CATEGORIES</small>
                    </div>
            
            <?php } ?>
        <?php } ?>
    <?php } ?>
   


</div>

