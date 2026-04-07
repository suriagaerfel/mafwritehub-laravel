<?php

$registrantCode= isset($_SESSION['registrant-code']) ? $_SESSION['registrsnt-code'] : "";
$registrantId= isset($_SESSION['registrant-id']) ? $_SESSION['registrant-id'] : "";
$toolSubscribed= isset($_SESSION ['tool-subscribed']) ? $_SESSION ['tool-subscribed'] : "";

$contentViewTimeNew = isset ($_POST['time_spent']) ? $_POST['time_spent'] : 0;

$url =  str_replace('/eskquip','',$_SERVER['REQUEST_URI']);

$sqlTool = "SELECT * FROM developer_tools WHERE developer_toolSlug = '$url'";
$sqlToolResult = mysqli_query($conn,$sqlTool);
$tool = $sqlToolResult->fetch_assoc();

$toolId = $tool ['developer_toolId'] ? $tool ['developer_toolId'] : "";
$toolOwner = $tool ['developer_toolDeveloper'] ? $tool ['developer_toolDeveloper'] : "";
$toolStatus = $tool ['developer_toolStatus'] ? $tool ['developer_toolStatus'] : "";

if(!$registrantCode) {
    header('Location:'.$publicFolder.'/login/');
} else {
    if(!$toolSubscribed && $registrantId!=$toolOwner) {
    header('Location:'.$publicFolder.'/tools/');
    }
}

$pageName = $tool ['developer_toolTitle'] ? ucfirst($tool ['developer_toolTitle']) : "";
if ($toolStatus!='Published' && $registrantId==$toolOwner) {
$pageName = $pageName.' [Preview]';
}
 

if ($toolStatus=='Published') {

    if ($contentViewTimeNew > 0) {

    $contentTable = 'developer_tools';
    $contentId = $toolId;

    $viewerId = 0;
    
    if ($registrantId) {
            $viewerId = $registrantId;
    }


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





<?php //----------------------------------------- HEAD------------------------------------------------?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="<?php echo asset('/images/eskquip-icon.png');?>">
    <title><?php echo $pageName?></title> 
    <link rel="stylesheet" href="<?php echo asset('/css/styles.css'); ?>">

</head>


<?php //----------------------------------------- BODY------------------------------------------------?>

<body>

    <?php if ($toolStatus!='Published' && $registrantId!=$toolOwner) {?>

    <div style="text-align: center; padding-top:10%;">
        <p><?php echo $pageName ?> is currently under development.</p>
    </div>

    <?php echo $url; ?>

    <?php } ?>