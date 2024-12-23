<?php

include_once 'generique/vue_generique.php';
Class VueGroupe extends VueGenerique{
    public function __construct() {
        parent::__construct();
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
            <form action="index.php?module=professeur&action=supprimerMembresGroupe" method="post">
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
                            <input type="checkbox" name="membres_a_supprimer[]" value="<?php echo htmlspecialchars($membre['id_utilisateur']); ?>">
                            <?php echo htmlspecialchars($membre['prenom'] . ' ' . $membre['nom']); ?>
                            (<?php echo htmlspecialchars($membre['email']); ?>)
                        </li>
                    <?php endforeach; ?>
                </ul>

                <button type="submit" class="btn btn-danger">Supprimer les membres sélectionnés</button>
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