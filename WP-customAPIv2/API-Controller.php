<?php
/**
 * Plugin Name: Custom APIv2
 * Description: Receive file and put in selected folder
 */


class Latest_Posts_Controller extends WP_REST_Controller {

    public function get_items_permissions_check($request) {
        return true;
      }

    public function register_routes() {
        $namespace = 'customAPI/v2';
        $path = 'latest-posts/(?P<category_id>\d+)';
        $path2 = 'posts/(?P<category_id>\d+)';
  
        register_rest_route( $namespace, '/' . $path, [
        array(
          'methods'             => 'GET',
          'callback'            => array( $this, 'get_items' ),
          'permission_callback' => array( $this, 'get_items_permissions_check' )
              ),
  
          ]);   
        register_rest_route( $namespace, '/' . $path2, [
        array(
            'methods'             => 'GET',
            'callback'            => array( $this, 'get_items' ),
            'permission_callback' => array( $this, 'get_items_permissions_check' )
                ),

            ]);     
          

      }




      public function get_items($request) {

        $args = array(
                'category' => $request['category_id']
        );
    
        $posts = get_posts($args);
    
    
        if (empty($posts)) {
    
                return new WP_Error( 'empty_category', 'there is no post in this category', array( 'status' => 404 ) );
        }
        return new WP_REST_Response($posts, 200);
      }

    
  }

  add_action('rest_api_init', function () {           
    $latest_posts_controller = new Latest_Posts_Controller();
    $latest_posts_controller->register_routes();
});
