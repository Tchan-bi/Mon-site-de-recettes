<?php
// Config avant session
ini_set('session.cookie_httponly', 1);
ini_set('session.cookie_secure', isset($_SERVER['HTTPS']));
ini_set('session.use_strict_mode', 1);
ini_set('session.cookie_samesite', 'lax');

session_start();
session_regenerate_id(true);



require_once(__DIR__ . '/db/connect.php');



// Protection CSRF
if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
    $_SESSION['erreur_login'] = "Accès refusé.";
    header("Location: index.php");
    exit();
    // die("Requête non autorisée (CSRF détecté)");

}

// Tentatives
$max_attempts = 10;
$lock_time = 900;

if (!isset($_SESSION['login_attempts'])) {
    $_SESSION['login_attempts'] = 0;
    $_SESSION['last_attempt_time'] = time();
}

if (time() - $_SESSION['last_attempt_time'] > $lock_time) {
    $_SESSION['login_attempts'] = 0;
}

if ($_SESSION['login_attempts'] >= $max_attempts) {
    die("Trop de tentatives. Réessayez plus tard.");
}

// Login
$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';

$stmt = $pdo->prepare("SELECT * FROM users WHERE mail = ?");
$stmt->execute([$email]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// echo "<pre>";
// print_r($user);
// echo "</pre>";


if ($user && password_verify($password, $user['password'])) {
    $_SESSION['login_attempts'] = 0;

    $type = (int)$user['type_user'];
    // echo "<pre>";
    // print_r($type);
    // echo "</pre>";
    // exit();

    $_SESSION['type_user'] = $type;
    $_SESSION['nom'] = $user['nom'];
    $_SESSION['prenom'] = $user['prenom'];
    $_SESSION['email'] = $user['mail'];
    $_SESSION['LOGGED_USER'] = [
        'email' => $user['mail'],
        'type_user' => (int)$user['type_user']  // CAST ICI
    ];

    $_SESSION['welcome_shown'] = false;
    $_SESSION['succes_message'] = false;

    if ($type === 1) {
        $_SESSION['flash'] = "Connexion réussie ! Bienvenue Chef " . $_SESSION['nom'];
        header("Location: accueil_chef.php");
        exit();
    } elseif ($type === 0) {
        header("Location: accueil_visiteur.php");
        exit();
    } else {
        $_SESSION['flash'] = "Type d'utilisateur non reconnu.";
        header("Location: index.php");
        exit();
    }
} else {
    $_SESSION['login_attempts']++;
    $_SESSION['last_attempt_time'] = time();
    $_SESSION['erreur_login'] = "Identifiants incorrects.";
    header("Location: index.php");
    exit();
}
