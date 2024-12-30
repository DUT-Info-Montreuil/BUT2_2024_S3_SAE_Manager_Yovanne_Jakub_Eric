<?php
include_once 'generique/vue_generique.php';
class VueDepotBase extends VueGenerique
{
    public function __construct()
    {
        parent::__construct();
    }
    public function afficherDepotBase($depot, $actionModifier, $actionSupprimer, $actionCreerDepot)
    {
        ?>
        <div class="container mt-4">
            <h1 class="mb-4">Liste des Dépôts</h1>
            <div class="accordion" id="depotAccordion">
                <?php foreach ($depot as $index => $item): ?>
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="heading-<?= $index ?>">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#collapse-<?= $index ?>" aria-expanded="false"
                                    aria-controls="collapse-<?= $index ?>">
                                <?= htmlspecialchars($item['titre']) ?>
                            </button>
                        </h2>
                        <div id="collapse-<?= $index ?>" class="accordion-collapse collapse"
                             aria-labelledby="heading-<?= $index ?>" data-bs-parent="#depotAccordion">
                            <div class="accordion-body">
                                <strong>Date limite :</strong> <?= htmlspecialchars($item['date_limite']) ?>
                                <button type="button" class="btn btn-link float-end p-0" data-bs-toggle="modal"
                                        data-bs-target="#editModal-<?= $index ?>">
                                    <img src="assets/edit-icon.png" alt="Modifier" width="24" height="24"
                                         style="cursor: pointer;">
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="modal fade" id="editModal-<?= $index ?>" tabindex="-1" aria-labelledby="editModalLabel-<?= $index ?>" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="editModalLabel-<?= $index ?>">Modifier le dépôt</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <form action="<?= $actionModifier ?>" method="post">
                                        <input type="hidden" name="id_rendu" value="<?= htmlspecialchars($item['id_rendu']) ?>">
                                        <div class="mb-3">
                                            <label for="titre-<?= $index ?>" class="form-label">Titre</label>
                                            <input type="text" class="form-control" id="titre-<?= $index ?>" name="titre"
                                                   value="<?= htmlspecialchars($item['titre']) ?>" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="date_limite-<?= $index ?>" class="form-label">Date limite</label>
                                            <input type="date" class="form-control" id="date_limite-<?= $index ?>"
                                                   name="date_limite" value="<?= htmlspecialchars($item['date_limite']) ?>" required>
                                        </div>

                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                                            <button type="submit" class="btn btn-primary">Enregistrer</button>
                                        </div>
                                    </form>

                                    <form action="<?= $actionSupprimer ?>" method="post" class="d-inline">
                                        <input type="hidden" name="id_rendu" value="<?= htmlspecialchars($item['id_rendu']) ?>">
                                        <button type="submit" class="btn btn-danger">Supprimer</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            <div class="d-flex justify-content-end mt-4">
                <a href="<?= $actionCreerDepot ?>" class="btn btn-primary">
                    <i class="bi bi-plus-lg"></i> Ajouter un Dépôt
                </a>
            </div>
        </div>
        <?php
    }

    public function formulaireCreerDepotBase($actionSubmit, $urlAnnuler)
    {
        ?>
        <div class="container mt-4">
            <h1 class="mb-4">Créer un Nouveau Dépôt</h1>
            <form action="<?= $actionSubmit ?>" method="POST">
                <div class="mb-3">
                    <label for="titre" class="form-label">Titre du Dépôt</label>
                    <input type="text" class="form-control" id="titre" name="titre" placeholder="Titre du dépôt"
                           required>
                </div>
                <div class="mb-3">
                    <label for="date_limite" class="form-label">Date Limite</label>
                    <input type="date" class="form-control" id="date_limite" name="date_limite" required>
                </div>
                <button type="submit" class="btn btn-primary">Créer</button>
                <a href="<?= $urlAnnuler ?>" class="btn btn-secondary">Annuler</a>
            </form>
        </div>
        <?php
    }
}
?>
