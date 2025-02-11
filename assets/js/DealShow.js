// Fonction pour afficher ou masquer le formulaire de réponse à un commentaire

window.addEventListener("turbo:load", function () {
  console.log("DealShow.js importé vie assets/app.js ✅");

  document.querySelectorAll(".reply-btn").forEach((button) => {
    button.addEventListener("click", function () {
      const commentId = this.dataset.id;
      const replyForm = document.getElementById(`reply-form-${commentId}`);
      if (replyForm) {
        replyForm.style.display =
          replyForm.style.display === "none" ? "block" : "none";
      }
    });
  });


    // Afficher le commentaire ciblé par son ID dans l'URL
  const urlParams = new URLSearchParams(window.location.search);
  if (urlParams.has("comment")) {
    const commentId = urlParams.get("comment");
    const commentElement = document.getElementById(`comment-${commentId}`);
    if (commentElement) {
      setTimeout(() => {
        commentElement.scrollIntoView({ behavior: "smooth" });
        commentElement.style.transition = "background 0.5s ease-in-out";
        commentElement.style.background = "#00a2bf19";
        setTimeout(() => (commentElement.style.background = ""), 1500);
      }, 500);
    }
  }
});

window.copyLink = function (event, commentId) {
  event.preventDefault(); // Empêche la redirection de l'élément <a>

  const link = window.location.href + "#comment-" + commentId;
  console.log("Lien du commentaire :", link);

  navigator.clipboard
    .writeText(link)
    .then(() => {
      // Afficher un SweetAlert pour notifier que le lien a été copié
      Swal.fire({
        icon: "success",
        title: "Succès",
        text: "Lien du commentaire copié !",
        showConfirmButton: false,
        timer: 2000, // Disparaît après 2 secondes
      });
    })
    .catch((err) => {
      console.error("Erreur lors de la copie :", err);
      // Afficher une alerte d'erreur si la copie échoue
      Swal.fire({
        icon: "error",
        title: "Erreur",
        text: "Une erreur est survenue lors de la copie.",
        showConfirmButton: false,
        timer: 2000, // Disparaît après 2 secondes
      });
    });
};

// fonctions pour l'édition d'un commentaire

window.enableEdit = function (event, commentId) {
    // Empêcher le comportement par défaut du lien (pas de redirection)
    event.preventDefault();

    // Trouver l'élément du commentaire à modifier
    const commentElement = document.getElementById('comment-content-' + commentId);

    // Récupérer le contenu actuel du commentaire
    const currentContent = commentElement.innerText;

    // Créer un champ textarea et y insérer le contenu actuel
    const textarea = document.createElement('textarea');
    textarea.classList.add('form-control');
    textarea.value = currentContent;

    // Créer un bouton d'enregistrement
    const saveButton = document.createElement('button');
    saveButton.textContent = 'Enregistrer';
    saveButton.classList.add('btn', 'btn-deal', 'mt-2', 'rounded-pill');

    // Créer un bouton d'annulation
    const cancelButton = document.createElement('button');
    cancelButton.textContent = 'Annuler';
    cancelButton.classList.add('btn', 'btn-secondary', 'mt-2', 'rounded-pill', 'ml-2');

    // Remplacer le contenu du commentaire par le textarea et les boutons
    commentElement.innerHTML = '';  // Vider l'élément du commentaire
    commentElement.appendChild(textarea);
    commentElement.appendChild(saveButton);
    commentElement.appendChild(cancelButton);

    // Ajouter un gestionnaire d'événements au bouton d'enregistrement
    saveButton.addEventListener('click', function () {
        const newContent = textarea.value;

        // Récupérer le token CSRF depuis l'input hidden
        const csrfToken = document.getElementById('csrf_token').value;

        // Envoie du contenu via AJAX
        fetch(`/comment/edit/${commentId}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': csrfToken 
            },
            body: JSON.stringify({ content: newContent })
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Si la modification est réussie, réafficher le texte mis à jour
                    commentElement.innerHTML = newContent;
                    Swal.fire({
                        icon: 'success',
                        title: 'Succès',
                        text: 'Commentaire mis à jour avec succès',
                        showConfirmButton: false,
                        timer: 1500
                    });
                } else {
                    console.log('Erreur lors de la mise à jour du commentaire');
                }
            })
            .catch(error => {
                console.error('Erreur AJAX :', error);
                console.log('Une erreur s\'est produite.');
            });
    });

    // Ajouter un gestionnaire d'événements au bouton d'annulation
    cancelButton.addEventListener('click', function () {
        commentElement.innerHTML = currentContent;
    });
}


