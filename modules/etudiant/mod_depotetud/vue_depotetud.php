<?php

include_once 'generique/vue_generique.php';

class VueDepotEtud extends VueGenerique
{
    public function __construct()
    {
        parent::__construct();
    }
    public function afficherAllDepot($tabAllDepot)
    {
        ?>
        <div class="container mt-5">
            <h2 class="text-center mb-4" style="font-weight: bold; color: #343a40;">Tous les Dépôts</h2>
            <div class="accordion" id="depotAccordion">
                <?php
                if (!is_array($tabAllDepot) || empty($tabAllDepot)) {
                    $this->afficherMessageAucunDepot();
                } else {
                    foreach ($tabAllDepot as $index => $depot) {
                        $this->afficherDepot($index, $depot);
                    }
                }
                ?>
            </div>
        </div>
        <?php
    }

    private function afficherMessageAucunDepot()
    {
        ?>
        <div class="text-center">
            <p>Aucun dépôt disponible pour ce projet.</p>
        </div>
        <?php
    }

    private function afficherDepot($index, $depot)
    {
        ?>
        <div class="accordion-item mb-3">
            <h2 class="accordion-header" id="heading-<?= $index ?>">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                        data-bs-target="#collapse-<?= $index ?>" aria-expanded="false"
                        aria-controls="collapse-<?= $index ?>">
                    <?= htmlspecialchars($depot['titre']) ?>
                </button>
            </h2>

            <div id="collapse-<?= $index ?>" class="accordion-collapse collapse"
                 aria-labelledby="heading-<?= $index ?>" data-bs-parent="#depotAccordion">
                <div class="accordion-body p-3">

                    <?= $this->afficherDetailsDepot($depot) ?>
                    <?= $this->afficherNoteEtCommentaire($depot) ?>
                    <?= $this->afficherAuteurEtDateRemise($depot) ?>
                    <?= $this->afficherFichiersRemis($depot) ?>
                    <?= $this->afficherFormulaireDepot($depot) ?>

                </div>
            </div>
        </div>
        <?php
    }

    private function afficherDetailsDepot($depot)
    {
        ?>
        <div class="p-3 bg-light border rounded mb-3">
            <p><strong>Date limite :</strong> <?= htmlspecialchars($depot['date_limite']) ?></p>
            <p class="mb-0"><strong>Statut :</strong> <?= htmlspecialchars($depot['statut']) ?></p>
        </div>
        <?php
    }

    private function afficherNoteEtCommentaire($depot)
    {
        if (!empty($depot['note'])) {
            ?>
            <div class="p-3 bg-light border rounded mb-3">
                <p><strong>Coefficient :</strong> <?= htmlspecialchars($depot['coefficient']) ?></p>
                <p><strong>Note :</strong> <?= htmlspecialchars($depot['note']) ?> / <?= htmlspecialchars($depot['note_max']) ?></p>
                <p class="mb-0"><strong>Commentaire :</strong> <?= htmlspecialchars($depot['commentaire']) ?: 'Aucun commentaire' ?></p>
            </div>
            <?php
        }
    }
    private function afficherAuteurEtDateRemise($depot)
    {
        if ($depot['statut'] === 'Remis') {
            if (!empty($depot['auteur'])) {
                ?>
                <div class="p-3 bg-light border rounded mb-3">
                    <p><strong>Auteur :</strong> <?= htmlspecialchars($depot['auteur']) ?></p>
                    <p class="mb-0"><strong>Date de dépôt :</strong> <?= htmlspecialchars($depot['date_remise']) ?></p>
                </div>
                <?php
            }
        }
    }
    private function afficherFichiersRemis($depot)
    {
        if ($depot['statut'] === 'Remis') {
            ?>
            <p class="mt-3"><strong>Fichiers remis :</strong></p>
            <?php
            if (!empty($depot['fichiers'])) {
                ?>
                <ul>
                    <?php foreach ($depot['fichiers'] as $fichier): ?>
                        <li>
                            <a href="<?= htmlspecialchars($fichier['chemin_fichier']) ?>" target="_blank">
                                <?= htmlspecialchars($fichier['nom_fichier']) ?>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
                <?php
            } else {
                ?>
                <p>Aucun fichier associé.</p>
                <?php
            }
        }
    }
    private function afficherFormulaireDepot($depot)
    {
        if ($depot['statut'] !== 'Remis') {
            ?>
            <form action="index.php?module=depotetud&action=upload" method="post" enctype="multipart/form-data" class="mt-3">
                <input type="hidden" name="id_rendu" value="<?= htmlspecialchars($depot['id_rendu']) ?>">
                <div class="mb-3">
                    <label for="fileUpload-<?= $depot['id_rendu'] ?>" class="form-label">Uploader des fichiers :</label>
                    <input type="file" class="form-control" id="fileUpload-<?= $depot['id_rendu'] ?>" name="uploaded_files[]" multiple required>
                </div>
                <button type="submit" class="btn btn-primary w-100">Envoyer</button>
            </form>
            <?php
        } else {
            ?>
            <form action="index.php?module=depotetud&action=supprimerTravailRemis" method="post" class="mt-4">
                <input type="hidden" name="id_rendu" value="<?= htmlspecialchars($depot['id_rendu']) ?>">
                <button type="submit" class="btn btn-danger w-100">Supprimer le travail remis</button>
            </form>
            <?php
        }
    }
}
