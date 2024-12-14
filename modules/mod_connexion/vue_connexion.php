<?php
include_once 'modules/mod_connexion/cont_connexion.php';
include_once 'modules/mod_connexion/modele_connexion.php';
include_once 'generique/vue_generique.php';
class VueConnexion extends VueGenerique{
    public function __construct() {
        parent::__construct();
    }

    public function formConnexion() {
        ?>

        <label class="test">Test</label>
        <div class="d-flex justify-content-center align-items-center vh-90">
            <div class="bg-light p-4 rounded shadow" style="width: 300px; background-color: #6a1b9a;">
                <h2 class="text-center">Connexion</h2>
                <form method="POST" action="cont_connexion.php?module=connexion&action=connexion">
                    <div class="mb-3">
                        <label for="login" class="form-label">Login:</label>
                        <input type="text" name="login" id="login" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Mot de passe:</label>
                        <input type="password" name="password" id="password" class="form-control">
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Connexion</button>
                </form>
            </div>
        </div>


        <?php
    }
}
