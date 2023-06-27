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
<?php require_once('includes/header.php');?>
<?php


  if (isset($_REQUEST['username'], $_REQUEST['email'], $_REQUEST['password'])){
    // récupérer le nom d'utilisateur 
    $username = stripslashes($_REQUEST['username']);

    // récupérer l'email 
    $email = stripslashes($_REQUEST['email']);
    
    // récupérer le mot de passe 
    $password = stripslashes($_REQUEST['password']);
  
    
    

      if($res){
        echo "<div class='sucess'>
              <h3>Vous êtes inscrit avec succès.</h3>
              <p>Cliquez ici pour vous <a href='login.php'>connecter</a></p>
        </div>";
      }
  }else{
?>
  <div class="box-container">
  <h1 class="box-title">S'inscrire</h1>
  <form action="" method="post">
      <div class="form-group">
        <input type="text" class="box-input" name="username" placeholder="Nom d'utilisateur" required />
        <input type="text" class="box-input" name="email" placeholder="Courriel" required />
        <input type="password" class="box-input" name="password" placeholder="Mot de passe" required />
        <button type="submit" name="submit">S'inscrire</button>
        <p class="box-register">Déjà inscrit? 
        <a href="login.php">Connectez-vous ici</a></p>
      </div>
  </form>
  </div>
<?php } ?>
</div>
</body>
</html>