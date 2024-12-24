<?php
include_once 'generique/vue_generique.php';

class VueGerant extends VueGenerique{
    public function __construct()
    {
        parent::__construct();
    }
    public function afficherGerantSAE($gerantSAE)
    {
        ?>
        <div class="container mt-4">
            <h2>Gestion des Gérants pour la SAE</h2>
            <table class="table table-bordered table-striped mt-4">
                <thead class="thead-dark">
                <tr>
                    <th>Nom Prénom</th>
                    <th>Rôle</th>
                    <th>Modifier</th>
                </tr>
                </thead>
                <tbody>
                <?php
                if (!empty($gerantSAE)) {
                    $currentGroup = null;

                    foreach ($gerantSAE as $row) {
                        if ($currentGroup === null || $currentGroup['id_utilisateur'] !== $row['id_utilisateur']) {
                            if ($currentGroup !== null) {
                                echo "<tr>";
                                echo "<td>{$currentGroup['nom_complet']}</td>";
                                echo "<td>{$currentGroup['role_utilisateur']}</td>";
                                echo "<td>
                                    <a href='index.php?module=gerantprof&action=versModifierGerant&idGerant={$currentGroup['id_utilisateur']}' class='btn btn-sm btn-secondary'>
                                        <i class='fas fa-cog'></i>
                                    </a>
                                  </td>";
                                echo "</tr>";
                            }
                            $currentGroup = [
                                'nom_complet' => $row['nom_complet'],
                                'id_utilisateur' => $row['id_utilisateur'],
                                'role_utilisateur' => $row['role_utilisateur']
                            ];
                        }
                    }

                    if ($currentGroup !== null) {
                        echo "<tr>";
                        echo "<td>{$currentGroup['nom_complet']}</td>";
                        echo "<td>{$currentGroup['role_utilisateur']}</td>";
                        echo "<td>
                            <a href='index.php?module=gerantprof&action=versModifierGerant&idGerant={$currentGroup['id_utilisateur']}' class='btn btn-sm btn-secondary'>
                                <i class='fas fa-cog'></i>
                            </a>
                          </td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='3'>Aucun gérant trouvé pour cette SAE</td></tr>";
                }
                ?>
                </tbody>
            </table>

            <div class="text-center mt-4">
                <a href="index.php?module=gerantprof&action=ajouterGerantFormulaire" class="btn btn-primary btn-lg">
                    <i class="fas fa-plus"></i> Ajouter un Gérant
                </a>
            </div>
        </div>
        <?php
    }
    public function formulaireModifierGerant($tabDetailsGerant, $idGerant)
    {
        ?>
        <div class="container mt-4">
            <h2>Modifier le Gérant</h2>
            <form action="index.php?module=gerantprof&action=enregistrerModificationsGerant" method="post">
                <input type="hidden" name="id_groupe"
                       value="<?php echo htmlspecialchars($tabDetailsGerant['id_utilisateur']); ?>">

                <div class="form-group">
                    <label for="nomGroupe">Nom du Gérant</label>
                    <p> <?php echo $tabDetailsGerant['nom_complet'] ?></p>
                </div>

                <label>Rôle Gérant</label>
                <div class="form-group">
                    <div class="form-check form-check-inline">
                        <input
                                type="radio"
                                id="coreponsable"
                                name="role_gerant"
                                class="form-check-input"
                                value="1"
                            <?php echo $tabDetailsGerant['role_utilisateur'] == "Co-Responsable" ? 'checked' : ''; ?>>
                        <label class="form-check-label" for="modifiable_oui">Co-Responsable</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input
                                type="radio"
                                id="intervenant"
                                name="role_gerant"
                                class="form-check-input"
                                value="0"
                            <?php echo !$tabDetailsGerant['role_utilisateur'] == "Intervenant" ? 'checked' : ''; ?>>
                        <label class="form-check-label" for="modifiable_non">Intervenant</label>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary mt-4">Enregistrer les modifications</button>
                <a href="index.php?module=gerantprof&action=supprimerGerant&idGerant=<?php echo $idGerant ?>"
                   class="btn btn-danger mt-4">Supprimer le Gérant</a>
                <a href="index.php?module=gerantprof&action=gestionGerantSAE" class="btn btn-secondary mt-4">Retour</a>
            </form>
        </div>
        <?php
    }

    public function afficherFormulaireAjoutGerant($tabGerant){
        ?>
        <div class="container mt-5">
            <h2>Ajouter des Gérants</h2>
            <form method="post" action="index.php?module=gerantprof&action=ajouterGerants">
                <div class="form-group">
                    <label>Choisissez un rôle pour les gérant :</label>
                    <div class="btn-group btn-group-toggle d-flex mt-2" data-toggle="buttons">
                        <label class="btn btn-outline-primary flex-fill">
                            <input
                                    type="radio"
                                    id="coreponsable"
                                    name="role_gerant"
                                    value="1"
                                    autocomplete="off"
                            >
                            Co-Responsable
                        </label>
                        <label class="btn btn-outline-secondary flex-fill">
                            <input
                                    type="radio"
                                    id="intervenant"
                                    name="role_gerant"
                                    value="0"
                                    autocomplete="off"
                            >
                            Intervenant
                        </label>
                    </div>
                </div>
                <div class="form-group mt-3">
                    <label for="gerants">Sélectionner des Professeurs</label>
                    <select multiple class="form-control" id="gerants" name="gerants[]">
                        <?php foreach ($tabGerant as $gerant): ?>
                            <option value="<?php echo htmlspecialchars($gerant['id_utilisateur']); ?>">
                                <?php echo htmlspecialchars($gerant['nom_complet']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <button type="submit" class="btn btn-success mt-4">Ajouter les Gérants</button>
                <a href="index.php?module=gerantprof&action=gestionGerantSAE" class="btn btn-secondary mt-4">Retour</a>
            </form>
        </div>
        <?php
    }

}