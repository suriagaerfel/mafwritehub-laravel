<?php

//Initializing the paths.
require '../../private/initialize.php';

$pageName ="Terms of Use";

//The file for the header will be included in the page.
require (SECTION_PATH.'/head.php');






?>





<?php require (SECTION_PATH.'/header.php'); ?>


<div id="terms-page" class="page" style="display: flex; flex-direction:column;padding:20px; margin:0px;  background-image: url(<?php echo $website.'/assets/images/home-image.jpg'?>);">
    
    <?php require (SECTION_PATH.'/page-articles.php'); ?>

</div>







<?php require (SECTION_PATH.'/footer-scripts.php');?>


</body>
</html>


