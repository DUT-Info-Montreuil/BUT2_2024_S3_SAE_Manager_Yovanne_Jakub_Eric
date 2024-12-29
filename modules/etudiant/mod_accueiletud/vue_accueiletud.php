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
                        <p>Aucun projet auquel vous êtes inscrit.</p>
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

    public function afficherSaeDetails($titre)
    {
        ?>
        <div class="container mt-5">
            <h2 class="text-center mb-4" style="font-weight: bold; color: #343a40;"><?= htmlspecialchars($titre) ?></h2>
            <div class="row justify-content-center g-4">
                <?php
                $sections = [
                    ["href" => "index.php?module=groupeetud", "title" => "Groupe"],
                    ["href" => "index.php?module=depotetud", "title" => "Dépôt"],
                    ["href" => "index.php?module=ressourceetud", "title" => "Ressource"],
                    ["href" => "index.php?module=soutenanceetud", "title" => "Soutenance"],
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

}