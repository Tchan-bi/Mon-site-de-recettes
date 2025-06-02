<?php
session_start();
// Redirection si l'utilisateur n'est pas connecté ou n'est pas un visiteur
require_once(__DIR__ . '/db/connect.php');
require_once(__DIR__ . '/functions/functions.php');
redirectIfNotAuthorized(0);  // Vérifie que l'utilisateur est un visiteur (type_user = 0)

// Si le message de bienvenue n'a pas encore été affiché, définir la variable de session.
$show_welcome_message = false;

if (isset($_SESSION['welcome_shown']) && $_SESSION['welcome_shown'] === false) {
    $show_welcome_message = true;
    $_SESSION['welcome_shown'] = true; // Ensuite on ne le montre plus
}

// Préparation de la requête pour récupérer les recettes et les infos des chefs
$sql = "
    SELECT r.id, r.titre, r.description, u.nom, u.mail, u.age
    FROM recipes r
    JOIN users u ON r.chef_id = u.mail
    WHERE u.type_user != 0
";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$recettes = $stmt->fetchAll(PDO::FETCH_ASSOC);



?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Accueil Visiteur</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/styles.css">
</head>

<body class="d-flex flex-column min-vh-100">
    <!-- HEADER -->
    <?php require_once(__DIR__ . '/header.php'); ?>

    <div class="container mt-4">
        <!-- Message de bienvenue temporaire -->
        <?php if ($show_welcome_message): ?>
            <div id="welcome-message" class="alert alert-success text-center">
                <h1>Bienvenue, <?php echo htmlspecialchars($_SESSION['nom']); ?> !</h1>
            </div>
        <?php endif; ?>
        <h1 class="text-center mt-5"><u>Liste de Recettes</u></h1>

        <div class="container d-flex flex-column align-items-center">
            <?php foreach ($recettes as $recette): ?>
                <div class="recette" data-id="<?= $recette['id'] ?>">
                    <h2><?= htmlspecialchars($recette['titre']) ?></h2>
                    <p><strong>Chef :</strong> <?= htmlspecialchars($recette['nom']) ?> (<?= $recette['age'] ?> ans)</p>

                    <!-- Description cachée -->
                    <div id="desc-<?= $recette['id'] ?>" style="display: none;">
                        <p><?= nl2br(htmlspecialchars($recette['description'])) ?></p>
                    </div>

                    <!-- Bouton pour afficher -->
                    <button type="button" onclick="voirRecette(<?= $recette['id'] ?>)">👁 Voir la recette</button>

                    <?php
                    // Récupérer les votes pour chaque recette
                    $countsStmt = $pdo->prepare("SELECT type, COUNT(*) as total FROM votes WHERE recipe_id = ? GROUP BY type");
                    $countsStmt->execute([$recette['id']]);
                    $results = $countsStmt->fetchAll(PDO::FETCH_ASSOC);
                    $likes = 0;
                    $dislikes = 0;
                    foreach ($results as $row) {
                        if ($row['type'] === 'like') $likes = $row['total'];
                        if ($row['type'] === 'dislike') $dislikes = $row['total'];
                    }
                    ?>

                    <!-- Boutons de vote -->
                    <button id="like-btn-<?= $recette['id'] ?>" onclick="envoyerVote(<?= $recette['id'] ?>, 'like')">
                        👍 J’aime (<?= $likes ?>)
                    </button>
                    <button id="dislike-btn-<?= $recette['id'] ?>" onclick="envoyerVote(<?= $recette['id'] ?>, 'dislike')">
                        👎 Je n’aime pas (<?= $dislikes ?>)
                    </button>

                    <hr>
                </div>
            <?php endforeach; ?>

        </div>
    </div>

    <?php require_once(__DIR__ . '/footer.php'); ?>

    <!-- Script pour faire disparaître le message après 3 secondes -->
    <script src="js/functions.js"></script>
    <script>
        hideAfterDelay("welcome-message", 3000); // 3 secondes
    </script>
</body>

</html>