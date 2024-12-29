<?php

class DossierHelper {

    public static function getBaseDossierSAE($idSae, $nomSae) {
        $nomSae = preg_replace('/[^a-zA-Z0-9-_]/', '_', $nomSae);
        $nomSae .= '_' . $idSae;
        $baseDossier = 'sae' . DIRECTORY_SEPARATOR . $nomSae;
        return $baseDossier;
    }

    public static function creerDossiersSAE($idSae, $nomSae) {
        $baseDossier = self::getBaseDossierSAE($idSae, $nomSae);
        $ressourcesDossier = $baseDossier . DIRECTORY_SEPARATOR . 'ressources';
        $depotDossier = $baseDossier . DIRECTORY_SEPARATOR . 'depot';
        $soutenanceDossier = $baseDossier . DIRECTORY_SEPARATOR . 'soutenance';

        $dossiers = [$ressourcesDossier, $depotDossier, $soutenanceDossier];

        foreach ($dossiers as $dossier) {
            if (!is_dir($dossier)) {
                if (!mkdir($dossier, 0777, true)) {
                    error_log("Erreur : Impossible de créer le dossier $dossier");
                    die("Une erreur est survenue lors de la création des dossiers nécessaires.");
                }
            }
        }
    }

    public static function supprimerDossierComplet($dossier) {
        if (!is_dir($dossier)) {
            return false;
        }

        $fichiers = array_diff(scandir($dossier), ['.', '..']);

        foreach ($fichiers as $fichier) {
            $cheminFichier = $dossier . DIRECTORY_SEPARATOR . $fichier;
            if (is_dir($cheminFichier)) {
                // récursif pour supprimer les sous-dossiers
                self::supprimerDossierComplet($cheminFichier);
            } else {
                // Supprimer les fichiers
                unlink($cheminFichier);
            }
        }

        // supprimer le dossier lui-même
        return rmdir($dossier);
    }

    public static function supprimerDossiersSAE($idSae, $nomSae) {
        $nomSae = preg_replace('/[^a-zA-Z0-9-_]/', '_', $nomSae);
        $nomSae .= '_' . $idSae;
        $baseDossier = 'sae/' . $nomSae;
        $dossiersSousSAE = ['ressources', 'depot', 'soutenance'];

        foreach ($dossiersSousSAE as $dossierSous) {
            $dossierComplet = $baseDossier . '/' . $dossierSous;
            self::supprimerDossierComplet($dossierComplet); // suppression récursive
        }
        self::supprimerDossierComplet($baseDossier);
    }
}
