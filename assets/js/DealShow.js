
console.log("DealShow.js loaded!");
document.addEventListener('DOMContentLoaded', function() {
    // Gestion des votes
    document.querySelectorAll('.vote-btn').forEach(button => {
        button.addEventListener('click', function() {
            const commentId = this.dataset.id;
            const voteType = this.dataset.type; // "upvote" ou "downvote"

            // Simuler l'incrémentation des votes
            const counter = this.querySelector(voteType === 'upvote' ? '.positive-votes' : '.negative-votes');
            counter.textContent = parseInt(counter.textContent) + 1;

            // TODO: Envoyer la requête AJAX à Symfony ici
            console.log(`Vote ${voteType} pour le commentaire ID: ${commentId}`);
        });
    });

    // Affichage du formulaire de réponse 
    document.querySelectorAll('.reply-btn').forEach(button => {
        button.addEventListener('click', function() {
            const commentId = this.dataset.id;
            const replyForm = document.getElementById(`reply-form-${commentId}`);
            replyForm.style.display = replyForm.style.display === 'none' ? 'block' : 'none';
        });
    });
});

