 <div style="display:flex; flex-direction:column;width:100%;">
        
            <?php 
            
            $stmt=$conn->prepare("SELECT * FROM articles WHERE title = 'About Us'");
            $stmt->execute();
            $aboutArticle = $stmt->fetch(); ?>

            <?php  if ($aboutArticle) { 
                $aboutArticleId = $aboutArticle ['id'];
                $aboutArticleTitle = $aboutArticle ['title'];
                $aboutArticleVersion = $aboutArticle ['version'];
                $aboutArticleStatus = $aboutArticle ['status'];

                $stmt=$conn->prepare("SELECT * FROM article_versions WHERE article_id = $aboutArticleId AND version = $aboutArticleVersion");
                $stmt->execute();
                $articleVersion=$stmt->fetch();

                if ($articleVersion) {
                    $aboutArticleBody = $articleVersion ['version_body'];

                    if (str_word_count($aboutArticleBody)>80) {
                        $aboutArticleBody= get_first_n_words($aboutArticleBody,80)."<a href='$publicFolder/about-us' target='_blank'>Read more</a>";
                    }
                } ?>

                
                <?php if ($aboutArticleStatus=='Published') { ?>
                    <strong>Maf Write Hub at Glance</strong>
                    <p><?php echo $aboutArticleBody?></p>      
                <?php } ?>

            <?php } ?>

        
            </div>

        @include('components/footer-links')