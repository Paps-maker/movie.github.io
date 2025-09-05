<?php
// save_movies.php
header('Content-Type: application/json');

// Optional: Simple admin check
$adminPassword = '@Stone001'; // same as in JS
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $json = file_get_contents('php://input');
    if (!$json) {
        echo json_encode(['status' => 'error', 'message' => 'No data received']);
        exit;
    }

    $data = json_decode($json, true);
    if ($data === null) {
        echo json_encode(['status' => 'error', 'message' => 'Invalid JSON']);
        exit;
    }

    // Save to movies.json
    if (file_put_contents('movies.json', json_encode($data, JSON_PRETTY_PRINT))) {
        echo json_encode(['status' => 'success', 'message' => 'Movies saved successfully']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to save file']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
}
?>
