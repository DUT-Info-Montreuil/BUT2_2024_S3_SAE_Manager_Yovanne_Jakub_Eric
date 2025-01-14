<?php
include_once 'Connexion.php';
Class ModeleNoteFinalProf extends Connexion
{
    public function __construct()
    {
    }

    public function getAllNoteFinalAndEtudiant($idSae)
    {
        $bdd = $this->getBdd();

        $query = "
        SELECT 
            u.nom AS nom_etudiant,
            u.prenom AS prenom_etudiant,
            u.email AS email_etudiant,
            g.nom AS nom_groupe,
            ge.note_finale AS note_finale
        FROM 
            Groupe_Etudiant ge
        INNER JOIN 
            Utilisateur u ON ge.id_utilisateur = u.id_utilisateur
        INNER JOIN 
            Groupe g ON ge.id_groupe = g.id_groupe
        INNER JOIN 
            Projet_Groupe pg ON g.id_groupe = pg.id_groupe
        WHERE 
            pg.id_projet = :idSae
    ";

        $stmt = $bdd->prepare($query);
        $stmt->bindParam(':idSae', $idSae, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

}