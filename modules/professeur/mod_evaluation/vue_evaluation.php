<script src="../../../script.js"></script>
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
                                <input type="hidden" name="id_groupe" value="<?= htmlspecialchars($evaluation['id_groupe']) ?>">
                                <input type="hidden" name="id_rendu" value="<?= htmlspecialchars($evaluation['id_rendu']) ?>">
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

    public function afficherFormulaireNotation($allMembres)
    {
        $mode = "individuel";
        ?>
        <div class="container mt-5">
            <h2 class="mb-4 text-center">Notation des Membres</h2>
            <div class="d-flex justify-content-center mb-4">
                <button class="btn btn-primary me-2" id="btn-individuel">Noter Individuellement</button>
                <button class="btn btn-secondary" id="btn-groupe">Noter en Groupe</button>
            </div>

            <form method="POST" action="traitement_notation.php" id="form-individuel" <?= $mode === "groupe" ? 'style="display:none;"' : '' ?>>
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="table-dark">
                        <tr>
                            <th>Nom</th>
                            <th>Prénom</th>
                            <th>Email</th>
                            <th>Note</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($allMembres as $membre): ?>
                            <tr>
                                <td><?= htmlspecialchars($membre['nom']) ?></td>
                                <td><?= htmlspecialchars($membre['prenom']) ?></td>
                                <td><?= htmlspecialchars($membre['email']) ?></td>
                                <td>
                                    <input type="number"
                                           class="form-control"
                                           name="notes[<?= htmlspecialchars($membre['id_utilisateur']) ?>]"
                                           step="0.01"
                                           min="0"
                                           max="20"
                                           placeholder="Note"
                                           required>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <div class="text-center mt-3">
                    <button type="submit" class="btn btn-success">Soumettre les Notes</button>
                </div>
            </form>

            <form method="POST" action="traitement_notation.php" id="form-groupe" <?= $mode === "individuel" ? 'style="display:none;"' : '' ?>>
                <div class="table-responsive mt-4">
                    <table class="table table-bordered table-hover">
                        <thead class="table-dark">
                        <tr>
                            <th>Nom</th>
                            <th>Prénom</th>
                            <th>Email</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($allMembres as $membre): ?>
                            <tr>
                                <td><?= htmlspecialchars($membre['nom']) ?></td>
                                <td><?= htmlspecialchars($membre['prenom']) ?></td>
                                <td><?= htmlspecialchars($membre['email']) ?></td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <div class="mb-3">
                    <label for="note-groupe" class="form-label">Note pour le groupe</label>
                    <input type="number"
                           class="form-control"
                           id="note-groupe"
                           name="note_groupe"
                           step="0.01"
                           min="0"
                           max="20"
                           placeholder="Attribuer une note au groupe"
                           required>
                </div>
                <input type="hidden" name="id_groupe" value="<?= htmlspecialchars($allMembres[0]['id_groupe'])?>">
                <div class="text-center mt-3">
                    <button type="submit" class="btn btn-success">Soumettre la Note de Groupe</button>
                </div>
            </form>
        </div>
        <?php
    }

}
?>
