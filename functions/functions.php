<?php

function clean_input($data)
{
    return trim(stripslashes($data));
}

/**
 * Redirige un utilisateur selon son rôle si ce n’est pas celui attendu.
 * @param int $expectedType 0 = visiteur, 1 = chef
 */
function redirectIfNotAuthorized($expectedType)
{
    if (!isset($_SESSION['type_user'])) {
        header("Location: index.php");
        exit();
    }

    if ($_SESSION['type_user'] != $expectedType) {
        $_SESSION['access_denied'] = "Accès refusé à cette page.";

        if ($_SESSION['type_user'] == 0) {
            header("Location: accueil_visiteur.php");
        } elseif ($_SESSION['type_user'] == 1) {
            header("Location: accueil_chef.php");
        } else {
            header("Location: index.php");
        }
        exit();
    }
}
