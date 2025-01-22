<?php

require_once 'generique/composant/vue_composant_generique.php';

class VueCompMenu extends VueCompGenerique
{
    public function __construct()
    {
        parent::__construct();

    }

    public function afficherMenu($login, $profilPicture) {
        $this->affichage = '
    <div class="dropdown">
        <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            <img src="' . htmlspecialchars($profilPicture) . '" alt="Profil" class="rounded-circle" style="width: 30px; height: 30px; margin-right: 10px;">
            <span style="color: white; font-size: 1.2rem; margin-left: 10px;">' . htmlspecialchars($login) . '</span>
        </a>
        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
            <li>
                <a class="dropdown-item d-flex align-items-center" href="index.php?module=connexion&action=deconnexion">
                    <i class="bi bi-box-arrow-right me-2"></i> Déconnexion
                </a>
            </li>
            <li>
                <a class="dropdown-item d-flex align-items-center" href="index.php?module=parametre&action=afficherCompte">
                    <i class="bi bi-gear me-2"></i> Paramètres
                </a>
            </li>
        </ul>
    </div>';
    }



}


