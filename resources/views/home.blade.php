<x-main>
    @include('components/head')
    @include('components/header')


<body>

    <div id="home-page" class="page with-sidebars-page with-single-sidebar-page" style="padding-top:20px;">
        <div style="display: flex; flex-direction:column;">
            <div id="home-page-account-details" class="page-details account-details" style="display:none;margin-top:-65px;">  
            @include ('components/profile') 
                <input type="text" id="home-searched-user-show-shared" hidden> 
                <div id="account-contents-filter">
                    <small id="by-user-indicator" class="account-contents-indicator"></small>
                    <small class="link-tag-button" id="by-user-button" style="display: none;"></small>
                    <small id="show-shared-indicator" style="display: none;" class="account-contents-indicator"></small>
                    <small class="link-tag-button" id="show-shared-button" ></small>
                </div>
                <div id="account-contents-list" >     
                </div>
            </div>

            <div id="contents-list" class="page-details page-details-single-sidebar">     
            </div>
            
            
        </div>
        
    

    @include ('components/website-sidebar')

    </div>

    @include ('components/footer')

 </body>

</x-main>