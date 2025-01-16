<?php
include_once 'generique/vue_generique.php';

class VueGerantProf extends VueGenerique
{
    public function __construct()
    {
        parent::__construct();
    }

    public function afficherGerantSAE($gerantsData, $idSae)
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
                <?php if (!empty($gerantsData)): ?>
                    <?php foreach ($gerantsData as $group): ?>
                        <tr>
                            <td><?= htmlspecialchars($group['nom_complet']) ?></td>
                            <td><?= htmlspecialchars($group['role_utilisateur']) ?></td>
                            <td>
                                <?php if ($group['role_utilisateur'] !== 'Responsable'): ?>
                                    <a href="index.php?module=gerantprof&action=versModifierGerant&idGerant=<?= $group['id_utilisateur'] ?>&idProjet=<?= $idSae ?>" class="btn btn-sm btn-secondary">
                                        <i class="fas fa-cog"></i>
                                    </a>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="3">Aucun gérant trouvé pour cette SAE</td></tr>
                <?php endif; ?>
                </tbody>
            </table>

            <div class="text-center mt-4">
                <a href="index.php?module=gerantprof&action=ajouterGerantFormulaire&idProjet=<?= $idSae ?>" class="btn btn-primary btn-lg">
                    <i class="fas fa-plus"></i> Ajouter un Gérant
                </a>
            </div>
        </div>
        <?php
    }


    public function formulaireModifierGerant($tabDetailsGerant, $idGerant, $idSae)
    {
        ?>
        <div class="container mt-4">
            <h2>Modifier le Gérant</h2>
            <form action="index.php?module=gerantprof&action=enregistrerModificationsGerant&idProjet=<?php echo $idSae; ?>"
                  method="post">
                <input type="hidden" name="id_utilisateur"
                       value="<?php echo htmlspecialchars($tabDetailsGerant['id_utilisateur']); ?>">
                <div class="form-group">
                    <label for="nomGerant">Nom du Gérant</label>
                    <p id="nomGerant"><?php echo htmlspecialchars($tabDetailsGerant['nom_complet']); ?></p>
                </div>
                <div class="form-group">
                    <label>Choisissez un rôle pour le gérant :</label>
                    <div class="btn-group btn-group-toggle d-flex mt-2" data-toggle="buttons">
                        <label class="btn btn-outline-primary flex-fill <?php echo $tabDetailsGerant['role_utilisateur'] === "Co-Responsable" ? 'active' : ''; ?>">
                            <input type="radio" id="coreponsable" name="role_gerant" value="Co-Responsable"
                                   autocomplete="off"
                                <?php echo $tabDetailsGerant['role_utilisateur'] === "Co-Responsable" ? 'checked' : ''; ?>>
                            Co-Responsable
                        </label>
                        <label class="btn btn-outline-primary flex-fill <?php echo $tabDetailsGerant['role_utilisateur'] === "Intervenant" ? 'active' : ''; ?>">
                            <input type="radio" id="intervenant" name="role_gerant" value="Intervenant"
                                   autocomplete="off"
                                <?php echo $tabDetailsGerant['role_utilisateur'] === "Intervenant" ? 'checked' : ''; ?>>
                            Intervenant
                        </label>
                    </div>
                </div>
                <div class="d-flex mt-4 gap-3">
                    <button type="submit" class="btn btn-primary">Enregistrer les modifications</button>
                </div>
            </form>
            <form action="index.php?module=gerantprof&action=supprimerGerant&idProjet=<?php echo $idSae; ?>"
                  method="post" onsubmit="return confirmationSupprimer();">
                <input type="hidden" name="idGerant" value="<?php echo htmlspecialchars($idGerant); ?>">
                <div class="d-flex mt-3 gap-3">
                    <button type="submit" class="btn btn-danger">Supprimer le Gérant</button>
                </div>
            </form>
            <div class="d-flex mt-4 gap-3">
                <a href="index.php?module=gerantprof&action=gestionGerantSAE&idProjet=<?php echo $idSae; ?>"
                   class="btn btn-secondary">Retour</a>
            </div>
        </div>

        <?php
    }

    public function afficherFormulaireAjoutGerant($tabGerant, $idSae)
    {
        ?>
        <div class="container mt-5">
            <h2>Ajouter des Gérants</h2>
            <form method="post" action="index.php?module=gerantprof&action=ajouterGerants&idProjet=<?php echo $idSae; ?>">
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
                <a href="index.php?module=gerantprof&action=gestionGerantSAE&idProjet=<?php echo $idSae; ?>" class="btn btn-secondary mt-4">Retour</a>
            </form>
        </div>
        <?php
    }
}