<x-main>
    @include('components/head')
    @include('components/header')

<body>

<div id="home-page" class="page" style="display: flex; flex-direction:column;padding:20px; gap:20px;margin:0px;  background-image: url(<?php echo $publicFolder.'/assets/images/home-image.jpg'?>);">
    
 <div style="display: flex; flex-direction:row;">
        <div id="home-contents-container" style="width:100%; text-align:center; padding:0px 20px 0px 20px; display:flex; flex-direction:column;">
          
                <?php 
               

                $stmt= $conn->prepare(
                $sqlArticlesList = "SELECT * FROM articles WHERE status='Published' and category != 'Administrative' ORDER BY published DESC LIMIT 1");
                $stmt->execute();
                $count = $stmt->rowCount(); 
                ?>

                <?php if ($count >0) {
                
                while ($articlesList = $stmt->fetch()) {
                $articleListId = $articlesList ['id'];
                $articleListTitle = $articlesList ['title']; 
                $articleListSlug = $articlesList ['slug']; 
                $articleListCategory = $articlesList ['category'];
                $articleListTopic = $articlesList ['topic'];
                $articleListContentVersion = $articlesList ['content_version'];

                $stmt=$conn->prepare("SELECT * FROM article_content_versions WHERE article_id= ? AND content_version = ?");

                $stmt->execute([$articleListId,$articleListContentVersion]);
                $articleVersion = $stmt->fetch();

                if ($articleVersion){
                    $articleListContent = $articleVersion ['version_content'];

                    if (str_word_count($articleListContent)>160) {
                         $articleListContent = get_first_n_words($articleListContent,160);
                    }
                }



                $articleListWriterId = $articlesList ['writer_id'];
            
                $stmt=$conn->prepare("SELECT name FROM users WHERE id = ?");
                $stmt->execute([$articleListWriterId]);
                $articleListWriterInfo = $stmt->fetch();

                $articleListWriter = $articleListWriterInfo ? $articleListWriterInfo ['name'] : "";
                $articleListWriteDate = dcomplete_format($articlesList ['drafted']);
                $articleListPubDate = dcomplete_format($articlesList ['published']);
                $articleListUpdateDate =dcomplete_format($articlesList ['updated']);
                $articleListImage = $articlesList ['image'] ? $privateFolder.$articlesList ['image'] : $publicFolder.'/assets/images/default-featured-image.jpg';

                ?>

                <div class="list list-container contents-list-container" id="home-articles-list-container" style="display: flex;">
                    
                    <div class="list-info" id="article-list-info" style="text-align:left; margin-top:40px; background-color:white; padding:20px;border-radius: 20px;">
                        <div id="article-list-title">
                            <h3><?php echo $articleListTitle;?></h3>
                        </div>
                        <div id="article-list-content">
                            <?php echo $articleListContent; ?>
                        </div>
                        <div class="list-buttons-container" style="display: flex; gap:10px;">
                            <a class="link-tag-button" href="<?php echo $publicFolder.'/articles/read/'.$articleListSlug;?>">Read</a>
                            <small><?php echo $articleListWriter;?></small>
                            <small><?php echo $articleListCategory;?></small>
                            <small><?php echo $articleListTopic;?></small>
                            <small><?php echo $articleListPubDate;?></small>
                            

                        </div>

                    </div>
        
                </div>

                <?php } } ?>   
        

        </div>

        <div style="display:flex; flex-direction:column;width:100%;">
            <div style="height: fit-content; display:flex; width:100%; padding:20px 20px 0px 20px;  flex-direction:column;">

            </div>





            <div style="height: fit-content; display:flex; width:100%; padding:20px 20px 0px 20px;  flex-direction:column;">
        
            <?php 
          
            $stmt=$conn->prepare("SELECT * FROM articles WHERE title = 'About Us'");
            $stmt->execute();
            $aboutArticle = $stmt->fetch();

            if ($aboutArticle) {
                $aboutArticleId = $aboutArticle ['id'];
                $aboutArticleTitle = $aboutArticle ['title'];
                $aboutArticleContentVersion = $aboutArticle ['content_version'];

                $stmt=$conn->prepare("SELECT * FROM article_content_versions WHERE article_id = $aboutArticleId AND content_version = $aboutArticleContentVersion");
                $stmt->execute();
                $aboutArticleVersion=$stmt->fetch();

                if ($aboutArticleVersion) {
                    $aboutArticleContent = $aboutArticleVersion ['version_content'];


                        if (str_word_count($aboutArticleContent)>80) {
                            $aboutArticleContent= get_first_n_words($aboutArticleContent,80)."<a href='$publicFolder/about-us' target='_blank'>Read more</a>";
                        }
                }

                $aboutArticleStatus = $aboutArticle ['status'];

                    
                if ($aboutArticleStatus=='Published') { ?>
                    <strong><?php echo $websiteName?> at Glance</strong>
                    <p><?php echo $aboutArticleContent?></p>      
                <?php }
                } ?>

            </div>
        </div>

    </div>

     @include('components/footer-links')
   
</div>
    @include('components/footer-scripts')
 </body>


</x-main>

