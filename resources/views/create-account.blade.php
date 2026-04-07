

<x-main>
    @include('components/head')
    @include('components/header')


<body>

<div id="create-account-page" class="page form-page">

    <div class="form-page-content-container">

        @include('components/home-sidebar')
       
        <div class="form-section" style="display: flex; flex-direction:column; gap: 20px; width:fit-content;">
           
                <div id="create-account-form" style="width: 350px;">
                    
                    <h5 class="form-title">Create Account </h5>
                
                    <nav class="create-account-type-navigation navigation">
                        <small>Account Type: </small>
                        <tr class="nav-list">
                            <a class="link-tag-button" id="create-account-personal-button">Personal</a>
                            <a class="link-tag-button" id="create-account-school-button">School</a>
                        </tr>
                    </nav>
                  

                  <div >

                    <div class="inputs-group inputs-group-school">  

                        <input id="create-school-name" placeholder="Name">
                        <select id="create-school-basic-account">">
                            <option value="" hidden>Category</option>
                            <option  value="Elementary School">Elementary School</option>
                            <option  value="Junior High School">Junior High School</option>
                            <option  value="Senior High School" >Senior High School</option>
                            <option  value="College or University" >College or University</option>
                            <option  value="Integrated School" >Integrated School</option>
                        </select>
                    </div>

                    

                   

                    <div class="inputs-group inputs-group-personal">
                       
                        <input type="text" placeholder="First Name" id="create-personal-first-name">
        
                        <input type="text" placeholder="Last Name" id="create-personal-last-name">
                       
                    </div>

                    <div class="inputs-group inputs-group-personal">

                        <input type="date" id="create-personal-birthdate"  autocomplete="off">
                        <select name="gender" id="create-personal-gender">
                            <option value="" hidden>Gender</option>
                            <option  value="Male">Male</option>
                            <option value="Female">Female</option>
                            <option value="Other Gender">Other Gender</option>
                            <option value="No Gender" >No Gender</option>
                            <option value="Hide Gender">Hide Gender</option>
                        </select>
                         
                    </div>

                 

                    <div class="inputs-group inputs-group-general">
                            <input type="text" placeholder="Email Address"id="create-email-address">
                            <input type="text"  placeholder="Username" id="create-username">   
                    </div>

                
                    <div class="inputs-group inputs-group-general">
                            <input type="password" name="pwd" placeholder="Password" id="create-password">
                            <input type="password" placeholder="Retype Password" id="create-password-retype">
                    
                    </div>
                    
                    <div class="inputs-group inputs-group-general">
                        <input type="text" id="create-type" hidden>
                        <button id="create-submit-button">
                            Submit
                        </button>
                    </div>
                    <br>
            

                    <span class="form-links"> Have an account already? 
                        <a href="<?php echo $publicFolder.'/login';?>">
                        Login
                        </a>
                    </span>
                    </div>
                </div>

        

            <?php if ($currentTimeZone !='Asia/Manila') {?>
                <div>
                    <small>Sorry, but we only accept registrants residing in the Philippines.</small>
                </div>
               
            <?php } ?>

        </div>

    </div>




</div>

@include('components/footer')

</body>
</x-main>