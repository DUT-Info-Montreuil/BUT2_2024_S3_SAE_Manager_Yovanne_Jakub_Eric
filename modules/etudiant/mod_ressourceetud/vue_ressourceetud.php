<?php

include_once 'generique/vue_generique.php';

class VueRessourceEtud extends VueGenerique
{
    public function __construct()
    {
        parent::__construct();
    }

    public function afficherAllSae($tabAllSae)
    {
        ?>
        <div class="container mt-5">
            <h2 class="text-center mb-4 fw-bold text-dark">Ressources mises en avant</h2>
            <div class="accordion" id="saeAccordion">
                <?php if (!is_array($tabAllSae) || empty($tabAllSae)): ?>
                    <div class="text-center">
                        <p class="fst-italic">Aucune ressource mise en avant disponible pour ce projet</p>
                    </div>
                <?php else: ?>
                    <?php foreach ($tabAllSae as $index => $sae): ?>
                        <div class="accordion-item <?= $sae['mise_en_avant'] ? 'border-left border-primary shadow-lg' : '' ?>">
                            <h2 class="accordion-header" id="heading-<?= $index ?>">
                                <button class="accordion-button <?= $sae['mise_en_avant'] ? 'bg-primary text-white fw-bold' : 'bg-light text-dark' ?> collapsed"
                                        type="button" data-bs-toggle="collapse"
                                        data-bs-target="#collapse-<?= $index ?>" aria-expanded="false"
                                        aria-controls="collapse-<?= $index ?>">
                                    <?php if ($sae['mise_en_avant']): ?>
                                        <i class="bi bi-star-fill text-warning me-2"></i>
                                    <?php endif; ?>
                                    <?= htmlspecialchars($sae['titre']) ?>
                                </button>
                            </h2>
                            <div id="collapse-<?= $index ?>" class="accordion-collapse collapse"
                                 aria-labelledby="heading-<?= $index ?>" data-bs-parent="#saeAccordion">
                                <div class="accordion-body">
                                    <p><strong>Ressource : </strong>
                                        <a href="<?= htmlspecialchars($sae['lien']) ?>" target="_blank"
                                           class="text-decoration-underline text-dark">
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
?>
