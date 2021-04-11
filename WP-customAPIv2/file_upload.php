<?php

/**
 * Plugin Name: Custom APIv3
 * Description: Receive file and put in selected folder
 */

class PluginException extends Exception
{
    public function restApiErrorResponse()
    {
        //error message
        $errorMsg = 'Error on line ' . $this->getLine() . ' in ' . $this->getFile()
            . ':  ' . $this->getMessage();
        $response = new WP_Error('Not Uploaded', $errorMsg, array('status' => 404));
        return rest_ensure_response($response);
    }
}


class MyPluginRestAPI
{

    private const BASE = 'customAPI/v3';


    public function initRoutes()
    {

        register_rest_route(self::BASE, 'latest-posts/(?P<category_id>\d+)', array(
            'methods'  => 'GET',
            'callback' => 'get_latest_posts_by_category'
        ));

        register_rest_route(self::BASE, '/import/csv', [
            'methods' => ['POST'],
            'callback' => [$this, 'importCSVPostRequestHandler'],

        ]);
    }


    public function importCSVPostRequestHandler(WP_REST_Request $request)
    {

        // if you sent any parameters along with the request, you can access them like so:
        // $myParam = $request->get_param('my_param');

        $permittedExtension = 'csv';
        $permittedTypes = ['text/csv', 'text/plain'];

        $files = $request->get_file_params();
        $headers = $request->get_headers();

        if (!empty($files) && !empty($files['file'])) {
            $file = $files['file'];
        }

        try {
            // smoke/sanity check
            if (!$file) {
                throw new PluginException('Error');
            }
            // confirm file uploaded via POST
            if (!is_uploaded_file($file['tmp_name'])) {
                throw new PluginException('File upload check failed ');
            }
            // confirm no file errors
            if (!$file['error'] === UPLOAD_ERR_OK) {
                throw new PluginException('Upload error: ' . $file['error']);
            }
            // confirm extension meets requirements
            $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
            if ($ext !== $permittedExtension) {
                throw new PluginException('Invalid extension. ');
            }
            // check type
            $mimeType = mime_content_type($file['tmp_name']);
            if (
                !in_array($file['type'], $permittedTypes)
                || !in_array($mimeType, $permittedTypes)
            ) {
                throw new PluginException('Invalid mime type');
            }
        } catch (PluginException $pe) {
            return $pe->restApiErrorResponse('...');
        }

        // we've passed our checks, now read and process the file
        $handle = fopen($file['tmp_name'], 'r');
        $headerFlag = true;
        while (($data = fgetcsv($handle, 1000, ',')) !== FALSE) { // next arg is field delim e.g. "'"
            // skip csv's header row / first iteration of loop
            if ($headerFlag) {
                $headerFlag = false;
                continue;
            }
            // process rows in csv body
            if ($data[0]) {
                $field1  = sanitize_text_field($data[0]);
                $field2  = sanitize_text_field($data[1]);
                // ... 
                // your code here to do something with the data
                // such as put it in the database, write it to a file, send it somewhere, etc. 
                // ...
            }
        }
        fclose($handle);
        // return any necessary data in the response here
        return rest_ensure_response(['success' => true]);
    }
}


add_action('rest_api_init', function () {
    $MyPluginRoutesI = new MyPluginRestAPI();
    $MyPluginRoutesI->initRoutes();
});
