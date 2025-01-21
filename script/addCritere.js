document.addEventListener('DOMContentLoaded', function () {
    let criterionCount = 0;
    console.log("DOMContentLoaded déclenché");
    const addButton = document.getElementById('add-criterion-btn');
    console.log("addButton trouvé :", addButton);

    if (addButton) {
        addButton.removeEventListener('click', addCriterion);
        addButton.addEventListener('click', addCriterion);
    }

    function addCriterion() {
        console.log("Bouton cliqué - Ajout d'un critère");

        const criteriaContainer = document.getElementById('criteria-container');
        const newCriterion = document.createElement('div');
        newCriterion.classList.add('card', 'mb-3');

        newCriterion.innerHTML = `
            <div class="card-body">
                <h4 class="card-title">Critère ${criterionCount + 1}</h4>
                <div class="mb-3">
                    <label for="critere_${criterionCount}_nom" class="form-label">Nom du critère</label>
                    <input type="text" class="form-control" name="criteria[${criterionCount}][nom]" placeholder="Nom du critère" required>
                </div>
                <div class="mb-3">
                    <label for="critere_${criterionCount}_description" class="form-label">Description</label>
                    <input type="text" class="form-control" name="criteria[${criterionCount}][description]" placeholder="Description du critère" required>
                </div>
                <div class="mb-3">
                    <label for="critere_${criterionCount}_coefficient" class="form-label">Coefficient</label>
                    <input type="number" step="0.01" class="form-control" name="criteria[${criterionCount}][coefficient]" placeholder="Coefficient" required>
                </div>
                <div class="mb-3">
                    <label for="critere_${criterionCount}_note_max" class="form-label">Note Maximale</label>
                    <input type="number" step="0.01" class="form-control" name="criteria[${criterionCount}][note_max]" placeholder="Note maximale" required>
                </div>
                <button type="button" class="btn btn-danger btn-sm delete-criterion-btn">Supprimer ce critère</button>
            </div>
        `;

        criteriaContainer.appendChild(newCriterion);
        criterionCount++;

        console.log(criteriaContainer);
        const deleteButton = newCriterion.querySelector('.delete-criterion-btn');
        deleteButton.addEventListener('click', function() {
            newCriterion.remove();
            criterionCount--;
        });
    }
});
