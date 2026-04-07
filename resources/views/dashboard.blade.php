


<x-main>

    @include ('components/head')
    @include ('components/header')

    <div id="dashboard-page" class="page" style="background-image: url(<?php echo $publicFolder.'/assets/images/home-image.jpg'?>);">
        <div id="dashboard-menus" class="dashboard-sections" style="width: 50px; height:max-content; display:flex; flex-direction:column;gap:20px;">
            <a href="<?php echo $publicFolder;?>"><img src="<?php echo $publicFolder.'/assets/images/home.png'?>" class="icon"></a>
            
            <?php if ($type == 'Owner') {?>
                <img src="<?php echo $publicFolder.'/assets/images/settings.png'?>" class="icon">
            <?php } ?>

                <img src="<?php echo $publicFolder.'/assets/images/logout.png'?>" class="icon" id="dashboard-logout-button">
            </span>
          

        </div>
        <div id="dashboard-profile" class="dashboard-sections" style="justify-content:space-between;">
            <div id="profile-head-picture" style="display: flex; justify-content:space-between; align-content:center;" >
                <h2>Profile</h2>
                <div style="display:flex;flex-direction:column;">
                    <img src="<?php echo $profilePictureLink; ?>" style="width: 80px; height:80px;border-radius:25%; border-radius:25%;">
                   <div style="display: flex; justify-content:right; margin-top:-25px; padding-right:5px;">
                    <img src="<?php echo $publicFolder.'/assets/images/camera.svg'?>" class="icon" id="profile-picture-camera-button">
                    </div>
                   
                </div>
            </div>
            <hr style="margin-bottom: 5px;">
            <em id="profile-description-view"></em>
            <br>
           
            <p id="profile-first-name-view"></p>
            

            <p id="profile-middle-name-view"></p>
            

            <p id="profile-last-name-view"></p>
            

            <p id="profile-username-view"></p>
            

            <p id="profile-email-address-view"></p>
            

            <p id="profile-account-type-view"></p>

            

            <br>

            <div id="dashboard-profile-action-buttons" style="display: flex; gap:20px; justify-content:right;">
          
                <img src="<?php echo $publicFolder.'/assets/images/edit.png'?>" class="icon dashboard-profile-view" id="dashboard-profile-action-edit-button">
            
            </div>

        </div>

        <div id="dashboard-articles" class="dashboard-sections" style="justify-content:space-between; ">
            <h2>Articles</h2>
           
         
            <div style="display: flex; gap:10px;" id="dashboard-article-filter">
                <?php if ($type == 'Owner') {?>
                    <select id='dashboard-article-author'>
                    </select>
                <?php } ?>

                <input type=search placeholder='Search articles...' id='dashboard-article-search'>
                
            </div>

           

             
            
            <div style="margin-top: 20px; height:320px;" id="dashboard-articles-list" >
            
            </div>
            
            
              <div style="display: flex; gap:10px; justify-content:right;">
                <hr>
                <small id="article-current-page-show" class="article-pages-show"></small>
                <small id="article-pages-show" class="article-pages-show"></small>
                
                <span class="link-tag-button article-pagination" id="article-previous-page">Prev</span>
                <span class="link-tag-button article-pagination" id="article-next-page">Next</span>
                <span class="link-tag-button" id="article-add-button">Add</span>
              </div>

        </div>

        <?php if ($type == 'Owner') {?>
        <div id="dashboard-other-users" class="dashboard-sections">
            <h2>Other Users</h2>
            <div style="display: flex; gap:10px;" id="dashboard-user-filter">
                

                <input type=search placeholder='Search users...' id='dashboard-user-search'>
                
            </div>

            <div style="margin-top: 20px; height:320px;" id="dashboard-users-list" >
            
            </div>
               
                   
              <div style="display: flex; gap:10px; justify-content:right;">
                    <hr>
                    <small id="user-current-page-show" class="user-pages-show"></small>
                    <small id="user-pages-show" class="user-pages-show"></small>
                    
                    <span class="link-tag-button user-pagination" id="user-previous-page">Prev</span>
                    <span class="link-tag-button user-pagination" id="user-next-page">Next</span>

                    <span class="link-tag-button" id="user-add-button">Add</span>
            </div>

        </div>
        <?php } ?>
    
                                

    </div>


    

    @include('components/footer-scripts')

</body>
</x-main>