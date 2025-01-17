<?php

include_once 'Connexion.php';
Class ModeleAccueilEtud extends Connexion{
    public function __construct() {
    }

    public function saeInscrit($id_utilisateur) {
        $bdd = $this->getBdd();

        $query = "
            SELECT p.id_projet, p.titre, p.annee_universitaire, p.semestre
            FROM Projet p
            JOIN Projet_Groupe pg ON p.id_projet = pg.id_projet
            JOIN Groupe_Etudiant ge ON pg.id_groupe = ge.id_groupe
            WHERE ge.id_utilisateur = ?
        ";

        $stmt = $bdd->prepare($query);
        $stmt->execute([$id_utilisateur]);
        $projets = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $projets;
    }
    public function getAllChamp($idProjet, $idGroupe) {
        $bdd = $this->getBdd();
        $query = "
            SELECT c.id_champ, c.champ_nom, cg.champ_valeur
            FROM Champ c
            LEFT JOIN Champ_Groupe cg 
                ON c.id_champ = cg.id_champ AND cg.id_groupe = ?
            WHERE c.id_projet = ?
        ";

        $stmt = $bdd->prepare($query);
        $stmt->execute([$idGroupe, $idProjet]);
        $champs = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $this->formaterChamps($champs);
    }

    private function formaterChamps($champs) {
        if (empty($champs)) {
            return "Aucun champ n'a encore été rempli.";
        }

        $champsRemplis = [];
        foreach ($champs as $champ) {
            $champsRemplis[] = "<strong>" . htmlspecialchars($champ['champ_nom']) . ":</strong> " . htmlspecialchars($champ['champ_valeur']);
        }

        return implode(' | ', $champsRemplis);
    }


}
