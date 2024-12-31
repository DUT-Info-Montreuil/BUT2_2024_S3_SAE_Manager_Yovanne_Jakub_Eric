document.addEventListener('DOMContentLoaded', function () {
    const btnIndividuel = document.getElementById('btn-individuel');
    const btnGroupe = document.getElementById('btn-groupe');
    const formIndividuel = document.getElementById('form-individuel');
    const formGroupe = document.getElementById('form-groupe');

    btnIndividuel.addEventListener('click', function (e) {
        e.preventDefault();
        formIndividuel.style.display = 'block';
        formGroupe.style.display = 'none';
        console.log('Forme individuelle affichée');
    });

    btnGroupe.addEventListener('click', function (e) {
        e.preventDefault();
        formGroupe.style.display = 'block';
        formIndividuel.style.display = 'none';
        console.log('Forme groupe affichée');
    });

});




