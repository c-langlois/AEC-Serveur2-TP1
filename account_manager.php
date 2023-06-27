<?php

session_start();

if (!isset($_SESSION['authenticated']) || $_SESSION['authenticated'] !== true) {
    header('Location: login.php');
    exit;
}

$usersJson = file_get_contents('includes/data/user.json');
$users = json_decode($usersJson, true);

if ($users === null) {
  die('Erreur lors du chargement des données des utilisateurs.');
}

$userId = isset($_GET['userId']) ? $_GET['userId'] : '';
if ((isset($userId) && $userId !== '')) {
  $user = $users[$userId];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
  if ($_POST['user-id']) {
    $userId = $_POST['user-id'];
  } // s'assurez que le userId++ quand c'est un nouveau compte
  if ($_POST['email']) {
    $email = $_POST['email'];
  }
  if (isset($_POST['preferences'])) {
    $preferences = $_POST['preferences'];
  }
  if ($_POST['password'] && $_POST['password-confirmation']) {
    $password = $_POST['password'];
    $passwordConfirmation = $_POST['password-confirmation'];
  }
  $errors = [];

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
      header('Location: index.php');
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
          <input type="hidden" name="user-id" value=<?= $user['userId'] ?? $user['userId'] ?>>
          <button type="submit" name="submit">Soumettre</button>
        </div>
      </form>
    </div>
    <?php require_once ('includes/footer.php');?>
  </div>
</body>
</html>