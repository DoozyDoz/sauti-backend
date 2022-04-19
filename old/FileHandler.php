<?php
 

class FileHandler
{
 
    private $con;
 
    public function __construct()
    {
        require_once dirname(__FILE__) . '/DbConnect.php';
 
        $db = new DbConnect();
        $this->con = $db->connect();
    }
 
 
    public function saveFile($file, $extension, $names, $topic, $date)
    {
        $name = round(microtime(true) * 1000) . '.' . $extension;
        $filedest = dirname(__FILE__) . UPLOAD_PATH . $name;
        move_uploaded_file($file, $filedest);
 
        $url = $server_ip = gethostbyname(gethostname());
        $new = $url . UPLOAD_PATH . $name;
 
        $stmt = $this->con->prepare("INSERT INTO audios (url,name,date,topic) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $new, $names, $date, $topic);
        if ($stmt->execute())
            return true;
        return false;
    }
 
    public function getAllFiles()
    {
        $stmt = $this->con->prepare("SELECT id, description, url FROM images ORDER BY id DESC");
        $stmt->execute();
        $stmt->bind_result($id, $desc, $url);
 
        $images = array();
 
        while ($stmt->fetch()) {
 
            $temp = array();
            $absurl = 'http://' . gethostbyname(gethostname()) . '/ImageUploadApi' . UPLOAD_PATH . $url;
            $temp['id'] = $id;
            $temp['desc'] = $desc;
            $temp['url'] = $absurl;
            array_push($images, $temp);
        }
 
        return $images;
    }
 
}