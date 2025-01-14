<?php

include_once 'generique/vue_generique.php';

class VueNotesEtud extends VueGenerique
{
    public function __construct()
    {
        parent::__construct();
    }


    public function afficherAucuneNoteDispo()
    {
        ?>
        <div class='d-flex justify-content-center align-items-center' style='height: 75vh;'>
            <div class='w-50 text-center' style='font-size: 20px;'>
                Aucune note disponible
            </div>
        </div>
        <?php
    }

    public function afficherAllNotesSAE($allNotes)
    {
        ?>

        <h1 class="text-center">Notes d'Évaluation</h1>

        <?php
        if (!empty($allNotes['rendus'])) {
            ?>
            <div class="container mt-3">
                <h3 class="text-dark">Rendus</h3>
                <table class="table table-bordered table-striped">
                    <thead class="thead-dark">
                    <tr>
                        <th>Titre</th>
                        <th>Coefficient</th>
                        <th>Note</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($allNotes['rendus'] as $note): ?>
                        <tr>
                            <td><?= htmlspecialchars($note['titre_rendu']) ?></td>
                            <td class="text-center"><?= htmlspecialchars($note['coef_rendu']) ?></td>
                            <td class="text-center"><?= htmlspecialchars($note['note_rendu']) ?> /<?= htmlspecialchars($note['note_max']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <?php
        }

        if (!empty($allNotes['soutenances'])) {
            ?>
            <div class="container mt-5">
                <h3 class="text-dark">Soutenances</h3>
                <table class="table table-bordered table-striped">
                    <thead class="thead-dark">
                    <tr>
                        <th>Titre</th>
                        <th>Coefficient</th>
                        <th>Note</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($allNotes['soutenances'] as $note): ?>
                        <tr>
                            <td><?= htmlspecialchars($note['titre_soutenance']) ?></td>
                            <td class="text-center"><?= htmlspecialchars($note['coef_soutenance']) ?></td>
                            <td class="text-center"><?= htmlspecialchars($note['note_soutenance']) ?> /<?= htmlspecialchars($note['note_max']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <?php
        }
    }
    public function afficherNoteFinal($noteFinal)
    {
        ?>
        <div class="container mt-5">
            <div class="text-center mt-4 display-6 " style="font-size: 25px;">
                <strong class="note-final-text">Note Finale : <?= htmlspecialchars($noteFinal) ?></strong>
            </div>
        </div>
        <?php
    }

}
