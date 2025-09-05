<?php
// upload.php

header('Content-Type: application/json');

$uploadDir = "uploads/";

// Create folders if they don't exist
if (!file_exists($uploadDir)) mkdir($uploadDir, 0777, true);
if (!file_exists($uploadDir."posters")) mkdir($uploadDir."posters", 0777, true);
if (!file_exists($uploadDir."movies")) mkdir($uploadDir."movies", 0777, true);
if (!file_exists($uploadDir."episodes")) mkdir($uploadDir."episodes", 0777, true);

if (!isset($_FILES['file']) || !isset($_POST['type'])) {
    echo json_encode(["status"=>"error","message"=>"No file or type provided"]);
    exit;
}

$file = $_FILES['file'];
$type = $_POST['type'];
$targetDir = $uploadDir;

switch($type){
    case "poster":
        $targetDir .= "posters/";
        break;
    case "movie":
        $targetDir .= "movies/";
        break;
    case "episode":
        $targetDir .= "episodes/";
        break;
    default:
        echo json_encode(["status"=>"error","message"=>"Invalid type"]);
        exit;
}

// Generate unique filename
$ext = pathinfo($file['name'], PATHINFO_EXTENSION);
$filename = time() . "_" . uniqid() . "." . $ext;
$targetFile = $targetDir . $filename;

// Move uploaded file
if(move_uploaded_file($file['tmp_name'], $targetFile)){
    $url = $targetFile; // Relative URL to store in movies.json
    echo json_encode(["status"=>"success", "url"=>$url]);
}else{
    echo json_encode(["status"=>"error","message"=>"Upload failed"]);
}
?>
