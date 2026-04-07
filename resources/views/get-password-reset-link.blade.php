
<?php 
$pageName = 'Get Password Reset Link';
$credential = isset($_SESSION['email_username']) ? $_SESSION['email_username'] :"";

?>

<x-main>
    @include('components/head')
    @include('components/header')

<body>

<div id="get-reset-link-page" class="page form-page">

    <div class="form-page-content-container">

        @include('components/home-sidebar')

        <div class="form-section">

            <div id="get-link-form" >
                   
                <div id="get-reset-password-link-message" class="alert alert-danger" style="display: none;"></div>
                <h5 class="form-title">Provide Details</h5>
                <input type="text" id="get-reset-password-link-credential" placeholder="Email address o username">
               
                <button id="get-password-reset-link-submit-button">Get Password Reset Link</button> 
                 <br><br>
                <span class="form-links">Rembered your password? <a href="{{route('login')}}">Login</a></span> 
            </div>
        </div>

    </div>





</div>

@include('components/footer')

</body>
</x-main>