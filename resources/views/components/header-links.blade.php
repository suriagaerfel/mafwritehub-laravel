<div style="display:flex; justify-content:space-between; gap:25px; padding-top:20px;">

      
    @include ('components/socials')

    <?php if (!$loggedIn){?>
    <span class="login-button link-tag-button" style="cursor:pointer;color:white;" >Login</span>
    <?php } ?>

    <?php if ($loggedIn){?>
    <div style="display: flex; gap:15px; justify-content:center;">
        <span>
            <a href="<?php echo $publicFolder.'/dashboard' ?>" target="_blank" style="color:white;cursor:pointer;" class="link-tag-button">
                Dashboard
            </a>
        </span>
        <span id="home-logout-button" style="color:white;cursor:pointer;" class="link-tag-button">
            Logout
        </span>
    </div>
    <?php } ?>
</div>