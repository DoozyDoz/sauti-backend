<?php
require "init.php";
if($con){
    function getAllFiles($cn)
    {
        $stmt = $cn->prepare("SELECT * FROM audios ORDER BY id DESC");
        $stmt->execute();
        $stmt->bind_result($id, $url, $name, $date, $topic); 
        $audios = array();
 
        while ($stmt->fetch()) {             
            $temp = array();
            $absurl = 'http://' . $url;
            $temp['id'] = $id;
            $temp['url'] = $absurl;
            $temp['name'] = $name;
            $temp['date'] = $date;
            $temp['topic'] = $topic;
            array_push($audios, $temp);
        }
 
        return $audios;
    }
    $response['audios'] = getAllFiles($con);
    echo json_encode($response,JSON_UNESCAPED_SLASHES);
}
?>