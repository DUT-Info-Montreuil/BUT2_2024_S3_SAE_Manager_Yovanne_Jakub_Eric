<?php
include_once 'generique/vue_generique.php';

class VueEvaluation extends VueGenerique
{
    public function __construct()
    {
        parent::__construct();
    }

    public function afficherTableauRendu($rendueEvaluations)
    {
        ?>
        <div class="container mt-4">
            <h1 class="mb-4">Gestion des Rendus</h1>
            <table class="table table-bordered table-hover">
                <thead class="table-dark">
                <tr>
                    <th>Groupe</th>
                    <th>Rendu</th>
                    <th>Date Limite</th>
                    <th>Statut</th>
                    <th>Note Rendu</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($rendueEvaluations as $evaluation): ?>
                    <tr>
                        <td><?= htmlspecialchars($evaluation['groupe_nom']) ?></td>
                        <td><?= htmlspecialchars($evaluation['rendu_titre']) ?></td>
                        <td><?= htmlspecialchars($evaluation['rendu_date_limite']) ?></td>
                        <td><?= htmlspecialchars($evaluation['rendu_statut']) ?></td>
                        <td><?= htmlspecialchars($evaluation['note_rendu']) ?></td>
                        <td>
                            <form method="POST" action="index.php?module=evaluationprof&action=traiterNote">
                                <input type="hidden" name="groupe_id" value="<?= htmlspecialchars($evaluation['id_groupe']) ?>">
                                <input type="hidden" name="rendu_id" value="<?= htmlspecialchars($evaluation['id_rendu']) ?>">
                                <button type="submit" class="btn btn-sm btn-primary">Noter</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php
    }
}
?>
