<?php
include_once 'generique/vue_generique.php';

class VueInfoSae extends VueGenerique
{
    public function __construct()
    {
        parent::__construct();
    }

    public function afficherChoix($choix)
    {
        ?>
        <div class="container mt-5">
            <h2 class="text-center mb-4" style="font-weight: bold; color: #343a40;">Gestion SAE</h2>
            <div class="row justify-content-center g-4">
                <?php foreach ($choix as $option): ?>
                    <div class="col-md-4 col-lg-3 d-flex justify-content-center">
                        <div class="card border-0"
                             style="width: 250px; height: 250px; border-radius: 10px;
                         background-color: #f5f5f5; display: flex; justify-content: center;
                         align-items: center; text-align: center; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);">
                            <a class="text-decoration-none" href="<?php echo htmlspecialchars($option['link']); ?>"
                               style="color: #495057; text-align: center;">
                                <h3 style="font-weight: bold; font-size: 1.1rem; margin-bottom: 10px;">
                                    <?php echo htmlspecialchars($option['title']); ?>
                                </h3>
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php
    }

    public function afficherFormAddChamp()
    {
        ?>
        <div class="container mt-4">
            <h2>Ajouter un Champ à Remplir</h2>
            <form method="POST" action="index.php?module=infosae&action=ajouterChamp">
                <div class="mb-3">
                    <label for="champ_nom" class="form-label"><strong>Nom du champ :</strong></label>
                    <input type="text" class="form-control" id="champ_nom" name="champ_nom" required>
                </div>

                <div class="mb-3">
                    <label for="rempli_par" class="form-label"><strong>Qui remplit ce champ ?</strong></label>
                    <select class="form-select" id="rempli_par" name="rempli_par" required>
                        <option value="Responsable">Responsable</option>
                        <option value="Groupe">Groupe</option>
                    </select>
                </div>


                <button type="submit" class="btn btn-primary">Ajouter le Champ</button>
            </form>
        </div>
        <?php
    }


    public function afficherSaeInfoGeneral($saeDetails)
    {
        ?>
        <div class="container mt-4">
            <h2>Détails de la SAE</h2>
            <form method="POST" action="index.php?module=infosae&action=updateSae">
                <div class="mb-3">
                    <label for="titre" class="form-label"><strong>Titre :</strong></label>
                    <input type="text" class="form-control" id="titre" name="titre"
                           value="<?php echo htmlspecialchars($saeDetails['titre']); ?>" required>
                </div>
                <div class="mb-3">
                    <label for="annee_universitaire" class="form-label"><strong>Année universitaire :</strong></label>
                    <input type="text" class="form-control" id="annee_universitaire" name="annee_universitaire"
                           value="<?php echo htmlspecialchars($saeDetails['annee_universitaire']); ?>" required>
                </div>
                <div class="mb-3">
                    <label for="semestre" class="form-label"><strong>Semestre :</strong></label>
                    <input type="text" class="form-control" id="semestre" name="semestre"
                           value="<?php echo htmlspecialchars($saeDetails['semestre']); ?>" required>
                </div>
                <div class="mb-3">
                    <label for="description_projet" class="form-label"><strong>Description :</strong></label>
                    <textarea class="form-control" id="description_projet" name="description_projet" rows="4"
                              required><?php echo htmlspecialchars($saeDetails['description_projet']); ?></textarea>
                </div>

                <button type="submit" class="btn btn-primary">Mettre à jour</button>
            </form>
            <form action="index.php?module=infosae&action=supprimerSAE" method="post">
                <button type="submit" class="btn btn-danger">Supprimer la SAE</button>
            </form>
        </div>
        <?php
    }

    public function afficherAllChamp($allChamp)
    {
        ?>
        <div class="container mt-4">
            <h2>Tous les Champs</h2>
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                    <tr>
                        <th>Nom du Champ</th>
                        <th>Responsable/Groupe</th>
                        <th>Modifier</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($allChamp as $champ): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($champ['champ_nom']); ?></td>
                            <td><?php echo htmlspecialchars($champ['rempli_par']); ?></td>
                            <td>
                                <button class="btn btn-warning" data-bs-toggle="modal"
                                        data-bs-target="#modalModifier<?php echo $champ['id_champ']; ?>">Modifier
                                </button>
                            </td>
                        </tr>

                        <div class="modal fade" id="modalModifier<?php echo $champ['id_champ']; ?>" tabindex="-1"
                             aria-labelledby="modalModifierLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="modalModifierLabel">Modifier le Champ</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <form method="POST" action="index.php?module=infosae&action=modifierChamp">
                                            <input type="hidden" name="id_champ"
                                                   value="<?php echo htmlspecialchars($champ['id_champ']); ?>">

                                            <div class="mb-3">
                                                <label for="champ_nom<?php echo $champ['id_champ']; ?>"
                                                       class="form-label">Nom du champ :</label>
                                                <input type="text" class="form-control"
                                                       id="champ_nom<?php echo $champ['id_champ']; ?>" name="champ_nom"
                                                       value="<?php echo htmlspecialchars($champ['champ_nom']); ?>"
                                                       required>
                                            </div>

                                            <div class="mb-3">
                                                <label for="rempli_par<?php echo $champ['id_champ']; ?>"
                                                       class="form-label">Responsable/Groupe :</label>
                                                <select class="form-select"
                                                        id="rempli_par<?php echo $champ['id_champ']; ?>"
                                                        name="rempli_par" required>
                                                    <option value="Responsable" <?php echo ($champ['rempli_par'] == 'Responsable') ? 'selected' : ''; ?>>
                                                        Responsable
                                                    </option>
                                                    <option value="Groupe" <?php echo ($champ['rempli_par'] == 'Groupe') ? 'selected' : ''; ?>>
                                                        Groupe
                                                    </option>
                                                </select>
                                            </div>

                                            <div class="text-center mb-3">
                                                <button type="submit" class="btn btn-primary">Sauvegarder les
                                                    modifications
                                                </button>
                                            </div>
                                        </form>
                                        <div class="modal-footer d-flex justify-content-center">
                                            <form method="POST" action="index.php?module=infosae&action=supprimerChamp">
                                                <input type="hidden" name="id_champ"
                                                       value="<?php echo htmlspecialchars($champ['id_champ']); ?>">
                                                <button type="submit" class="btn btn-danger w-50">Supprimer</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                    </tbody>
                </table>
                <div class="text-center mt-4">
                    <a href="index.php?module=infosae&action=formAddChamp" class="btn btn-primary btn-lg">
                        <i class="fas fa-plus"></i> Ajouter un Champ
                    </a>
                </div>

            </div>
        </div>
        <?php
    }


}