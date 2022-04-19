<?php
ini_set('display_errors',1);
ini_set('upload_max_filesize', '50M');
ini_set('post_max_size', '50M');
ini_set('max_input_time', 30000);
ini_set('max_execution_time', 30000);

require "init.php";
if($con){
    if (isset($_POST['name']) && strlen($_POST['name']) > 0 && isset($_POST['topic']) && strlen($_POST['topic']) > 0 && isset($_POST['date']) && strlen($_POST['date']) > 0 && $_FILES['audio']['error'] === UPLOAD_ERR_OK) {

    $sheikh_name = $_POST['name'];
    $topic = $_POST['topic'];
    $date = $_POST['date'];
    $upload_path = "uploads/$sheikh_name.mp3";
    $url = $server_ip = gethostbyname(gethostname());    
    $tmp_file = $_FILES['audio']['tmp_name'];
    $path_parts = pathinfo($_FILES['audio']['name']);
    $ext = $path_parts['extension'];
    $rand = round(microtime(true) * 1000);
    $name = $rand . '.' . $ext;
    $target_file = basename($_FILES['audio']['name']);//. "/uploads/" . $name;
    $upload_dir = "uploads";
    $name_tail = "/". $upload_dir."/". $rand ."_".$target_file;
    $new = $url ."/sauti". $name_tail;
    $response = array(); 

    function uplod($tmp, $ntail, $nw, $sname, $dt, $tpc, $cn){
        move_uploaded_file($tmp, dirname(__FILE__).$ntail); 
        $stmt = $cn->prepare("INSERT INTO audios (url,name,date,topic) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $nw, $sname, $dt, $tpc);
        if ($stmt->execute())
            return true;        
        return false;
    }

    if (uplod($tmp_file, $name_tail, $new, $sheikh_name, $date, $topic, $con)){
        $response['error'] = false;
        $response['message'] = 'File Uploaded Successfullly';
    }else{
        $response['error'] = true;
        $response['message'] = 'Uploading File Failed';        
    }        
}
echo json_encode($response);
mysqli_close($con); 
}


?>