document.getElementById('searchGerants').addEventListener('input', function () {
    const filter = this.value.toLowerCase();
    const options = document.getElementById('gerants').options;

    for (let i = 0; i < options.length; i++) {
        const text = options[i].textContent || options[i].innerText;
        options[i].style.display = text.toLowerCase().includes(filter) ? '' : 'none';
    }
});