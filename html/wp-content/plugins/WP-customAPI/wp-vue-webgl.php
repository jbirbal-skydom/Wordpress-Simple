<?php

/**
 * Plugin Name: WordPress Vue webGL
 * Description: Vue-App in WordPress.
 */

function func_load_vuescripts()
{
    // addd style
    wp_register_style(
        'custom',
        plugin_dir_url(__FILE__) . 'dist/css/app.css',
    );

    // add js
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
}

add_action('wp_enqueue_scripts', 'func_load_vuescripts');


function unity3d_embed_webgl_function($atts = array())
{

    // Unwrap the shortcode attributes, substituting the given defaults for any undefined attributes
    $args = shortcode_atts(
        array(
            'width' => '100',
            'height' => '100',
        ),
        $atts
    );

    $width = esc_attr($args['width']);
    $height = esc_attr($args['height']);


    // add the UnityLoader javascript file to footer
    wp_enqueue_script('app');
    wp_enqueue_script('vendors');

    //proces style
    wp_enqueue_style('custom');


    // returns the game window html code
    return '<div id="app" style="width: ' . $width . '%; height: ' . $height . '%; margin: auto; overflow: auto;"></div>';
}

add_shortcode('unity3d_embed_webgl', 'unity3d_embed_webgl_function');
