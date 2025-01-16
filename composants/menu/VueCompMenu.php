<?php

require_once 'generique/composant/vue_composant_generique.php';

class VueCompMenu extends VueCompGenerique
{
    public function __construct()
    {
        parent::__construct();

    }

    public function afficherMenu($imagePath, $login) {
        $this->affichage = '
    <div class="dropdown">
        <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            <img src="' . $imagePath . '" alt="Logo Profil" class="rounded-circle me-2" style="width: 30px; height: 30px; object-fit: cover;">
            <span style="color: white; font-size: 1.2rem;">' . $login . '</span>
        </a>
        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
            <li>
                <a class="dropdown-item d-flex align-items-center" href="index.php?module=connexion&action=deconnexion">
                    <i class="bi bi-box-arrow-right me-2"></i> Déconnexion
                </a>
            </li>
            <li>
                <a class="dropdown-item d-flex align-items-center" href="index.php?module=parametre">
                    <i class="bi bi-gear me-2"></i> Paramètre
                </a>
            </li>
        </ul>
    </div>';
    }


}


