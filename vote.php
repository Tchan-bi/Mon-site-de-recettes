<?php
session_start();
require_once(__DIR__ . '/db/connect.php'); // Connexion à la base

if (!isset($_SESSION['email']) || $_SESSION['type_user'] != 0) {
    echo "Vous devez être connecté en tant que visiteur.";
    exit;
}

$visitor = $_SESSION['email'];
$recipe_id = $_POST['recipe_id'] ?? null;
$type = $_POST['type'] ?? null;

if (!$recipe_id || !in_array($type, ['like', 'dislike'])) {
    echo "Données invalides.";
    exit;
}

// Vérifie si ce visiteur a déjà voté pour cette recette
$sql = "SELECT id FROM votes WHERE recipe_id = ? AND visitor_id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$recipe_id, $visitor]);
$vote = $stmt->fetch();

if ($vote) {
    // Récupérer l'ancien type
    $currentVoteStmt = $pdo->prepare("SELECT type FROM votes WHERE id = ?");
    $currentVoteStmt->execute([$vote['id']]);
    $currentVote = $currentVoteStmt->fetchColumn();

    if ($currentVote === $type) {
        // Même vote qu’avant, inutile de faire quoi que ce soit
        $status = "same";
    } else {
        // Mise à jour si le vote est différent
        $update = "UPDATE votes SET type = ?, date_vote = NOW() WHERE id = ?";
        $stmt = $pdo->prepare($update);
        $stmt->execute([$type, $vote['id']]);
        $status = "updated";
    }
} else {
    // Nouveau vote
    $insert = "INSERT INTO votes (recipe_id, type, visitor_id) VALUES (?, ?, ?)";
    $stmt = $pdo->prepare($insert);
    $stmt->execute([$recipe_id, $type, $visitor]);
    $status = "new";
}

// Compter les likes et dislikes
$countsStmt = $pdo->prepare("SELECT type, COUNT(*) as total FROM votes WHERE recipe_id = ? GROUP BY type");
$countsStmt->execute([$recipe_id]);
$results = $countsStmt->fetchAll(PDO::FETCH_ASSOC);

// Initialiser les compteurs
$likes = 0;
$dislikes = 0;

foreach ($results as $row) {
    if ($row['type'] === 'like') $likes = $row['total'];
    if ($row['type'] === 'dislike') $dislikes = $row['total'];
}

// Réponse en JSON pour JS
echo json_encode([
    'success' => true,
    'status' => $status,
    'likes' => $likes,
    'dislikes' => $dislikes
]);
exit;
