<div class="col-md-6 offset-md-3 mt-4">
    <div class="card shadow-lg p-4">
        <h3 class="text-center mb-4">Connexion</h3>
        <?php
        session_start();

        // if (!isset($_SESSION['email'])) {
        //     header('Location: index.php');
        //     exit();
        // }
        require_once(__DIR__ . '/db/connect.php');
        if (isset($_SESSION['erreur_login'])) {
            echo '<div class="alert alert-danger">' . $_SESSION['erreur_login'] . '</div>';
            unset($_SESSION['erreur_login']);
        }


        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        $csrf_token = $_SESSION['csrf_token'];
        ?>
        <form action="login_traitement.php" method="POST">
            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf_token) ?>">
            <div class="mb-3">
                <label for="email" class="form-label">Adresse email</label>
                <input type="email" autocomplete="email" class="form-control" id="email" name="email" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Mot de passe</label>
                <input type="password" autocomplete="current-password" class="form-control" id="password" name="password" required>
                <div class="form-check mt-2">
                    <input class="form-check-input" type="checkbox" id="showPassword">
                    <label class="form-check-label" for="showPassword">
                        Afficher le mot de passe
                    </label>
                </div>
                <div class="text-end">
                    <a href="forgot_password.php" class="small">Mot de passe oublié ?</a>
                </div>
            </div>


            <div class="d-grid">
                <button type="submit" class="btn btn-primary">Se connecter</button>
            </div>
        </form>
        <div class="mt-3 text-center">
            <a href="#" data-bs-toggle="modal" data-bs-target="#choixModal">Créer un nouveau compte</a>
        </div>
    </div>
</div>

<!-- ✅ MODAL POUR CHOIX UTILISATEUR -->
<div class="modal fade" id="choixModal" tabindex="-1" aria-labelledby="choixModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="choixModalLabel">Créer un compte</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
            </div>
            <div class="modal-body text-center">
                <p>Choisissez le type de compte :</p>
                <div class="d-grid gap-2">
                    <a href="formulaire.php?type=visiteur">Inscription Visiteur</a>
                    <!-- <button type="submit" class="btn btn-outline-primary">Compte Visiteur</button> -->

                    <a href="formulaire.php?type=chef">Inscription Chef</a>
                    <!-- <button type="submit" class="btn btn-outline-secondary">Compte Chef Cuisinier</button> -->

                </div>
            </div>
        </div>
    </div>
</div>
<script>
    document.getElementById('showPassword').addEventListener('change', function() {
        const passwordInput = document.getElementById('password');
        passwordInput.type = this.checked ? 'text' : 'password';
    });
</script>