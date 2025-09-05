<?php
// upload.php
header('Content-Type: application/json');

$uploadDir = "uploads/";

// Create folders if they don't exist
$folders = ["posters", "movies", "episodes"];
foreach($folders as $f){
    if(!file_exists("$uploadDir$f")) mkdir("$uploadDir$f", 0777, true);
}

if(!isset($_FILES['file']) || !isset($_POST['type'])){
    echo json_encode(["status"=>"error","message"=>"No file or type provided"]);
    exit;
}

$file = $_FILES['file'];
$type = $_POST['type'];

$targetDir = match($type){
    "poster" => $uploadDir . "posters/",
    "movie" => $uploadDir . "movies/",
    "episode" => $uploadDir . "episodes/",
    default => null
};

if(!$targetDir){
    echo json_encode(["status"=>"error","message"=>"Invalid type"]);
    exit;
}

// Generate unique filename
$ext = pathinfo($file['name'], PATHINFO_EXTENSION);
$filename = time() . "_" . uniqid() . "." . $ext;
$targetFile = $targetDir . $filename;

// Move uploaded file
if(move_uploaded_file($file['tmp_name'], $targetFile)){
    // Return relative path for front-end
    $url = $targetFile;
    echo json_encode(["status"=>"success","url"=>$url]);
}else{
    echo json_encode(["status"=>"error","message"=>"Upload failed"]);
}
?>
