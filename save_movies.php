<?php
// save_movies.php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = file_get_contents('php://input');
    $movies = json_decode($data, true);

    if ($movies === null) {
        http_response_code(400);
        echo json_encode(["status" => "error", "message" => "Invalid JSON"]);
        exit;
    }

    // Save JSON to file
    $jsonFile = 'movies.json';
    if (file_put_contents($jsonFile, json_encode($movies, JSON_PRETTY_PRINT))) {
        echo json_encode(["status" => "success", "message" => "Movies saved"]);
    } else {
        http_response_code(500);
        echo json_encode(["status" => "error", "message" => "Failed to save movies"]);
    }
}
?>

