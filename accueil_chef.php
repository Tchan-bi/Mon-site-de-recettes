<?php
session_start();
require_once(__DIR__ . '/functions/functions.php');
require_once(__DIR__ . '/db/connect.php');

redirectIfNotAuthorized(1); // Seuls les chefs accèdent ici

$chef_id = $_SESSION['email'];

$show_welcome_message = false;

if (isset($_SESSION['welcome_shown']) && $_SESSION['welcome_shown'] === false) {
    $show_welcome_message = true;
    $_SESSION['welcome_shown'] = true; // Ensuite on ne le montre plus
}

// Récupérer les recettes du chef
$stmt = $pdo->prepare("SELECT * FROM recipes WHERE chef_id = ?");
$stmt->execute([$chef_id]);
$recipes = $stmt->fetchAll();

?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Accueil Chef Cuisinier</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet" href="css/styles.css">
</head>

<body class="d-flex flex-column min-vh-100">

    <!-- HEADER -->
    <?php require_once(__DIR__ . '/header.php'); ?>

    <div class="container mt-4">

        <?php if (isset($_SESSION['access_denied'])): ?>
            <div class="alert alert-danger text-center" id="access-denied-message">
                <?php echo $_SESSION['access_denied'];
                unset($_SESSION['access_denied']); ?>
            </div>
        <?php endif; ?>

        <?php if ($show_welcome_message): ?>
            <div id="welcome-message" class="alert alert-success text-center">
                <h1>Bienvenue, Chef <?= htmlspecialchars($_SESSION['nom']); ?> 👨‍🍳</h1>
            </div>
        <?php endif; ?>

        <div class="container d-flex flex-column align-items-center">
            <?php foreach ($recipes as $recipe): ?>
                <div class="card mb-4 shadow-sm" style="max-width: 700px; width: 100%;">
                    <div class="card-body">
                        <h5 class="card-title">
                            <a href="recipes_comments.php?id=<?= $recipe['id'] ?>" class="text-decoration-none text-dark">
                                <?= htmlspecialchars($recipe['titre']) ?>
                            </a>
                        </h5>

                        <p class="card-text">
                            <strong>Description :</strong><br>
                            <?= nl2br(htmlspecialchars($recipe['description'])) ?>
                        </p>

                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <a href="recipes_update.php?id=<?= $recipe['id'] ?>" class="btn btn-outline-primary btn-sm">Éditer</a>
                                <a href="recipes_delete.php?id=<?= $recipe['id'] ?>" class="btn btn-outline-danger btn-sm">Supprimer</a>
                            </div>
                            <div class="text-muted">
                                👍🏾 <?= $recipe['likes'] ?? 0 ?> &nbsp;&nbsp; 👎🏾 <?= $recipe['dislikes'] ?? 0 ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>



    </div>

    <!-- FOOTER -->
    <?php require_once(__DIR__ . '/footer.php'); ?>

    <script src="js/functions.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        hideAfterDelay("welcome-message", 3000); // 3 secondes
        hideAfterDelay("access-denied-message", 3000); // 3 secondes
    </script>
</body>

</html>