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

    public function afficherSaeDetails($saeDetails) {
        ?>
        <div class="container mt-4">
            <h2>Détails de la SAE</h2>
            <p><strong>Titre:</strong> <?php echo htmlspecialchars($saeDetails['titre']); ?></p>
            <p><strong>Année universitaire:</strong> <?php echo htmlspecialchars($saeDetails['annee_universitaire']); ?></p>
            <p><strong>Semestre:</strong> <?php echo htmlspecialchars($saeDetails['semestre']); ?></p>
            <p><strong>Description:</strong> <?php echo nl2br(htmlspecialchars($saeDetails['description_projet'])); ?></p>
        </div>
        <?php
    }
}
?>
