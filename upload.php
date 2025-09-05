<?php
header('Content-Type: application/json');
$uploadDir="uploads/";
if(!file_exists($uploadDir)) mkdir($uploadDir,0777,true);
if(!file_exists($uploadDir."posters")) mkdir($uploadDir."posters",0777,true);
if(!file_exists($uploadDir."movies")) mkdir($uploadDir."movies",0777,true);
if(!file_exists($uploadDir."episodes")) mkdir($uploadDir."episodes",0777,true);

if(!isset($_FILES['file']) || !isset($_POST['type'])) { echo json_encode(["status"=>"error","message"=>"No file or type"]); exit; }

$file=$_FILES['file']; $type=$_POST['type']; $targetDir=$uploadDir;
switch($type){ case"poster":$targetDir.="posters/";break; case"movie":$targetDir.="movies/";break; case"episode":$targetDir.="episodes/";break; default: echo json_encode(["status"=>"error","message"=>"Invalid type"]);exit; }

$ext=pathinfo($file['name'],PATHINFO_EXTENSION);
$filename=time()."_".uniqid().".".$ext;
$targetFile=$targetDir.$filename;
if(move_uploaded_file($file['tmp_name'],$targetFile)){ echo json_encode(["status"=>"success","url"=>$targetFile]); }
else{ echo json_encode(["status"=>"error","message"=>"Upload failed"]); }
?>
