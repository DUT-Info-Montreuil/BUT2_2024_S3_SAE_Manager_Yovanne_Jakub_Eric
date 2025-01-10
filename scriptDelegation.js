document.addEventListener('DOMContentLoaded', function () {
    document.getElementById('deleguer_evaluation').addEventListener('change', function () {
        var delegationChoice = this.value;
        var delegationRadioButtons = document.getElementById('delegationRadioButtons');

        if (delegationChoice !== "") {
            delegationRadioButtons.style.display = 'block';
        } else {
            delegationRadioButtons.style.display = 'none';
        }
    });
});