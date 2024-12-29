<?php

include_once 'generique/vue_generique.php';

class VueSoutenance extends VueGenerique
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
                        <p>Aucune soutenance disponible pour ce projet.</p>
                    </div>
                <?php else: ?>
                    <?php foreach ($tabAllSoutenances as $index => $soutenance): ?>
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
                                    <p><strong>Date de Soutenance : </strong>
                                        <?= $this->formatDate($soutenance['date_soutenance']) ?>
                                    </p>
                                    <p><strong>Évaluation : </strong>
                                        <?php if ($soutenance['id_evaluation']): ?>
                                    <p>Coefficient évaluation : <?= htmlspecialchars($soutenance['coefficient']) ?></p>
                                    <p>Note max évaluation : <?= htmlspecialchars($soutenance['note_max']) ?></p>
                                    <?php else: ?>
                                        <p>Aucune évaluation définie.</p>
                                    <?php endif; ?>
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

    private function formatDate($date)
    {
        $dateObj = DateTime::createFromFormat('Y-m-d', $date);
        return $dateObj ? $dateObj->format('d/m/Y') : $date;
    }
}
?>
