<link rel="stylesheet" href="../../../styleprof.css">
<?php
include_once 'generique/vue_generique.php';

class VueAccueilProf extends VueGenerique
{
    public function __construct()
    {
        parent::__construct();
    }
    public function afficherSaeGerer($saeGerer, $typeUser) {
        ?>
        <div class="container">
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

                <?php if ($typeUser !== "intervenant" ): ?>
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
                <?php endif; ?>
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
    public function afficherSaeDetails($titre, $role, $desc)
    {
        ?>
        <div class="container mt-5">
            <div class="text-center mb-5">
                <h1 class="display-4" style="font-weight: bold; color: #343a40;">
                    <?= htmlspecialchars($titre) ?>
                </h1>
                <p class="lead" style="color: #6c757d; font-size: 1.2rem;">
                    <?= htmlspecialchars($desc) ?>
                </p>
            </div>

            <div class="bg-light p-5 rounded border mb-3">
                <div class="container">
                    <div class="row justify-content-center">
                        <?php
                        $sections = [
                            "Responsable" => [
                                ["href" => "index.php?module=infosae", "title" => "Gestion de la SAE"],
                                ["href" => "index.php?module=groupeprof&action=gestionGroupeSAE", "title" => "Groupe"],
                                ["href" => "index.php?module=gerantprof", "title" => "Gérant"],
                                ["href" => "index.php?module=depotprof", "title" => "Dépôt"],
                                ["href" => "index.php?module=ressourceprof", "title" => "Ressource"],
                                ["href" => "index.php?module=soutenanceprof", "title" => "Soutenance"],
                                ["href" => "index.php?module=evaluationprof", "title" => "Évaluation"]
                            ],
                            "Co-Responsable" => [
                                ["href" => "index.php?module=groupeprof&action=gestionGroupeSAE", "title" => "Groupe"],
                                ["href" => "index.php?module=gerantprof", "title" => "Gérant"],
                                ["href" => "index.php?module=depotprof", "title" => "Dépôt"],
                                ["href" => "index.php?module=ressourceprof", "title" => "Ressource"],
                                ["href" => "index.php?module=soutenanceprof", "title" => "Soutenance"],
                                ["href" => "index.php?module=evaluationprof", "title" => "Évaluation"]
                            ],
                            "Intervenant" => [
                                ["href" => "index.php?module=depotprof", "title" => "Dépôt"],
                                ["href" => "index.php?module=soutenanceprof", "title" => "Soutenance"],
                                ["href" => "index.php?module=ressourceprof", "title" => "Ressource"],
                                ["href" => "index.php?module=evaluationprof", "title" => "Évaluation"]
                            ]
                        ];

                        $availableSections = isset($sections[$role]) ? $sections[$role] : [];
                        foreach ($availableSections as $section):
                            ?>
                            <div class="col-12 mb-3">
                                <a href="<?= htmlspecialchars($section['href']); ?>" class="text-decoration-none">
                                    <div class="d-flex align-items-center p-4 shadow rounded hover-grow"
                                         style="background-color: #f8f9fa; border-left: 5px solid #007bff;">
                                        <h4 class="mb-0" style="font-weight: bold; color: #343a40;">
                                            <?= htmlspecialchars($section['title']); ?>
                                        </h4>
                                    </div>
                                </a>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }


}

?>
