<?php
session_start();
require_once(__DIR__ . '/db/connect.php');

//Pour filtrer les champs
require_once(__DIR__ . '/functions/functions.php');

// Vérification des champs obligatoires
if (
    empty($_POST['nom']) || empty($_POST['prenom']) || empty($_POST['age']) ||
    empty($_POST['sexe']) || empty($_POST['pays']) || empty($_POST['mail']) ||
    empty($_POST['password']) || empty($_POST['confirm_password']) || !isset($_POST['type_utilisateur'])
) {
    $_SESSION['error'] = "Veuillez remplir tous les champs obligatoires.";
    header("Location: formulaire.php?type=" . ($_POST['type_utilisateur'] == 1 ? 'chef' : 'visiteur'));
    exit;
}

// Récupération des données
$nom = clean_input($_POST['nom']);
$prenom = clean_input($_POST['prenom']);
$age = (int) $_POST['age'];
$sexe = clean_input($_POST['sexe']);
$pays = clean_input($_POST['pays']);
$mail = filter_var($_POST['mail'], FILTER_SANITIZE_EMAIL);
$password = $_POST['password'];
$confirm_password = $_POST['confirm_password'];
$type_utilisateur = (int) $_POST['type_utilisateur']; // 1 = chef, 0 = visiteur

// Vérification mot de passe
if ($password !== $confirm_password) {
    $_SESSION['error'] = "Les mots de passe ne correspondent pas.";
    header("Location: formulaire.php?type=" . ($type_utilisateur == 1 ? 'chef' : 'visiteur'));
    exit;
}

// Hash du mot de passe
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

// Champs spécifiques au chef
$domaine = $experience = $abonnement = null;

if ($type_utilisateur === 1) {
    if (empty($_POST['domaine']) || empty($_POST['experience'])) {
        $_SESSION['error'] = "Veuillez renseigner le domaine culinaire et l'expérience.";
        header("Location: formulaire.php?type=chef");
        exit;
    }
    $domaine = clean_input($_POST['domaine']);
    $experience = (int) $_POST['experience'];
    $abonnement = !empty($_POST['abonnement']) ? clean_input($_POST['abonnement']) : null;
}

try {
    $sql = "INSERT INTO users (
    nom, prenom, age, sexe, pays, mail, password, type_user, domaine, experience, abonnement
) VALUES (
    :nom, :prenom, :age, :sexe, :pays, :mail, :password, :type_user, :domaine, :experience, :abonnement
)";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':nom' => $nom,
        ':prenom' => $prenom,
        ':age' => $age,
        ':sexe' => $sexe,
        ':pays' => $pays,
        ':mail' => $mail,
        ':password' => $hashed_password,
        ':type_user' => $type_utilisateur,
        ':domaine' => $domaine,
        ':experience' => $experience,
        ':abonnement' => $abonnement
    ]);


    $_SESSION['success'] = "Compte créé avec succès.";
    header("Location: index.php");
    exit;
} catch (PDOException $e) {
    if ($e->errorInfo[1] == 1062) {
        $_SESSION['error'] = "Cette adresse email est déjà utilisée.";
    } else {
        $_SESSION['error'] = "Erreur lors de la création du compte : " . $e->getMessage();
    }
    header("Location: formulaire.php?type=" . ($type_utilisateur == 1 ? 'chef' : 'visiteur'));
    exit;
}
