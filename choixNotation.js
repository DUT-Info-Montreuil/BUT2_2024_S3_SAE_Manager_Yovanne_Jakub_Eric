document.addEventListener('DOMContentLoaded', function () {
    const btnIndividuel = document.getElementById('btn-individuel');
    const btnGroupe = document.getElementById('btn-groupe');
    const formIndividuel = document.getElementById('form-individuel');
    const formGroupe = document.getElementById('form-groupe');

    // Initialement, afficher le formulaire individuel et cacher celui de groupe
    formIndividuel.style.display = 'block';
    formGroupe.style.display = 'none';

    // Fonction pour basculer vers le formulaire individuel
    btnIndividuel.addEventListener('click', function (e) {
        e.preventDefault();
        formIndividuel.style.display = 'block';  // Afficher le formulaire individuel
        formGroupe.style.display = 'none';      // Cacher le formulaire groupe
        console.log('Formulaire individuel affiché');
    });

    // Fonction pour basculer vers le formulaire groupe
    btnGroupe.addEventListener('click', function (e) {
        e.preventDefault();
        formGroupe.style.display = 'block';     // Afficher le formulaire groupe
        formIndividuel.style.display = 'none';  // Cacher le formulaire individuel
        console.log('Formulaire groupe affiché');
    });
});
