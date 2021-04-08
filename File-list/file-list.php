<?php
/**
 * Plugin Name: File List
 * Description: File List to Hyperlinks
 */


/* To Do:

function Beautification ( $data ){
  } 

function Images ( $data ){
  } 

function Buy ( $data ){
  } 

function Edit ( $data ){
  } 

function Validate ( $data ){ // add directory check user 
  } 

function Delete ( $data ){ // Delete User
  } 

function Add ( $data ){ // add user
  } 


*/

function console_log( $data ){
    echo '<script>';
    echo 'console.log('. json_encode( $data ) .')';
    echo '</script>';
  }





function Find() { //find files in directory

	$current_user = wp_get_current_user();
    $username = $current_user->user_login;
    $type = gettype($current_user);
    $folder = '/var/www/html/wp-content/uploads/user_uploads/'. $username . '/ftp' ;
    $path = realpath($folder);
    $home = 'localhost:8080/wp-content/uploads/user_uploads/' . $username . '/ftp/';
    // console_log ($username);
    if($path !== false AND is_dir($path)){
       // console_log ('entered');
        if ($dh = opendir($path)) {
            $fileArr = " ";
            while (($file = readdir($dh)) !== false) { // read Directory for each file and do something for each file
                if ($file != "." && $file != "..") {
                    $fileArr .= '<a href="' . $home . $file . '">' . $file . '</a>' . "<br />"; // append hyperlink


                }

                
            }
            closedir($dh);
            return $fileArr;


        }

        

        // return  'yay!';
    }

    // return  'false';
}



// Add Shortcodes

add_shortcode( 'my_output', 'Find');