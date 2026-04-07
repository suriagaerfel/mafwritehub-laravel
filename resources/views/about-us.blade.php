<?php


require '../../private/initialize.php';

$pageName ="About Us";


require (SECTION_PATH.'/head.php');


?>





<?php require (SECTION_PATH.'/header.php'); ?>

<div id="about-page" class="page" style="display: flex; flex-direction:column;padding:20px; margin:0px;  background-image: url(<?php echo $website.'/assets/images/home-image.jpg'?>);">
    
    <?php require (SECTION_PATH.'/page-articles.php'); ?>


</div>


<?php require (SECTION_PATH.'/footer-scripts.php');?>


</body>
</html>


