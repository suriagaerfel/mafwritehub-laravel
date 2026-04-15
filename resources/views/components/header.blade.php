
<input id="session-userid" value="{{$userId}}" hidden>
<input id="page-name" value="{{$pageName}}" hidden>
<input id="tempoary-session-userid" value="{{session('temporary-session-userid')}}" hidden>
<input type="text" id='public-folder' value="{{$publicFolder}}"hidden>

<?php if ($pageName != 'Dashboard') {?>
    <div id="header">
        <div style="width: fit-content !important;">
            <a  href="<?php echo $publicFolder;?>">Maf Write Hub</a>
        </div>
       
     
        <div id="desktop-tablet-categories-list-container">
         @include('components/categories-list')
       </div>
        <span id="search-article-button"><i class="bi bi-search" ></i></span>
        
        <div id="desktop-tablet-header-links-container" style="margin-top:-20px;">
         @include('components/header-links')
        </div>
       
        <span id="menu"><i class="bi bi-three-dots-vertical"></i></span>

       
       
    </div>
     <hr>
<?php } ?>
    @include ('components/website-modal')
   
      
        

       

    






