<?php
include_once 'Connexion.php';

class ModeleEvaluation extends Connexion
{
    public function __construct()
    {
    }

    public function getRenduEvaluation($idSae)
    {
        $bdd = self::getBdd();
        $query = "
                SELECT 
                    g.nom AS groupe_nom, 
                    r.titre AS rendu_titre, 
                    r.date_limite AS rendu_date_limite, 
                    rg.statut AS rendu_statut, 
                    re.note AS note_rendu,
                    ge.id_groupe,
                    r.id_rendu,
                    GROUP_CONCAT(u.nom, ' ', u.prenom ORDER BY u.nom) AS etudiants,
                    COUNT(u.id_utilisateur) AS nombre_etudiants
                FROM 
                    Projet p
                JOIN 
                    Rendu r ON r.id_projet = p.id_projet
                LEFT JOIN 
                    Rendu_Groupe rg ON rg.id_rendu = r.id_rendu
                LEFT JOIN 
                    Rendu_Evaluation re ON re.id_rendu = r.id_rendu AND re.id_groupe = rg.id_groupe
                JOIN 
                    Projet_Groupe pg ON pg.id_projet = p.id_projet
                JOIN 
                    Groupe g ON g.id_groupe = pg.id_groupe
                JOIN 
                    Groupe_Etudiant ge ON ge.id_groupe = g.id_groupe
                JOIN 
                    Utilisateur u ON u.id_utilisateur = ge.id_utilisateur
                WHERE 
                    p.id_projet = ?
                GROUP BY 
                    g.id_groupe, r.id_rendu
                ORDER BY 
                    g.nom, r.date_limite;
                ";

        $stmt = $bdd->prepare($query);
        $stmt->execute([$idSae]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAllMembreSAE($id_groupe){
        $bdd = self::getBdd();
        $query = "SELECT u.id_utilisateur, u.nom, u.prenom, u.email
              FROM Utilisateur u
              INNER JOIN Groupe_Etudiant ge ON u.id_utilisateur = ge.id_utilisateur
              WHERE ge.id_groupe = ?";
        $stmt = $bdd->prepare($query);
        $stmt->execute([$id_groupe]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }



}