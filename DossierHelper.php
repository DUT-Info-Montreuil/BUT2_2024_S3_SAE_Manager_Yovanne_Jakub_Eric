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

    public static function getDossierPathDepot($nomSae, $idSae, $nomGroupe, $idGroupe, $nomRendu, $idRendu)
    {
        $baseDossier = self::getBaseDossierSAE($idSae, $nomSae);
        $nomGroupe = preg_replace('/[^a-zA-Z0-9-_]/', '_', $nomGroupe);
        $nomRendu = preg_replace('/[^a-zA-Z0-9-_]/', '_', $nomRendu);

        $dossierPath = $baseDossier . DIRECTORY_SEPARATOR . 'depots' . DIRECTORY_SEPARATOR . $nomGroupe . '_' . $idGroupe . DIRECTORY_SEPARATOR . $nomRendu . '_' . $idRendu;
        return $dossierPath;
    }

    public static function creerDepotPourGroupe($idSae, $nomSae, $idGroupe, $nomGroupe, $idRendu, $nomRendu)
    {
        $dossierGroupeDepot = self::getDossierPathDepot($nomSae, $idSae, $nomGroupe, $idGroupe, $nomRendu, $idRendu);
        if (!is_dir($dossierGroupeDepot)) {
            if (!mkdir($dossierGroupeDepot, 0777, true)) {
                error_log("Erreur : Impossible de créer le dossier du groupe $dossierGroupeDepot");
                return "Une erreur est survenue lors de la création du dossier du groupe.";
            }
        }
    }

    public static function supprimerDepotPourGroupe($idSae, $nomSae, $idGroupe, $nomGroupe, $idRendu, $nomRendu)
    {
        $dossierPath = self::getDossierPathDepot($nomSae, $idSae, $nomGroupe, $idGroupe, $nomRendu, $idRendu);

        if (is_dir($dossierPath)) {
            return self::supprimerDossierComplet($dossierPath);
        } else {
            error_log("Le dossier : $dossierPath  n'existe pas");
            return false;
        }
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

    public static function renommerDepotPourGroupe($idSae, $nomSae, $idGroupe, $nomGroupe, $idDepot, $ancienNomDepot, $nouveauNomDepot)
    {
        $baseDossier = self::getBaseDossierSAE($idSae, $nomSae);

        $nomGroupe = preg_replace('/[^a-zA-Z0-9-_]/', '_', $nomGroupe);
        $ancienNomDepot = preg_replace('/[^a-zA-Z0-9-_]/', '_', $ancienNomDepot);
        $nouveauNomDepot = preg_replace('/[^a-zA-Z0-9-_]/', '_', $nouveauNomDepot);

        // Dossier du groupe avec l'ID et le nom du dépôt
        $dossierGroupeDepotAncien = $baseDossier . DIRECTORY_SEPARATOR . 'depots' . DIRECTORY_SEPARATOR . $nomGroupe . '_' . $idGroupe . DIRECTORY_SEPARATOR . $ancienNomDepot . '_' . $idDepot;
        $dossierGroupeDepotNouveau = $baseDossier . DIRECTORY_SEPARATOR . 'depots' . DIRECTORY_SEPARATOR . $nomGroupe . '_' . $idGroupe . DIRECTORY_SEPARATOR . $nouveauNomDepot . '_' . $idDepot;

        // Vérifier si le dossier existe avant de renommer
        if (is_dir($dossierGroupeDepotAncien)) {
            // Renommer le dossier
            if (rename($dossierGroupeDepotAncien, $dossierGroupeDepotNouveau)) {
                return true; // Le dossier a été renommé avec succès
            } else {
                error_log("Erreur : Impossible de renommer le dossier $dossierGroupeDepotAncien en $dossierGroupeDepotNouveau");
                return false; // Impossible de renommer le dossier
            }
        } else {
            error_log("Erreur : Le dossier $dossierGroupeDepotAncien n'existe pas");
            return false; // Le dossier n'existe pas
        }
    }


}
