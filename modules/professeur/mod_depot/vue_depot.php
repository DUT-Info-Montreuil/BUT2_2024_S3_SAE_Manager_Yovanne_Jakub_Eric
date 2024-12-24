<?php
include_once 'generique/vue_generique.php';

class VueDepot extends VueGenerique
{
    public function __construct()
    {
        parent::__construct();
    }

    public function afficheAllDepotSAE($allDepot)
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
                            </div>
                        </div>
                    </div>
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