<?php
session_start();
session_unset();
session_destroy();
header("Location: index.php");  // Redirige l'utilisateur vers la page de login
exit();
