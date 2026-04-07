<?php 

namespace App\Services;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class FunctionsService
{
   
   function dcomplete_format($string="") {
  return date("M j, Y g:i a",strtotime($string));
}

function dmdy_format($string="") {
  return date("M j, Y",strtotime($string));
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



function limit_words_description ($string, $word_limit=50) {
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





function limit_words_title ($string, $word_limit_title=15) {
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








function get_first_n_words($string, $n = 90) {
    // Split the string into an array of words using space as a delimiter
    $words = explode(' ', $string);
    
    // If the total number of words is less than or equal to $n, return the original string
    if (count($words) <= $n) {
        return $string;
    }
    
    // Extract the first $n words
    $first_n_words = array_slice($words, 0, $n);
    
    // Join the words back into a string, adding an ellipsis if desired
    $result = implode(' ', $first_n_words) . '...';
    
    return $result;
}
}



?>