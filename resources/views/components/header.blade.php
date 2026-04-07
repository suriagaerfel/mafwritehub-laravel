    <input type="text" id="home-searched-user" value="<?php echo $user;?>"hidden>
    <input type="text" id="page-name" value="<?php echo $pageName;?>" hidden>
    <input type="text" id="registrant-id" value="<?php echo $registrantId;?>" hidden >
    <input type="text" id="account-type" value="<?php echo $type; ?>" hidden>
    <input type="text" id="registrant-code" value="<?php echo $registrantCode; ?>" hidden>
    <input type="text" id="account-name" value="<?php echo $accountName;?>"  hidden>
    <input type="text" id="private-folder" value="<?php echo $privateFolder;?>" hidden>
    <input type="text" id="public-folder" value="<?php echo $publicFolder;?>" hidden>

    <input type="text" id="teacher-registration" value="<?php echo $teacherRegistration;?>" hidden>

 



    <input type="text" id="wm-super-manager-registration" value="<?php echo $websiteManagerSuperManagerRegistration;?>" hidden>
    <input type="text" id="wm-registration-manager-registration" value="<?php echo $websiteManagerRegistrationManagerRegistration;?>" hidden>
    <input type="text" id="wm-subscription-manager-registration" value="<?php echo $websiteManagerSubscriptionManagerRegistration;?>" hidden>
    <input type="text" id="wm-message-manager-registration" value="<?php echo $websiteManagerMessageManagerRegistration;?>" hidden>
    <input type="text" id="wm-promotion-manager-registration" value="<?php echo $websiteManagerPromotionManagerRegistration;?>" hidden>

    <input type="text" id="tool-subscribed" value="<?php echo $toolSubscribed;?>" hidden>
    <input type="text" id="pending-tool-subscription" value="<?php echo $pendingToolSubscription;?>" hidden>
    <input type="text" id="file-subscribed" value="<?php echo $fileSubscribed;?>" hidden>
    <input type="text" id="pending-file-subscription" value="<?php echo $pendingFileSubscription;?>" hidden>
    <input type="text" id="seller-subscribed" value="<?php echo $sellerSubscribed;?>" hidden>
    <input type="text" id="pending-seller-subscription" value="<?php echo $pendingSellerSubscription;?>" hidden>

    <input type="text" id="shelf-subscribed" value="<?php echo $shelfSubscribed;?>" hidden>
    <input type="text" id="pending-shelf-subscription" value="<?php echo $pendingShelfSubscription;?>" hidden>




    
    

    <?php if (str_contains($currentURL, '/articles') || str_contains($currentURL,'/teacher-files') || str_contains($currentURL,'/researches') || str_contains($currentURL,'/tools')){ ?>
    <input type="text" id="content-slug" value="<?php echo $slug; ?>" hidden>
    <?php } ?>
    
   
    <div class="header" style="display: flex; justify-content:space-between;">
        <div class="logo-container">
            <a href="<?php echo $publicFolder;?>">
                <img src="<?php echo asset('images/EskQuip-new3.png');?>" alt="EskQuip logo" class="logo" title="EskQuip">
            </a>
        </div>

        <div class="page-navigation-container">
            @include('components/header-page-navigation')
        </div>
        <div style="margin-right: 5px; margin-left:5px;">
            <img src="{{ asset('images/caret-down.svg')}}" class="icon header-icon show-mobile-navigation" id="show-mobile-navigation" onclick="toggleMobileTabletNavigation()">

            <img src="{{ asset('images/caret-up.svg')}}" class="icon header-icon hide-mobile-navigation" id="hide-mobile-navigation" onclick="toggleMobileTabletNavigation()">
        </div>
        
            
        
        <?php if ($pageName !='Search'){ ?> 
        
        <div id="header-search-container" class="search-container" style="width: fit-content;margin-left:5px; margin-right:5px;">
            <a href="<?php echo $publicFolder.'/search'?>">
                <img src="{{ asset('images/header-search.svg')}}"  title="Search" style="width: 25px; cursor:pointer;">
            </a>
        </div>
        <?php }?>


       
        <?php if ($loggedIn) {?>
       

        <div id="account-loggedin" style="width: fit-content;">
                
                <a id="updates-button"><img src="{{ asset('images/update.svg')}}" class="header-icon" title="Updates"></a>
                
                <span id="unread-updates-counter" style="display: none;" class="counter"></span>
                
                
                <a ><img src="{{ asset('images/message.svg')}}" class="header-icon message" title="Messages"></a>

                
                <span id="unread-messages-counter" style="display: none;" class="counter"></span>
                
            
                <a href="<?php echo $publicFolder.'/account/'?>"><img src="<?=$profilePictureLink?>" class="header-icon header-profile-image" alt="<?php echo $accountName.' Profile Picture';?>"id='header-profile-picture'></a>

                <span class="header-link" id="header-account-name"><?php echo $accountName?></span>
                                                
                <a id="logout-button"><img src="{{ asset('images/logout.svg')}}" class="header-icon" title="Logout" ></a>
        
        </div>
        <?php } ?>
                            
                    

        <?php if ($pageName != 'Create Account' && $pageName != 'Login' && $pageName != 'Get Password Link') {?>
         <?php if (!$loggedIn) {?>
         <div style="display: flex; gap:5px;  width:fit-content;">

           
            <a href="<?php echo $publicFolder.'/login';?>" class="header-link">Login</a>

            <a class="header-link">/</a>

        
            <a href="<?php echo $publicFolder.'/create-account';?>" class="header-link">Create Account</a>
           
        </div>
         <?php } ?>


        <?php } ?>
   


    </div>

    

    <div class="page-navigation-container-mobile-tablet" id="page-navigation-container-mobile-tablet">
           @include ('components/header-page-navigation')
    </div>



    @include ('components/website-modal')