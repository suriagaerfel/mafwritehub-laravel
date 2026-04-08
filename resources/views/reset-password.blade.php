

<x-main>
<body>


@include('components/head')
@include('components/header')



<div id="change-password-page" class="page form-page" style="display: flex; flex-direction:column;padding:20px; margin:0px;  background-image: url(<?php echo $publicFolder.'/assets/images/home-image.jpg'?>);">
    
    <div class="form-page-content-container" >

            <div id="reset-password-form" class="form" >
             
                <h5 class="form-title">Change Password</h5>
                <input class="registrantInputs" type="password" placeholder="New password">
                <input class="registrantInputs" type="password" placeholder="Retype new password">
                <button>Reset Password</button> 

            </div>
   
    </div>

    @include('components/footer-links')


</div>



@include('components/footer-scripts')
</body>

</x-main>