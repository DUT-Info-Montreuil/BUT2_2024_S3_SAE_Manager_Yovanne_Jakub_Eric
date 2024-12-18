<link rel="stylesheet" href="../../styleConnexion.css">

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
        <div class="formulaire-wrapper">
            <div class="d-flex justify-content-center align-items-center">
                <div class="bg-light p-4 rounded shadow" style="width: 500px;">
                    <h2 class="text-center">Connexion</h2>
                    <form method="POST" action="index.php?module=connexion&action=connexion">
                        <div class="mb-3">
                            <label for="login" class="form-label">Identifiant :</label>
                            <input type="text" name="login" id="login" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Mot de passe :</label>
                            <input type="password" name="password" id="password" class="form-control">
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Connexion</button>
                    </form>
                    <div class="d-flex justify-content-center mt-3">
                        <a href="index.php?module=connexion&action=inscription" class="btn btn-secondary w-50">
                            Inscription
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <?php
    }

    public function formInscription() {
        ?>
        <div class="formulaire">
            <div class="d-flex justify-content-center align-items-center">
                <div class="bg-light p-4 rounded shadow" style="width: 500px;">
                    <h2 class="text-center">Inscription</h2>
                    <form method="POST" action="index.php?module=connexion&action=inscription">
                        <div class="mb-3">
                            <label for="nom" class="form-label">Nom : </label>
                            <input type="text" name="nom" id="nom" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label for="prenom" class="form-label">Prénom :</label>
                            <input type="text" name="prenom" id="prenom" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">E-mail : </label>
                            <input type="text" name="email" id="email" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label for="login" class="form-label">Identifiant :</label>
                            <input type="text" name="login" id="login" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Mot de passe : </label>
                            <input type="password" name="password" id="password" class="form-control">
                        </div>
                        <button type="submit" class="btn btn-primary w-100">S'inscrire</button>
                    </form>
                    <div class="d-flex justify-content-center mt-3">
                        <a href="index.php?module=connexion&action=connexion" class="btn btn-secondary w-50">
                            Connexion
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }

}
