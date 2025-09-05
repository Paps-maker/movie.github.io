<?php
header('Content-Type: application/json');

// Make sure uploads directory exists
$uploadDir = 'uploads/';
if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0777, true);
}

if (!isset($_FILES['file'])) {
    echo json_encode(['status'=>'error', 'message'=>'No file sent']);
    exit;
}

$file = $_FILES['file'];
$type = isset($_POST['type']) ? $_POST['type'] : 'file';

// Validate file type (optional, you can customize)
$allowedImage = ['image/jpeg','image/png','image/gif'];
$allowedVideo = ['video/mp4'];

if ($type === 'poster' && !in_array($file['type'], $allowedImage)) {
    echo json_encode(['status'=>'error', 'message'=>'Invalid poster type']);
    exit;
}
if (($type === 'movie' || $type === 'episode') && !in_array($file['type'], $allowedVideo)) {
    echo json_encode(['status'=>'error', 'message'=>'Invalid video type']);
    exit;
}

// Create unique filename
$ext = pathinfo($file['name'], PATHINFO_EXTENSION);
$targetFile = $uploadDir . time() . '_' . preg_replace('/\s+/', '_', basename($file['name']));

// Move uploaded file
if (move_uploaded_file($file['tmp_name'], $targetFile)) {
    echo json_encode(['status'=>'success', 'url'=>$targetFile]);
} else {
    echo json_encode(['status'=>'error', 'message'=>'Upload failed']);
}
?>
