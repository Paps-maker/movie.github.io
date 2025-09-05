<?php
header('Content-Type: application/json');

$adminPassword = '@Stone001';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pwd = $_POST['password'] ?? '';
    if ($pwd !== $adminPassword) {
        echo json_encode(['status'=>'error','message'=>'Wrong password']);
        exit;
    }

    $moviesData = $_POST['movies'] ?? '';
    if (!$moviesData) {
        echo json_encode(['status'=>'error','message'=>'No movie data']);
        exit;
    }

    $movies = json_decode($moviesData, true);
    if ($movies === null) {
        echo json_encode(['status'=>'error','message'=>'Invalid JSON']);
        exit;
    }

    // Create folders if they don't exist
    if (!is_dir('uploads/posters')) mkdir('uploads/posters', 0777, true);
    if (!is_dir('uploads/movies')) mkdir('uploads/movies', 0777, true);
    if (!is_dir('uploads/episodes')) mkdir('uploads/episodes', 0777, true);

    // Handle poster upload
    foreach ($movies as $i => $m) {
        // Poster
        if (isset($_FILES['poster'.$i]) && $_FILES['poster'.$i]['error'] === 0) {
            $ext = pathinfo($_FILES['poster'.$i]['name'], PATHINFO_EXTENSION);
            $fileName = 'uploads/posters/'.time().'_'.$i.'.'.$ext;
            move_uploaded_file($_FILES['poster'.$i]['tmp_name'], $fileName);
            $movies[$i]['poster'] = $fileName;
        }

        // Movie file
        if (isset($_FILES['file'.$i]) && $_FILES['file'.$i]['error'] === 0) {
            $ext = pathinfo($_FILES['file'.$i]['name'], PATHINFO_EXTENSION);
            $fileName = 'uploads/movies/'.time().'_'.$i.'.'.$ext;
            move_uploaded_file($_FILES['file'.$i]['tmp_name'], $fileName);
            $movies[$i]['fileUrl'] = $fileName;
        }

        // Episodes
        if (isset($_FILES['episodes'.$i])) {
            $episodes = $_FILES['episodes'.$i];
            $movies[$i]['episodeIds'] = [];
            for ($j=0;$j<count($episodes['name']);$j++) {
                if ($episodes['error'][$j] === 0) {
                    $ext = pathinfo($episodes['name'][$j], PATHINFO_EXTENSION);
                    $fileName = 'uploads/episodes/'.time().'_'.$i.'_'.$j.'.'.$ext;
                    move_uploaded_file($episodes['tmp_name'][$j], $fileName);
                    $movies[$i]['episodeIds'][] = $fileName;
                }
            }
        }
    }

    if(file_put_contents('movies.json', json_encode($movies, JSON_PRETTY_PRINT))) {
        echo json_encode(['status'=>'success','message'=>'Movies uploaded and saved']);
    } else {
        echo json_encode(['status'=>'error','message'=>'Failed to save JSON']);
    }

} else {
    echo json_encode(['status'=>'error','message'=>'Invalid request method']);
}
?>
