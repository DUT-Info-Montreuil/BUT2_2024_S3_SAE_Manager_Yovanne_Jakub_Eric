<?php

include_once 'generique/vue_generique.php';
Class VueGroupeProf extends VueGenerique{
    public function __construct() {
        parent::__construct();
    }
    public function afficherGroupeSAE($groupes) {
        ?>
        <div class="container mt-4">
            <h2>Gestion des Groupes pour la SAE</h2>
            <table class="table table-bordered table-striped mt-4">
                <thead class="thead-dark">
                <tr>
                    <th>Nom du Groupe</th>
                    <th>Membres</th>
                    <th>Champs</th>
                    <th>Modifier</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                <?php if (!empty($groupes)): ?>
                    <?php foreach ($groupes as $groupe): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($groupe['nom_groupe']); ?></td>
                            <td><?php echo implode(', ', $groupe['membres']); ?></td>
                            <td><?php echo implode(', ', $groupe['champs']); ?></td>
                            <td>
                                <a href="index.php?module=groupeprof&action=versModifierGroupe&idGroupe=<?php echo $groupe['id_groupe']; ?>" class="btn btn-sm btn-secondary">
                                    <i class="fas fa-cog"></i>
                                </a>
                            </td>
                            <td>
                                <button type="button" class="btn btn-sm btn-warning malus-btn" data-student-id="<?php echo $groupe['id_groupe']; ?>" data-bs-toggle="modal" data-bs-target="#malusModal">
                                    <i class="fas fa-minus-circle"></i> Appliquer Malus
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="5">Aucun groupe trouvé pour cette SAE</td></tr>
                <?php endif; ?>
                </tbody>
            </table>

            <div class="text-center mt-4">
                <a href="index.php?module=groupeprof&action=ajouterGroupeFormulaire" class="btn btn-primary btn-lg">
                    <i class="fas fa-plus"></i> Ajouter un Groupe
                </a>
            </div>
        </div>

        <!-- Modal pour appliquer le malus -->
        <div class="modal fade" id="malusModal" tabindex="-1" aria-labelledby="malusModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="malusModalLabel">Appliquer un Malus</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form>
                            <div class="mb-3">
                                <label for="malusInput" class="form-label">Valeur du malus</label>
                                <input type="number" class="form-control" id="malusInput" min="0" step="0.01">
                                <div class="invalid-feedback">
                                    Veuillez entrer une valeur valide.
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                        <button type="button" class="btn btn-primary" id="applyMalus">Appliquer</button>
                    </div>
                </div>
            </div>
        </div>
        <script src="scripteMalus.js"></script>
        <?php
    }






    public function formulaireModifierGroupe($detailsGroupe, $tabNvEtudiant, $idGroupe) {
        ?>
        <div class="container mt-5">
            <div class="card shadow-lg p-4 mb-5">
                <h3 class="text-center mb-4">Modifier le Groupe</h3>
                <form action="index.php?module=groupeprof&action=enregistrerModificationsGroupe" method="post">
                    <input type="hidden" name="id_groupe" value="<?php echo htmlspecialchars($detailsGroupe['id_groupe']); ?>">

                    <div class="mb-4">
                        <label for="nomGroupe" class="form-label fs-5">Nom du Groupe</label>
                        <input type="text" id="nomGroupe" name="nomGroupe" class="form-control form-control-lg"
                               value="<?php echo htmlspecialchars($detailsGroupe['nom_groupe']); ?>" required>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fs-5">Modifiable par le groupe</label>
                        <div class="d-flex justify-content-start">
                            <div class="form-check me-4">
                                <input type="radio" id="modifiable_oui" name="modifiable_par_groupe" class="form-check-input" value="1"
                                    <?php echo $detailsGroupe['modifiable_par_groupe'] ? 'checked' : ''; ?>>
                                <label class="form-check-label" for="modifiable_oui">Oui</label>
                            </div>
                            <div class="form-check">
                                <input type="radio" id="modifiable_non" name="modifiable_par_groupe" class="form-check-input" value="0"
                                    <?php echo !$detailsGroupe['modifiable_par_groupe'] ? 'checked' : ''; ?>>
                                <label class="form-check-label" for="modifiable_non">Non</label>
                            </div>
                        </div>
                    </div>

                    <div class="mb-4">
                        <h5 class="mb-3">Modifiez les Champs</h5>
                        <?php if (!empty($detailsGroupe['champs'])): ?>
                            <?php foreach ($detailsGroupe['champs'] as $champ): ?>
                                <input type="hidden" name="champs[<?php echo htmlspecialchars($champ['champ_id']); ?>][id_champ]" value="<?php echo htmlspecialchars($champ['champ_id']); ?>">

                                <div class="row mb-2">
                                    <div class="col-md-1">
                                        <label for="champ_<?php echo htmlspecialchars($champ['champ_id']); ?>" class="form-label">
                                            <?php echo htmlspecialchars($champ['champ_nom']); ?>
                                        </label>
                                    </div>
                                    <div class="col">
                                        <input type="text" id="champ_<?php echo htmlspecialchars($champ['champ_id']); ?>"
                                               name="champs[<?php echo htmlspecialchars($champ['champ_id']); ?>][champ_valeur]"
                                               class="form-control form-control-lg"
                                               value="<?php echo htmlspecialchars($champ['champ_valeur']); ?>" />
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>


                    <div class="mb-4">
                        <h5 class="fs-5 mb-3">Supprimer des Membres</h5>
                        <ul class="list-unstyled">
                            <?php foreach ($detailsGroupe['membres'] as $membre): ?>
                                <li class="d-flex justify-content-between mb-2">
                                    <div>
                                        <input type="checkbox" name="membres_a_supprimer[]" value="<?php echo htmlspecialchars($membre['id_utilisateur']); ?>" class="form-check-input me-2">
                                        <strong><?php echo htmlspecialchars($membre['prenom'] . ' ' . $membre['nom']); ?></strong>
                                        <span class="text-muted d-block"><?php echo htmlspecialchars($membre['email']); ?></span>
                                    </div>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>

                    <div class="mb-4">
                        <h5 class="fs-5 mb-3">Ajouter des Étudiants</h5>
                        <select multiple class="form-select form-select-lg" id="etudiants" name="etudiants[]">
                            <?php foreach ($tabNvEtudiant as $etudiant): ?>
                                <option value="<?php echo $etudiant['id_utilisateur']; ?>">
                                    <?php echo htmlspecialchars($etudiant['nom_complet']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="d-flex justify-content-center mt-4">
                        <button type="submit" class="btn btn-primary btn-lg w-100 shadow-sm me-2">Enregistrer</button>
                        <a href="index.php?module=groupeprof&action=gestionGroupeSAE" class="btn btn-secondary btn-lg w-100 shadow-sm">Retour</a>
                    </div>


                </form>
                <form action="index.php?module=groupeprof&action=supprimerGrp" method="post" class="text-center" onsubmit="return confirmationSupprimer();">
                    <input type="hidden" name="idGroupe" value="<?php echo $idGroupe; ?>">
                    <button type="submit" class="btn btn-danger btn-lg w-100 shadow-sm">Supprimer le groupe</button>
                </form>
                <script src="scriptConfirmationSuppr.js"></script>
            </div>
        </div>
        <?php
    }

    public function afficherFormulaireAjoutGroupe($etudiants) {
        ?>
        <div class="container mt-5">
            <h2>Ajouter un Nouveau Groupe</h2>
            <form method="post" action="index.php?module=groupeprof&action=creerGroupe">
                <div class="form-group mt-4">
                    <label for="nom_groupe">Nom du Groupe</label>
                    <input type="text" class="form-control" id="nom_groupe" name="nom_groupe"
                           placeholder="Entrez le nom du groupe" required>
                </div>
                <div class="form-group mt-3">
                    <label for="etudiants">Sélectionner des Étudiants</label>
                    <select multiple class="form-control" id="etudiants" name="etudiants[]">
                        <?php foreach ($etudiants as $etudiant): ?>
                            <option value="<?php echo $etudiant['id_utilisateur']; ?>">
                                <?php echo htmlspecialchars($etudiant['nom_complet']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <button type="submit" class="btn btn-success mt-4">Créer le Groupe</button>
                <a href="index.php?module=groupeprof&action=gestionGroupeSAE" class="btn btn-secondary mt-4">Retour</a>
            </form>
        </div>
        <?php
    }
}