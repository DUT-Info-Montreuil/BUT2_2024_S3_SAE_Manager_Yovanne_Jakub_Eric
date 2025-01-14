<?php
include_once 'generique/vue_generique.php';

class VueNoteFinalProf extends VueGenerique
{
    public function __construct()
    {
        parent::__construct();
    }

    public function afficherAllNoteAndEtudiant($allNoteFinalAndEtudiant)
    {
        ?>
        <div class="container mt-4">
            <h1 class="mb-4 text-center">Notes Finales</h1>

            <table class="table table-bordered table-hover">
                <thead class="table-dark">
                <tr>
                    <th scope="col">Nom</th>
                    <th scope="col">Prénom</th>
                    <th scope="col">Email</th>
                    <th scope="col">Groupe</th>
                    <th scope="col">Note Finale</th>
                </tr>
                </thead>
                <tbody>
                <?php if (!empty($allNoteFinalAndEtudiant)): ?>
                    <?php foreach ($allNoteFinalAndEtudiant as $etudiant): ?>
                        <tr>
                            <td><?= htmlspecialchars($etudiant['nom_etudiant']) ?></td>
                            <td><?= htmlspecialchars($etudiant['prenom_etudiant']) ?></td>
                            <td><?= htmlspecialchars($etudiant['email_etudiant']) ?></td>
                            <td><?= htmlspecialchars($etudiant['nom_groupe']) ?></td>
                            <td class="<?= $etudiant['note_finale'] >= 10 ? 'text-success' : 'text-danger' ?>">
                                <?= htmlspecialchars($etudiant['note_finale']) ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" class="text-center">Aucune donnée disponible</td>
                    </tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
        <?php
    }

}