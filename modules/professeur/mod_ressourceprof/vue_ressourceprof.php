<?php
include_once 'generique/vue_generique.php';

class VueRessourceProf extends VueGenerique
{
    public function __construct()
    {
        parent::__construct();
    }
    public function afficherAllRessource($allRessources){
        ?>
        <div class="container mt-4">
            <h1 class="mb-4">Liste des Ressources</h1>
            <div class="accordion" id="ressourceAccordion">
                <?php foreach ($allRessources as $index => $ressource): ?>
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="heading-<?= $index ?>">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#collapse-<?= $index ?>" aria-expanded="false"
                                    aria-controls="collapse-<?= $index ?>">
                                <?= htmlspecialchars($ressource['titre']) ?>
                            </button>
                        </h2>
                        <div id="collapse-<?= $index ?>" class="accordion-collapse collapse"
                             aria-labelledby="heading-<?= $index ?>" data-bs-parent="#ressourceAccordion">
                            <div class="accordion-body">
                                <strong>Lien :</strong> <a href="<?= htmlspecialchars($ressource['lien']) ?>" target="_blank">
                                    <?= htmlspecialchars($ressource['lien']) ?></a><br>
                                <strong>Mise en avant :</strong> <?= $ressource['mise_en_avant'] ? 'Oui' : 'Non' ?>
                                <button type="button" class="btn btn-link float-end p-0" data-bs-toggle="modal"
                                        data-bs-target="#editModal-<?= $index ?>">
                                    <img src="assets/edit-icon.png" alt="Modifier" width="24" height="24"
                                         style="cursor: pointer;">
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="modal fade" id="editModal-<?= $index ?>" tabindex="-1"
                         aria-labelledby="editModalLabel-<?= $index ?>" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="editModalLabel-<?= $index ?>">Modifier la Ressource</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <form action="index.php?module=ressourceprof&action=modifierRessource" method="post" enctype="multipart/form-data">
                                        <input type="hidden" name="id_ressource" value="<?= htmlspecialchars($ressource['id_ressource']) ?>">
                                        <div class="mb-3">
                                            <label for="titre-<?= $index ?>" class="form-label">Titre</label>
                                            <input type="text" class="form-control" id="titre-<?= $index ?>" name="titre"
                                                   value="<?= htmlspecialchars($ressource['titre']) ?>" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="fichier-<?= $index ?>" class="form-label">Télécharger un fichier</label>
                                            <input type="file" class="form-control" id="fichier-<?= $index ?>" name="fichier">
                                            <?php if (!empty($ressource['lien'])): ?>
                                                <small class="form-text text-muted">Fichier actuel: <?= htmlspecialchars($ressource['lien']) ?></small>
                                            <?php endif; ?>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="mise_en_avant-<?= $index ?>" name="mise_en_avant"
                                                <?= $ressource['mise_en_avant'] ? 'checked' : '' ?>>
                                            <label class="form-check-label" for="mise_en_avant-<?= $index ?>">
                                                Mettre en avant
                                            </label>
                                        </div>

                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                                            <button type="submit" class="btn btn-primary">Enregistrer</button>
                                        </div>
                                    </form>

                                    <form action="index.php?module=ressourceprof&action=supprimerRessource" method="post" class="d-inline" onsubmit="return confirmationSupprimer();">
                                        <input type="hidden" name="id_ressource" value="<?= htmlspecialchars($ressource['id_ressource']) ?>">
                                        <button type="submit" class="btn btn-danger">Supprimer</button>
                                    </form>
                                    <script src="scriptConfirmationSuppr.js"></script>
                                </div>
                            </div>
                        </div>
                    </div>

                <?php endforeach; ?>
            </div>
            <div class="d-flex justify-content-end mt-4">
                <a href="index.php?module=ressourceprof&action=creerRessource" class="btn btn-primary">
                    <i class="bi bi-plus-lg"></i> Ajouter une Ressource
                </a>
            </div>
        </div>
        <?php
    }
    public function formulaireCreerRessource()
    {
        ?>
        <div class="container mt-4">
            <h1 class="mb-4">Créer une Nouvelle Ressource</h1>
            <form action="index.php?module=ressourceprof&action=submitRessource" method="POST" enctype="multipart/form-data">
                <div class="mb-3">
                    <label for="titre" class="form-label">Titre de la Ressource</label>
                    <input type="text" class="form-control" id="titre" name="titre" placeholder="Titre de la ressource" required>
                </div>
                <div class="mb-3">
                    <label for="fichier" class="form-label">Fichier</label>
                    <input type="file" class="form-control" id="fichier" name="fichier" required>
                    <small class="text-muted">Formats autorisés : PDF, DOCX, PNG, JPG, MP4, etc. (taille max : 10 MB)</small>
                </div>
                <div class="form-check mb-3">
                    <input class="form-check-input" type="checkbox" id="mise_en_avant" name="mise_en_avant">
                    <label class="form-check-label" for="mise_en_avant">Mettre en avant cette ressource</label>
                </div>
                <button type="submit" class="btn btn-primary">Créer</button>
                <a href="index.php?module=ressourceprof&action=gestionRessourceSAE" class="btn btn-secondary">Annuler</a>
            </form>
        </div>
        <?php
    }
}