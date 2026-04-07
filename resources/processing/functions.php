<?php 

function dcomplete_format($string="") {
    if ($string){
      return date("M j, Y g:i a",strtotime($string));
    }
}

function dMDY_format($string="") {
   if ($string){
      return date("M j, Y",strtotime($string));
    }
  
}

function image_crop ($file,$maxResolution) {
  if (file_exists($file)) {

    $imageFileNameActualExt="";

    if ($imageFileNameActualExt=='jpeg') {
    $originalImage = imagecreatefromjpeg($file);
    }

    if ($imageFileNameActualExt=='png') {
    $originalImage = imagecreatefrompng($file);
    }
    
    $originalWidth = imagesx($originalImage);
    $originalHeight = imagesy($originalImage);

    if ($originalHeight > $originalWidth) {
    $ratio = $maxResolution / $originalWidth;
    $newWidth = $maxResolution;
    $newHeight = $originalHeight * $ratio;

    $difference= $newHeight - $newWidth;

    $x=0;
    $y = round($difference/2);

    } else {

      $ratio = $maxResolution / $originalHeight;
      $newHeight = $maxResolution;
      $newWidth = $originalWidth * $ratio;

      $difference= $newWidth - $newHeight;

      $x = round($difference/2);
      $y=0;
    }   

   
    

    if ($originalImage) {
      $newImage = imagecreatetruecolor($newWidth,$newHeight);
    imagecopyresampled($newImage,$originalImage,0,0,0,0,$newWidth,$newHeight,$originalWidth,$originalHeight); 

    $newCropImage = imagecreatetruecolor($maxResolution,$maxResolution);
    imagecopyresampled($newCropImage,$newImage,0,0,$x,$y,$maxResolution,$maxResolution,$maxResolution,$maxResolution); 

    imagejpeg($newCropImage,$file,90);
    }


  }
}



function generateSlug($string) {
    // Convert to lowercase
    $slug = mb_strtolower($string, 'UTF-8');

    // Replace non-alphanumeric characters (except hyphens and spaces) with a space
    $slug = preg_replace('/[^a-z0-9\s-]/', ' ', $slug);

    // Replace spaces with a single plus
    $slug = preg_replace('/\s+/', '+', $slug);

    // Remove multiple hyphens
    $slug = preg_replace('/-+/', '+', $slug);

    // Trim leading/trailing hyphens
    $slug = trim($slug, '+');

    return $slug;

}



$word_limit = 50;

function limit_words($string, $word_limit) {
    // Split the string into an array of words using a space delimiter
    $words = explode(' ', $string);
    
    // Check if the total number of words exceeds the limit
    if (count($words) > $word_limit) {
        // Slice the array to keep only the desired number of words (e.g., 50)
        $limited_words_array = array_slice($words, 0, $word_limit);
        
        // Join the limited word array back into a string
        $limited_string = implode(' ', $limited_words_array);
        
        // Optional: append an ellipsis or other indicator if the text was truncated
        // $limited_string .= '...'; 
    } else {
        // If the string is 50 words or less, return the original string
        $limited_string = $string;
    }

    return $limited_string;
}




$word_limit_title = 15;

function limit_words_title ($string, $word_limit_title) {
    // Split the string into an array of words using a space delimiter
    $words = explode(' ', $string);
    
    // Check if the total number of words exceeds the limit
    if (count($words) > $word_limit_title) {
        // Slice the array to keep only the desired number of words (e.g., 50)
        $limited_words_array = array_slice($words, 0, $word_limit_title);
        
        // Join the limited word array back into a string
        $limited_string = implode(' ', $limited_words_array);
        
        // Optional: append an ellipsis or other indicator if the text was truncated
        // $limited_string .= '...'; 
    } else {
        // If the string is 50 words or less, return the original string
        $limited_string = $string;
    }

    return $limited_string;
}


function access_tables ($queryIn,$website,$fromPage) {
  
   


}


?>