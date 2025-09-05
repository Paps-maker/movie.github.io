<?php
header("Content-Type: application/json");

// Folder for uploads
$targetDir = "uploads/";

// Make sure the folder exists
if (!is_dir($targetDir)) {
    mkdir($targetDir, 0777, true);
}

if (!empty($_FILES["file"]["name"])) {
    $fileName = basename($_FILES["file"]["name"]);
    $targetFile = $targetDir . time() . "_" . $fileName;

    if (move_uploaded_file($_FILES["file"]["tmp_name"], $targetFile)) {
        echo json_encode([
            "status" => "success",
            "url" => $targetFile
        ]);
    } else {
        echo json_encode([
            "status" => "error",
            "message" => "Failed to move file"
        ]);
    }
} else {
    echo json_encode([
        "status" => "error",
        "message" => "No file received"
    ]);
}
?>
