<?php 

$title = htmlspecialchars($pageName);

$stmt = $conn->prepare("SELECT * FROM articles WHERE title = ?");
$stmt->execute([$title]);
$pageArticle = $stmt-> fetch();

if ($pageArticle) {
    $articleTitle = $pageArticle ['title'];
    $articleId = $pageArticle ['id'];
    $articleVersion = $pageArticle ['version'];

    $stmt= $conn->prepare("SELECT * FROM article_versions WHERE article_id=$articleId AND version=$articleVersion LIMIT 1");
    $stmt->execute();
   
    $version = $stmt->fetch();

     if ($version) {
        $articleBody =  $version['version_body'];
     }

    $articleStatus = $pageArticle ['status'];
} 


?>







<div class="page-details page-details-single-sidebar" style="padding:20px;"> 
        
        <div class="individual-content"> 
        <?php if ($pageArticle) {?> 
            <?php if ($articleStatus == 'Published') {?>
            <h1><h1><?php echo $title?></h1></h1>
            <hr>
            <div><?php echo $articleBody?></div>
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
        <a href="<?php echo $publicFolder.'/terms-of-use'?>"><strong>Terms of Use</strong></a>
        <?php } ?>

        <?php if ($pageName != 'Data Privacy') {?>
        <a href="<?php echo $publicFolder.'/data-privacy'?>"><strong>Data Privacy</strong></a>
        <?php } ?>

        <?php if ($pageName != 'About Us') {?>
        <a href="<?php echo $publicFolder.'/about-us'?>"><strong>About Us</strong></a>
        <?php } ?>
        </div>
    </div>


    </div>