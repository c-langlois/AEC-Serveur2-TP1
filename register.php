<?php
// Chargement des données des utilisateurs depuis le fichier JSON
$usersJson = file_get_contents('includes/data/user.json');
$users = json_decode($usersJson, true);

$errors = [
  'password' => '',
];

if ($users === null) {
  die('Erreur lors du chargement des données des utilisateurs.');
}
// Vérification si la requête est de type POST et si le bouton de soumission a été cliqué
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
  // Génération d'un nouvel ID pour le nouvel utilisateur en incrémentant le plus grand ID existant
  $userId = max(array_keys($users));
  $userId++;
  // Récupération des données du formulaire

  $array = $_POST;
  array_walk_recursive($array, function (&$v) {
    $v = filter_var(trim($v), FILTER_SANITIZE_FULL_SPECIAL_CHARS);
  });
  $prepared = $array;

  if ($prepared['username']) {
    $username = $prepared['username'];
  }
  if ($prepared['email']) {
    $email = $prepared['email'];
  }
  if ($prepared['preferences']) {
    $preferences = $prepared['preferences'];
  }
  if ($prepared['password'] && $prepared['password-confirmation']) {
    $password = $prepared['password'];
    $passwordConfirmation = $prepared['password-confirmation'];
    if ($password !==  $passwordConfirmation) {
      $errors['password'] = 'Les deux mots de passe ne sont pas identiques!';
    }
  }
  $errors = [];
  // Validation des données du formulaire
  // Si aucune erreur n'est présente, on ajoute le nouvel utilisateur et on met à jour le fichier JSON
  if (empty(array_filter($errors, fn ($element) => $element !== ''))) {
    $users = [
      ...$users, [
        "userId" => $userId,
        "username" => $username,
        "email" => $email,
        "preferences" => $preferences,
        "password" => $password
      ],
    ];
    file_put_contents('includes/data/user.json', json_encode(($users)));
    // Affichage d'un message de succès
    echo "<div class='sucess'> 
      <h3>Vous êtes inscrit avec succès.</h3> 
      <p>Cliquez ici pour vous <a href='login.php'>connecter</a></p> 
    </div>";
  }
}

?>
<!DOCTYPE html>
<html>

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>FoodieShare - S'enregistrer</title>
  <link rel="stylesheet" href="assets/css/style.css">
</head>

<body>
  <div class="container">
    <?php require_once('includes/header.php'); ?>
    <div class="box-container">
      <h1>S'inscrire</h1>
      <form action="register.php" method="post">
        <div class="form-group">
          <input type="text" name="username" placeholder="Nom d'utilisateur" required />
          <input type="email" name="email" placeholder="Courriel" required />
          <div class="preferences">
            <label for="html"><input type="checkbox" name="preferences[]" value="Italien"> Italien</label>
            <label for="Vegan"><input type="checkbox" name="preferences[]" value="Vegan"> Vegan</label>
            <label for="Asiatique"><input type="checkbox" name="preferences[]" value="Asiatique"> Asiatique</label>
            <label for="Mexicain"><input type="checkbox" name="preferences[]" value="Mexicain"> Mexicain</label>
            <label for="Français"><input type="checkbox" name="preferences[]" value="Français"> Français</label>
            <label for="Americain"><input type="checkbox" name="preferences[]" value="Americain"> Américain</label>
          </div>
          <input type="password" name="password" placeholder="Mot de passe" required />
          <input type="password" name="password-confirmation" placeholder="Mot de passe à nouveau" required />
          <?php if (isset($errors['password'])) : ?>
            <p class='text-danger'><?= $errors['password'] ?? '' ?>
            <?php endif; ?>
            <button type="submit" name="submit">S'inscrire</button>
            <p class="box-register">Déjà inscrit?
              <a href="login.php">Connectez-vous ici</a>
            </p>
        </div>
      </form>
    </div>
  </div>
</body>

</html>