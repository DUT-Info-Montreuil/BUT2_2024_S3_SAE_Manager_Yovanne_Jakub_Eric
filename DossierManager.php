<?php

class DossierManager
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
        $baseDossier = self::getBaseDossierSAE($idSae, $nomSae);
        $dossier = $baseDossier . DIRECTORY_SEPARATOR . $sousDossier . DIRECTORY_SEPARATOR . $nomDossier;

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

        $dossierGroupeDepotAncien = $baseDossier . DIRECTORY_SEPARATOR . 'depots' . DIRECTORY_SEPARATOR . $nomGroupe . '_' . $idGroupe . DIRECTORY_SEPARATOR . $ancienNomDepot . '_' . $idDepot;
        $dossierGroupeDepotNouveau = $baseDossier . DIRECTORY_SEPARATOR . 'depots' . DIRECTORY_SEPARATOR . $nomGroupe . '_' . $idGroupe . DIRECTORY_SEPARATOR . $nouveauNomDepot . '_' . $idDepot;

        if (is_dir($dossierGroupeDepotAncien)) {
            if (rename($dossierGroupeDepotAncien, $dossierGroupeDepotNouveau)) {
                return true;
            } else {
                error_log("Erreur : Impossible de renommer le dossier $dossierGroupeDepotAncien en $dossierGroupeDepotNouveau");
                return false;
            }
        } else {
            error_log("Erreur : Le dossier $dossierGroupeDepotAncien n'existe pas");
            return false;
        }
    }

    public static function uploadFichier($fichierSource, $dossierCible)
    {
        $extensionsAutorisees = ['pdf', 'docx', 'png', 'jpg', 'php', 'java', 'c'];
        $tailleMax = 10485760;

        if (!isset($fichierSource) || $fichierSource['error'] !== UPLOAD_ERR_OK) {
            throw new Exception("Erreur lors du téléchargement du fichier.");
        }

        $nomFichier = basename($fichierSource['name']);
        $extensionFichier = strtolower(pathinfo($nomFichier, PATHINFO_EXTENSION));
        $tailleFichier = $fichierSource['size'];

        if (!in_array($extensionFichier, $extensionsAutorisees)) {
            throw new Exception("Extension non autorisée : $extensionFichier");
        }
        if ($tailleFichier > $tailleMax) {
            throw new Exception("Le fichier dépasse la taille maximale autorisée de " . ($tailleMax / 1024 / 1024) . " Mo.");
        }
        if (!is_dir($dossierCible)) {
            if (!mkdir($dossierCible, 0777, true)) {
                throw new Exception("Impossible de créer le dossier cible : $dossierCible");
            }
        }
        $cheminFichierFinal = $dossierCible . DIRECTORY_SEPARATOR . uniqid() . '-' . $nomFichier;

        // Déplacer le fichier vers son emplacement final
        if (!move_uploaded_file($fichierSource['tmp_name'], $cheminFichierFinal)) {
            throw new Exception("Erreur lors du déplacement du fichier vers $cheminFichierFinal");
        }
        return $cheminFichierFinal;
    }

    public static function supprimerFichier($cheminFichier)
    {
        if (!file_exists($cheminFichier)) {
            error_log("Erreur : Le fichier $cheminFichier n'existe pas.");
            throw new Exception("Le fichier spécifié n'existe pas.");
        }

        if (!unlink($cheminFichier)) {
            error_log("Erreur : Impossible de supprimer le fichier $cheminFichier");
            throw new Exception("Une erreur est survenue lors de la suppression du fichier.");
        }
        return true;
    }

    public static function uploadRessource($fichierSource, $idSae, $nomSae)
    {
        $extensionsAutorisees = ['pdf', 'docx', 'png', 'jpg'];
        $tailleMax = 10 * 1024 * 1024; // 10 Mo

        if (!isset($fichierSource) || $fichierSource['error'] !== UPLOAD_ERR_OK) {
            throw new Exception("Erreur lors du téléchargement du fichier.");
        }

        $nomFichier = basename($fichierSource['name']);
        $extensionFichier = strtolower(pathinfo($nomFichier, PATHINFO_EXTENSION));
        $tailleFichier = $fichierSource['size'];

        if (!in_array($extensionFichier, $extensionsAutorisees)) {
            throw new Exception("Extension non autorisée : $extensionFichier");
        }

        if ($tailleFichier > $tailleMax) {
            throw new Exception("Le fichier dépasse la taille maximale autorisée de " . ($tailleMax / 1024 / 1024) . " Mo.");
        }

        $uploadDossier = self::getBaseDossierSAE($idSae, $nomSae) . DIRECTORY_SEPARATOR . 'ressources' . DIRECTORY_SEPARATOR;
        if (!is_dir($uploadDossier)) {
            if (!mkdir($uploadDossier, 0777, true)) {
                throw new Exception("Impossible de créer le dossier cible : $uploadDossier");
            }
        }
        $cheminFichierFinal = $uploadDossier . uniqid() . '-' . $nomFichier;

        if (!move_uploaded_file($fichierSource['tmp_name'], $cheminFichierFinal)) {
            throw new Exception("Erreur lors du déplacement du fichier vers $cheminFichierFinal");
        }

        return $cheminFichierFinal;
    }


}
