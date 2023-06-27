<header>
    <div class="logo-container">
        <a class="header-left-side" href="index.php">
            <img class="logo" src="../assets/images/logo.svg" alt="Logo"></img>
            <span class="header-text">Foodie Share</span>
        </a>
    </div>
    <?php
    if (!isset($_SESSION['authenticated']) || $_SESSION['authenticated'] !== true) : ?>
   
    <?php else : ?>
    <span class = "header-greeting">Bonjour <?= $_SESSION['username'] ?> </span>    
    <?php endif; ?>
    <nav class="header-nav">
        <a class="header-button" href="../search.php">Recherche</a>
        <?php
            if (!isset($_SESSION['authenticated']) || $_SESSION['authenticated'] !== true) : ?>
                <a class="header-button" href="../login.php">Connexion</a>
            <?php else : ?>
            <a class="header-button" href="../profile.php?userId=<?= $_SESSION['userId'] ?>">Votre profil</a>
            <?php endif; ?>
    </nav>
</header>
