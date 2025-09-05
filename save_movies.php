<?php
// save_movies.php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $json = file_get_contents('php://input');
    if($json){
        file_put_contents('movies.json', $json);
        echo json_encode(["status"=>"success"]);
    } else {
        echo json_encode(["status"=>"error","message"=>"No data received"]);
    }
} else {
    echo json_encode(["status"=>"error","message"=>"Invalid request method"]);
}
?>
