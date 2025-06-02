<?php

session_start();

require_once(__DIR__ . '/db/connect.php');

/**
 * On ne traite pas les super globales provenant de l'utilisateur directement,
 * ces données doivent être testées et vérifiées.
 */
$postData = $_POST;

if (!isset($postData['id']) || !is_numeric($postData['id'])) {
    echo 'Il faut un identifiant valide pour supprimer une recette.';
    return;
}

$deleteRecipeStatement = $pdo->prepare('DELETE FROM recipes WHERE id = :id');
$deleteRecipeStatement->execute([
    'id' => (int)$postData['id'],
]);

header('Location: accueil_chef.php');
