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
                                    <p><strong>Date limite :</strong> <?= htmlspecialchars($depot['date_limite']) ?></p>
                                    <p><strong>Statut :</strong> <?= htmlspecialchars($depot['statut']) ?></p>

                                    <?php if ($depot['statut'] === 'Remis'): ?>
                                        <p><strong>Contenu remis :</strong>
                                            <a href="<?= htmlspecialchars($depot['contenu_rendu']) ?>" target="_blank">
                                                Voir le fichier remis
                                            </a>
                                        </p>
                                        <form action="index.php?module=depotetud&action=supprimerTravailRemis" method="post" class="mt-4">
                                            <input type="hidden" name="id_rendu" value="<?php echo $depot['id_rendu']; ?>">
                                            <button type="submit" class="btn btn-danger">Supprimer le travail remis</button>
                                        </form>
                                    <?php else: ?>
                                        <form action="index.php?module=depotetud&action=upload" method="post" enctype="multipart/form-data">
                                            <input type="hidden" name="id_rendu" value="<?= htmlspecialchars($depot['id_rendu']) ?>">
                                            <div class="mb-3">
                                                <label for="fileUpload-<?= $index ?>" class="form-label">Uploader un fichier :</label>
                                                <input type="file" class="form-control" id="fileUpload-<?= $index ?>" name="uploaded_file" required>
                                            </div>
                                            <button type="submit" class="btn btn-primary">Envoyer</button>
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