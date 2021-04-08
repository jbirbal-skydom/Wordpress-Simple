<?php
/**
 * Plugin Name: Hook Test
 * Description: A simple plugin to test hooks
 */
function console_log( $data ){
    echo '<script>';
    echo 'console.log('. json_encode( $data ) .')';
    echo '</script>';
  }
function my_shortcode() {

	$current_user = wp_get_current_user();
    $username = $current_user->user_login;
    $type = gettype($current_user);
    $folder = '/var/www/html/wp-content/uploads/user_uploads/'. $username . '/ftp' ;
    $path = realpath($folder);
    $home = 'localhost:8080/wp-content/uploads/user_uploads/' . $username . '/ftp/';
    // console_log ($username);
    if($path !== false AND is_dir($path)){
        console_log ('entered');
        if ($dh = opendir($path)) {
            $fileArr = " ";
            while (($file = readdir($dh)) !== false) {
                if ($file != "." && $file != "..") {
                    $fileArr .= '<a href="' . $home . $file . '">' . $file . '</a>' . "<br />";


                }

                
            }
            closedir($dh);
            return $fileArr;


        }

        

        // return  'yay!';
    }

    // return  'false';
}

add_shortcode( 'my_output', 'my_shortcode');