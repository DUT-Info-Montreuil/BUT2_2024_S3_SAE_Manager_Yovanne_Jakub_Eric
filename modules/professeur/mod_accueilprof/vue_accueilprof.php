<?php
include_once 'generique/vue_generique.php';

class VueAccueilProf extends VueGenerique
{
    public function __construct()
    {
        parent::__construct();
    }

    public function afficherSaeGerer($saeGerer) {
        ?>
        <div class="container mt-5">
            <div class="row justify-content-center g-4">
                <?php foreach ($saeGerer as $sae): ?>
                    <div class="col-md-4 col-lg-3 d-flex justify-content-center">
                        <div class="card shadow border-0"
                             style="width: 250px; height: 250px; border-radius: 15px;
                         background-color: #f8f9fa; display: flex; flex-direction: column;
                         justify-content: center; align-items: center; text-align: center;">
                            <a class="text-decoration-none" href="index.php?module=accueilprof&action=choixSae&id=<?php echo htmlspecialchars($sae['id_projet']); ?>"
                               style="color: #495057;">
                                <h3 style="font-weight: 600; font-size: 1.2rem;">
                                    <?php echo htmlspecialchars($sae['titre']); ?>
                                </h3>
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>

                <div class="col-md-4 col-lg-3 d-flex justify-content-center">
                    <a href="index.php?module=accueilprof&action=creerSAEForm" class="text-center text-decoration-none">
                        <div class="card shadow border-0"
                             style="width: 250px; height: 250px; border-radius: 15px;
                         background-color: #e9ecef; display: flex; flex-direction: column;
                         justify-content: center; align-items: center; cursor: pointer; text-align: center;">
                            <h1 style="font-weight: bold; color: #6c757d; font-size: 3rem;">+</h1>
                            <p style="font-size: 1rem; color: #6c757d; font-weight: 500;">Ajouter une SAE</p>
                        </div>
                    </a>
                </div>
            </div>
        </div>
        <?php
    }

    public function creerUneSAEForm()
    {
        ?>
        <div class="container mt-4">
            <h2>Formulaire de création d'une SAE</h2>
            <form action="index.php?module=accueilprof&action=creerSAE" method="post">
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

    public function afficherSaeDetails($titre)
    {
        ?>
        <div class="container mt-5">
            <h2 class="text-center mb-4" style="font-weight: bold; color: #343a40;"><?= htmlspecialchars($titre) ?></h2>
            <div class="row justify-content-center g-4">
                <?php
                $sections = [
                    ["href" => "index.php?module=accueilprof&action=infoGeneralSae", "title" => "Modifier la SAE"],
                    ["href" => "index.php?module=groupeprof&action=gestionGroupeSAE", "title" => "Groupe"],
                    ["href" => "index.php?module=gerantprof", "title" => "Gérant"],
                    ["href" => "index.php?module=depotprof", "title" => "Dépôt"],
                    ["href" => "index.php?module=ressourceprof", "title" => "Ressource"],
                    ["href" => "index.php?module=soutenanceprof", "title" => "Soutenance"],
                    ["href" => "index.php?module=evaluationprof", "title" => "Évaluation"],
                ];

                foreach ($sections as $section): ?>
                    <div class="col-md-4 col-lg-3 d-flex justify-content-center">
                        <div class="card border-0"
                             style="width: 250px; height: 250px; border-radius: 10px;
                         background-color: #f5f5f5; display: flex; justify-content: center;
                         align-items: center; text-align: center; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);">
                            <a class="text-decoration-none" href="<?php echo htmlspecialchars($section['href']); ?>"
                               style="color: #495057; text-align: center;">
                                <h3 style="font-weight: bold; font-size: 1.1rem; margin-bottom: 10px;">
                                    <?php echo htmlspecialchars($section['title']); ?>
                                </h3>
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php
    }

    public function afficherSaeInfoGeneral($saeDetails)
    {
        ?>
        <div class="container mt-4">
            <h2>Détails de la SAE</h2>
            <form method="POST" action="index.php?module=accueilprof&action=updateSae">
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
            <form action="index.php?module=accueilprof&action=supprimerSAE" method="post">
                <button type="submit" class="btn btn-danger">Supprimer la SAE</button>
            </form>
        </div>
        <?php
    }
}

?>
