<?php
session_start();
require_once(__DIR__ . '/db/connect.php');

// Validation simple des champs
if (
    empty($_POST['titre']) ||
    empty($_POST['description']) ||
    trim(strip_tags($_POST['titre'])) === '' ||
    trim(strip_tags($_POST['description'])) === ''
) {
    $_SESSION['error'] = "Veuillez remplir tous les champs.";
    header("Location: recipes_create.php");
    exit;
}

// Vérifier si utilisateur connecté et type_user = 1 (chef)
if (
    !isset($_SESSION['email']) ||
    !isset($_SESSION['type_user']) ||
    (int)$_SESSION['type_user'] !== 1
) {
    $_SESSION['error'] = "Vous devez être connecté en tant que chef cuisinier pour ajouter une recette.";
    header("Location: recipes_create.php");
    exit;
}

$title = trim(strip_tags($_POST['titre']));
$recipe = trim(strip_tags($_POST['description']));
$chef_id = $_SESSION['email'];

try {
    $sql = "INSERT INTO recipes (chef_id, titre, description) VALUES (:chef_id, :titre, :description)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':chef_id' => $chef_id,
        ':titre' => $title,
        ':description' => $recipe
    ]);

    $_SESSION['success'] = "Recette ajoutée avec succès !";
    header("Location: recipes_create.php");
    exit;
} catch (PDOException $e) {
    $_SESSION['error'] = "Erreur lors de l'ajout de la recette : " . $e->getMessage();
    header("Location: recipes_create.php");
    exit;
}
