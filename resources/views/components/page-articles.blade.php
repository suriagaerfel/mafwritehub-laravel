<?php 

$title = htmlspecialchars($pageName);

$sqlPageArticle = "SELECT * FROM articles WHERE title = '$title'";
$sqlPageArticleResult = mysqli_query($conn,$sqlPageArticle);
$pageArticle = $sqlPageArticleResult-> fetch_assoc();

if ($pageArticle) {
    $articleTitle = $pageArticle ['title'];
    $articleId = $pageArticle ['id'];
    $articleContentVersion = $pageArticle ['content_version'];

    $sqlArticleVersionContent = "SELECT * FROM article_content_versions WHERE article_id=$articleId AND content_version=$articleContentVersion LIMIT 1";
     $sqlArticleVersionContentResult = mysqli_query($conn,$sqlArticleVersionContent);
     $versionContent = $sqlArticleVersionContentResult->fetch_assoc();

     if ($versionContent) {
        $articleContent =  $versionContent ['version_content'];
     }

    $articleStatus = $pageArticle ['status'];
} 


?>







<div class="page-details page-details-single-sidebar"> 
        
        <div class="individual-content"> 
        <?php if ($pageArticle) {?> 
            <?php if ($articleStatus == 'Published') {?>
            <h1><h1><?php echo $title?></h1></h1>
            <hr>
            <div><?php echo $articleContent?></div>
            <?php } ?>

            <?php if ($articleStatus != 'Published') {?>
            <h1><?php echo $title?></h1>
             <hr>
             <p>[Found but not published]</p>
            <?php } ?>
        <?php } ?>

        <?php if (!$pageArticle) {?>
            <h1><?php echo $title?></h1>
             <hr>
            <p>[Not found]</p>
        <?php } ?>
        </div>

        <hr>

    <div class="page-links-container" style="display: flex; gap:10px;">
        <span>Read : </span>

        <div style="display: flex; gap:20px;">
        <?php if ($pageName != 'Terms of Use') {?>
        <a href="<?php echo $website.'/terms-of-use/'?>"><strong>Terms of Use</strong></a>
        <?php } ?>

        <?php if ($pageName != 'Data Privacy') {?>
        <a href="<?php echo $website.'/data-privacy/'?>"><strong>Data Privacy</strong></a>
        <?php } ?>

        <?php if ($pageName != 'About Us') {?>
        <a href="<?php echo $website.'/about-us/'?>"><strong>About Us</strong></a>
        <?php } ?>
        </div>
    </div>


    </div>