<?php

include_once 'generique/vue_generique.php';

class VueSoutenanceEtud extends VueGenerique
{
    public function __construct()
    {
        parent::__construct();
    }

    public function afficherAllSoutenances($tabAllSoutenances)
    {
        ?>
        <div class="container mt-5">
            <h2 class="text-center mb-4" style="font-weight: bold; color: #343a40;">Soutenances</h2>

            <div class="accordion" id="soutenanceAccordion">
                <?php if (!is_array($tabAllSoutenances) || empty($tabAllSoutenances)): ?>
                    <div class="text-center">
                        <p style="font-style: italic">Aucune soutenance disponible pour ce projet.</p>
                    </div>
                <?php else: ?>
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
                                        <p class="bg-light p-2 border rounded"><strong>Date de Soutenance :</strong> <?= $this->formatDate($soutenance['date_soutenance']) ?></p>
                                    </div>

                                    <?php if (!empty($soutenance['note'])): ?>
                                        <div class="p-3 bg-light border rounded">
                                            <p><strong>Coefficient :</strong> <?= htmlspecialchars($soutenance['coefficient']) ?></p>
                                            <p><strong>Note :</strong> <?= htmlspecialchars($soutenance['note']) ?> / <?= htmlspecialchars($soutenance['note_max']) ?></p>
                                            <p class="mb-0"><strong>Commentaire :</strong> <?= htmlspecialchars($soutenance['commentaire']) ?: 'Aucun commentaire' ?></p>
                                        </div>
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

    private function formatDate($date)
    {
        $dateObj = DateTime::createFromFormat('Y-m-d', $date);
        return $dateObj ? $dateObj->format('d/m/Y') : $date;
    }
}
