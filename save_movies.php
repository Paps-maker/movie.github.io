<?php
$moviesFile = 'movies.json';
$movies = file_exists($moviesFile) ? json_decode(file_get_contents($moviesFile), true) : [];

// ----- Handle adding a new movie -----
if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add'){
    $title = $_POST['title'] ?? '';
    $year = $_POST['year'] ?? '';
    $genres = isset($_POST['genres']) ? explode(',', $_POST['genres']) : [];
    $desc = $_POST['description'] ?? '';
    $poster = '';
    $file = '';
    $episodes = [];

    // Handle poster upload
    if(isset($_FILES['poster']) && $_FILES['poster']['tmp_name'] != ''){
        $poster = 'uploads/'.time().'_'.basename($_FILES['poster']['name']);
        move_uploaded_file($_FILES['poster']['tmp_name'],$poster);
    }

    // Handle main movie file
    if(isset($_FILES['movieFile']) && $_FILES['movieFile']['tmp_name'] != ''){
        $file = 'uploads/'.time().'_'.basename($_FILES['movieFile']['name']);
        move_uploaded_file($_FILES['movieFile']['tmp_name'],$file);
    }

    // Handle episodes
    if(isset($_FILES['episodes'])){
        foreach($_FILES['episodes']['tmp_name'] as $key => $tmpName){
            if($tmpName){
                $epPath = 'uploads/'.time().'_'.basename($_FILES['episodes']['name'][$key]);
                move_uploaded_file($tmpName,$epPath);
                $episodes[] = $epPath;
            }
        }
    }

    $movies[] = [
        'title'=>$title,
        'year'=>$year,
        'genres'=>$genres,
        'poster'=>$poster,
        'file'=>$file,
        'episodes'=>$episodes,
        'trailer'=>$_POST['trailer'] ?? '',
        'description'=>$desc
    ];

    file_put_contents($moviesFile,json_encode($movies,JSON_PRETTY_PRINT));
    echo json_encode(['success'=>true,'movies'=>$movies]);
    exit;
}

// ----- Handle deleting a movie -----
if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['deleteIndex'])){
    $idx = (int)$_POST['deleteIndex'];
    if(isset($movies[$idx])){
        // Delete poster
        if(!empty($movies[$idx]['poster']) && file_exists($movies[$idx]['poster'])){
            unlink($movies[$idx]['poster']);
        }
        // Delete main movie
        if(!empty($movies[$idx]['file']) && file_exists($movies[$idx]['file'])){
            unlink($movies[$idx]['file']);
        }
        // Delete episodes
        if(!empty($movies[$idx]['episodes'])){
            foreach($movies[$idx]['episodes'] as $ep){
                if(file_exists($ep)) unlink($ep);
            }
        }
        array_splice($movies,$idx,1);
        file_put_contents($moviesFile,json_encode($movies,JSON_PRETTY_PRINT));
    }
    echo json_encode(['success'=>true,'movies'=>$movies]);
    exit;
}

// ----- Return all movies -----
header('Content-Type: application/json');
echo json_encode($movies);
