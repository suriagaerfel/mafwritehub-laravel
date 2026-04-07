<x-main>
    @include('components/head')
    @include('components/header')


<body>
    <div id="user-page" class="page with-sidebars-page">
    
  
        <div id="account-page-account-details" class="page-details account-details">    
                @include('components/profile')
            
                <input type="text" id="home-searched-user-show-shared" hidden> 
                <div id="account-contents-filter">
                    <small id="by-user-indicator" class="account-contents-indicator">Contents Owned</small>
                    <small class="link-tag-button" id="by-user-button" style="display: none;">Contents Owned</small>
                    <small id="show-shared-indicator" style="display: none;" class="account-contents-indicator">Contents by Others</small>
                    <small class="link-tag-button" id="show-shared-button">Contents by Others</small>
                </div>
                <div id="account-contents-list" >     </div>
                <div id="contents-list" class="page-details page-details-single-sidebar"> </div>
        </div>
                      

    </div>


@include('components/footer')

</body>
</x-main>
