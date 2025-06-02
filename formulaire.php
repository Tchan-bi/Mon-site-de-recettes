<?php
session_start();
require_once(__DIR__ . '/db/connect.php');

$type = $_GET['type'] ?? 'visiteur';
$isChef = ($type === 'chef');
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription <?= $isChef ? 'Chef Cuisinier' : 'Visiteur' ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/styles.css">
</head>

<body class="bg-light">
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow-lg p-4">
                    <h3 class="text-center mb-4">Inscription <?= $isChef ? 'Chef Cuisinier' : 'Visiteur' ?></h3>

                    <?php if (isset($_SESSION['error'])): ?>
                        <div class="alert alert-danger">
                            <?= $_SESSION['error'];
                            unset($_SESSION['error']); ?>
                        </div>
                    <?php endif; ?>

                    <form action="traitement_inscription.php" method="POST">
                        <input type="hidden" name="type_utilisateur" value="<?= $isChef ? '1' : '0' ?>">

                        <!-- Nom -->
                        <div class="mb-3">
                            <label for="nom" class="form-label">Nom</label>
                            <input type="text" class="form-control" id="nom" name="nom" required>
                        </div>

                        <!-- Prénom -->
                        <div class="mb-3">
                            <label for="prenom" class="form-label">Prénom</label>
                            <input type="text" class="form-control" id="prenom" name="prenom" required>
                        </div>

                        <!-- Âge -->
                        <div class="mb-3">
                            <label for="age" class="form-label">Âge</label>
                            <input type="number" class="form-control" id="age" name="age" min="0" required>
                        </div>

                        <!-- Sexe -->
                        <div class="mb-3">
                            <label for="sexe" class="form-label">Sexe</label>
                            <select class="form-select" id="sexe" name="sexe" required>
                                <option value="">-- Sélectionnez --</option>
                                <option value="Homme">Homme</option>
                                <option value="Femme">Femme</option>
                            </select>
                        </div>

                        <!-- Pays -->
                        <div class="mb-3">
                            <label for="pays" class="form-label">Pays</label>
                            <select id="pays" name="pays" class="form-select"></select>
                        </div>

                        <!-- Email -->
                        <div class="mb-3">
                            <label for="email" class="form-label">Adresse email</label>
                            <input type="email" class="form-control" id="email" name="mail" autofocus required aria-describedby="emailHelp">
                        </div>

                        <?php if ($isChef): ?>
                            <!-- Domaine Culinaire -->
                            <div class="mb-3">
                                <label for="domaine" class="form-label">Domaine Culinaire</label>
                                <input type="text" class="form-control" id="domaine" name="domaine" required placeholder="Ex: Pâtisserie, Cuisine du Monde...">
                            </div>

                            <!-- Expérience -->
                            <div class="mb-3">
                                <label for="experience" class="form-label">Années d'expérience</label>
                                <input type="number" class="form-control" id="experience" name="experience" min="0" required>
                            </div>
                        <?php endif; ?>

                        <!-- Mot de passe -->
                        <div class="mb-3">
                            <label for="password" class="form-label">Mot de passe</label>
                            <input type="password" class="form-control" id="password" name="password" required minlength="6">
                        </div>

                        <!-- Confirmer mot de passe -->
                        <div class="mb-3">
                            <label for="confirmPassword" class="form-label">Confirmer le mot de passe</label>
                            <input type="password" class="form-control" id="confirmPassword" name="confirm_password" required>
                            <div class="form-check mt-2">
                                <input class="form-check-input" type="checkbox" id="showPassword">
                                <label class="form-check-label" for="showPassword">Afficher les mots de passe</label>
                            </div>
                        </div>

                        <?php if ($isChef): ?>
                            <!-- Abonnement -->
                            <div class="mb-3">
                                <label for="abonnement" class="form-label">Abonnement (optionnel)</label>
                                <input type="text" class="form-control" id="abonnement" name="abonnement" placeholder="Pour accéder aux fonctionnalités premium">
                                <small class="form-text text-muted">Ce champ sera activé une fois que le système de paiement sera disponible.</small>
                            </div>
                        <?php endif; ?>

                        <div class="d-grid mt-4">
                            <button type="submit" class="btn btn-primary">Créer mon compte</button>
                        </div>
                    </form>

                    <div class="mt-3 text-center">
                        <a href="index.php">Retour à l'accueil</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts JS -->
    <script src="js/functions.js"></script>
    <script>
        initPasswordToggle('showPassword', 'password', 'confirmPassword');
        chargerPays('pays');
    </script>
</body>

</html>