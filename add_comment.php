<?php

session_start();

if (!isset($_SESSION['authenticated']) || $_SESSION['authenticated'] !== true) {
    header('Location: login.php');
    exit;
}

$mealsJson = file_get_contents('includes/data/meal.json');
$meals = json_decode($mealsJson, true);

if ($meals === null) {
  die('Erreur lors du chargement des données des repas.');
}

$mealId = isset($_GET['mealId']) ? $_GET['mealId'] : '';
$userId = isset($_SESSION['userId']) ? $_SESSION['userId'] : '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
    $shortComment = $_POST['short-comment'];
    $longComment = $_POST['long-comment'];
    $photo = $_FILES['photo']['name'];
    $mealId = $_POST['meal-id'];
    $userId = $_POST['user-id'];
    $rating = $_POST['rating'];
    $errors = [];

    

   function debug($variable) {
    echo '<pre>';
    print_r($variable);
    echo '</pre>';
    }

    if (isset($_FILES['photo'])) {
        $tmp_name = $_FILES['photo']['tmp_name'];
        $file_extension = strrchr($_FILES['photo']['type'], "/");
        $file_extension = str_replace("/", ".", $file_extension);
        $file_name = date("ymdhs") . $file_extension;
        $folder = dirname(__FILE__) . '/assets/images/';
        $max_size = 5000000;
        $file_size = filesize($tmp_name);
        $extension_array = array('.png', '.jpg', '.jpeg');
        if ($file_size > $max_size) {
            $error = 'Fichier trop volumineux';
        }

        if (!in_array($file_extension, $extension_array)) {
            $error = "Mauvais type de fichier";
        }

        if(!isset($error)) {
            if(move_uploaded_file($tmp_name, $folder . $file_name)) {
                $photo= $file_name;
                echo "C'est réussi !";
            }

            else {
                echo "Ah...il semblerait que ça ne se passe pas comme prévu..";
            }
        }

        else {
            echo '<div>' . $error . '</div>';
        }
        //Si il n'y a pas d'erreurs, alors le nouvel article est ajouté au reste.
     if (empty(array_filter($errors, fn($e) => $e !== ''))) {
        foreach ($meals as $index => $meal) {
            if ($meal['id'] === intval($mealId)) {
                $meal['comment'] = [...$meal['comment'], [
                    "userId" => $userId,
                    "short" => $shortComment,
                    "rating" => $rating,
                    "long" => $longComment,
                    "image-comment"=>$photo
                ],
                ];
                $meals[$index] = $meal;
            }
        }
        file_put_contents('includes/data/meal.json', json_encode($meals));
        header('Location: index.php');
    } 
    } 
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>FoodieShare - Accueil</title>
  <link rel="stylesheet" href="assets/css/style.css">
</head>

<body>
  <div class="container">
    <?php require_once ('includes/header.php');?>
    <div class="box-container">
        <h1>Ajouter un commentaire</h1>
        <form method="post" action="add_comment.php" enctype="multipart/form-data">
            <div class="form-group">
                <input type="text" placeholder="Titre de votre commentaire" name="short-comment">
                <textarea name="long-comment" placeholder="Rédigez votre commentaire" id="" cols="30" rows="10"></textarea>
                <input type="file" name="photo" accept="image/png, image/jpeg">
                <input type="hidden" name="meal-id" value=<?= $mealId ?>>
                <input type="hidden" name="user-id" value=<?= $userId ?>>
                <select name="rating">
                    <option value="" disabled selected>Comment l'évalueriez-vous?</option>
                    <option value="1">1 étoile</option>
                    <option value="2">2 étoiles</option>
                    <option value="3">3 étoiles</option>
                    <option value="4">4 étoiles</option>
                    <option value="5">5 étoiles</option>
                </select>
                <button type="submit" name="submit">Soumettre</button>
            </div>
        </form>
    </div>
    <?php require_once ('includes/footer.php');?>
  </div>
</body>
</html>