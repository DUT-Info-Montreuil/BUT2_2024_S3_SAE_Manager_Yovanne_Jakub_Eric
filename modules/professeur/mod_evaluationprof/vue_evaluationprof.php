<?php
include_once 'generique/vue_generique.php';

class VueEvaluationProf extends VueGenerique
{
    public function __construct()
    {
        parent::__construct();
    }

    public function formulaireCreationEvaluation($id, $type_evaluation, $idSAE)
    {
        ?>
        <div class="container mt-4">
            <h1 class="mb-4">Créer une Évaluation</h1>
            <form method="POST"
                  action="index.php?module=evaluationprof&action=creerEvaluation&idProjet=<?php echo $idSAE; ?>">
                <input type="hidden" name="token" value="<?= $_SESSION['token'] ?>">
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

                <div id="criteria-container"></div>

                <button type="button" class="btn btn-primary" id="add-criterion-btn">Ajouter un critère</button>

                <div class="text-center mt-4">
                    <button type="submit" class="btn btn-success">Créer l'Évaluation</button>
                </div>
            </form>
        </div>
        <?php
    }


    public function afficherFormulaireModifierNote($notes, $id_groupe, $id_evaluation, $type_evaluation, $idSAE)
    {
        ?>
        <div class="container mt-5">
            <h2 class="mb-4 text-center">Modifier les Notes</h2>
            <form method="POST"
                  action="index.php?module=evaluationprof&action=traitementModificationNote&idProjet=<?php echo $idSAE; ?>">
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

    public function afficherEvaluateurs($evaluateurs)
    {
        ?>
        <div class="card mb-4">
            <div class="card-header bg-dark text-white">
                <h6 class="m-0">Évaluateurs :</h6>
            </div>
            <div class="card-body">
                <ul class="list-group">
                    <?php foreach ($evaluateurs as $evaluateur): ?>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span>
                                <strong><?= htmlspecialchars($evaluateur['nom'] . ' ' . $evaluateur['prenom']) ?></strong><br>
                                <small class="text-muted"><?= htmlspecialchars($evaluateur['email']) ?></small>
                            </span>
                            <?php if ($evaluateur['is_principal']): ?>
                                <span class="badge bg-success">Évaluateur Principal</span>
                            <?php endif; ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
        <?php
    }

    public function formulaireModificationEvaluation($id, $tabAllGerant, $tabAllGerantNonEvaluateur, $tabAllEvaluateur, $idSae)
    {
        $coefficient = null;
        $noteMax = null;
        ?>
        <div class="container mt-4">
            <h1 class="mb-4 text-center">Modifier l'Évaluation</h1>
            <div class="alert alert-warning text-center" role="alert">
                <strong>Attention :</strong> La suppression d'une évaluation est irréversible.
            </div>

            <form id="modificationForm" method="POST"
                  action="index.php?module=evaluationprof&action=modifierEvaluation&idProjet=<?php echo $idSae; ?>">
                <input type="hidden" name="token" value="<?= $_SESSION['token'] ?>">
                <input type="hidden" name="id" value="<?= htmlspecialchars($id) ?>">
                <input type="hidden" id="delegation_choice" name="delegation_choice" value="">

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="coefficient" class="form-label">Coefficient</label>
                        <input type="number" step="0.01" class="form-control" id="coefficient" name="coefficient"
                               placeholder="Entrez le coefficient" value="<?= htmlspecialchars($coefficient) ?>">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="note_max" class="form-label">Note Maximale</label>
                        <input type="number" step="0.01" class="form-control" id="note_max" name="note_max"
                               placeholder="Entrez la note maximale" value="<?= htmlspecialchars($noteMax) ?>">
                    </div>
                </div>

                <div class="mb-3">
                    <label for="deleguer_evaluation" class="form-label">Déléguer l'Évaluation</label>
                    <select class="form-control" id="deleguer_evaluation" name="deleguer_evaluation">
                        <option value="">Sélectionner une personne</option>
                        <?php foreach ($tabAllGerant as $gerant): ?>
                            <option value="<?= htmlspecialchars($gerant['id_utilisateur']) ?>">
                                <?= htmlspecialchars($gerant['nom']) ?> <?= htmlspecialchars($gerant['prenom']) ?>
                                (<?= htmlspecialchars($gerant['role_utilisateur']) ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div id="delegationRadioButtons" class="mt-3" style="display: none;">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="delegation_action" id="stayEvaluateur"
                               value="stay">
                        <label class="form-check-label" for="stayEvaluateur">
                            Rester Évaluateur et Déléguer
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="delegation_action" id="removeEvaluateur"
                               value="remove">
                        <label class="form-check-label" for="removeEvaluateur">
                            Déléguer et Ne Plus Être Évaluateur
                        </label>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="ajouter_evaluateurs" class="form-label">Ajouter des évaluateurs :</label>
                    <select class="form-control" id="ajouter_evaluateurs" name="ajouter_evaluateurs[]"
                            multiple="multiple">
                        <?php foreach ($tabAllGerantNonEvaluateur as $gerant): ?>
                            <option value="<?= htmlspecialchars($gerant['id_utilisateur']) ?>">
                                <?= htmlspecialchars($gerant['nom']) ?> <?= htmlspecialchars($gerant['prenom']) ?>
                                (<?= htmlspecialchars($gerant['role_utilisateur']) ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <?php if (!empty($tabAllEvaluateur)): ?>
                    <div class="mb-3">
                        <label for="supprimer_evaluateurs" class="form-label">Supprimer des évaluateurs :</label>
                        <?php foreach ($tabAllEvaluateur as $evaluateur): ?>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox"
                                       id="supprimer_evaluateur_<?= htmlspecialchars($evaluateur['id_utilisateur']) ?>"
                                       name="supprimer_evaluateurs[]"
                                       value="<?= htmlspecialchars($evaluateur['id_utilisateur']) ?>">
                                <label class="form-check-label"
                                       for="supprimer_evaluateur_<?= htmlspecialchars($evaluateur['id_utilisateur']) ?>">
                                    <?= htmlspecialchars($evaluateur['nom']) ?> <?= htmlspecialchars($evaluateur['prenom']) ?>
                                </label>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

                <div class="text-center">
                    <button type="submit" id="modifierButton" class="btn btn-primary">Modifier l'Évaluation</button>
                </div>
            </form>

            <div class="text-center mt-3">
                <form method="POST"
                      action="index.php?module=evaluationprof&action=supprimerEvaluation&idProjet=<?php echo $idSae; ?>"
                      onsubmit="return confirmationSupprimer();">
                    <input type="hidden" name="token" value="<?= $_SESSION['token'] ?>">
                    <input type="hidden" name="id_evaluation" value="<?= htmlspecialchars($id) ?>">
                    <button type="submit" class="btn btn-danger">Supprimer l'Évaluation</button>
                </form>
            </div>
        </div>

        <?php
    }


    public function afficherTableauAllEvaluation($allRendue, $allSoutenance, $idSae)
    {
        ?>
        <div class="container mt-4">
            <h1 class="mb-4">Gestion évaluation</h1>
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
                            <form method="POST"
                                  action="index.php?module=evaluationprof&action=formEvaluation&idProjet=<?php echo $idSae; ?>">
                                <input type="hidden" name="id_rendu"
                                       value="<?= htmlspecialchars($rendue['id_rendu']) ?>">
                                <button type="submit"
                                        class="btn btn-sm <?= $rendue['typeDemande'] === 'creer' ? 'btn-primary' : 'btn-warning' ?>">
                                    <?= $rendue['typeDemande'] === 'creer' ? 'Créer une évaluation' : ($rendue['typeDemande'] === 'gestion' ? 'Gérer l\'évaluation' : 'Voir l\'évaluation') ?>
                                </button>
                                <input type="hidden" name="type_demande" value="<?= $rendue['typeDemande'] ?>">
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>

                <?php foreach ($allSoutenance as $soutenance): ?>
                    <tr>
                        <td><?= htmlspecialchars($soutenance['titre']) ?></td>
                        <td>
                            <form method="POST"
                                  action="index.php?module=evaluationprof&action=formEvaluation&idProjet=<?= htmlspecialchars($idSae); ?>">
                                <input type="hidden" name="id_soutenance"
                                       value="<?= htmlspecialchars($soutenance['id_soutenance']) ?>">
                                <button type="submit"
                                        class="btn btn-sm <?= $soutenance['typeDemandeSoutenance'] === 'creer' ? 'btn-primary' : 'btn-warning' ?>">
                                    <?= $soutenance['typeDemandeSoutenance'] === 'creer' ? 'Créer une évaluation' : ($soutenance['typeDemandeSoutenance'] === 'gestion' ? 'Gérer l\'évaluation' : 'Voir l\'évaluation') ?>
                                </button>
                                <input type="hidden" name="type_demande"
                                       value="<?= htmlspecialchars($soutenance['typeDemandeSoutenance']) ?>">
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>

                </tbody>
            </table>
            <div class="text-center mt-4">
                <a href="index.php?module=notefinalprof&idProjet=<?php echo $idSae; ?>" class="btn btn-success"
                   style="padding: 10px 18px; font-size: 16px;">
                    <i class="bi bi-clipboard-check"></i> Voir les notes finales
                </a>
            </div>
        </div>
        <?php
    }

    public function afficherTableauRenduGerer($rendueEvaluations, $iAmEvaluateurPrincipal, $evaluateurs, $idSAE)
    {
        ?>
        <div class="container mt-4">
            <h1 class="text-center"><?= htmlspecialchars($rendueEvaluations[0]['rendu_titre']); ?></h1>
            <?php if ($iAmEvaluateurPrincipal): ?>
                <form method="POST"
                      action="index.php?module=evaluationprof&action=versModifierEvaluation&idProjet=<?php echo $idSAE; ?>">
                    <input type="hidden" name="id_evaluation"
                           value="<?= htmlspecialchars($rendueEvaluations[0]['id_evaluation']) ?>">
                    <button type="submit" class="btn btn-sm btn-primary mt-3">Modifier le rendu</button>
                </form>
            <?php endif; ?>

            <?php $this->afficherEvaluateurs($evaluateurs); ?>

            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="card-title">Informations Générales</h5>
                    <p class="card-text"><strong>Date Limite
                            :</strong> <?= htmlspecialchars($rendueEvaluations[0]['rendu_date_limite']) ?></p>
                    <p class="card-text"><strong>Note Max
                            :</strong> <?= htmlspecialchars($rendueEvaluations[0]['note_max']) ?></p>
                    <p class="card-text"><strong>Coefficient
                            :</strong> <?= htmlspecialchars($rendueEvaluations[0]['note_coef']) ?></p>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="table-dark text-center">
                    <tr>
                        <th>Groupe</th>
                        <th>Statut</th>
                        <th>Note Rendu</th>
                        <th>Noter</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($rendueEvaluations as $evaluation): ?>
                        <tr>
                            <td class="text-center align-middle"><?= htmlspecialchars($evaluation['groupe_nom']) ?></td>
                            <td class="text-center align-middle"><?= htmlspecialchars($evaluation['rendu_statut']) ?></td>
                            <td class="align-middle"><?= nl2br(htmlspecialchars($evaluation['notes_individuelles'])) ?></td>
                            <td class="text-center align-middle">
                                <form method="POST"
                                      action="index.php?module=evaluationprof&action=choixNotation&idProjet=<?php echo $idSAE; ?>">
                                    <input type="hidden" name="id_groupe"
                                           value="<?= htmlspecialchars($evaluation['id_groupe']) ?>">
                                    <input type="hidden" name="type_evaluation" value="rendu">
                                    <input type="hidden" name="id_rendu"
                                           value="<?= htmlspecialchars($evaluation['id_rendu']) ?>">
                                    <?php if ($evaluation['rendu_note'] !== null): ?>
                                        <input type="hidden" name="id_evaluation"
                                               value="<?= htmlspecialchars($evaluation['id_evaluation']) ?>">
                                        <button type="submit" class="btn btn-sm btn-warning"><i class="fas fa-edit"></i>
                                            Modifier les notes
                                        </button>
                                    <?php else: ?>
                                        <button type="submit" class="btn btn-sm btn-primary"><i
                                                    class="fas fa-pencil-alt"></i> Noter
                                        </button>
                                    <?php endif; ?>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <?php
    }


    public function afficherTableauSoutenanceGerer($soutenanceEvaluations, $iAmEvaluateurPrincipal, $evaluateurs, $idSAE)
    {
        ?>
        <div class="container mt-4">
            <h1 class="text-center mb-4"><?= htmlspecialchars($soutenanceEvaluations[0]['soutenance_titre']); ?></h1>

            <?php if ($iAmEvaluateurPrincipal): ?>
                <form method="POST"
                      action="index.php?module=evaluationprof&action=versModifierEvaluation&idProjet=<?php echo $idSAE; ?>">
                    <input type="hidden" name="id_evaluation"
                           value="<?= htmlspecialchars($soutenanceEvaluations[0]['id_evaluation']) ?>">
                    <button type="submit" class="btn btn-sm btn-primary mt-3">Modifier la soutenance</button>
                </form>
            <?php endif; ?>

            <?php $this->afficherEvaluateurs($evaluateurs); ?>

            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="card-title">Informations Générales</h5>
                    <p class="card-text"><strong>Date :</strong> <?= htmlspecialchars($soutenanceEvaluations[0]['soutenance_date']) ?></p>
                    <p class="card-text"><strong>Note Max :</strong> <?= htmlspecialchars($soutenanceEvaluations[0]['note_max']) ?></p>
                    <p class="card-text"><strong>Coefficient :</strong> <?= htmlspecialchars($soutenanceEvaluations[0]['note_coef']) ?></p>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="table-dark text-center">
                    <tr>
                        <th>Groupe</th>
                        <th>Date et Heure</th>
                        <th>Note Soutenance</th>
                        <th>Noter</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($soutenanceEvaluations as $evaluation): ?>
                        <tr>
                            <td class="text-center align-middle"><?= htmlspecialchars($evaluation['groupe_nom']) ?></td>
                            <td class="text-center align-middle">
                                <?= htmlspecialchars($evaluation['soutenance_date']) ?>
                                à <?= htmlspecialchars($evaluation['heure_passage']) ?>
                            </td>
                            <td class="align-middle"><?= nl2br(htmlspecialchars($evaluation['notes_individuelles'])) ?></td>
                            <td class="text-center align-middle">
                                <form method="POST"
                                      action="index.php?module=evaluationprof&action=choixNotation&idProjet=<?php echo $idSAE; ?>">
                                    <input type="hidden" name="id_groupe"
                                           value="<?= htmlspecialchars($evaluation['id_groupe']) ?>">
                                    <input type="hidden" name="type_evaluation" value="soutenance">
                                    <input type="hidden" name="id_soutenance"
                                           value="<?= htmlspecialchars($evaluation['id_soutenance']) ?>">
                                    <?php if ($evaluation['soutenance_note'] !== null): ?>
                                        <input type="hidden" name="id_evaluation"
                                               value="<?= htmlspecialchars($evaluation['id_evaluation']) ?>">
                                        <button type="submit" class="btn btn-sm btn-warning"><i
                                                    class="fas fa-edit"></i> Modifier les notes
                                        </button>
                                    <?php else: ?>
                                        <button type="submit" class="btn btn-sm btn-primary"><i
                                                    class="fas fa-pencil-alt"></i> Noter
                                        </button>
                                    <?php endif; ?>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <?php
    }


    public function afficherFormulaireNotation($allMembres, $id_groupe, $id, $type_evaluation, $contenue, $champsRemplis, $idSAE, $criteres)
    {
        $mode = "individuel";
        ?>
        <div class="container mt-5">
            <h2 class="mb-4 text-center fw-bold">Notation des Membres</h2>

            <?php $this->afficherFichiersRendus($contenue); ?>
            <?php $this->afficherChampsRemplis($champsRemplis); ?>

            <div class="d-flex justify-content-center mb-4">
                <button class="btn btn-outline-primary me-2" id="btn-individuel">Noter Individuellement</button>
                <button class="btn btn-outline-secondary" id="btn-groupe">Noter en Groupe</button>
            </div>

            <!-- Formulaire de notation individuelle -->
            <?= $this->formulaireNotationIndividuelle($allMembres, $id, $type_evaluation, $id_groupe, $criteres, $mode, $idSAE); ?>

            <!-- Formulaire de notation de groupe -->
            <?= $this->formulaireNotationGroupe($allMembres, $id, $type_evaluation, $id_groupe, $criteres, $mode, $idSAE); ?>

        </div>
        <?php
    }

    private function afficherFichiersRendus($contenue)
    {
        if (!empty($contenue)) {
            echo '<div class="mt-5"><h3>Fichiers Rendus :</h3><ul>';
            foreach ($contenue as $fichier) {
                echo '<li><a href="' . htmlspecialchars($fichier['chemin_fichier']) . '" target="_blank">' . htmlspecialchars($fichier['nom_fichier']) . '</a></li>';
            }
            echo '</ul></div>';
        }
    }

    private function afficherChampsRemplis($champsRemplis)
    {
        if (!empty($champsRemplis)) {
            echo '<div class="mt-4"><table class="table table-bordered"><thead><tr><th>Champ</th><th>Valeur</th></tr></thead><tbody>';
            foreach ($champsRemplis as $champ) {
                echo '<tr><td>' . htmlspecialchars($champ['champ_nom']) . '</td><td>' . htmlspecialchars($champ['champ_valeur']) . '</td></tr>';
            }
            echo '</tbody></table></div>';
        }
    }

    private function formulaireNotationIndividuelle($allMembres, $id, $type_evaluation, $id_groupe, $criteres, $mode, $idSAE)
    {
        ?>
        <form method="POST" action="index.php?module=evaluationprof&action=traitementNotationIndividuelle&idProjet=<?= $idSAE; ?>"
              id="form-individuel" <?= $mode === "groupe" ? 'style="display:none;"' : '' ?>>

            <input type="hidden" name="id" value="<?= $id ?>">
            <input type="hidden" name="type_evaluation" value="<?= $type_evaluation ?>">
            <input type="hidden" name="id_groupe" value="<?= $id_groupe ?>">

            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle text-center">
                    <thead class="table-dark">
                    <tr>
                        <th>Nom</th>
                        <th>Prénom</th>
                        <th>Email</th>
                        <?= $this->afficherCritèresTableHead($criteres); ?>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($allMembres as $membre): ?>
                        <tr>
                            <td><?= htmlspecialchars($membre['nom']) ?></td>
                            <td><?= htmlspecialchars($membre['prenom']) ?></td>
                            <td><?= htmlspecialchars($membre['email']) ?></td>
                            <?= $this->afficherNotesIndividuelles($membre, $criteres); ?>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                <label for="commentaire_individuel" class="form-label fw-bold">Ajouter un commentaire (optionnel)</label>
                <textarea class="form-control" id="commentaire_individuel" name="commentaire" rows="4" placeholder="Votre commentaire"></textarea>
            </div>

            <div class="text-center mt-4">
                <button type="submit" class="btn btn-success px-4">Soumettre les Notes</button>
            </div>
        </form>
        <?php
    }


    private function afficherCritèresTableHead($criteres)
    {
        if (!empty($criteres)) {
            foreach ($criteres as $critere) {
                echo '<th>' . htmlspecialchars($critere['nom_critere']) . '</th>';
            }
        } else {
            echo '<th>Note</th>';
        }
    }

    private function afficherNotesIndividuelles($membre, $criteres)
    {
        if (!empty($criteres)) {
            foreach ($criteres as $critere) {
                echo '<td><input type="number" class="form-control" name="notes[' . htmlspecialchars($membre['id_utilisateur']) . '][' . htmlspecialchars($critere['id_critere']) . ']" step="0.01" placeholder="Note" required></td>';
            }
        } else {
            echo '<td><input type="number" class="form-control" name="notes[' . htmlspecialchars($membre['id_utilisateur']) . '][default]" step="0.01" placeholder="Note" required></td>';
        }
    }

    private function formulaireNotationGroupe($allMembres, $id, $type_evaluation, $id_groupe, $criteres, $mode, $idSAE)
    {
        ?>
        <form method="POST" action="index.php?module=evaluationprof&action=traitementNotationGroupe&idProjet=<?= $idSAE; ?>"
              id="form-groupe" <?= $mode === "individuel" ? 'style="display:none;"' : '' ?>>

            <div class="mt-4">
                <h3>Membres du Groupe</h3>
                <div class="table-responsive">
                    <table class="table table-bordered table-hover align-middle text-center">
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
            </div>

            <div class="mt-4">
                <h3>Notation</h3>
                <div class="table-responsive">
                    <table class="table table-bordered align-middle text-center">
                        <thead class="table-dark">
                        <tr>
                            <?php if (!empty($criteres)): ?>
                                <th>Critère</th>
                                <th>Description</th>
                                <th>Note</th>
                            <?php else: ?>
                                <th>Note</th>
                            <?php endif; ?>
                        </tr>
                        </thead>
                        <tbody>
                        <?php if (!empty($criteres)): ?>
                            <?php foreach ($criteres as $critere): ?>
                                <tr>
                                    <td><?= htmlspecialchars($critere['nom_critere']) ?></td>
                                    <td><?= htmlspecialchars($critere['description']) ?></td>
                                    <td>
                                        <input type="number" class="form-control"
                                               name="notes[<?= htmlspecialchars($critere['id_critere']) ?>]"
                                               step="0.01"
                                               placeholder="Note pour ce critère"
                                               required>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="3">
                                    <input type="number" class="form-control" name="notes[default]"
                                           step="0.01" placeholder="Note pour le groupe" required>
                                </td>
                            </tr>
                        <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="mt-4">
                <label for="commentaire_groupe" class="form-label fw-bold">Ajouter un commentaire (optionnel)</label>
                <textarea class="form-control" id="commentaire_groupe" name="commentaire" rows="4" placeholder="Votre commentaire"></textarea>
            </div>

            <input type="hidden" name="id_groupe" value="<?= $id_groupe ?>">
            <input type="hidden" name="id" value="<?= $id ?>">
            <input type="hidden" name="type_evaluation" value="<?= $type_evaluation ?>">

            <div class="text-center mt-4">
                <button type="submit" class="btn btn-success px-4">Soumettre la Note de Groupe</button>
            </div>
        </form>
        <?php
    }





    public function afficherTableauRenduNonGerer($rendueEvaluations, $evaluateurs)
    {
        ?>
        <div class="container mt-4">
            <h1 class="text-center mb-4"><?= htmlspecialchars($rendueEvaluations[0]['rendu_titre']); ?></h1>

            <?php $this->afficherEvaluateurs($evaluateurs); ?>

            <?php if (!empty($rendueEvaluations)): ?>
                <div class="card mb-4">
                    <div class="card-body">
                        <h5 class="card-title">Informations Générales</h5>
                        <p class="card-text"><strong>Date Limite
                                :</strong> <?= htmlspecialchars($rendueEvaluations[0]['rendu_date_limite']) ?></p>
                        <p class="card-text"><strong>Note Max
                                :</strong> <?= htmlspecialchars($rendueEvaluations[0]['note_max']) ?></p>
                        <p class="card-text"><strong>Coefficient
                                :</strong> <?= htmlspecialchars($rendueEvaluations[0]['note_coef']) ?></p>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="table-dark text-center">
                        <tr>
                            <th>Groupe</th>
                            <th>Statut</th>
                            <th>Note Rendu</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($rendueEvaluations as $evaluation): ?>
                            <tr>
                                <td class="text-center align-middle"><?= htmlspecialchars($evaluation['groupe_nom']) ?></td>
                                <td class="text-center align-middle"><?= htmlspecialchars($evaluation['rendu_statut']) ?></td>
                                <td class="align-middle"><?= nl2br(htmlspecialchars($evaluation['notes_individuelles'])) ?></td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
        <?php
    }

    public function afficherTableauSoutenanceNonGerer($soutenanceEvaluations, $evaluateurs)
    {
        ?>
        <div class="container mt-4">
            <h1 class="text-center mb-4"><?= htmlspecialchars($soutenanceEvaluations[0]['soutenance_titre']); ?></h1>

            <?php $this->afficherEvaluateurs($evaluateurs); ?>

            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="card-title">Informations Générales</h5>
                    <p class="card-text"><strong>Date Soutenance
                            :</strong> <?= htmlspecialchars($soutenanceEvaluations[0]['soutenance_date']) ?></p>
                    <p class="card-text"><strong>Note Max
                            :</strong> <?= htmlspecialchars($soutenanceEvaluations[0]['note_max']) ?></p>
                    <p class="card-text"><strong>Coefficient
                            :</strong> <?= htmlspecialchars($soutenanceEvaluations[0]['note_coef']) ?></p>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="table-dark text-center">
                    <tr>
                        <th>Groupe</th>
                        <th>Date</th>
                        <th>Note Soutenance</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($soutenanceEvaluations as $evaluation): ?>
                        <tr>
                            <td class="text-center align-middle"><?= htmlspecialchars($evaluation['groupe_nom']) ?></td>
                            <td class="text-center align-middle"><?= htmlspecialchars($evaluation['soutenance_date']) ?></td>
                            <td class="align-middle"><?= nl2br(htmlspecialchars($evaluation['notes_individuelles'])) ?></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <?php
    }


}

?>
