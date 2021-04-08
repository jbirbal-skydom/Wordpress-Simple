<?php
/**
 * Plugin Name: Custom API
 * Description: Receive file and put in selected folder
 */

add_action('rest_api_init', function () {
    register_rest_route( 'customAPI/v1', 'latest-posts/(?P<category_id>\d+)',array(
                  'methods'  => 'GET',
                  'callback' => 'get_latest_posts_by_category'
        ));
  });
