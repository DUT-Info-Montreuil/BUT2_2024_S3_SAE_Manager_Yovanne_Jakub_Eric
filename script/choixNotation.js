document.addEventListener('DOMContentLoaded', function () {
    const btnIndividuel = document.getElementById('btn-individuel');
    const btnGroupe = document.getElementById('btn-groupe');
    const formIndividuel = document.getElementById('form-individuel');
    const formGroupe = document.getElementById('form-groupe');

    formIndividuel.style.display = 'block';
    formGroupe.style.display = 'none';

    btnIndividuel.addEventListener('click', function (e) {
        e.preventDefault();
        formIndividuel.style.display = 'block';
        formGroupe.style.display = 'none';
        console.log('Formulaire individuel affiché');
    });

    btnGroupe.addEventListener('click', function (e) {
        e.preventDefault();
        formGroupe.style.display = 'block';
        formIndividuel.style.display = 'none';
        console.log('Formulaire groupe affiché');
    });
});
