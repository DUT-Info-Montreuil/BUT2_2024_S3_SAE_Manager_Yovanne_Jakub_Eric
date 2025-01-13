document.addEventListener('DOMContentLoaded', function() {
    let currentStudentId = null;
    let currentNoteInput = null;

    // Gestionnaire pour les boutons de malus
    document.querySelectorAll('.malus-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            currentStudentId = this.dataset.studentId;
            currentNoteInput = document.querySelector(`input[name="notes[${currentStudentId}]"]`);
            document.getElementById('malusInput').value = '';
            document.getElementById('malusInput').max = currentNoteInput.value;
        });
    });

    // Gestionnaire pour le bouton d'application du malus
    document.getElementById('applyMalus').addEventListener('click', function() {
        const malusInput = document.getElementById('malusInput');
        const malusValue = parseFloat(malusInput.value);
        const currentNote = parseFloat(currentNoteInput.value);

        if (malusValue <= currentNote && malusValue >= 0) {
            const newNote = (currentNote - malusValue).toFixed(2);
            currentNoteInput.value = newNote;
            malusInput.classList.remove('is-invalid');
            bootstrap.Modal.getInstance(document.getElementById('malusModal')).hide();
        } else {
            malusInput.classList.add('is-invalid');
        }
    });
});