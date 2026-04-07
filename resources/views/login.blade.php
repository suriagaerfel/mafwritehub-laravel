<x-main>
    @include('components/head')
    @include('components/header')


<body>
    
<div id="login-page" class="page form-page">
        <div class="form-page-content-container">

        @include('components/home-sidebar')
       

        <div  class="form-section">

            <div id="login-form">

                
                <h5 class="form-title">Login</h5>

                <div id="login-message" class="alert"></div>
                
                <div class="input-containers">
                <input type="text" placeholder="Email Address/Username" id="login-email-username">
                </div>


                <div class="inputContainers">
                <input type="password" placeholder="Password" id="login-password">
                </div>

                <div>
                <button id="login-submit-button">Submit</button>
                </div>
                <br>
                <span class="form-links">Forgot your password? <a href="{{route('get-password-reset-link')}}">Reset</a></span>
                <br>
                <span class="form-links">No account yet? <a href="{{route('create-account')}}">Create Account</a></span>
                <br>

            </div>

        </div>

        </div>

    

    </div>



@include('components/footer') 

</body>
</x-main>