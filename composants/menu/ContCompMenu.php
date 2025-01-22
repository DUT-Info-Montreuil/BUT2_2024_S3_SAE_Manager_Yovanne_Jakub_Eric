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
            $login = $this->modele->getLoginById($idUser);
            $picture = $this->modele->getProfilPictureById($idUser);
            $files = glob("photo_profil/" . $picture);
            $path = !empty($files) ? $files[0] : glob("photo_profil/default_avatar.png");
            $this->vue->afficherMenu(htmlspecialchars($login), $path);
        }
    }

    public function getVue() {
        return $this->vue;
    }
}
