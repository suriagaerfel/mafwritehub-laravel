
<?php //----------------------------------------- HEAD------------------------------------------------?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
     <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="<?php echo $publicFolder.'/assets/images/eskquip-icon-new.png';?>">
    <title><?php echo $pageName?></title>

    <!-- include libraries(jQuery) -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    
    <!-- include libraries(bootstrap) -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css" >
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script> 
  

    <!-- include summernote css/js -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/summernote@0.9.0/dist/summernote.min.css" >
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.9.0/dist/summernote.min.js"></script>


    <!-- include font awesome css -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />

    



<!-- offline files -->
    <!-- <script src="<?php echo $publicFolder.'/assets/js/jquery-4.0.0.min.js'; ?>"></script>
    <link rel="stylesheet" href="<?php echo $publicFolder.'/assets/bootstrap/css/bootstrap.min.css'; ?>" >
    <script src="<?php echo $publicFolder.'/assets/bootstrap/js/bootstrap.bundle.min.js'; ?>"></script>
    
    <link rel="stylesheet" href="<?php echo $publicFolder.'/assets/summernote/summernote.min.css'; ?>" >
    <script src="<?php echo $publicFolder.'/assets/summernote/summernote.min.js'; ?>"></script> -->




    <!-- include my website's own css -->
    <link rel="stylesheet" href="<?php echo $publicFolder.'/assets/css/styles.css'; ?>">
    <link rel="stylesheet" href="<?php echo $publicFolder.'/assets/css/media-queries.css'; ?>">


    

</head>


