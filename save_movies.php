<?php
// save_movies.php
header('Content-Type: application/json');

$moviesFile = "movies.json";

// Get the POSTed JSON
$data = file_get_contents("php://input");
if(!$data){
    echo json_encode(["status"=>"error","message"=>"No data received"]);
    exit;
}

// Decode JSON to validate
$movies = json_decode($data, true);
if($movies === null){
    echo json_encode(["status"=>"error","message"=>"Invalid JSON"]);
    exit;
}

// Save to movies.json
if(file_put_contents($moviesFile, json_encode($movies, JSON_PRETTY_PRINT))){
    echo json_encode(["status"=>"success"]);
} else {
    echo json_encode(["status"=>"error","message"=>"Failed to write to movies.json"]);
}
?>
