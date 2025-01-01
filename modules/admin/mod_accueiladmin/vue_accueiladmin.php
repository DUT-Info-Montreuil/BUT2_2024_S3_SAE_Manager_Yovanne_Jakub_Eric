<?php

include_once 'generique/vue_generique.php';
Class VueAccueilAdmin extends VueGenerique
{
    public function __construct()
    {
        parent::__construct();
    }

    public function afficherMenu(){
      ?>
            <div class="container mt-5">
                <div class="row justify-content-center g-4">
                        <div class="col-md-4 col-lg-3 d-flex justify-content-center">
                            <div class="card shadow border-0"
                                 style="width: 250px; height: 250px; border-radius: 15px;
                         background-color: #f8f9fa; display: flex; flex-direction: column;
                         justify-content: center; align-items: center; text-align: center;">
                                <a class="text-decoration-none" href="index.php?module=gestuser"
                                   style="color: #495057;">
                                    <h3 style="font-weight: 600; font-size: 1.2rem;">
                                       Gestion Utilisateurs
                                    </h3>
                                </a>
                            </div>
                        </div>
                </div>
            </div>
            <?php

    }
}