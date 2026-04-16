<x-main>


    @include('components/head')


<body>
    @include('components/header')

    <div id="article-page" class='page' style="margin-top:20px;  display:flex;">

        <input type="text" id="hidden-article-category" value="{{$category}}" placeholder="Category..." hidden>
        <input type="text" id="hidden-article-writer" value="{{$writer}}" placeholder="Writer..."hidden>
        <input type="text" id="hidden-article-tag" value="{{$tag}}" placeholder="Tag..." hidden>
        <input type="text" id="hidden-article-date" value="{{$date}}" placeholder="Date..."hidden>
        <input type="text" id="hidden-article-slug" value="{{$slug}}" placeholder="Slug..."hidden>


        <?php if ($slug) {?> 
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
                                <a href="<?php echo $publicFolder.'/articles/writers/'.$articleWriterUsername?>">
                                    <img src="<?php echo $articleWriterProfilePicture?>"class="icon" style="border-radius:50%;">
                                    <span><?php echo $articleWriterName;?></span>
                                </a>
                                
                            </div>
                            <div>
                                <a href="<?php echo $publicFolder.'/articles/categories/'.$articleCategory;?>">
                                    <em><?php echo $articleCategory;?></em>
                                </a>
                            </div>
                            <div>
                                <a href="<?php echo $publicFolder.'/articles/dates/'.date('Y-m', strtotime($articlePubDate));?>">  
                                    <?php echo dcomplete_format($articlePubDate);?>
                                </a>
                            </div>

                            <?php if ($articleUpdateDate > $articlePubDate) {?>
                                <div>
                                    <span>(Updated <?php echo dcomplete_format($articleUpdateDate);?>)</span>
                                </div>
                            <?php } ?>
                        
                            @include('components/share-with')
                        
                            
    
                    </div>

                    <?php if ($articleImage) {?>
                        <img src="<?php echo $articleImage?>" alt="<?php echo 'Featured image:'.$articleTitle;?>" ><br>
                    <?php }?>

                    <br>

                    <div id="article-content">
                        <?php echo $articleBody?>
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









        <?php if (!$slug) {?>

        
            <div id="articles-list" style="padding:50px;">
            
            </div>




        <?php } ?>



    </div>


@include('components/footer-scripts')

</body>

</x-main>
