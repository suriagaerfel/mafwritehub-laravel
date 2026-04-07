s
<?php

$pageName = "Reset Password";

$newPwd = isset($_SESSION ['newPassword'] ) ? $_SESSION ['newPassword'] : "";
$newPwdRetype = isset($_SESSION ['newPasswordRetype'] ) ? $_SESSION ['newPasswordRetype'] : "";
$token=isset($_GET['token']) ? $_GET['token'] : "";
$userIdReset=isset($_GET['userid']) ? $_GET['userid'] : "";

?>


<x-main>
    @include('components/head')
    @include('components/header')


<body>
<div id="change-password-page" class="page form-page">
    
    <div class="form-page-content-container">

       @include('components/home-sidebar')


        <div class="form-section">

            <?php if (isset ($_SESSION['reset-now'])) { ?>

            <div id="change-password-form">
                <div id="reset-password-message" class="alert alert-danger" style="display: none;"></div>
                <h5 class="form-title">Reset Password</h5>
                <input type="text" id="reset-user-code" value="{{$userCode}}">
                <input type="password" id='reset-new-password' placeholder="New Password">
                <input type="password" id='reset-new-password-retype' placeholder="Retype New Password">
            
                <button id="reset-password-submit-button">Reset Password</button> 

            </div>

            <?php } ?>

        </div>

    </div>




</div>

@include('components/footer')

</body>
</x-main>