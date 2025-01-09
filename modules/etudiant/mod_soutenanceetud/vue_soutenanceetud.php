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
                        <p>Aucune soutenance disponible pour ce projet.</p>
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

                                    <div class="d-flex justify-content-center mb-2">
                                        <div class="mx-2">
                                            <p><strong>Date de Soutenance
                                                    :</strong> <?= $this->formatDate($soutenance['date_soutenance']) ?>
                                            </p>
                                        </div>
                                        <?php if (!$soutenance['id_evaluation']): ?>
                                            <div class="mx-2">
                                                <p><strong>Évaluation :</strong>
                                                    <span>Aucune évaluation définie</span>
                                                </p>
                                            </div>
                                        <?php endif; ?>
                                    </div>

                                    <?php if ($soutenance['id_evaluation']): ?>
                                        <div class="mt-3 p-3 bg-light border rounded">
                                            <p><strong>Coefficient évaluation
                                                    :</strong> <?= htmlspecialchars($soutenance['coefficient']) ?></p>
                                            <p><strong>Note max évaluation
                                                    :</strong> <?= htmlspecialchars($soutenance['note_max']) ?></p>
                                        </div>
                                    <?php endif; ?>

                                    <?php if (!empty($soutenance['note']) || !empty($soutenance['commentaire'])): ?>
                                        <div class="mt-3 p-3 bg-light border rounded">
                                            <p><strong>Note
                                                    :</strong> <?= htmlspecialchars($soutenance['note']) ?: 'Non noté' ?>
                                            </p>
                                            <p><strong>Commentaire
                                                    :</strong> <?= htmlspecialchars($soutenance['commentaire']) ?: 'Aucun commentaire' ?>
                                            </p>
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

?>
