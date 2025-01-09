<?php

include_once 'generique/vue_generique.php';
Class VueDepotEtud extends VueGenerique
{
    public function __construct()
    {
        parent::__construct();
    }

    public function afficherAllDepot($tabAllDepot) {
        ?>
        <div class="container mt-5">
            <h2 class="text-center mb-4" style="font-weight: bold; color: #343a40;">Tous les Dépôts</h2>

            <div class="accordion" id="depotAccordion">
                <?php if (!is_array($tabAllDepot) || empty($tabAllDepot)): ?>
                    <div class="text-center">
                        <p>Aucun dépôt disponible pour ce projet.</p>
                    </div>
                <?php else: ?>
                    <?php foreach ($tabAllDepot as $index => $depot): ?>
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
                                    <div class="d-flex justify-content-center mb-2">
                                        <div class="mx-2">
                                            <p><strong>Date limite :</strong> <?= htmlspecialchars($depot['date_limite']) ?></p>
                                        </div>
                                        <div class="mx-2">
                                            <p><strong>Statut :</strong> <?= htmlspecialchars($depot['statut']) ?></p>
                                        </div>
                                    </div>


                                    <?php if (!empty($depot['note_max']) && !empty($depot['coefficient'])): ?>
                                        <div class="mt-3 p-3 bg-light border rounded">
                                            <p><strong>Note maximale :</strong> <?= htmlspecialchars($depot['note_max']) ?></p>
                                            <p><strong>Coefficient :</strong> <?= htmlspecialchars($depot['coefficient']) ?></p>
                                        </div>
                                    <?php else: ?>
                                        <p class="mt-3"><strong>Évaluation non définie</strong></p>
                                    <?php endif; ?>

                                    <?php if (!empty($depot['note']) || !empty($depot['commentaire'])): ?>
                                        <div class="mt-3 p-3 bg-light border rounded">
                                            <p><strong>Note :</strong> <?= htmlspecialchars($depot['note']) ?: 'Non noté' ?></p>
                                            <p><strong>Commentaire :</strong> <?= htmlspecialchars($depot['commentaire']) ?: 'Aucun commentaire' ?></p>
                                        </div>
                                    <?php endif; ?>

                                    <?php if ($depot['statut'] === 'Remis'): ?>
                                        <p class="mt-3"><strong>Fichiers remis :</strong></p>
                                        <?php if (!empty($depot['fichiers'])): ?>
                                            <ul>
                                                <?php foreach ($depot['fichiers'] as $fichier): ?>
                                                    <li>
                                                        <a href="<?= htmlspecialchars($fichier['chemin_fichier']) ?>" target="_blank">
                                                            <?= htmlspecialchars($fichier['nom_fichier']) ?>
                                                        </a>
                                                    </li>
                                                <?php endforeach; ?>
                                            </ul>
                                        <?php else: ?>
                                            <p>Aucun fichier associé.</p>
                                        <?php endif; ?>

                                        <form action="index.php?module=depotetud&action=supprimerTravailRemis" method="post" class="mt-4">
                                            <input type="hidden" name="id_rendu" value="<?= htmlspecialchars($depot['id_rendu']) ?>">
                                            <button type="submit" class="btn btn-danger w-100">Supprimer le travail remis</button>
                                        </form>
                                    <?php else: ?>
                                        <form action="index.php?module=depotetud&action=upload" method="post" enctype="multipart/form-data" class="mt-3">
                                            <input type="hidden" name="id_rendu" value="<?= htmlspecialchars($depot['id_rendu']) ?>">
                                            <div class="mb-3">
                                                <label for="fileUpload-<?= $index ?>" class="form-label">Uploader des fichiers :</label>
                                                <input type="file" class="form-control" id="fileUpload-<?= $index ?>" name="uploaded_files[]" multiple required>
                                            </div>
                                            <button type="submit" class="btn btn-primary w-100">Envoyer</button>
                                        </form>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
        <?php
    }




}