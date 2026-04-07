<div id="profile">
        <div id="profile-top" style="margin-top: 20px;">
                
                <div id="cover-photo-container" style="height:fit-content;">
                    <?php if ($user) {?>
                   <img id="cover-photo" src="{{$user_coverPhotoLink}}">
                   <?php } ?>
                    
                    <?php if (!$user) {?>
                    <?php if ($registrantCode){?>
                     <img id="cover-photo" src="{{$coverPhotoLink}}">
                    <div id="cover-photo-camera-container">
                        <img src="<?php echo asset('/images/camera.svg');?>" id="cover-photo-camera-icon" class="icon profile-details-icon">
                    </div>
                    <?php } ?>
                    <?php } ?>
                </div>
                
              
                <div id="profile-picture-summary">
                    <div id="profile-picture-container">
                         <?php if ($user) {?>
                        <img id='profile-picture' src="{{$user_profilePictureLink}}"> 
                        <?php } ?>
                      
                        <?php if (!$user) {?>  
                        <?php if ($registrantCode){?>
                             <img id='profile-picture' src="{{$profilePictureLink}}"> 
                            <div id="profile-picture-camera-container">
                                <img src="<?php echo asset('images/camera.svg');?>" 
                                id="profile-picture-camera-icon" class="icon profile-details-icon">
                            </div>
                            <?php } ?>
                        <?php } ?>
                    </div>
                    <div id="profile-summary">
                        <?php if (!$user) {?>
                        <h4 >{{$accountName}}</h4>
                        <p>{{$registrations}}</p>
                        <?php } ?>

                        <?php if ($user) {?>
                         <h4 >{{$user_accountName}}</h4>
                        <p>{{$user_registrations}}</p>
                        <?php } ?>
                    </div>
                </div>
        </div>

        <?php if ($user){?>
        <div style="margin-top: 80px;">
             <em class="profile-details-view" id="profile-description-view-searched">{{$user_registrantDescription}}</em>
        </div>
        <?php } ?>
       
        <?php if ($registrantCode){?>
        <?php if (!$user){?>
        <div id="profile-details">    
            <div id="profile-details-top">
                <h5 id="details">My Details</h5>
                <?php if ($registrantCode){?>
                <div id="edit-profile-details-button">
                    <img src="<?php echo asset('/images/edit.svg');?>">
                </div> 
                <?php } ?>
            </div>

            <em class="profile-details-view" id="profile-description-view">{{$registrantDescription}}</em>
        
            <hr>

            <div id="profile-details-bottom">
                <div class="profile-details-group">
                       
                        <?php if ($type=='Personal') { ?>
                            
                            <p class="profile-details-view" id="profile-first-name-view">First Name:  {{$firstName}}</p>
                        
                            <p class="profile-details-view" id="profile-middle-name-view">Middle Name:  {{$middleName}}</p>
                            
                            <p class="profile-details-view" id="profile-last-name-view">Last Name: {{$lastName}}</p>
                        
                        <?php } ?>

                        <?php if($type=='School') {?>
                            <p class="profile-details-view" id="profile-school-name-view">Name: {{$accountName}}</p>
                            <p class="profile-details-view" id="profile-school-category-view">{{$basicRegistration}}</p>      
                        <?php } ?>

                        <p class="profile-details-view" id="profile-username-view">Username: {{$username}}</p>
                        <p class="profile-details-view" id="profile-email-address-view">Email Address: {{$emailAddress}}</p>
                        <p class="profile-details-view" id="profile-mobile-number-view">Mobile Number {{$mobileNumber}}</p>
                </div>

                <?php if ($type=='Personal') { ?>
                <div class="profile-details-group" > 
                        <p class="profile-details-view" id="profile-birthdate-view">Birthdate: {{$birthdate}}</p>  
                        <p class="profile-details-view" id="profile-gender-view">Gender: {{$gender}}</p>                           
                        <p class="profile-details-view" id="profile-civil-status-view">Civil Status: {{$civilStatus}}</p>
                        <p class="profile-details-view" id="profile-educational-attainment-view">Eductional Attainment: {{$education}}</p>
                        <p class="profile-details-view" id="profile-school-view">School: {{$school}}</p>
                        <p class="profile-details-view" id="profile-occupation-view">Occupation: {{$occupation}}</p>
                </div>

                <?php } ?>
 
                <div class="profile-details-group">
                        
                    <p class="profile-details-view" id="profile-region-view">Region: {{$region}}</p> 
                    <p class="profile-details-view" id="profile-province-state-view">Province/State: {{$province_state}}</p>
                    <p class="profile-details-view" id="profile-city-municipality-view">City/Municipality: {{$city_municipality}}</p>
                    <p class="profile-details-view" id="profile-barangay-view">Barangay: {{$barangay}}</p>
                    <p class="profile-details-view" id="profile-street-subd-village-view">Street/Subd./Village: {{$street_subd_village}}</p>  
                </div>
                
            </div>

    </div>        
    <?php } ?>     
    <?php } ?>
</div>









            

          






