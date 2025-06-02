<?php
session_start();
require_once(__DIR__ . '/db/connect.php');

$postData = $_POST;

if (
    !isset($postData['id'])
    || !is_numeric($postData['id'])
    || empty($postData['titre'])
    || empty($postData['description'])
    || trim(strip_tags($postData['titre'])) === ''
    || trim(strip_tags($postData['description'])) === ''
) {
    echo 'Il manque des informations pour permettre l\'édition du formulaire.';
    return;
}

$id = (int)$postData['id'];
$titre = trim(strip_tags($postData['titre']));
$description = trim(strip_tags($postData['description']));

$insertRecipeStatement = $pdo->prepare('UPDATE recipes SET titre = :titre, description = :description WHERE id = :id');
$insertRecipeStatement->execute([
    'titre' => $titre,
    'description' => $description,
    'id' => $id,
]);

// ✅ Message flash
$_SESSION['succes_message'] = false;

// ✅ Redirection vers la page de modification
header("Location: recipes_update.php?id=" . $id);
exit;
