<?php 

$featuredCategory = isset($_GET['category'])? htmlspecialchars($_GET['category']) : '';
$featuredTag = isset($_GET['tag'])? htmlspecialchars($_GET['tag']) : '';
$featuredDate = isset($_GET['date'])? htmlspecialchars($_GET['date']) : '';
$featuredWriter= isset($_GET['writer'])? htmlspecialchars($_GET['writer']) : '';
$featuredSlug = isset($_GET['slug']) ? urlencode($_GET['slug']) : '';

 if ($featuredSlug) {

        $sqlArticleInfo = "SELECT * FROM articles WHERE slug='$featuredSlug'";
        $sqlArticleInfoResult = mysqli_query($conn,$sqlArticleInfo);
        $articleInfo = $sqlArticleInfoResult->fetch_assoc();

        //If the article exists, assign values to the variables.
        $articleWriterId="";
        $articleTitle="";
        $articleImage="";
        $articleCategory="";
        $articleTopic="";
        $articleContent="";
        $articleWriter="";
        $articleUpdateDate="";

        if ($articleInfo) {
            $articleId = $articleInfo ['id'];
            $articleWriterId = $articleInfo ['writer_id'];

            $sqlWriterInfo = "SELECT * FROM users WHERE id = $articleWriterId";
            $sqlWriterInfoResult = mysqli_query($conn,$sqlWriterInfo);
            $writerInfo = $sqlWriterInfoResult->fetch_assoc();
            if($writerInfo) {
                $articleWriterName = $writerInfo ['name'];
                $articleWriterDescription = $writerInfo ['description'];
                $articleWriterProfilePicture = $writerInfo ['profile_picture_link'] ? $privateFolder.$writerInfo ['profile_picture_link'] : $publicFolder."/assets/images/user.svg";
                $articleWriterUsername = $writerInfo ['username'];
            } else {
                $articleWriterName = "";
                $articleWriterDescription = "";
                $articleWriterProfilePicture= "";
                $articleWriterUsername ="";
            }

            $articleTitle = $articleInfo ['title'];
            $articleImage = $articleInfo ['image'] ? $privateFolder.$articleInfo ['image'] : "";
            $articleCategory = $articleInfo ['category'];
            $articleTopic = $articleInfo ['topic'];
            $articleContentVersion = $articleInfo ['content_version'];

            $sqlArticleVersion = "SELECT * FROM article_content_versions WHERE article_id= $articleId AND content_version = $articleContentVersion";
            $sqlArticleVersionResult = mysqli_query($conn, $sqlArticleVersion);
            $articleVersion= $sqlArticleVersionResult->fetch_assoc();

            if ($articleVersion){
                $articleContent = $articleVersion ['version_content'];
            }
           

       
        
            $articlePubDate = $articleInfo ['published'];
            $articleUpdateDate = $articleInfo ['updated'];
            $articleStatus = $articleInfo ['status'];

        
            if ($articleStatus!="Published") {
                $unpublishedNotice = true;
            }

            $pageName = $articleTitle; 

            if ($pageName == 'Terms of Use') {
            header ('Location:'.$publicFolder.'/terms-of-use/');
            }

            if ($pageName == 'About Us') {
            header ('Location:'.$publicFolder.'/about-us/');
            }

            if ($pageName == 'Data Privacy') {
            header ('Location:'.$publicFolder.'/data-privacy/');
            }
            
        } 

        

        
      }

?>




<x-main>


@include('components/head')
@include('components/header')

<body>
    

<div id="article-page" class='page' style="margin-top:20px; background-image: url(<?php echo $publicFolder.'/assets/images/home-image.jpg'?>); display:flex;">

<input type="text" id="hidden-article-category" value="<?php echo $featuredCategory;?>" placeholder="Category..." hidden>
<input type="text" id="hidden-article-writer" value="<?php echo $featuredWriter;?>" placeholder="Writer..."hidden>
<input type="text" id="hidden-article-tag" value="<?php echo $featuredTag;?>" placeholder="Tag..." hidden>
<input type="text" id="hidden-article-date" value="<?php echo $featuredDate;?>" placeholder="Date..."hidden>
<input type="text" id="hidden-article-slug" value="<?php echo $featuredSlug;?>" placeholder="Slug..."hidden>


    <?php if ($featuredSlug) {?> 
            <?php if ($articleInfo) {?>
            <?php if ($articleStatus == 'Published'){?>
           
             <div style="width:25%; padding:50px; background-color:white; display:flex;
             flex-direction:column; justify-content:start;">
                <div style="min-height: 300px;">
            
                    <strong style="position:sticky;top:0;">Table of Contents</strong>
                </div>
              
                    @include('components/native-ad')
                

            </div>

            <div class="live-article-container" style="padding:50px 50px 20px 50px;width:75%;">
                <h1 id="live-article-title"><?php echo $articleTitle?></h1>
                <div id="live-article-details-container"  style="display:flex; gap:10px;">
                        <div>
                            <em>by </em>
                            <a href="<?php echo $publicFolder.'/articles/writers/'.$articleWriterUsername?>">
                                <img src="<?php echo $articleWriterProfilePicture?>"class="icon" style="border-radius:50%;">
                                <span><?php echo $articleWriterName;?></span>
                            </a>
                            
                        </div>
                        <div>
                            <em>Category: </em>
                            <a href="<?php echo $publicFolder.'/articles/categories/'.$articleCategory;?>">
                                <em><?php echo $articleCategory;?></em>
                            </a>
                        </div>
                        <div>
                            <em>Published: </em>
                            <a href="<?php echo $publicFolder.'/articles/dates/'.date('Y-m', strtotime($articlePubDate));?>">  
                                <em><?php echo dcomplete_format($articlePubDate);?></em>
                            </a>
                        </div>

                        <?php if ($articleUpdateDate > $articlePubDate) {?>
                            <div>
                                <em>Updated: </em><em><?php echo dcomplete_format($articleUpdateDate);?></em>
                            </div>
                        <?php } ?>
                    
                        @include('components/share-with')
                       
                        
 
                </div>

                <?php if ($articleImage) {?>
                    <img src="<?php echo $articleImage?>" alt="<?php echo 'Featured image:'.$articleTitle;?>" ><br>
                <?php }?>

                <br>

                <div id="article-content">
                    <?php echo $articleContent?>
                </div>

                <ul id="table-of-contents" style="display: block;">
                    

                </ul>


                @include('components/native-ad')

                <div id="<?php echo 'article-writer-description-'.$articleWriterUsername?>" style="height:fit-content; background-color:white; box-shadow:inset; padding:15px;border-radius:15px;" >
                    <p><?php echo $articleWriterDescription?></p>
                </div>

            </div>



            <?php } ?>

             <?php if ($articleStatus != 'Published') {?>
                <div class="content-notice" style="padding:50px 200px 20px 200px;">
                    <p >Opps!<?php echo $articleTitle?> is currently not published.</p>
                    @include('components/native-ad')
                </div>
            <?php }  ?>

            <?php } ?>

            <?php if (!$articleInfo) {?>
                <div class="content-notice" style="padding:50px 200px 20px 200px;">
                    <p >Opps! We cannot find the article.</p>
                    @include('components/native-ad')
                </div>
            <?php }  ?>

    
    <?php }  ?>









    <?php if (!$featuredSlug) {?>

       
        <div id="articles-list" style="padding:50px;">
         
        </div>




    <?php } ?>



</div>


@include('components/footer-scripts')

</body>

</x-main>
