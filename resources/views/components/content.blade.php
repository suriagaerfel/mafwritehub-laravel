<div id="content-page"class="page with-sidebars-page with-single-sidebar-page" style="padding-top:20px;">

    <div class="page-details page-details-single-sidebar">

         <?php if (!$slug) { ?>
            <input type="text" id="accessed-category" value="<?php echo $accessedCategory;?>" hidden>
            <input type="text" id="accessed-tag" value="<?php echo $accessedTag;?>" hidden>
            <input type="text" id="accessed-date" value="<?php echo $accessedDate;?>" hidden>
            <input type="text" id="accessed-owner" value="<?php echo $accessedOwner;?>" hidden>

            <div id="teacher-files-list">
            </div> 
            
            <div id="researches-list">
            </div>

            <div id="articles-list">
            </div>

            <div id="tools-list">
            </div>
        <?php } ?>


        <?php if ($slug) { ?>
         <input type="text" id="content-type" value="<?php echo $contentType;?>" hidden>

            <?php if ($contentInfo) {?>

                    <?php if ($hasAccess) {?>

                        <?php if (str_contains($currentURL,'/teacher-files/') || str_contains($currentURL,'/researches/')) { ?>
                                <div style="display: flex; gap:10px; margin-bottom:10px;">
                                    <div>
                                        <button class="show-details link-tag-button">Show Details</button>
                                        <button class="hide-details link-tag-button">Hide Details</button>
                                    </div>
                                
                                </div>
                


                                <br>
                                <div class="live-content-details-container">
                                    <h1 class="live-content-title"><?php echo $contentTitle;?></h1>
                                    <hr>
                                    
                                    <div class="details-container">
                                        <p>
                                            <strong>Type: </strong>
                                            <a href="<?php echo $pageLink;?>">
                                                <?php echo ucwords(str_replace('-',' ',$content_type));?>
                                            </a>
                                        </p>
                                        <p>
                                            <strong>Category: </strong>
                                            <a href="<?php echo $pageLink.'category/'.$contentCategory;?>">
                                                <?php echo $contentCategory;?>
                                            </a>
                                        </p>
                                        <p>
                                            <strong> <?php echo $ownerLabel;?>: </strong>
                                            <a href="<?php echo $pageLink.strtolower($ownerLabel).'/'.$contentRegistrantUsername;?>">
                                                <?php echo $contentRegistrant;?>
                                            </a>
                                        </p>
                                        <p>
                                            <strong> Tags: </strong>

                                            <?php 
                                            
                                            if ($contentTags){
                                                $contentTags = explode(', ',$contentTags);

                                                foreach($contentTags as $contentTag){
                                                    echo "
                                                    <a class='link-tag-button' href='".$pageLink.'tag/'.$contentTag."'>$contentTag</a>
                                                    ";
                                                }



                                            } else {
                                                echo "<span>None</span>";
                                            }
                                            
                                            
                                            
                                            ?>


                                        </p>

                                    
                                    </div>
                                    <hr>
                                    <div class="details-container">
                                        <p>
                                            <strong>Published:</strong> 
                                            <a href="<?php echo $pageLink.'date/'.date('Y-m',strtotime($contentPubDate));?>"><?php echo $contentPubDate;?></a>
                                        </p>
                                
                                        <?php if ($contentUpdateDate > $contentPubDate) {?>
                                        <p><strong>Updated:</strong> <?php echo $contentUpdateDate;?></p>
                                        <?php } ?>
                                    </div>
                                    <hr>
                                    <p><strong>Description:</strong> <?php echo $contentDescription;?></p>
                                </div>


                                <?php if ($contentFormat=="pdf") {?>  
                                    <iframe class="pdf-reader" src="<?php echo $privateFolder.$contentContent.'?iframe=true'?>"></iframe>
                                <?php }?>



                                <?php if($contentFormat=="docx") {?>
                                <div class="content-notice">
                                    <p>Docx file cannot be loaded.</p>  
                                </div>  
                                <?php } ?>

                        <?php } ?>






                        <?php if (str_contains($currentURL,'/articles/')){?>
                            <h1 id="live-article-title"><?php echo $contentTitle?></h1>
                            <div id="live-article-details-container">
                                    <div>
                                        <em>by </em><em><?php echo $contentRegistrant;?></em>
                                    </div>
                                    <div>
                                        <em>Category: </em><em><?php echo $contentCategory;?></em>
                                    </div>
                                    <div>
                                        <em>Published: </em><em><?php echo dcomplete_format($contentPubDate);?></em>
                                    </div>

                                    <?php if ($contentUpdateDate > $contentPubDate) {?>
                                    <div>
                                        <em>Updated: </em><em><?php echo dcomplete_format($contentUpdateDate);?></em>
                                    </div>
                                    <?php } ?>

                                
                            </div>

                            <?php if ($isContentImageUpload) {?>
                                <img src="<?php echo $contentImage?>" alt="<?php echo 'Featured image:'.$contentTitle;?>" ><br><br>
                            <?php }?>

                            <div class="article-content">
                                <?php echo $contentContent?>
                            </div>


                        <?php } ?>
                    <?php } ?>

                    <?php if (!$hasAccess) {?>
                        <p><?php echo 'Sorry, your access to '.'<strong>'.$contentTitle.'</strong>'.' is denied.';?></p>

                        <?php if ($contentAccessType == 'Purchased') {?>
                            <?php if (!$contentPurchase){?>
                                <small class="purchase link-tag-button" id="<?php echo 'purchase-'.$content_type.'-'.$id;?>">Purchase</small>
                            <?php } ?>

                            <?php if ($contentPurchase){?>
                                <small class="purchase link-tag-button active" id="<?php echo 'purchase-'.$content_type.'-'.$id;?>"><?php echo $contentPurchaseStatus;?></small>
                            <?php } ?>

                        <?php }?>
                    <?php } ?>

            

                    
                <?php if ($contentStatus != 'Published') {?>
                    <p><?php echo '<strong>'.$contentTitle.'</strong>'.' is currently not published.'?></p>
                <?php } ?>


            <?php } ?>

         <?php if (!$contentInfo) {?>
                <p>Sorry, but we cannot find the content you look for.</p>
        <?php } ?>



        @include('components/native-ad')
      
            <?php if ($contentInfo) { ?>

            <?php  $sqlCategoryContents = "SELECT * FROM $table WHERE $categoryColumn = '$contentCategory' AND $idColumn !=$id AND $statusColumn = 'Published' ORDER BY $pubDateColumn DESC LIMIT 5";

                $sqlCategoryContentsResult = mysqli_query($conn,$sqlCategoryContents);

                if ($sqlCategoryContentsResult->num_rows > 0){ ?>
                        <br><br><br><br>
                        <div class="more-label">
                            <p><strong>Category: <?php echo $contentCategory;?></p>    
                        </div>
                        <hr>
                <?php  while ($categoryContents = $sqlCategoryContentsResult->fetch_assoc()){  
                        $categoryContentId = $categoryContents [$idColumn];
                        $categoryContentTitle = $categoryContents [$titleColumn];
                        $categoryContentImage = $categoryContents [$imageColumn] ? $privateFolder.$categoryContents [$imageColumn] : $defaultImage;
                        $categoryContentHref = $pageLink.$access.'/'.$categoryContents [$slugColumn];
                    ?>
    
                            <div>
                                <img src="<?php echo $categoryContentImage?>" alt="" style="width: 50px;"> 
                                <strong><a href="<?php echo $categoryContentHref?>"><?php echo $categoryContentTitle;?></a></strong>
                            </div>

                            
                
                    <?php  } ?>


                    <?php if ($sqlCategoryContentsResult->num_rows > 4) {?>
                            <br>
                            <div>
                                <a href="<?php echo $pageLink.'category/'.$contentCategory;?>" class="link-tag-button">More</a>
                            </div>
                    <?php } ?>

            <?php   }  

        }  ?>
        
        
        


        <?php } ?>

     


       











    </div>


    
    @include('components/website-sidebar')
  


</div>


