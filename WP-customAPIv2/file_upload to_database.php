<?php

/**
 * Plugin Name: customAPI/v4
 * Description:  Upload base64 image using WordPress API and saves as user's avatar
 */

/**
 *   Title:
 *   Upload base64 image using WordPress API and saves as user's avatar
 *
 *   Description:
 *	Using React Native Expo, blobs aren't supported yet and this snippet
 *   uploads an encoded image file (base64), adds it to the WordPress database
 *   and saves it as user's avatar.
 *
 *   The data is received using HTTP POST request containing an JSON encoded
 *   'image' object in its body, and authorizarion set as BEARER with the
 *   appropriate user's token.
 *
 *   Date:
 *   July.2019
 */


/**
 * Registers API endpoints
 */

function fs_get_wp_config_path()
{
    $base = dirname(__FILE__);
    $path = false;

    if (@file_exists(dirname(dirname($base)) . "/wp-config.php")) {
        $path = dirname(dirname($base));
    } else
    if (@file_exists(dirname(dirname(dirname($base))) . "/wp-config.php")) {
        $path = dirname(dirname(dirname($base)));
    } else
        $path = false;

    if ($path != false) {
        $path = str_replace("\\", "/", $path);
    }
    return $path;
}


function myplugin_register_endpoints()
{

    register_rest_route('customAPI/v4', 'uploadAvatar/', [
        'methods'     => 'POST',
        'callback'    => 'myplugin_api_upload_avatar_image'
    ]);
}
add_action('rest_api_init', 'myplugin_register_endpoints');


/**
 * Uploads image and set as user's avatar
 */


/***
		The data expected in $params is:
		$params->image = [
			'width' 	=> @int,
			'height 	=> @int,
			'uri'		=> @string (path to file or file name)
			'base64'	=> @string 
		]
 */


function myplugin_api_upload_avatar_image($request)
{


    // WordPress environment
    $home = fs_get_wp_config_path();
    require($home . '/wp-load.php');

    // Get image path where it will be saved in WordPress
    $wordpress_upload_dir = wp_upload_dir();
    try {  // prelimiary check
        // checked logged in status 
        if (!is_user_logged_in()) {

            throw new PluginException(' no_permission ');
        }


        $params = json_decode($request->get_body());
        // check form
        if (!isset($params->image) || empty($params->image))
            throw new PluginException('invalid_file');

        // File type
        $image = $params->image;
        $image->name = basename($image->uri);
        $filetype = wp_check_filetype($image->name, null);
        $image->ext = $filetype['ext']; // "JPG", "PNG"
        $image->type = $filetype['type']; // "image/jpeg"

        if (!in_array($image->type, ['image/jpg', 'image/png', 'image/jpeg']))
            throw new PluginException('invalid_file_type');

        // file size
        if ($image->width > 1024 || $image->height > 1024)
            throw new PluginException('image_size_exceeded');



        if ($image->width < 150 || $image->height < 150)
            throw new PluginException('image_size_too_small');
    } catch (PluginException $pe) {
        return $pe->restApiErrorResponse('...');
    }




    /*     try {
    } catch (PluginException $pe) {
        return $pe->restApiErrorResponse('...');
    } */










    $image_path = $wordpress_upload_dir['path'] . '/' . $image->name;




    // Checks if file with same name exists, if so, we append an integer to it
    $i = 1;


    while (file_exists($image_path)) {
        $image_path = $wordpress_upload_dir['path'] . '/' . $i . '_' .  $image->name;
        $i++;
    }







    // Let's save the file now
    if (file_put_contents($image_path, base64_decode($image->base64))) {

        $upload_id = wp_insert_attachment(array(
            'guid'           => $image_path,
            'post_mime_type' => $image->type,
            'post_title'     => preg_replace('/\.[^.]+$/', '', $image->name),
            'post_content'   => '',
            'post_status'    => 'inherit'
        ), $image_path);

        // wp_generate_attachment_metadata() won't work if you do not include this file
        require_once(ABSPATH . 'wp-admin/includes/image.php');

        // Generate and save the attachment metas into the database
        wp_update_attachment_metadata($upload_id, wp_generate_attachment_metadata($upload_id, $image_path));

        $avatar = wp_get_attachment_image_src($upload_id, 'thumbnail'); // returns [0] => uri, [1] => width, [2] => height
        update_user_meta(get_current_user_id(), 'basic_user_avatar', $avatar[0]);
        return $upload_id; //$avatar[0];
    }

    return array(
        'code'        => 'avatar_upload_failed',
        'message'    => 'Something went wrong while saving image',
        'data'        => array('status' => 500)
    );
}
