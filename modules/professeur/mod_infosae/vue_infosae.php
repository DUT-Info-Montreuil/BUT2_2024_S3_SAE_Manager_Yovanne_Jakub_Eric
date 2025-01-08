<?php
include_once 'generique/vue_generique.php';

class VueInfoSae extends VueGenerique
{
    public function __construct()
    {
        parent::__construct();
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

}