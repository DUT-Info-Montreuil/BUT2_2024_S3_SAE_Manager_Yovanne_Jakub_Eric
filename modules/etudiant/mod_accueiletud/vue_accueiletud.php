<link rel="stylesheet" href="../../../styleaccueil.css">
<?php

include_once 'generique/vue_generique.php';
Class VueAccueilEtud extends VueGenerique{
    public function __construct()
    {
        parent::__construct();
    }

    public function afficherSaeGerer($saeGerer) {
        ?>
        <div class="container mt-5">
            <div class="row justify-content-center g-4">
                <?php if (empty($saeGerer)): ?>
                    <div class="col-12 text-center">
                        <p style="font-style: italic">Aucun projet auquel vous êtes inscrit.</p>
                    </div>
                <?php else: ?>
                    <?php foreach ($saeGerer as $sae): ?>
                        <div class="col-md-4 col-lg-3 d-flex justify-content-center">
                            <div class="card shadow border-0"
                                 style="width: 250px; height: 250px; border-radius: 15px;
                             background-color: #f8f9fa; display: flex; flex-direction: column;
                             justify-content: center; align-items: center; text-align: center;">
                                <a class="text-decoration-none" href="index.php?module=accueiletud&action=choixSae&id=<?php echo htmlspecialchars($sae['id_projet']); ?>"
                                   style="color: #495057;">
                                    <h3 style="font-weight: 600; font-size: 1.2rem;">
                                        <?php echo htmlspecialchars($sae['titre']); ?>
                                    </h3>
                                </a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
        <?php
    }
    public function afficherSaeDetails($titre, $desc, $sections, $allChamp)
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

                <div class="mt-4">
                    <div style="color: #6c757d; font-size: 1rem;">
                        <?= $allChamp ?>
                    </div>
                </div>
            </div>

            <div class="bg-light p-5 rounded border mb-3">
                <div class="container">
                    <div class="row justify-content-center">
                        <?php
                        foreach ($sections as $section):
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