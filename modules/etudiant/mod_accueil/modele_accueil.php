<?php

include_once 'Connexion.php';
Class ModeleAccueil extends Connexion{
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

    public function getTitreSAE($idProjet){
        $bdd = $this->getBdd();
        $query = "SELECT titre FROM Projet WHERE id_projet = ?";
        $stmt = $bdd->prepare($query);
        $stmt->execute([$idProjet]);
        $sae = $stmt->fetch(PDO::FETCH_ASSOC);
        return $sae['titre'];
    }

}
