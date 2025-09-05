<?php
$moviesFile = 'movies.json';
$uploadsDir = 'uploads/';

// Ensure uploads folder exists
if (!is_dir($uploadsDir)) mkdir($uploadsDir, 0755, true);

// Load existing movies
$movies = [];
if(file_exists($moviesFile)){
    $movies = json_decode(file_get_contents($moviesFile), true);
}

// Handle POST request
if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $title = $_POST['title'] ?? '';
    $year = $_POST['year'] ?? '';
    $genres = $_POST['genres'] ?? '';
    $description = $_POST['description'] ?? '';
    $trailer = $_POST['trailer'] ?? '';
    $posterURL = $_POST['posterURL'] ?? '';

    $movie = [
        'title' => $title,
        'year' => $year,
        'genres' => array_map('trim', explode(',', $genres)),
        'description' => $description,
        'trailer' => $trailer,
        'episodes' => [],
    ];

    // Handle poster upload
    if(isset($_FILES['posterFile']) && $_FILES['posterFile']['error']===0){
        $ext = pathinfo($_FILES['posterFile']['name'], PATHINFO_EXTENSION);
        $posterPath = $uploadsDir.'poster_'.time().'.'.$ext;
        move_uploaded_file($_FILES['posterFile']['tmp_name'], $posterPath);
        $movie['poster'] = $posterPath;
    } else {
        $movie['poster'] = $posterURL;
    }

    // Handle main movie file
    if(isset($_FILES['movieFile']) && $_FILES['movieFile']['error']===0){
        $ext = pathinfo($_FILES['movieFile']['name'], PATHINFO_EXTENSION);
        $moviePath = $uploadsDir.'movie_'.time().'.'.$ext;
        move_uploaded_file($_FILES['movieFile']['tmp_name'], $moviePath);
        $movie['file'] = $moviePath;
    }

    // Handle episodes
    if(isset($_FILES['episodes'])){
        foreach($_FILES['episodes']['tmp_name'] as $key => $tmpName){
            if($_FILES['episodes']['error'][$key]===0){
                $ext = pathinfo($_FILES['episodes']['name'][$key], PATHINFO_EXTENSION);
                $epPath = $uploadsDir.'ep_'.time().'_'.$key.'.'.$ext;
                move_uploaded_file($tmpName, $epPath);
                $movie['episodes'][] = $epPath;
            }
        }
    }

    $movies[] = $movie;
    file_put_contents($moviesFile, json_encode($movies, JSON_PRETTY_PRINT));
    echo json_encode(['success'=>true, 'movies'=>$movies]);
    exit;
}

// GET request returns all movies
header('Content-Type: application/json');
echo json_encode($movies);
