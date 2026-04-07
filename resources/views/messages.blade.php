<x-main>
    @include('components/head')
    @include('components/header')


<body>
    @if ($loggedIn)
    <div id="message-page" class="page with-sidebars-page">
    <div style="width: 30%;overflow:scroll;scrollbar-width: none; scrollbar-height: none; display:flex;flex-direction:column; justify-content:space-between; padding-bottom:50px;" class="message-page-column" id="message-page-first-column">
     
        <div style="height:fit-content;background-color:white;position:fixed;top:0;left:0; margin-top:50px;width: inherit;border-right:blue solid 2pt;" id="message-filter">
            <div style="display: flex; justify-content:space-between;width: 100%;">
                <div style="display:flex; flex-direction:column;justify-content:center;padding-left:10px;">
                    <strong>MESSAGES</strong>
                </div>
                
                <div>
                    <input type="search" placeholder="Search a user..." id="message-searched-user" style="border:none;">
                </div>
   
            </div>
            <div>
                <input type="text" id="message-mode" placeholder="Message mode..." value='Personal'hidden>
            </div>
            <hr style="margin-top: -5px;">
            
        </div>
       
        
        <div id="message-threads" style="height:500px;margin-top:70px;">          
        </div>
       

        
        <div style="position: fixed;bottom:0; left:0;padding-top:5px;background-color:white;width: inherit;border-right:blue solid 2pt;">
        <?php if (!$websiteManagerMessageManagerRegistration && !$websiteManagerSuperManagerRegistration){?>
            <span class="link-tag-button" id="message-admin-button">EskQuip Support</span>
            <span id="unread-message-counter-message-manager-non-admin" style="display: none;" class="counter"></span>
        <?php } ?>
        <?php if ($websiteManagerMessageManagerRegistration || $websiteManagerSuperManagerRegistration){?>
        <div id="message-modes" style="display: flex; gap:10px; padding-left:10px;">
            <small>Mode:</small>   
            <span class="link-tag-button active" id="message-mode-personal-button">Personal</span>
            <span id="unread-message-counter-personal" style="display: none;" class="counter"></span>
            <span class="link-tag-button" id="message-mode-message-manager-button">Message Manager</span>
            <span id="unread-message-counter-message-manager-admin" style="display: none;" class="counter"></span>
        </div>
        <?php } ?>

        </div>
        

      
    </div>

    <div style="width: 70%; display:flex; flex-direction:column; justify-content:space-between;"class="message-page-column" id="message-page-second-column">
        <input type="text" id="thread-message-mode" value="Personal"hidden>
        <input type="text" id="admin-message-thread-code" placeholder="Admin message thread code..." value="<?php echo 'TOADMIN'.$registrantCode;?>"hidden>
        <input type="text" id="message-recipient-code" value="<?php echo $registrantCode;?>" hidden>
        <input type="text" id="message-sender-code" value="<?php echo $registrantCode;?>" hidden>
        <input type="text" id="seller-code" value="<?php if (isset($_GET['sc'])) {echo $_GET['sc'];} else {echo '';}?>" hidden>
        
        <div style="display: flex; gap:10px; border-bottom: #0ec9c0 solid 1pt; margin-bottom:-10px; padding-bottom:10px;justify-content:space-between;">
            <div style="display: flex; gap:10px;">
                <div>
                    <img src="<?php echo $profilePictureLink;?>" style="width: 50px;" id="message-recipient-profile-picture">
                </div>
                <div style="display: flex;flex-direction:column;">
                    <strong id="message-recipient-account-name"><?php echo $accountName;?></strong>
                    <small id="message-recipient-accounts" style="margin-top: 0px;;"><?php echo $type.' | '.$registrations;?></small>
                </div>
            </div>
            <div style="display: flex; justify-content:right;">
                <div>
                <img src="<?php echo $publicFolder.'/assets/images/settings.png'?>" style="width: 20px;">
                </div>
            </div>
        </div>
   
        <div id="thread-messages-list" style="height: 430px; max-height:430px; overflow:scroll;scrollbar-width: none; scrollbar-height: none; display:flex;flex-direction:column-reverse; gap:10px; overflow-y: auto;position:sticky;top:0; ">

        </div>
        <div style="display: flex; gap:5px; vertical-align:middle;" >
            <textarea id="message-content" placeholder="What's on your mind?" style="width: 100%; resize:none;"></textarea>
            <span style="margin-top: 5px; display:none;">
             
                <img src="<?php echo $publicFolder.'/assets/images/image.png'?>" style="width: 30px;height:auto;">
              
              
            </span>
            <span class='link-tag-button'style="width: fit-content; padding:10px; margin-top: 5px;" id="send-message-button">Send</span>
        </div>
    </div>


 
</div>

@endif

@if  (!$loggedIn)
    <h1>Please login</h1>

@endif

@include('components/footer')
</body>
</x-main>