<?php
include_once 'generique/vue_generique.php';

class VueSoutenanceProf extends VueGenerique
{
    public function __construct()
    {
        parent::__construct();
    }
    public function afficherAllSoutenance($allSoutenances, $idSae){
        ?>
        <div class="container mt-4">
            <h1 class="mb-4">Liste des Soutenances</h1>
            <div class="accordion" id="soutenanceAccordion">
                <?php foreach ($allSoutenances as $index => $soutenance): ?>
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="heading-<?= $index ?>">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#collapse-<?= $index ?>" aria-expanded="false"
                                    aria-controls="collapse-<?= $index ?>">
                                <?= htmlspecialchars($soutenance['titre']) ?>
                            </button>
                        </h2>
                        <div id="collapse-<?= $index ?>" class="accordion-collapse collapse"
                             aria-labelledby="heading-<?= $index ?>" data-bs-parent="#soutenanceAccordion">
                            <div class="accordion-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <strong>Date :</strong>
                                        <p><?= htmlspecialchars($soutenance['date_soutenance']) ?></p>
                                    </div>
                                    <button type="button" class="btn btn-link p-0" data-bs-toggle="modal"
                                            data-bs-target="#editModal-<?= $index ?>">
                                        <img src="assets/edit-icon.png" alt="Modifier" width="24" height="24" style="cursor: pointer;">
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal fade" id="editModal-<?= $index ?>" tabindex="-1"
                         aria-labelledby="editModalLabel-<?= $index ?>" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="editModalLabel-<?= $index ?>">Modifier la Soutenance</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <form action="index.php?module=soutenanceprof&action=modifierSoutenance&idProjet=<?php echo $idSae; ?>" method="post" enctype="multipart/form-data">
                                        <input type="hidden" name="id_soutenance" value="<?= htmlspecialchars($soutenance['id_soutenance']) ?>">
                                        <div class="mb-3">
                                            <label for="titre-<?= $index ?>" class="form-label">Titre</label>
                                            <input type="text" class="form-control" id="titre-<?= $index ?>" name="titre"
                                                   value="<?= htmlspecialchars($soutenance['titre']) ?>" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="date_soutenance-<?= $index ?>" class="form-label">Date de Soutenance</label>
                                            <input type="date" class="form-control" id="date_soutenance-<?= $index ?>" name="date_soutenance"
                                                   value="<?= htmlspecialchars($soutenance['date_soutenance']) ?>" required>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                                            <button type="submit" class="btn btn-primary">Enregistrer</button>
                                        </div>
                                    </form>

                                    <form action="index.php?module=soutenanceprof&action=supprimerSoutenance&idProjet=<?php echo $idSae; ?>" method="post" class="d-inline" onsubmit="return confirmationSupprimer();">
                                        <input type="hidden" name="token" value="<?= $_SESSION['token'] ?>">
                                        <input type="hidden" name="id_soutenance" value="<?= htmlspecialchars($soutenance['id_soutenance']) ?>">
                                        <button type="submit" class="btn btn-danger">Supprimer</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            <div class="d-flex justify-content-end mt-4">
                <a href="index.php?module=soutenanceprof&action=creerSoutenance&idProjet=<?php echo $idSae; ?>" class="btn btn-primary">
                    <i class="bi bi-plus-lg"></i> Ajouter une Soutenance
                </a>
            </div>
        </div>
        <?php
    }
    public function formulaireCreerSoutenance($idSae, $allGroupe)
    {
        ?>
        <div class="container mt-4">
            <h1 class="mb-4">Créer une Nouvelle Soutenance</h1>
            <form action="index.php?module=soutenanceprof&action=submitSoutenance&idProjet=<?php echo $idSae; ?>" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="token" value="<?= $_SESSION['token'] ?>">

                <div class="mb-3">
                    <label for="titre" class="form-label">Titre de la Soutenance</label>
                    <input
                            type="text"
                            class="form-control"
                            id="titre"
                            name="titre"
                            placeholder="Titre de la soutenance"
                            required>
                </div>

                <div class="mb-3">
                    <label for="date_soutenance" class="form-label">Date Générale de la Soutenance</label>
                    <input
                            type="date"
                            class="form-control"
                            id="date_soutenance"
                            name="date_soutenance"
                            required>
                </div>

                <h3 class="mt-4">Planification des Groupes</h3>
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                        <tr>
                            <th>Nom du Groupe</th>
                            <th>Heure de Passage</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($allGroupe as $groupe): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($groupe['nom']); ?></td>

                                <td>
                                    <input
                                            type="time"
                                            class="form-control"
                                            name="heure_passage[<?php echo $groupe['id_groupe']; ?>]"
                                            required>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <div class="d-flex justify-content-between">
                    <button type="submit" class="btn btn-primary">Créer</button>
                    <a href="index.php?module=soutenanceprof&action=gestionSoutenancesSAE&idProjet=<?php echo $idSae; ?>" class="btn btn-secondary">Annuler</a>
                </div>
            </form>
        </div>
        <?php
    }



}