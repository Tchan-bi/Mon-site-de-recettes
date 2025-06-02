<?php
require_once(__DIR__ . '/db/connect.php');
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accueil</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="d-flex flex-column min-vh-100">
    <!-- HEADER -->
    <?php require_once(__DIR__ . '/header.php'); ?>

    <!-- CONTENU PRINCIPAL -->
    <div class="container mt-4">
        <!-- Message d'accueil -->
        <h1 class="text-center mb-4">Bienvenue sur votre site de recettes préféré !</h1>
        <?php
        // Redirection si utilisateur est déjà connecté
        if (isset($_SESSION['type_user'])) {
            if ($_SESSION['type_user'] == 0) {
                header("Location: accueil_visiteur.php");
                exit();
            } elseif ($_SESSION['type_user'] == 1) {
                header("Location: accueil_chef.php");
                exit();
            }
        }

        ?>

        <!-- Formulaire centré -->
        <div class="row justify-content-center">
            <div class="col-md-6">
                <?php require_once(__DIR__ . '/login.php'); ?>
            </div>
        </div>
    </div>

    <!-- FOOTER -->
    <?php require_once(__DIR__ . '/footer.php'); ?>

    <!-- Script Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>