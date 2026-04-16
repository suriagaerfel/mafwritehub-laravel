<x-main>
    @include('components/head')
   

<body>
      @include('components/header')
    <div id="home-page" class="page" style="margin-top:30px;">

        <div style="display: flex; flex-direction:row;" class="home-content-section">
            <div style="width: 50%; padding:30px;" >
                <?php 
                
                $stmt= $conn->prepare(
                $sqlArticlesList = "SELECT * FROM articles WHERE status='Published' and category != 'Administrative' ORDER BY published DESC LIMIT 1");
                $stmt->execute();
                $count = $stmt->rowCount(); 
                ?>

                <?php if ($count >0) { ?>
                
                <?php while ($articlesList = $stmt->fetch()) {
                $articleListId = $articlesList ['id'];
                $articleListTitle = $articlesList ['title']; 
                $articleListSlug = $articlesList ['slug']; 
                $articleListCategory = $articlesList ['category'];
                $articleListTags = $articlesList ['tags'];
                $articleListVersion = $articlesList ['version'];

                $stmt=$conn->prepare("SELECT * FROM article_versions WHERE article_id= ? AND version = ?");

                $stmt->execute([$articleListId,$articleListVersion]);
                $articleVersion = $stmt->fetch();

                if ($articleVersion){
                    $articleListBody = $articleVersion ['version_body'];

                    if (str_word_count($articleListBody)>160) {
                            $articleListBody = get_first_n_words($articleListBody,160);
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
 
                <h3><?php echo $articleListTitle;?></h3>
                <p><?php echo $articleListBody; ?></p>
                    
                <div class="list-buttons-container" style="display: flex; gap:10px;">
                    <a class="link-tag-button" href="<?php echo $publicFolder.'/articles/read/'.$articleListSlug;?>">Read</a>
                    <small><?php echo $articleListWriter;?></small>
                    <small><?php echo $articleListCategory;?></small>
                    <small><?php echo $articleListTags;?></small>
                    <small><?php echo $articleListPubDate;?></small>
                </div>

                <?php } } ?>
            </div>
            <div style="width: 50%;padding:30px;">
                <p>Other articles</p>
            </div>

        </div>
        <div style="display: flex; flex-direction:row;" class="home-content-section">
            <div style="width: 50%; padding:30px;" >
                <p>Maf Write Hub at Glance</p>
            </div>
            <div style="width: 50%;padding:30px;">
                <p>Other articles</p>
            </div>

        </div>
        @include ('components/footer-links')

     
       
    
    </div>


    @include('components/footer-scripts')
 </body>


</x-main>

