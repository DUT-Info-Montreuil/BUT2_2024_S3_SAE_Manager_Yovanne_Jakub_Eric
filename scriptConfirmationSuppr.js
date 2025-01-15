function confirmationSupprimer(actionUrl, question, message) {
    // Créer une div pour la popup
    const popup = document.createElement("div");
    popup.style.position = "fixed";
    popup.style.top = "50%";
    popup.style.left = "50%";
    popup.style.transform = "translate(-50%, -50%)";
    popup.style.padding = "20px";
    popup.style.boxShadow = "0 0 10px rgba(0, 0, 0, 0.5)";
    popup.style.backgroundColor = "#fff";
    popup.style.zIndex = "1000";
    popup.style.width = "400px";
    popup.style.textAlign = "center";
    popup.style.borderRadius = "8px";

    // Contenu de la popup
    popup.innerHTML = `
            <h3>${question}</h3>
            <p>${message}</p>
            <div style="margin-top: 20px;">
                <button id="btnSupprimer" style="background-color: rgb(199,60,60); color: white; padding: 10px 20px; margin-right: 10px; border: none; border-radius: 4px; cursor: pointer;">Supprimer</button>
                <button id="btnAnnuler" style="background-color: grey; color: white; padding: 10px 20px; border: none; border-radius: 4px; cursor: pointer;">Annuler</button>
            </div>
        `;

    // Ajouter la popup au body
    document.body.appendChild(popup);

    // Gérer le clic sur "Supprimer"
    document.getElementById("btnSupprimer").onclick = function () {
        document.body.removeChild(popup); // Fermer la popup
        window.location.href = actionUrl; // Rediriger vers l'URL spécifiée
    };

    // Gérer le clic sur "Annuler"
    document.getElementById("btnAnnuler").onclick = function () {
        document.body.removeChild(popup); // Fermer la popup
    };
}