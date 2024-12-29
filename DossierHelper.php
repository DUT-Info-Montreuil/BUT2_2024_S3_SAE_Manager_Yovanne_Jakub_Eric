<?php

class DossierHelper
{

    public static function getBaseDossierSAE($idSae, $nomSae)
    {
        $nomSae = preg_replace('/[^a-zA-Z0-9-_]/', '_', $nomSae);
        $nomSae .= '_' . $idSae;
        $baseDossier = 'sae' . DIRECTORY_SEPARATOR . $nomSae;
        return $baseDossier;
    }

    public static function creerDossiersSAE($idSae, $nomSae)
    {
        $baseDossier = self::getBaseDossierSAE($idSae, $nomSae);
        $ressourcesDossier = $baseDossier . DIRECTORY_SEPARATOR . 'ressources';
        $depotDossier = $baseDossier . DIRECTORY_SEPARATOR . 'depots';
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

    // Nouvelle méthode pour créer le dossier de groupe
    public static function creerDossier($idSae, $nomSae, $nomDossier, $sousDossier)
    {
        $baseDossier = self::getBaseDossierSAE($idSae, $nomSae);
        $nomDossier = preg_replace('/[^a-zA-Z0-9-_]/', '_', $nomDossier);
        $dossier = $baseDossier . DIRECTORY_SEPARATOR . $sousDossier . DIRECTORY_SEPARATOR . $nomDossier;

        if (!is_dir($dossier)) {
            if (!mkdir($dossier, 0777, true)) {
                error_log("Erreur : Impossible de créer le dossier du groupe $dossier");
                die("Une erreur est survenue lors de la création du dossier du groupe.");
            }
        }
    }

    public static function creerDepotPourGroupe($idSae, $nomSae, $idGroupe, $nomGroupe, $idDepot, $nomDepot)
    {
        $baseDossier = self::getBaseDossierSAE($idSae, $nomSae);
        $nomGroupe = preg_replace('/[^a-zA-Z0-9-_]/', '_', $nomGroupe);
        $nomDepot = preg_replace('/[^a-zA-Z0-9-_]/', '_', $nomDepot);

        $dossierGroupeDepot = $baseDossier . DIRECTORY_SEPARATOR . 'depots' . DIRECTORY_SEPARATOR . $nomGroupe . '_' . $idGroupe . DIRECTORY_SEPARATOR . $nomDepot . '_' . $idDepot;

        if (!is_dir($dossierGroupeDepot)) {
            if (!mkdir($dossierGroupeDepot, 0777, true)) {
                error_log("Erreur : Impossible de créer le dossier du groupe $dossierGroupeDepot");
                return "Une erreur est survenue lors de la création du dossier du groupe.";
            }
        }
    }


    public static function supprimerDossierComplet($dossier)
    {
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

    public static function supprimerDossiersSAE($idSae, $nomSae)
    {
        $nomSae = preg_replace('/[^a-zA-Z0-9-_]/', '_', $nomSae);
        $nomSae .= '_' . $idSae;
        $baseDossier = 'sae/' . $nomSae;
        self::supprimerDossierComplet($baseDossier);
    }

    public static function supprimerDossier($idSae, $nomSae, $nomDossier, $sousDossier)
    {
        // Construire le chemin du dossier du groupe
        $baseDossier = self::getBaseDossierSAE($idSae, $nomSae); // chemin de base du SAE
        $dossier = $baseDossier . DIRECTORY_SEPARATOR . $sousDossier . DIRECTORY_SEPARATOR . $nomDossier;

        // Appeler la méthode pour supprimer ce dossier
        return self::supprimerDossierComplet($dossier);
    }

    public static function renommerDossier($idSae, $nomSae, $ancienNom, $nouveauNom, $sousDossier)
    {
        $baseDossier = self::getBaseDossierSAE($idSae, $nomSae);

        $dossierAncienNom = $baseDossier . DIRECTORY_SEPARATOR . $sousDossier . DIRECTORY_SEPARATOR . $ancienNom;
        $dossierNouveauNom = $baseDossier . DIRECTORY_SEPARATOR . $sousDossier . DIRECTORY_SEPARATOR . $nouveauNom;

        // dossier existe
        if (is_dir($dossierAncienNom)) {
            if (rename($dossierAncienNom, $dossierNouveauNom)) {
                return true;
            } else {
                error_log("Erreur : Impossible de renommer le dossier $dossierAncienNom en $dossierNouveauNom");
                return false;
            }
        } else {
            error_log("Erreur : Le dossier $dossierAncienNom n'existe pas");
            return false;
        }
    }


}
