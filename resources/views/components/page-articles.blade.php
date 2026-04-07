
<?php 
$title = htmlspecialchars($pageName);

$sqlPageArticle = "SELECT * FROM writer_articles WHERE writer_articleTitle = '$title'";
$sqlPageArticleResult = mysqli_query($conn,$sqlPageArticle);
$pageArticle = $sqlPageArticleResult-> fetch_assoc();

if ($pageArticle) {
    $articleTitle = $pageArticle ['writer_articleTitle'];
    $articleContent = $pageArticle ['writer_articleContent'];
    $articleStatus = $pageArticle ['writer_articleStatus'];
} ?>


<div id="page" class="page with-sidebars-page with-single-sidebar-page"  style="padding-top:20px;">

    <div class="page-details page-details-single-sidebar"> 
                        
        <?php if ($pageArticle) {?> 
            <div class="individual-content"> 
                <?php if ($articleStatus == 'Published') {?>
                    <h1><?php echo $articleTitle?></h1>
                    <hr>
                    <div><?php echo $articleContent?></div>
                <?php } ?>

                <?php if ($articleStatus != 'Published') {?>
                <h1><?php echo $articleTitle;?></h1>
                <hr>
                <p>We found the content. But it is currently not published.</p>
                <?php } ?>
            </div>
        <?php } ?>

        <?php if (!$pageArticle) {?>
                <h1>Is your title correct?</h1>
                <hr>
                <p>Sorry, but we cannot find the content you look for.</p>
        <?php } ?>
        

        <hr>

        <div class="page-links-container">
            <span>Read : </span>

            <?php if ($pageName != 'Terms of Use') {?>
            <a href="<?php echo $publicFolder.'/terms-of-use/'?>"><strong>Terms of Use</strong></a>
            <?php } ?>

            <?php if ($pageName != 'Data Privacy') {?>
            <a href="<?php echo $publicFolder.'/data-privacy/'?>"><strong>Data Privacy</strong></a>
            <?php } ?>

            <?php if ($pageName != 'About Us') {?>
            <a href="<?php echo $publicFolder.'/about-us/'?>"><strong>About Us</strong></a>
            <?php } ?>
        </div>


    </div>

   @include('components/website-sidebar')
  

</div>

