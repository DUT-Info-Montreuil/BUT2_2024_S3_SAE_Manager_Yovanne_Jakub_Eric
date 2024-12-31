document.addEventListener('DOMContentLoaded', function () {
    const delegationModal = document.getElementById('delegationModal');

    if (delegationModal) {
        delegationModal.addEventListener('hidden.bs.modal', function () {
            document.body.classList.remove('modal-open');
            document.body.style.overflow = '';
        });
    }

    document.getElementById('modifierButton').addEventListener('click', function () {
        const delegation = document.getElementById('deleguer_evaluation').value;
        if (delegation) {
            const modal = new bootstrap.Modal(delegationModal);
            modal.show();
        } else {
            document.getElementById('modificationForm').submit();
        }
    });

    document.getElementById('stayEvaluatorButton').addEventListener('click', function () {
        document.getElementById('delegation_choice').value = 'stay';
        document.getElementById('modificationForm').submit();
    });

    document.getElementById('leaveEvaluatorButton').addEventListener('click', function () {
        document.getElementById('delegation_choice').value = 'leave';
        document.getElementById('modificationForm').submit();
    });


    /*
    forcer la suppression des modaux restants dans le DOM lors de leur fermeture
     */
    delegationModal.addEventListener('hidden.bs.modal', function () {
        document.querySelectorAll('.modal-backdrop').forEach(function (backdrop) {
            backdrop.remove();
        });
    });

});
