<?php

include_once 'generique/vue_generique.php';

class VueSoutenanceEtud extends VueGenerique
{
    public function __construct()
    {
        parent::__construct();
    }
    public function aucuneSoutenance()
    {
        ?>
        <div class="container mt-5">
            <h2 class="text-center mb-4" style="font-weight: bold; color: #343a40;">Soutenances</h2>

            <div class="accordion" id="soutenanceAccordion">
                <div class="text-center">
                    <p style="font-style: italic">Aucune soutenance disponible pour ce projet</p>
                </div>
            </div>
        </div>
        <?php
    }

    public function afficherAllSoutenances($tabAllSoutenances)
    {
        ?>
        <div class="container mt-5">
            <h2 class="text-center mb-4" style="font-weight: bold; color: #343a40;">Soutenances</h2>

            <div class="accordion" id="soutenanceAccordion">
                <?php foreach ($tabAllSoutenances as $index => $soutenance): ?>
                    <div class="accordion-item mb-3">
                        <h2 class="accordion-header" id="heading-<?= $index ?>">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#collapse-<?= $index ?>" aria-expanded="false"
                                    aria-controls="collapse-<?= $index ?>">
                                <?= htmlspecialchars($soutenance['titre']) ?>
                            </button>
                        </h2>
                        <div id="collapse-<?= $index ?>" class="accordion-collapse collapse"
                             aria-labelledby="heading-<?= $index ?>" data-bs-parent="#soutenanceAccordion">
                            <div class="accordion-body p-3">

                                <div class="mb-3">
                                    <p class="bg-light p-2 border rounded"><strong>Date de Soutenance :</strong> <?= htmlspecialchars($soutenance['date_soutenance']) ?></p>
                                    <?php if (!empty($soutenance['heure_passage'])): ?>
                                        <p class="bg-light p-2 border rounded"><strong>Heure de Passage :</strong> <?= htmlspecialchars($soutenance['heure_passage']) ?></p>
                                    <?php endif; ?>
                                </div>

                                <?php if (!empty($soutenance['note'])): ?>
                                    <div class="p-3 bg-light border rounded">
                                        <p><strong>Coefficient :</strong> <?= htmlspecialchars($soutenance['coefficient']) ?></p>
                                        <p><strong>Note :</strong> <?= htmlspecialchars($soutenance['note']) ?>
                                            / <?= htmlspecialchars($soutenance['note_max']) ?></p>
                                        <p class="mb-0"><strong>Commentaire :</strong> <?= htmlspecialchars($soutenance['commentaire'])?></p>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>

                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php
    }

}
