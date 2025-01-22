function filtrerEtudiants() {
    var input = document.getElementById("recherche_etudiant");
    var filter = input.value.toUpperCase();
    var options = document.getElementById("etudiants").getElementsByTagName("option");

    for (var i = 0; i < options.length; i++) {
        var text = options[i].textContent || options[i].innerText;
        if (text.toUpperCase().indexOf(filter) > -1) {
            options[i].style.display = "";
        } else {
            options[i].style.display = "none";
        }
    }
}