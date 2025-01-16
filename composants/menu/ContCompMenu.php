<?php

require_once "composants/menu/VueCompMenu.php";
require_once "composants/menu/ModeleCompMenu.php";

class ControleurCompMenu {
    private $vue;
    private $modele;

    public function __construct() {
        $this->vue = new VueCompMenu();
        $this->modele = new ModeleCompMenu();
        $this->afficherMenu();
    }

    public function afficherMenu() {
        if (isset($_SESSION['id_utilisateur'])) {
            $idUser = $_SESSION['id_utilisateur'];
            $imageName = $this->modele->getProfilPictureById($idUser);
            $imagePath = glob("photo_profil/" . $imageName);
            $login = $this->modele->getLoginById($idUser);
            $this->vue->afficherMenu(htmlspecialchars($imagePath[0]), htmlspecialchars($login));
        }
    }

    public function getVue() {
        return $this->vue;
    }
}
