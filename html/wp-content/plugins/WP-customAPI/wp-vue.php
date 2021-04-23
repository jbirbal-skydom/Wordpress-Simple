<?php

/**
 * Plugin Name: WordPress Vue
 * Description: Vue-App in WordPress.
 */

/* function func_load_vuescripts()
{
    wp_register_script('wpvue_vuejs', plugin_dir_url(__FILE__) . 'dist/js/app.c8d5a15f.js', true);
    wp_register_script('wpvue_vuejs1', plugin_dir_url(__FILE__) . 'dist/js/chunk-vendors.5e0c61d5.js', true);
    wp_register_script('wpvue_vuejs2', plugin_dir_url(__FILE__) . 'html\wp-content\plugins\WP-customAPI\dist\js\about.ea5fbbe6.js', true);
    wp_register_script('wpvue_vuejs3', plugin_dir_url(__FILE__) . 'html\wp-content\plugins\WP-customAPI\dist\js\app.fb10eec2.js', true);
    wp_register_script('wpvue_vuejs4', plugin_dir_url(__FILE__) . 'html\wp-content\plugins\WP-customAPI\dist\js\chunk-vendors.beae7116.js', true);
    wp_register_script('wpvue_vuejs5', plugin_dir_url(__FILE__) . 'html\wp-content\plugins\WP-customAPI\dist\precache-manifest.bd64e30f15aac3de68d8094ee57dcb91.js', true);
    wp_register_script('wpvue_vuejs6', plugin_dir_url(__FILE__) . 'html\wp-content\plugins\WP-customAPI\dist\service-worker.js', true);
}

add_action('wp_enqueue_scripts', 'func_load_vuescripts');

//Add shortscode
function func_wp_vue()
{
    wp_enqueue_script('wpvue_vuejs');
    wp_enqueue_script('wpvue_vuejs1');
    wp_enqueue_script('wpvue_vuejs2');
    wp_enqueue_script('wpvue_vuejs3');
    wp_enqueue_script('wpvue_vuejs4');
    wp_enqueue_script('wpvue_vuejs5');
    wp_enqueue_script('wpvue_vuejs6');

    $str = "<div id='app'>"
        . "Message from Vue: "
        . "</div>";
    return $str;
} // end function */

//Add shortscode
function func_wp_vue()
{
    $str = "<div id='divWpVue'>"
        . "Message from Vue: "
        . "</div>";
    return $str;
} // end function

add_shortcode('wpvue', 'func_wp_vue');
