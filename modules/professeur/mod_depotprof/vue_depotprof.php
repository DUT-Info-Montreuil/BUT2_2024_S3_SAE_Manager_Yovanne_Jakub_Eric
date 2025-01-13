<?php
include_once 'generique/vue_generique.php';

class VueDepotProf extends VueGenerique
{
    public function __construct()
    {
        parent::__construct();
    }

    public function afficheAllDepotSAE($allDepot, $allGroupe)
    {
        ?>
        <div class="container mt-4">
            <h1 class="mb-4">Liste des Dépôts</h1>
            <div class="accordion" id="depotAccordion">
                <?php foreach ($allDepot as $index => $depot): ?>
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="heading-<?= $index ?>">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#collapse-<?= $index ?>" aria-expanded="false"
                                    aria-controls="collapse-<?= $index ?>">
                                <?= htmlspecialchars($depot['titre']) ?>
                            </button>
                        </h2>
                        <div id="collapse-<?= $index ?>" class="accordion-collapse collapse"
                             aria-labelledby="heading-<?= $index ?>" data-bs-parent="#depotAccordion">
                            <div class="accordion-body">
                                <strong>Date limite :</strong> <?= htmlspecialchars($depot['date_limite']) ?>
                                <button type="button" class="btn btn-link float-end p-0" data-bs-toggle="modal"
                                        data-bs-target="#editModal-<?= $index ?>">
                                    <img src="assets/edit-icon.png" alt="Modifier" width="24" height="24"
                                         style="cursor: pointer;">
                                </button>
                            </div>
                        </div>
                    </div>
                    <?php
                    $this->afficheModifDepot($index, $depot);
                    $this->afficheAjoutTpsSupplementaire($index, $depot, $allGroupe);
                    ?>
                <?php endforeach; ?>
            </div>
            <div class="d-flex justify-content-end mt-4">
                <a href="index.php?module=depotprof&action=creerDepot" class="btn btn-primary">
                    <i class="bi bi-plus-lg"></i> Ajouter un Dépôt
                </a>
            </div>
        </div>
        <?php
    }
    private function afficheModifDepot($index, $depot)
    {
        ?>
        <div class="modal fade" id="editModal-<?= $index ?>" tabindex="-1" aria-labelledby="editModalLabel-<?= $index ?>" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title" id="editModalLabel-<?= $index ?>">
                            <i class="bi bi-pencil-square"></i> Modifier le dépôt
                        </h5>
                        <button type="button" class="btn-close text-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="index.php?module=depotprof&action=modifierDepot" method="post">
                            <input type="hidden" name="id_rendu" value="<?= htmlspecialchars($depot['id_rendu']) ?>">
                            <div class="alert alert-warning text-center" role="alert">
                                <strong>Attention :</strong> La suppression d'un dépôt est irréversible !
                            </div>

                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="titre-<?= $index ?>" class="form-label">
                                        <i class="bi bi-tag"></i> Titre
                                    </label>
                                    <input type="text" class="form-control" id="titre-<?= $index ?>" name="titre"
                                           value="<?= htmlspecialchars($depot['titre']) ?>" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="date_limite-<?= $index ?>" class="form-label">
                                        <i class="bi bi-calendar"></i> Date limite
                                    </label>
                                    <input type="date" class="form-control" id="date_limite-<?= $index ?>"
                                           name="date_limite" value="<?= htmlspecialchars($depot['date_limite']) ?>" required>
                                </div>
                            </div>

                            <div class="mt-3 d-flex justify-content-center">
                                <button type="submit" class="btn btn-success me-2">
                                    <i class="bi bi-check-circle"></i> Enregistrer
                                </button>
                                <button type="button" class="btn btn-warning" data-bs-toggle="modal"
                                        data-bs-target="#extendTimeModal-<?= $index ?>">
                                    <i class="bi bi-clock-history"></i> Donner du temps supplémentaire
                                </button>
                            </div>
                        </form>

                        <form action="index.php?module=depotprof&action=supprimerDepot" method="post" class="d-inline" onsubmit="return confirmationSupprimer();">
                            <input type="hidden" name="id_rendu" value="<?= htmlspecialchars($depot['id_rendu']) ?>">

                            <div class="mt-3 d-flex justify-content-center">
                                <button type="submit" class="btn btn-danger">
                                    <i class="bi bi-trash"></i> Supprimer le dépôt
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }
    private function afficheAjoutTpsSupplementaire($index, $depot, $allGroupe)
    {
        ?>
        <div class="modal fade" id="extendTimeModal-<?= $index ?>" tabindex="-1" aria-labelledby="extendTimeModalLabel-<?= $index ?>" aria-hidden="true">
            <div class="modal-dialog ">
                <div class="modal-content">
                    <div class="modal-header bg-warning text-white">
                        <h5 class="modal-title" id="extendTimeModalLabel-<?= $index ?>">
                            <i class="bi bi-clock"></i> Temps supplémentaire
                        </h5>
                        <button type="button" class="btn-close text-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="index.php?module=depotprof&action=ajouterTemps" method="post">
                            <input type="hidden" name="id_rendu" value="<?= htmlspecialchars($depot['id_rendu']) ?>">

                            <div class="mb-3">
                                <label for="groupes-<?= $index ?>" class="form-label">
                                    <i class="bi bi-people"></i> Sélectionner les groupes
                                </label>
                                <select class="form-select" id="groupes-<?= $index ?>" name="groupes[]" multiple required>
                                    <?php foreach ($allGroupe as $groupe) : ?>
                                        <option value="<?= htmlspecialchars($groupe['id_groupe']) ?>">
                                            <?= htmlspecialchars($groupe['nom']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="new_date_limite-<?= $index ?>" class="form-label">
                                    <i class="bi bi-calendar-plus"></i> Nouvelle date limite
                                </label>
                                <input type="date" class="form-control" id="new_date_limite-<?= $index ?>" name="new_date_limite" required>
                            </div>

                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                    <i class="bi bi-x-circle"></i> Annuler
                                </button>
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-check-circle"></i> Enregistrer
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }
    public function formulaireCreerDepot()
    {
        ?>
        <div class="container mt-4">
            <h1 class="mb-4">Créer un Nouveau Dépôt</h1>
            <form action="index.php?module=depotprof&action=submitDepot" method="POST">
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
                <a href="index.php?module=depotprof&action=gestionDepotSAE" class="btn btn-secondary">Annuler</a>
            </form>
        </div>
        <?php
    }
}