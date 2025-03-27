<?php
$apiKey = 'b7734675'; // Use your actual API key
$searchResults = [];
$movieDetails = null;
$error = '';

// Handle search request
if (isset($_GET['title']) && !empty($_GET['title'])) {
    $title = urlencode($_GET['title']);
    $url = "http://www.omdbapi.com/?s=$title&apikey=$apiKey";
    
    $response = file_get_contents($url);
    $movies = json_decode($response, true);
    
    if ($movies && $movies['Response'] == "True") {
        $searchResults = $movies['Search'];
    } else {
        $error = "No movies found or API error.";
    }
}

// Handle movie details request
if (isset($_GET['movie_id']) && !empty($_GET['movie_id'])) {
    $movieId = $_GET['movie_id'];
    $url = "http://www.omdbapi.com/?i=$movieId&apikey=$apiKey";
    
    $response = file_get_contents($url);
    $movieDetails = json_decode($response, true);
    
    if (!$movieDetails || $movieDetails['Response'] != "True") {
        $movieDetails = null;
        $error = "Movie details not found.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Movie Search App</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center">Movie Search</h1>
        <form method="GET" class="d-flex justify-content-center my-4">
            <input type="text" name="title" class="form-control w-50" placeholder="Enter movie title" required>
            <button type="submit" class="btn btn-primary ms-2">Search</button>
        </form>

        <div class="row">
            <?php if (!empty($searchResults)): ?>
                <?php foreach ($searchResults as $movie): ?>
                    <div class="col-md-4 mb-3">
                        <div class="card">
                            <img src="<?= $movie['Poster'] ?>" class="card-img-top" alt="Poster">
                            <div class="card-body">
                                <h5 class="card-title"><?= $movie['Title'] ?> (<?= $movie['Year'] ?>)</h5>
                                <a href="?movie_id=<?= $movie['imdbID'] ?>" class="btn btn-info">View Details</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php elseif ($error): ?>
                <p class="text-center text-danger"> <?= $error ?> </p>
            <?php endif; ?>
        </div>

        <?php if ($movieDetails): ?>
            <div class="card mt-4 mx-auto" style="max-width: 600px;">
                <img src="<?= $movieDetails['Poster'] ?>" class="card-img-top" alt="Poster">
                <div class="card-body">
                    <h2 class="card-title text-center"> <?= $movieDetails['Title'] ?> (<?= $movieDetails['Year'] ?>) </h2>
                    <p><strong>Plot:</strong> <?= $movieDetails['Plot'] ?></p>
                    <p><strong>Actors:</strong> <?= $movieDetails['Actors'] ?></p>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
