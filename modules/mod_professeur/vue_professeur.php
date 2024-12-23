<?php
include_once 'generique/vue_generique.php';

Class VueProfesseur extends VueGenerique {
    public function __construct() {
        parent::__construct();
    }
    public function afficherSaeGerer($saeGerer) {
        ?>
        <div class="container mt-4">
            <div class="row justify-content-center g-0">
                <?php foreach ($saeGerer as $sae): ?>
                    <div class="col-md-4 d-flex justify-content-center mb-2">
                        <div class="card shadow-sm border-light"
                             style="width: 250px; height: 250px; border-radius: 10px;
                        background-color: #c6c6c6; display: flex; justify-content: center;
                        align-items: center; text-align: center;">
                            <a class="text-decoration-none" href="index.php?module=professeur&action=choixSae&id=<?php echo htmlspecialchars($sae['id_projet']); ?>">
                                <h3 class="text-center" style="color: #333; font-weight: bold;">
                                    <?php echo htmlspecialchars($sae['titre']); ?>
                                </h3>
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>

                <div class="col-md-4 d-flex justify-content-center mb-2">
                    <a href="index.php?module=professeur&action=creerSAE" class="text-center"
                       style="color: #333; font-weight: bold; margin: 0; text-decoration: none;">
                        <div class="card shadow-sm border-light"
                             style="width: 250px; height: 250px; border-radius: 10px;
                                background-color: #c6c6c6; display: flex; justify-content: center;
                                align-items: center; cursor: pointer; text-align: center;">
                            <h1 style="color: #333; font-weight: bold; margin: 0; font-size: 3rem;">+</h1>
                        </div>
                    </a>
                </div>
            </div>
        </div>
        <?php
    }
    public function creerUneSAE() {
        ?>
        <div class="container mt-4">
            <h2>Formulaire de création d'une SAE</h2>
            <form action="index.php?module=professeur&action=creerSAE" method="post">
                <div class="mb-3">
                    <label for="titre" class="form-label">Titre de la SAE :</label>
                    <input type="text" class="form-control" id="titre" name="titre" required>
                </div>
                <div class="mb-3">
                    <label for="annee" class="form-label">Année universitaire :</label>
                    <input type="text" class="form-control" id="annee" name="annee" required>
                </div>
                <div class="mb-3">
                    <label for="semestre" class="form-label">Semestre :</label>
                    <input type="text" class="form-control" id="semestre" name="semestre" required>
                </div>
                <div class="mb-3">
                    <label for="description" class="form-label">Description :</label>
                    <textarea class="form-control" id="description" name="description" rows="3" required></textarea>
                </div>
                <button type="submit" class="btn btn-primary">Créer SAE</button>
            </form>
        </div>
        <?php
    }
    public function afficherSaeDetails() {
        ?>
        <div class="container mt-4">
            <div class="row justify-content-center g-0">
                <div class="col-md-4 d-flex justify-content-center mb-2">
                    <div class="card shadow-sm border-light"
                         style="width: 250px; height: 250px; border-radius: 10px;
                                background-color: #c6c6c6; display: flex; justify-content: center;
                                align-items: center; text-align: center;">
                        <a class="text-decoration-none" href="index.php?module=professeur&action=infoGeneralSae">
                            <h3 class="text-center" style="color: #333; font-weight: bold;">
                                Information General
                            </h3>
                        </a>
                    </div>
                </div>

                <div class="col-md-4 d-flex justify-content-center mb-2">
                    <div class="card shadow-sm border-light"
                         style="width: 250px; height: 250px; border-radius: 10px;
                                background-color: #c6c6c6; display: flex; justify-content: center;
                                align-items: center; text-align: center;">
                        <a class="text-decoration-none" href="index.php?module=professeur&action=gestionGroupeSAE">
                            <h3 class="text-center" style="color: #333; font-weight: bold;">
                                Groupe
                            </h3>
                        </a>
                    </div>
                </div>

                <div class="col-md-4 d-flex justify-content-center mb-2">
                    <div class="card shadow-sm border-light"
                         style="width: 250px; height: 250px; border-radius: 10px;
                                background-color: #c6c6c6; display: flex; justify-content: center;
                                align-items: center; text-align: center;">
                        <a class="text-decoration-none" href="index.php?module=professeur&action=gestionGerant">
                            <h3 class="text-center" style="color: #333; font-weight: bold;">
                                Gérant
                            </h3>
                        </a>
                    </div>
                </div>

                <div class="col-md-4 d-flex justify-content-center mb-2">
                    <div class="card shadow-sm border-light"
                         style="width: 250px; height: 250px; border-radius: 10px;
                                background-color: #c6c6c6; display: flex; justify-content: center;
                                align-items: center; text-align: center;">
                        <a class="text-decoration-none" href="index.php?module=professeur&action=gestionDepot">
                            <h3 class="text-center" style="color: #333; font-weight: bold;">
                                Dépôt
                            </h3>
                        </a>
                    </div>
                </div>

                <div class="col-md-4 d-flex justify-content-center mb-2">
                    <div class="card shadow-sm border-light"
                         style="width: 250px; height: 250px; border-radius: 10px;
                                background-color: #c6c6c6; display: flex; justify-content: center;
                                align-items: center; text-align: center;">
                        <a class="text-decoration-none" href="index.php?module=professeur&action=gestionRessource">
                            <h3 class="text-center" style="color: #333; font-weight: bold;">
                                Ressource
                            </h3>
                        </a>
                    </div>
                </div>

                <div class="col-md-4 d-flex justify-content-center mb-2">
                    <div class="card shadow-sm border-light"
                         style="width: 250px; height: 250px; border-radius: 10px;
                                background-color: #c6c6c6; display: flex; justify-content: center;
                                align-items: center; text-align: center;">
                        <a class="text-decoration-none" href="index.php?module=professeur&action=gestionSoutenance">
                            <h3 class="text-center" style="color: #333; font-weight: bold;">
                                Soutenance
                            </h3>
                        </a>
                    </div>
                </div>

            </div>
        </div>
        <?php
    }
    public function afficherSaeInfoGeneral($saeDetails) {
        ?>
        <div class="container mt-4">
            <h2>Détails de la SAE</h2>
            <form method="POST" action="index.php?module=professeur&action=updateSae>">
                <div class="mb-3">
                    <label for="titre" class="form-label"><strong>Titre :</strong></label>
                    <input type="text" class="form-control" id="titre" name="titre" value="<?php echo htmlspecialchars($saeDetails['titre']); ?>" required>
                </div>
                <div class="mb-3">
                    <label for="annee_universitaire" class="form-label"><strong>Année universitaire :</strong></label>
                    <input type="text" class="form-control" id="annee_universitaire" name="annee_universitaire" value="<?php echo htmlspecialchars($saeDetails['annee_universitaire']); ?>" required>
                </div>
                <div class="mb-3">
                    <label for="semestre" class="form-label"><strong>Semestre :</strong></label>
                    <input type="text" class="form-control" id="semestre" name="semestre" value="<?php echo htmlspecialchars($saeDetails['semestre']); ?>" required>
                </div>
                <div class="mb-3">
                    <label for="description_projet" class="form-label"><strong>Description :</strong></label>
                    <textarea class="form-control" id="description_projet" name="description_projet" rows="4" required><?php echo htmlspecialchars($saeDetails['description_projet']); ?></textarea>
                </div>

                <button type="submit" class="btn btn-primary">Mettre à jour</button>
            </form>
        </div>
        <?php
    }
    public function afficherGroupeSAE($groupeSAE) {
        ?>
        <div class="container mt-4">
            <h2>Gestion des Groupes pour la SAE</h2>
            <table class="table table-bordered table-striped mt-4">
                <thead class="thead-dark">
                <tr>
                    <th>Nom du Groupe</th>
                    <th>Membres</th>
                    <th>Modifier</th>
                </tr>
                </thead>
                <tbody>
                <?php
                if (!empty($groupeSAE)) {
                    $currentGroup = null;
                    $members = [];

                    foreach ($groupeSAE as $row) {
                        if ($currentGroup === null || $currentGroup['id_groupe'] !== $row['id_groupe']) {
                            if ($currentGroup !== null) {
                                echo "<tr>";
                                echo "<td>{$currentGroup['nom_groupe']}</td>";
                                echo "<td>" . implode(', ', $members) . "</td>";
                                echo "<td>
                                    <a href='index.php?module=professeur&action=modifierGroupe&idGroupe={$currentGroup['id_groupe']}' class='btn btn-sm btn-secondary'>
                                        <i class='fas fa-cog'></i>
                                    </a>
                                  </td>";
                                echo "</tr>";
                            }
                            $currentGroup = [
                                'id_groupe' => $row['id_groupe'],
                                'nom_groupe' => $row['nom_groupe']
                            ];
                            $members = [];
                        }
                        $members[] = $row['prenom_membre'] . " " . $row['nom_membre'];
                    }

                    if ($currentGroup !== null) {
                        echo "<tr>";
                        echo "<td>{$currentGroup['nom_groupe']}</td>";
                        echo "<td>" . implode(', ', $members) . "</td>";
                        echo "<td>
                            <a href='index.php?module=professeur&action=modifierGroupe&idGroupe={$currentGroup['id_groupe']}' class='btn btn-sm btn-secondary'>
                                <i class='fas fa-cog'></i>
                            </a>
                          </td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='3'>Aucun groupe trouvé pour cette SAE</td></tr>";
                }
                ?>
                </tbody>
            </table>

            <div class="text-center mt-4">
                <a href="index.php?module=professeur&action=ajouterGroupeFormulaire" class="btn btn-primary btn-lg">
                    <i class="fas fa-plus"></i> Ajouter un Groupe
                </a>
            </div>
        </div>
        <?php
    }
    public function formulaireModifierGroupe($detailsGroupe) {
        ?>
        <div class="container mt-4">
            <h2>Modifier le Groupe</h2>
            <form action="index.php?module=professeur&action=enregistrerModificationGroupe" method="post">
                <input type="hidden" name="id_groupe" value="<?php echo htmlspecialchars($detailsGroupe['id_groupe']); ?>">

                <div class="form-group">
                    <label for="nom">Nom du Groupe</label>
                    <input type="text" id="nom" name="nom" class="form-control"
                           value="<?php echo htmlspecialchars($detailsGroupe['nom_groupe']); ?>" required>
                </div>

                <div class="form-group">
                    <label for="modifiable">Modifiable par le groupe</label>
                    <input type="checkbox" id="modifiable" name="modifiable" class="form-control"
                        <?php echo $detailsGroupe['modifiable_par_groupe'] ? 'checked' : ''; ?>>
                </div>

                <h3>Membres</h3>
                <ul>
                    <?php foreach ($detailsGroupe['membres'] as $membre): ?>
                        <li>
                            <?php echo htmlspecialchars($membre['prenom'] . ' ' . $membre['nom']); ?>
                            (<?php echo htmlspecialchars($membre['email']); ?>)
                            <button type="button" class="btn btn-danger btn-sm">Supprimer</button>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </form>
        </div>
        <?php
    }
    public function afficherFormulaireAjoutGroupe($etudiants) {
        ?>
        <div class="container mt-5">
            <h2>Ajouter un Nouveau Groupe</h2>
            <form method="post" action="index.php?module=professeur&action=creerGroupe">
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
                <a href="index.php?module=professeur&action=gestionGroupeSAE" class="btn btn-secondary mt-4">Retour</a>
            </form>
        </div>
        <?php
    }
    public function ajouterEtudiantGrp($tabNvEtudiant, $idGroupe){
        ?>
        <div class="container mt-5">
            <h2>Ajouter des membres</h2>
            <form method="post" action="index.php?module=professeur&action=ajouterNouveauMembreGrp&idGroupe=<?php echo $idGroupe; ?>">
                <div class="form-group mt-3">
                    <label for="etudiants">Sélectionner des Étudiants</label>
                    <select multiple class="form-control" id="etudiants" name="etudiants[]">
                        <?php foreach ($tabNvEtudiant as $etudiant): ?>
                            <option value="<?php echo $etudiant['id_utilisateur']; ?>">
                                <?php echo htmlspecialchars($etudiant['nom_complet']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <button type="submit" class="btn btn-success mt-4">Ajouter les membres</button>
                <a href="index.php?module=professeur&action=gestionGroupeSAE" class="btn btn-secondary mt-4">Retour</a>
            </form>
        </div>
        <?php
    }
}
?>
