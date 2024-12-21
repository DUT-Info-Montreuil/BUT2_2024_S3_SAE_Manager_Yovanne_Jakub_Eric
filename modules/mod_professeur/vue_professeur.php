<?php
include_once 'generique/vue_generique.php';
Class VueProfesseur extends VueGenerique{

    public function __construct() {
        parent::__construct();
    }
    public function afficherSaeGerer($saeGerer) {
        ?>
        <div class="container mt-4">
            <div class="row justify-content-center">
                <?php foreach ($saeGerer as $sae): ?>
                    <div class="col-md-4 d-flex justify-content-center mb-4">
                        <div class="card shadow-sm border-light" style="width: 250px; height: 250px; border-radius: 10px; background-color: #c6c6c6; display: flex; justify-content: center; align-items: center;">
                            <h3 class="text-center" style="color: #333; font-weight: bold;"><?php echo htmlspecialchars($sae['titre']); ?></h3>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php
    }
}