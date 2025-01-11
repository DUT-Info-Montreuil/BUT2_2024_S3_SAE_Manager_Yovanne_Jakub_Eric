<?php

require_once 'generique/composant/vue_composant_generique.php';

class VueCompMenu extends VueCompGenerique {
    public function __construct() {
        parent::__construct();
        if (isset($_SESSION['id_utilisateur'])) {
            $this->affichage = '
                <div class="dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <img src="https://via.placeholder.com/30" alt="Logo Profil" class="rounded-circle"> ' . htmlspecialchars($_SESSION['login_utilisateur']) . '
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <li><a class="dropdown-item" href="index.php?module=connexion&action=deconnexion">Déconnexion</a></li>
                        <li><a class="dropdown-item" href="index.php?module=parametre">Paramètre</a></li>
                    </ul>
                </div>';
        }
    }
}
?>
