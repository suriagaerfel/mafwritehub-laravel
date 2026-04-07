
    <?php

        $placeholder = '';
        $searchId = '';

        if ($pageName == 'Workspace - Teacher') {
            $placeholder = 'Type to search files...';
            $searchId = 'search-file';
        }

         if ($pageName == 'Workspace - Writer') {
            $placeholder = 'Type to search articles...';
            $searchId = 'search-article';
        }

         if ($pageName == 'Workspace - Editor') {
            $placeholder = 'Type to search articles...';
            $searchId = 'search-article';
        }

         if ($pageName == 'School Workspace - Researches') {
            $placeholder = 'Type to search researches...';
            $searchId = 'search-research';
        }

        if ($pageName == 'Workspace - Developer') {
            $placeholder = 'Type to search tools...';
            $searchId = 'search-tool';
        }
    
    ?>

    <div class='workspace-search-container'>
        <input type='search' placeholder='<?php echo $placeholder;?>' id='<?php echo $searchId;?>' autofocus>
    </div>
   