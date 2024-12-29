<script src="../../../script.js"></script>
<?php
include_once 'generique/vue_generique.php';

class VueEvaluationProf extends VueGenerique
{
    public function __construct()
    {
        parent::__construct();
    }
    public function formulaireCreationEvaluation($id, $type_evaluation)
    {
        ?>
        <div class="container mt-4">
            <h1 class="mb-4">Créer une Évaluation</h1>
            <form method="POST" action="index.php?module=evaluationprof&action=creerEvaluation">
                <input type="hidden" name="id" value="<?= htmlspecialchars($id) ?>">
                <input type="hidden" name="type_evaluation" value="<?= htmlspecialchars($type_evaluation) ?>">
                <div class="mb-3">
                    <label for="coefficient" class="form-label">Coefficient</label>
                    <input type="number" step="0.01" class="form-control" id="coefficient" name="coefficient"
                           placeholder="Entrez le coefficient" required>
                </div>
                <div class="mb-3">
                    <label for="note_max" class="form-label">Note Maximale</label>
                    <input type="number" step="0.01" class="form-control" id="note_max" name="note_max"
                           placeholder="Entrez la note maximale" required>
                </div>
                <div class="text-center">
                    <button type="submit" class="btn btn-success">Créer l'Évaluation</button>
                </div>
            </form>
        </div>
        <?php
    }

    public function afficherFormulaireModifierNote($notes, $id_groupe, $id_evaluation, $type_evaluation)
    {
        ?>
        <div class="container mt-5">
            <h2 class="mb-4 text-center">Modifier les Notes</h2>
            <form method="POST" action="index.php?module=evaluationprof&action=traitementModificationNote">
                <input type="hidden" name="id_groupe" value="<?= htmlspecialchars($id_groupe) ?>">
                <input type="hidden" name="id_evaluation" value="<?= htmlspecialchars($id_evaluation) ?>">
                <input type="hidden" name="type_evaluation" value="<?= htmlspecialchars($type_evaluation) ?>">

                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="table-dark">
                        <tr>
                            <th>Soutenance/Rendu</th>
                            <th>Nom</th>
                            <th>Prénom</th>
                            <th>Email</th>
                            <th>Note</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($notes as $note): ?>
                            <tr>
                                <td><?= htmlspecialchars($note['titre']) ?></td>
                                <td><?= htmlspecialchars($note['nom']) ?></td>
                                <td><?= htmlspecialchars($note['prenom']) ?></td>
                                <td><?= htmlspecialchars($note['email']) ?></td>
                                <td>
                                    <input type="number"
                                           class="form-control"
                                           name="notes[<?= htmlspecialchars($note['id_utilisateur']) ?>]"
                                           value="<?= htmlspecialchars($note['note']) ?>"
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
                    <button type="submit" class="btn btn-success">Soumettre les Modifications</button>
                </div>
            </form>
        </div>
        <?php
    }

    public function formulaireModificationEvaluation($id)
    {
        ?>
        <div class="container mt-4">
            <h1 class="mb-4">Modifier l'Évaluation</h1>
            <form method="POST" action="index.php?module=evaluationprof&action=modifierEvaluation">
                <input type="hidden" name="id" value="<?= htmlspecialchars($id) ?>">
                <div class="mb-3">
                    <label for="coefficient" class="form-label">Coefficient</label>
                    <input type="number" step="0.01" class="form-control" id="coefficient" name="coefficient"
                           placeholder="Entrez le coefficient" required>
                </div>
                <div class="mb-3">
                    <label for="note_max" class="form-label">Note Maximale</label>
                    <input type="number" step="0.01" class="form-control" id="note_max" name="note_max"
                           placeholder="Entrez la note maximale" required>
                </div>
                <div class="text-center">
                    <button type="submit" class="btn btn-primary">Modifier l'Évaluation</button>
                </div>
            </form>
            <form method="POST" action="index.php?module=evaluationprof&action=supprimerEvaluation">
                <input type="hidden" name="id_evaluation" value="<?= htmlspecialchars($id) ?>">
                <button type="submit" class="btn btn-danger">Supprimer l'évaluation</button>
            </form>
        </div>
        <?php
    }


    public function afficherTableauAllRendu($allRendue, $allSoutenance)
    {
        ?>

        <div class="container mt-4">
            <h1 class="mb-4">Création évaluation</h1>
            <table class="table table-bordered table-hover">
                <thead class="table-dark">
                <tr>
                    <th>Titre</th>
                    <th>Créer une évaluation</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($allRendue as $rendue): ?>
                    <tr>
                        <td><?= htmlspecialchars($rendue['titre']) ?></td>
                        <td>
                            <form method="POST" action="index.php?module=evaluationprof&action=formEvaluation">
                                <input type="hidden" name="id_rendu" value="<?= htmlspecialchars($rendue['id_rendu']) ?>">
                                <?php if ($rendue['id_evaluation'] !== null): ?>
                                    <button type="submit" class="btn btn-sm btn-warning">Modifier l'évaluation</button>
                                <?php else: ?>
                                    <button type="submit" class="btn btn-sm btn-primary">Créer une évaluation</button>
                                <?php endif; ?>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>

                <?php foreach ($allSoutenance as $soutenance): ?>
                    <tr>
                        <td><?= htmlspecialchars($soutenance['titre']) ?></td>
                        <td>
                            <form method="POST" action="index.php?module=evaluationprof&action=formEvaluation">
                                <input type="hidden" name="id_soutenance" value="<?= htmlspecialchars($soutenance['id_soutenance']) ?>">

                                <?php if ($soutenance['id_evaluation'] !== null): ?>
                                    <button type="submit" class="btn btn-sm btn-warning">Modifier l'évaluation</button>
                                <?php else: ?>
                                    <button type="submit" class="btn btn-sm btn-primary">Créer une évaluation</button>
                                <?php endif; ?>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>

                </tbody>
            </table>
        </div>
        <?php
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
                    <th>Note Max</th>
                    <th>Coefficient</th>
                    <th>Noter</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($rendueEvaluations as $evaluation): ?>
                    <tr>
                        <td><?= htmlspecialchars($evaluation['groupe_nom']) ?></td>
                        <td><?= htmlspecialchars($evaluation['rendu_titre']) ?></td>
                        <td><?= htmlspecialchars($evaluation['rendu_date_limite']) ?></td>
                        <td><?= htmlspecialchars($evaluation['rendu_statut']) ?></td>
                        <td><?= nl2br(htmlspecialchars($evaluation['notes_individuelles'])) ?></td>
                        <td><?= htmlspecialchars($evaluation['note_max']) ?></td>
                        <td><?= htmlspecialchars($evaluation['note_coef']) ?></td>
                        <td>
                            <form method="POST" action="index.php?module=evaluationprof&action=choixNotation">
                                <input type="hidden" name="id_groupe" value="<?= htmlspecialchars($evaluation['id_groupe']) ?>">
                                <input type="hidden" name="type_evaluation" value="rendu">
                                <input type="hidden" name="id_rendu" value="<?= htmlspecialchars($evaluation['id_rendu']) ?>">
                                <?php if ($evaluation['rendu_note'] !== null): ?>
                                    <input type="hidden" name="id_evaluation" value="<?= htmlspecialchars($evaluation['id_evaluation']) ?>">
                                    <button type="submit" class="btn btn-sm btn-warning">Modifier les notes</button>
                                <?php else: ?>
                                    <button type="submit" class="btn btn-sm btn-primary">Noter</button>
                                <?php endif; ?>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>

            </table>
        </div>
        <?php
    }

    public function afficherTableauSoutenance($soutenanceEvaluations)
    {
        ?>
        <div class="container mt-4">
            <h1 class="mb-4">Gestion des Soutenances</h1>
            <table class="table table-bordered table-hover">
                <thead class="table-dark">
                <tr>
                    <th>Groupe</th>
                    <th>Soutenance</th>
                    <th>Date Soutenance</th>
                    <th>Note Soutenance</th>
                    <th>Note Max</th>
                    <th>Coefficient</th>
                    <th>Noter</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($soutenanceEvaluations as $evaluation): ?>
                    <tr>
                        <td><?= htmlspecialchars($evaluation['groupe_nom']) ?></td>
                        <td><?= htmlspecialchars($evaluation['soutenance_titre']) ?></td>
                        <td><?= htmlspecialchars($evaluation['soutenance_date']) ?></td>
                        <td><?= nl2br(htmlspecialchars(str_replace(', ', "\n", $evaluation['notes_individuelles']))) ?></td>
                        <td><?= htmlspecialchars($evaluation['note_max']) ?></td>
                        <td><?= htmlspecialchars($evaluation['note_coef']) ?></td>
                        <td>
                            <form method="POST" action="index.php?module=evaluationprof&action=choixNotation">
                                <input type="hidden" name="id_groupe" value="<?= htmlspecialchars($evaluation['id_groupe']) ?>">
                                <input type="hidden" name="type_evaluation" value="soutenance">
                                <input type="hidden" name="id_soutenance" value="<?= htmlspecialchars($evaluation['id_soutenance']) ?>">
                                <?php if ($evaluation['soutenance_note'] !== null): ?>
                                    <input type="hidden" name="id_evaluation" value="<?= htmlspecialchars($evaluation['id_evaluation']) ?>">
                                    <button type="submit" class="btn btn-sm btn-warning">Modifier les notes</button>
                                <?php else: ?>
                                    <button type="submit" class="btn btn-sm btn-primary">Noter</button>
                                <?php endif; ?>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php
    }

    public function afficherFormulaireNotation($allMembres, $id_groupe, $id, $type_evaluation)
    {
        $mode = "individuel";
        ?>
        <div class="container mt-5">
            <h2 class="mb-4 text-center">Notation des Membres</h2>
            <div class="d-flex justify-content-center mb-4">
                <button class="btn btn-primary me-2" id="btn-individuel">Noter Individuellement</button>
                <button class="btn btn-secondary" id="btn-groupe">Noter en Groupe</button>
            </div>

            <form method="POST" action="index.php?module=evaluationprof&action=traitementNotationIndividuelle" id="form-individuel" <?= $mode === "groupe" ? 'style="display:none;"' : '' ?>>
                <input type="hidden" name="id" value="<?= $id ?>">
                <input type="hidden" name="type_evaluation" value="<?= $type_evaluation ?>">
                <input type="hidden" name="id_groupe" value="<?= $id_groupe?>">
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

            <form method="POST" action="index.php?module=evaluationprof&action=traitementNotationGroupe" id="form-groupe" <?= $mode === "individuel" ? 'style="display:none;"' : '' ?>>
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
                    <label for="note_groupe" class="form-label">Note pour le groupe</label>
                    <input type="number"
                           class="form-control"
                           id="note_groupe"
                           name="note_groupe"
                           step="0.01"
                           min="0"
                           max="20"
                           placeholder="Attribuer une note au groupe"
                           required>
                </div>
                <input type="hidden" name="id_groupe" value="<?= $id_groupe?>">
                <input type="hidden" name="id" value="<?= $id ?>">
                <input type="hidden" name="type_evaluation" value="<?= $type_evaluation ?>">
                <div class="text-center mt-3">
                    <button type="submit" class="btn btn-success">Soumettre la Note de Groupe</button>
                </div>
            </form>
        </div>
        <?php
    }
}
?>
