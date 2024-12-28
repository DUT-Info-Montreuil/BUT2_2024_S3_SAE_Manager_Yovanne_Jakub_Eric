<?php

include_once 'generique/vue_generique.php';
Class VueDepot extends VueGenerique
{
    public function __construct()
    {
        parent::__construct();
    }

    public function afficherAllDepot($tabAllDepot) {
        ?>
        <div class="container mt-5">
            <h2 class="text-center mb-4" style="font-weight: bold; color: #343a40;">Tous les Dépôts</h2>

            <div class="row justify-content-center g-4">
                <?php if (empty($tabAllDepot)): ?>
                    <div class="col-12 text-center">
                        <p>Aucun dépôt disponible pour ce projet.</p>
                    </div>
                <?php else: ?>
                    <?php foreach ($tabAllDepot as $depot): ?>
                        <div class="col-md-4 col-lg-3 d-flex justify-content-center">
                            <div class="card shadow border-0"
                                 style="width: 250px; height: 250px; border-radius: 15px;
                             background-color: #f8f9fa; display: flex; flex-direction: column;
                             justify-content: center; align-items: center; text-align: center;">
                                <a class="text-decoration-none" href="index.php?module=depotetud&action=details&id=<?php echo htmlspecialchars($depot['id_rendu']); ?>"
                                   style="color: #495057;">
                                    <h3 style="font-weight: 600; font-size: 1.2rem;">
                                        <?php echo htmlspecialchars($depot['titre']); ?>
                                    </h3>
                                    <p class="mb-0" style="font-size: 0.9rem; color: #6c757d;">
                                        Date Limite: <?php echo htmlspecialchars($depot['date_limite']); ?>
                                    </p>
                                    <p class="mb-0" style="font-size: 0.9rem; color: #6c757d;">
                                        Statut: <?php echo htmlspecialchars($depot['statut']); ?>
                                    </p>
                                </a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
        <?php
    }

}