<?php

include_once 'generique/vue_generique.php';

class VueGroupeProf extends VueGenerique
{
    public function __construct()
    {
        parent::__construct();
    }

    public function afficherGroupeSAE($groupes, $idSae)
    {
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
                                <a href="index.php?module=groupeprof&action=versModifierGroupe&idGroupe=<?php echo $groupe['id_groupe']; ?>&idProjet=<?php echo $idSae; ?>">
                                    <img src="../../../assets/modif.png" alt="Modifier le groupe" class="img-fluid" style="max-width: 20px; height: auto;" />
                                </a>

                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5">Aucun groupe trouvé pour cette SAE</td>
                    </tr>
                <?php endif; ?>
                </tbody>
            </table>

            <div class="text-center mt-4">
                <a href="index.php?module=groupeprof&action=ajouterGroupeFormulaire&idProjet=<?php echo $idSae; ?>"
                   class="btn btn-primary btn-lg">
                    <i class="fas fa-plus"></i> Ajouter un Groupe
                </a>
            </div>
        </div>
        <?php
    }

    public function formulaireModifierGroupe($detailsGroupe, $tabNvEtudiant, $idGroupe, $idSae)
    {
        ?>
        <div class="container mt-5">
            <div class="card shadow-lg p-5 mb-5 border-0 rounded-5" style="background-color: #ffffff;">
                <h3 class="text-center mb-4" style="font-weight: bold; font-size: 2rem; color: #333;">Modifier le Groupe</h3>
                <form action="index.php?module=groupeprof&action=enregistrerModificationsGroupe&idProjet=<?php echo $idSae; ?>"
                      method="post" class="needs-validation" novalidate>
                    <input type="hidden" name="token" value="<?= $_SESSION['token'] ?>">
                    <input type="hidden" name="id_groupe" value="<?php echo htmlspecialchars($detailsGroupe['id_groupe']); ?>">

                    <div class="mb-4">
                        <label for="nomGroupe" class="form-label fs-5 text-dark">Nom du Groupe</label>
                        <input type="text" id="nomGroupe" name="nomGroupe" class="form-control form-control-lg"
                               value="<?php echo htmlspecialchars($detailsGroupe['nom_groupe']); ?>" required
                               style="border-radius: 10px; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);">
                        <div class="invalid-feedback">Veuillez entrer un nom de groupe valide.</div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fs-5 text-dark">Modifiable par le groupe</label>
                        <div class="d-flex justify-content-start">
                            <div class="form-check me-4">
                                <input type="radio" id="modifiable_oui" name="modifiable_par_groupe"
                                       class="form-check-input" value="1"
                                    <?php echo $detailsGroupe['modifiable_par_groupe'] ? 'checked' : ''; ?>
                                       style="border-radius: 50%;">
                                <label class="form-check-label" for="modifiable_oui">Oui</label>
                            </div>
                            <div class="form-check">
                                <input type="radio" id="modifiable_non" name="modifiable_par_groupe"
                                       class="form-check-input" value="0"
                                    <?php echo !$detailsGroupe['modifiable_par_groupe'] ? 'checked' : ''; ?>
                                       style="border-radius: 50%;">
                                <label class="form-check-label" for="modifiable_non">Non</label>
                            </div>
                        </div>
                    </div>

                    <?php if (!empty($detailsGroupe['champs'])): ?>
                        <div class="mb-4">
                            <h5 class="fs-5 mb-3 text-dark">Modifiez les Champs</h5>
                            <?php foreach ($detailsGroupe['champs'] as $champ): ?>
                                <input type="hidden" name="champs[<?php echo htmlspecialchars($champ['champ_id']); ?>][id_champ]"
                                       value="<?php echo htmlspecialchars($champ['champ_id']); ?>">

                                <div class="row mb-3">
                                    <div class="col-md-4">
                                        <label for="champ_<?php echo htmlspecialchars($champ['champ_id']); ?>"
                                               class="form-label text-dark"><?php echo htmlspecialchars($champ['champ_nom']); ?></label>
                                    </div>
                                    <div class="col">
                                        <input type="text" id="champ_<?php echo htmlspecialchars($champ['champ_id']); ?>"
                                               name="champs[<?php echo htmlspecialchars($champ['champ_id']); ?>][champ_valeur]"
                                               class="form-control form-control-lg" value="<?php echo htmlspecialchars($champ['champ_valeur']); ?>"
                                               style="border-radius: 10px; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);">
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>

                    <div class="mb-4">
                        <h5 class="fs-5 mb-3 text-dark">Supprimer des Membres</h5>
                        <ul class="list-unstyled">
                            <?php foreach ($detailsGroupe['membres'] as $membre): ?>
                                <li class="d-flex justify-content-between mb-3 align-items-center">
                                    <div>
                                        <input type="checkbox" name="membres_a_supprimer[]"
                                               value="<?php echo htmlspecialchars($membre['id_utilisateur']); ?>"
                                               class="form-check-input me-2">
                                        <strong><?php echo htmlspecialchars($membre['prenom'] . ' ' . $membre['nom']); ?></strong>
                                        <span class="text-muted"><?php echo htmlspecialchars($membre['email']); ?></span>
                                    </div>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>

                    <div class="mb-4">
                        <h5 class="fs-5 mb-3 text-dark">Ajouter des Étudiants</h5>
                        <select multiple class="form-select form-select-lg" id="etudiants" name="etudiants[]"
                                style="border-radius: 10px; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);">
                            <?php foreach ($tabNvEtudiant as $etudiant): ?>
                                <option value="<?php echo $etudiant['id_utilisateur']; ?>">
                                    <?php echo htmlspecialchars($etudiant['nom_complet']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="d-flex justify-content-center mt-4">
                        <button type="submit" class="btn btn-primary btn-lg w-100" style="border-radius: 30px; padding: 12px 20px;">
                            Enregistrer les modifications
                        </button>
                    </div>
                </form>

                <form action="index.php?module=groupeprof&action=supprimerGrp&idProjet=<?php echo $idSae; ?>"
                      method="post" class="text-center mt-4" onsubmit="return confirmationSupprimer();">
                    <input type="hidden" name="token" value="<?= $_SESSION['token'] ?>">
                    <input type="hidden" name="idGroupe" value="<?php echo $idGroupe; ?>">
                    <button type="submit" class="btn btn-danger btn-lg w-100" style="border-radius: 30px; padding: 12px 20px;">
                        Supprimer le groupe
                    </button>
                </form>
            </div>
        </div>

        <?php
    }





    public function afficherFormulaireAjoutGroupe($etudiants, $idSae)
    {
        ?>
        <div class="container mt-5">
            <h2>Ajouter un Nouveau Groupe</h2>
            <form method="post" action="index.php?module=groupeprof&action=creerGroupe&idProjet=<?php echo $idSae; ?>">
                <input type="hidden" name="token" value="<?= $_SESSION['token'] ?>">

                <div class="form-group mt-4">
                    <label for="nom_groupe">Nom du Groupe</label>
                    <input type="text" class="form-control" id="nom_groupe" name="nom_groupe"
                           placeholder="Entrez le nom du groupe" required>
                </div>

                <div class="form-group mt-3">
                    <label for="recherche_etudiant">Filtrer les Étudiants par Nom</label>
                    <input type="text" id="recherche_etudiant" class="form-control"
                           placeholder="Rechercher un étudiant par nom" onkeyup="filtrerEtudiants()" />
                </div>

                <div class="form-group mt-3">
                    <label for="etudiants">Sélectionner des Étudiants</label>
                    <select multiple class="form-control" id="etudiants" name="etudiants[]" size="20">
                        <?php foreach ($etudiants as $etudiant): ?>
                            <option value="<?php echo $etudiant['id_utilisateur']; ?>"
                                    data-semestre="<?php echo htmlspecialchars($etudiant['semestre']); ?>">
                                <?php echo htmlspecialchars($etudiant['nom_complet']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <button type="submit" class="btn btn-success mt-4">Créer le Groupe</button>
                <a href="index.php?module=groupeprof&action=gestionGroupeSAE&idProjet=<?php echo $idSae; ?>"
                   class="btn btn-secondary mt-4">Retour</a>
            </form>
        </div>
        <?php
    }

}