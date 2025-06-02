console.log("JS chargé");

function hideAfterDelay(id, delay) {
    const element = document.getElementById(id);
    if (element) {
        setTimeout(() => {
            element.style.opacity = '0';
            setTimeout(() => {
                element.style.display = 'none';
            }, 500); // Pour attendre la transition CSS
        }, delay);
    }
}

function initPasswordToggle(showCheckboxId, passwordId, confirmPasswordId = null) {
    const showCheckbox = document.getElementById(showCheckboxId);
    const passwordInput = document.getElementById(passwordId);
    const confirmPasswordInput = confirmPasswordId ? document.getElementById(confirmPasswordId) : null;

    if (showCheckbox && passwordInput) {
        showCheckbox.addEventListener('change', function () {
            const type = this.checked ? 'text' : 'password';
            passwordInput.type = type;
            if (confirmPasswordInput) {
                confirmPasswordInput.type = type;
            }
        });
    }
}

 function validerMotDePasse() {
            const pwd = document.getElementById('password').value;
            const confirmPwd = document.getElementById('confirmPassword').value;

            if (pwd.length < 6) {
                alert("Le mot de passe doit contenir au moins 6 caractères.");
                return false;
            }

            if (pwd !== confirmPwd) {
                alert("Les mots de passe ne correspondent pas.");
                return false;
            }

            return true;
        }

        function chargerPays(selectId) {
    const select = document.getElementById(selectId);
    if (!select) return;

    fetch("https://restcountries.com/v3.1/all")
        .then(response => response.json())
        .then(data => {
            data
                .sort((a, b) => {
                    const nomA = a.translations?.fra?.common || a.name.common;
                    const nomB = b.translations?.fra?.common || b.name.common;
                    return nomA.localeCompare(nomB);
                })
                .forEach(country => {
                    const nomPays = country.translations?.fra?.common || country.name.common;
                    const option = document.createElement("option");
                    option.value = nomPays;
                    option.textContent = nomPays;
                    select.appendChild(option);
                });
        })
      .catch(error => {
    console.error("Erreur lors du chargement des pays :", error);
    const option = document.createElement("option");
    option.value = "";
    option.textContent = "Impossible de charger la liste des pays";
    select.appendChild(option);
});
}

function envoyerVote(recipeId, type) {
    fetch('vote.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
        },
        body: `recipe_id=${recipeId}&type=${type}`
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Mettre à jour le texte des boutons directement
            document.getElementById(`like-btn-${recipeId}`).innerText = `👍(${data.likes})`;
            document.getElementById(`dislike-btn-${recipeId}`).innerText = `👎(${data.dislikes})`;

            // Message si clic redondant
            if (data.status === "same") {
                alert("Tu as déjà voté ainsi !");
            } else if (data.status === "updated") {
                console.log("Vote modifié !");
            } else if (data.status === "new") {
                console.log("Nouveau vote !");
            }
        } else {
            alert("Erreur lors du traitement du vote.");
        }
    })
    .catch(error => {
        console.error('Erreur :', error);
        alert("Erreur réseau.");
    });
}



function voirRecette(id) {
    const key = 'recettes_vues';
    let vues = JSON.parse(localStorage.getItem(key)) || [];

    const descriptionDiv = document.getElementById("desc-" + id);
    if (!descriptionDiv) return;

    // Si déjà visible, on cache
    if (descriptionDiv.style.display === "block") {
        descriptionDiv.style.display = "none";
        return;
    }

    // Sinon, afficher si pas déjà vu et moins de 5 recettes vues
    if (!vues.includes(id)) {
        vues.push(id);
        localStorage.setItem(key, JSON.stringify(vues));
    }

    if (vues.length <= 5) {
        descriptionDiv.style.display = "block";
    } else {
        alert("Limite atteinte ! Pour voir plus de recettes, veuillez vous abonner.");
    }
}

document.addEventListener("DOMContentLoaded", () => {
    // Rien à faire ici pour l’instant, mais ça garantit que le DOM est prêt
});