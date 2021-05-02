<?php

/**
 * Plugin Name: WordPress Vue
 * Description: Vue-App in WordPress.
 */

function func_load_vuescripts()
{
    wp_register_script(
        'app',
        plugin_dir_url(__FILE__) . 'dist/js/app.js',

        true,
    );
    wp_register_script(
        'vendors',
        plugin_dir_url(__FILE__) . 'dist/js/chunk-vendors.js',

        true,
    );
    /*     wp_register_script(
        'about',
        plugin_dir_url(__FILE__) . 'dist/js/about.js',

        true,
    ); */
}

add_action('wp_enqueue_scripts', 'func_load_vuescripts');

//Add shortscode
function func_wp_vue()
{
    wp_enqueue_script('app');
    wp_enqueue_script('vendors');
    // wp_enqueue_script('about');



    $str = "<div id='app'>"
        . "Message from Vue: "
        . "</div>";
    return $str;
} // end function 

//Add shortscode

add_shortcode('wpvue', 'func_wp_vue');
