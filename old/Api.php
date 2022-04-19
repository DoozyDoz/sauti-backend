<?php
/**
 * Created by PhpStorm.
 * User: Belal
 * Date: 10/5/2017
 * Time: 11:53 AM
 */
 
require_once dirname(__FILE__) . '/FileHandler.php';
 
$response = array();
 
if (isset($_GET['apicall'])) {
    switch ($_GET['apicall']) {
        case 'upload':
 
            if (isset($_POST['name']) && strlen($_POST['name']) > 0 && $_FILES['audio']['error'] === UPLOAD_ERR_OK) {
                $upload = new FileHandler();
 
                $file = $_FILES['audio']['tmp_name'];
                $name = $_POST['name'];
                $topic = $_POST['topic'];
                $date = $_POST['date'];
 
                if ($upload->saveFile($file, getFileExtension($_FILES['audio']['name']), $name, $topic, $date)) {
                    $response['error'] = false;
                    $response['message'] = 'File Uploaded Successfullly';
                }
 
            } else {
                $response['error'] = true;
                $response['message'] = 'Required parameters are not available';
            }
 
            break;
 
        case 'getallimages':
 
            $upload = new FileHandler();
            $response['error'] = false;
            $response['images'] = $upload->getAllFiles();
 
            break;
    }
}
 
echo json_encode($response);
 
function getFileExtension($file)
{
    $path_parts = pathinfo($file);
    return $path_parts['extension'];
}