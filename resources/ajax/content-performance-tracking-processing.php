<?php 

if (isset($_GET['slug'])) {

    $contentTable = '';
    $contentId = '';
    $contentstatus = '';
 
    if (str_contains($currentURL, '/teacher-files/')){
       $contentTable = 'teacher_files'; 
       $contentId = $fileId;  
       $contentStatus = $fileStatus;              
    }
    if (str_contains($currentURL, '/articles/')){
       $contentTable = 'writer_articles';
       $contentId = $articleId;   
       $contentStatus = $articleStatus;               
    }
    if (str_contains($currentURL, '/researches/')){
       $contentTable = 'school_researches';  
       $contentId = $researchId; 
       $contentStatus = $researchStatus;               
    }

    $viewerId = 0;

    if ($registrantId) {
        $viewerId = $registrantId;
    }

    if ($contentStatus=='Published') {

        $sqlViewingRecords = "SELECT * FROM content_performance WHERE content_viewUserId = '$viewerId' AND content_viewTable='$contentTable' AND content_viewForeignId = '$contentId'";
        $sqlViewingRecordsResults = mysqli_query ($conn,$sqlViewingRecords);
        $viewingRecords = $sqlViewingRecordsResults-> fetch_assoc();

        if ($viewingRecords) {
            $viewingId = $viewingRecords ['content_viewId'];
            $contentViewTimeOld = $viewingRecords ['content_viewTime'];
            $contentViewTimeUpdated = $contentViewTimeNew + $contentViewTimeOld;
            $contentLastView = strtotime($viewingRecords ['content_viewLastUpdate']);     
                        
            $sqlUpdateViewingRecords= "UPDATE content_performance
                                SET 
                                content_viewTime=?
                                WHERE content_viewId = $viewingId";


            $stmt = mysqli_stmt_init($conn);
            $prepareStmt = mysqli_stmt_prepare($stmt, $sqlUpdateViewingRecords);
            
            if ($prepareStmt) {
            mysqli_stmt_bind_param($stmt,"s", $contentViewTimeUpdated);

            mysqli_stmt_execute($stmt);

        }    
        } else {
            $sqlInsertViewingRecords= "INSERT INTO content_performance
                                (content_viewTime, content_viewTable,content_viewForeignId,content_viewUserId) VALUES (?,?,?,?)";

                $stmt=$conn->prepare($sqlInsertViewingRecords);
                $stmt ->bind_param("ssss",$contentViewTimeNew ,$contentTable,$contentId,$viewerId);
                $stmt-> execute();         
        }

    }
    
}

?>
