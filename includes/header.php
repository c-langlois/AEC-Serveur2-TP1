<header>
    <div class="logo-container">
        <a class="header-left-side" href="index.php">
            <img class="logo" src="../assets/images/logo.svg" alt="Logo"></img>
            <span class="header-text">Foodie Share</span>
        </a>
    </div>
    <nav class="header-nav">
        <a class="header-button" href="../search.php">Recherche</a>
        <?php
            if (!isset($_SESSION['authenticated']) || $_SESSION['authenticated'] !== true) : ?>
                <a class="header-button" href="../login.php">Connexion</a>
            <?php else : ?>
                <a class="header-button" href="../profile.php?userId=<?= $_SESSION['userId'] ?>">Bonjour <?= $_SESSION['username'] ?></a>
            <?php endif; ?>
    </nav>
</header>
