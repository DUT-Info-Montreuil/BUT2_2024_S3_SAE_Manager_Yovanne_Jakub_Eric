<?php
require_once "ControllerCommun.php";

if (isset($_SESSION['id_projet'])) {
    $idSae = $_SESSION['id_projet'];
    $isProfOuIntervenant = ControllerCommun::pasEtudiant($idSae, $_SESSION['id_utilisateur']);
    if ($isProfOuIntervenant) {
        $headerUrl = "http://localhost/index.php?module=accueilprof&action=choixSae&id=" . $idSae;
    } else {
        $headerUrl = "http://localhost/index.php?module=accueiletud&action=choixSae&id=" . $idSae;
    }
} else {
    $headerUrl = null;
}
