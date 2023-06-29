<?php

session_start();

$errors = [
  'password' => '',
];

if (!isset($_SESSION['authenticated']) || $_SESSION['authenticated'] !== true) {
    header('Location: login.php');
    exit;
}

$filename = 'includes/data/user.json';

if (file_exists($filename)) {
  $users = json_decode(file_get_contents($filename), true);
}

if ($users === null) {
  die('Erreur lors du chargement des données des utilisateurs.');
}

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['userId'])) {
  $_GET = filter_input_array(INPUT_GET, [
    'userId' => FILTER_SANITIZE_NUMBER_INT,
  ]);
  $userId = isset($_GET['userId']) ? $_GET['userId'] : '';
  if ((isset($userId) && $userId !== '')) {
    $user = $users[$userId];
  }
} else {
  $user['userId'] = $_POST['user-id'];
  $user['email'] = $_POST['email'];
}



if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {

  $array = $_POST;
  array_walk_recursive($array, function (&$v) {
    $v = filter_var(trim($v), FILTER_SANITIZE_FULL_SPECIAL_CHARS);
  });
  $prepared = $array;

  if ($prepared['user-id']) {
    $userId = $prepared['user-id'];
  } 

  if ($prepared['email']) {
    $email = $prepared['email'];
  }

  if (isset($prepared['preferences'])) {
    $preferences = $prepared['preferences'];
  } 
  
  if ($prepared['password'] && $prepared['password-confirmation']) {
    $password = $prepared['password'];
    $passwordConfirmation = $prepared['password-confirmation'];
    if ($password !==  $passwordConfirmation) {
      $errors['password'] = 'Les deux mots de passe ne sont pas identiques!';
    }
  }

  if (empty(array_filter($errors, fn($element) => $element !== ''))) {
    foreach ($users as $index => $user) {
      if ($user['userId'] === intval($userId)) {
          if (isset($email)) {
            $user['email'] = $email;
          }
          if (isset($preferences)) {
            $user['preferences'] = $preferences;
          }
          if (isset($password)) {
            $user['password'] = $password;
          }
          $users[$index] = $user;
      }
  }
      file_put_contents('includes/data/user.json', json_encode(($users)));
      header('Location: profile.php?userId=' . $user['userId']);
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
    <h1>Bienvenue sur FoodieShare</h1>
    <div class="box-container">
      <form action="account_manager.php" method="post">
        <div class="form-group">
          <div class="preferences">
            <label for="html"><input type="checkbox" name="preferences[]" value="Italien"> Italien</label>
            <label for="Vegan"><input type="checkbox" name="preferences[]" value="Vegan"> Vegan</label>
            <label for="Asiatique"><input type="checkbox" name="preferences[]" value="Asiatique"> Asiatique</label>
            <label for="Mexicain"><input type="checkbox" name="preferences[]" value="Mexicain"> Mexicain</label>
            <label for="Français"><input type="checkbox" name="preferences[]" value="Français"> Français</label>
            <label for="Americain"><input type="checkbox" name="preferences[]" value="Americain"> Américain</label>
          </div>
          <input type="email" name="email" placeholder="Courriel" value="<?= $user['email'] ?? $user['email'] ?>">
          <input type="password" name="password" placeholder="Nouveau mot de passe">
          <input type="password" name="password-confirmation" placeholder="Confirmation nouveau mot de passe">
          <?php if ($errors['password']) : ?>
            <p class='text-danger'><?= $errors['password'] ?? '' ?>
          <?php endif; ?>
          <input type="hidden" name="user-id" value=<?= $user['userId'] ?? $user['userId'] ?>>
          <button type="submit" name="submit">Soumettre</button>
        </div>
      </form>
    </div>
    <?php require_once ('includes/footer.php');?>
  </div>
</body>
</html>