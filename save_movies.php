<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = file_get_contents('php://input');
    if (!$data) { http_response_code(400); echo json_encode(["status"=>"error","message"=>"No data received"]); exit; }
    $json = json_decode($data, true);
    if ($json === null) { http_response_code(400); echo json_encode(["status"=>"error","message"=>"Invalid JSON"]); exit; }
    file_put_contents('movies.json', json_encode($json, JSON_PRETTY_PRINT));
    echo json_encode(["status"=>"success","message"=>"Movies saved"]);
}
?>
