



<div class="workspace-sidebar">
        <h5 id="workspace-title"></h5>

        @include('components/workspace-search')
       
        <?php if ($pageName == 'Workspace - Teacher') {?>
        <?php if ($sellerSubscribed) {?>
                <span class="link-tag-button" id="show-content-purchases-button">Purchases</span>
                <span class="link-tag-button" id="show-my-seller-details-button">Seller Details</span>
        <?php } ?>
        <?php }?>

       

        <?php if ($pageName == 'Workspace - Editor') {?>
               <input type="text" id="articles-availability" placeholder="Articles availability..." hidden>
               <span class="link-tag-button active" id="available-articles-button">Available</span>
               <span class="link-tag-button" id="taken-articles-button">Taken</span>
        <?php }?>

       

      
        <div id="sidebar-contents-list" style="margin-top: 10px;">
        </div>
</div>