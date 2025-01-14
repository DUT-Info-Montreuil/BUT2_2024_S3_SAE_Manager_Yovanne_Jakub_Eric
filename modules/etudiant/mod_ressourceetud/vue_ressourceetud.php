<?php

include_once 'generique/vue_generique.php';
Class VueRessourceEtud extends VueGenerique
{
    public function __construct()
    {
        parent::__construct();
    }

    public function afficherAllSae($tabAllSae) {
        ?>
        <div class="container mt-5">
            <h2 class="text-center mb-4" style="font-weight: bold; color: #343a40;">Ressources mises en avant</h2>

            <div class="accordion" id="saeAccordion">
                <?php if (!is_array($tabAllSae) || empty($tabAllSae)): ?>
                    <div class="text-center">
                        <p style="font-style: italic">Aucune ressources mise en avant disponible pour ce projet</p>
                    </div>
                <?php else: ?>
                    <?php foreach ($tabAllSae as $index => $sae): ?>
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="heading-<?= $index ?>">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#collapse-<?= $index ?>" aria-expanded="false"
                                        aria-controls="collapse-<?= $index ?>">
                                    <?= htmlspecialchars($sae['titre']) ?>
                                </button>
                            </h2>
                            <div id="collapse-<?= $index ?>" class="accordion-collapse collapse"
                                 aria-labelledby="heading-<?= $index ?>" data-bs-parent="#saeAccordion">
                                <div class="accordion-body">
                                    <p><strong>Ressource : </strong>
                                        <a href="<?= htmlspecialchars($sae['lien']) ?>" target="_blank">
                                            Voir la ressource
                                        </a>
                                    </p>
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