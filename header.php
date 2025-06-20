<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container-fluid">
        <a class="navbar-brand">Site de recettes</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <?php
                    $accueilLink = 'index.php';
                    if (isset($_SESSION['type_user'])) {
                        if ($_SESSION['type_user'] == 0) {
                            $accueilLink = 'accueil_visiteur.php';
                        } elseif ($_SESSION['type_user'] == 1) {
                            $accueilLink = 'accueil_chef.php';
                        }
                    }
                    ?>
                    <a class="nav-link active" aria-current="page" href="<?= $accueilLink ?>">Accueil</a>

                </li>

                <?php if (isset($_SESSION['LOGGED_USER'])) : ?>
                    <li class="nav-item">
                        <a class="nav-link" href="contact.php">Contact</a>
                    </li>
                    <?php if (isset($_SESSION['type_user']) && $_SESSION['type_user'] == 1) : ?>
                        <li class="nav-item">
                            <a class="nav-link" href="recipes_create.php">Ajoutez une recette !</a>
                        </li>
                    <?php endif; ?>
                    <li class="nav-item">
                        <a class="nav-link" href="logout.php">Déconnexion</a>
                    </li>
                <?php endif; ?>


            </ul>
        </div>
    </div>
</nav>