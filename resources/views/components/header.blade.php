
<input id="session-userid" value="{{$userId}}" hidden>
<input id="page-name" value="{{$pageName}}" hidden>
<input id="tempoary-session-userid" value="{{session('temporary-session-userid')}}" hidden>
<input type="text" id='public-folder' value="{{$publicFolder}}"hidden>

<?php if ($pageName != 'Dashboard') {?>
    <div id="header">
        <div style="width: fit-content !important;">
            <a  href="<?php echo $publicFolder;?>">Maf Write Hub</a>
        </div>
       
     

        @include('components/categories-list')
       

        <div style="width: 300px;">
            <input type="search" id="article-search" placeholder="Search..."> 
            <div id="searched-articles-list" style="background-color: aliceblue;padding:5px; display:none;">
            </div>
        </div>
        <div style="width: 300px;">
            @include ('components/footer-socials')
        </div>

         <?php if (!$loggedIn){?>
                    <span class="login-button" style="background-color: #77CBDA;padding:5px;border-radius:10px;cursor:pointer;color:white;">Login</span>
                <?php } ?>

                <?php if ($loggedIn){?>
                    <div style="display: flex; gap:10px; justify-content:center;">
                    <span><a href="<?php echo $publicFolder.'/dashboard' ?>" target="_blank" style="background-color: #77CBDA;padding:5px;border-radius:10px;color:white;">Dashboard</a></span>
                    <span id="home-logout-button" style="background-color: #9F1717; padding:5px;border-radius:10px;color:white;cursor:pointer;">Logout</span>
                    </div>
        <?php } ?>
       
    </div>
     <hr>
<?php } ?>

    @include('components/website-modal')
      
        

       

    






