<?php

class Sae{
    private static $instance = null;
    private $id_projet;
    private $titre;
    private $annee_universitaire;
    private $description_projet;
    private $semestre;

    public function __construct() {
    }

    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new SAE();
        }
        return self::$instance;
    }

    public function initialiserSAE($id, $titre, $annee_universitaire, $description_projet, $semestre) {
        $this->setIdProjet($id);
        $this->setTitre($titre);
        $this->setAnneeUniversitaire($annee_universitaire);
        $this->setDescriptionProjet($description_projet);
        $this->setSemestre($semestre);
    }

    /**
     * @return mixed
     */
    public function getIdProjet()
    {
        return $this->id_projet;
    }

    /**
     * @return mixed
     */
    public function getTitre()
    {
        return $this->titre;
    }

    /**
     * @return mixed
     */
    public function getAnneeUniversitaire()
    {
        return $this->annee_universitaire;
    }

    /**
     * @return mixed
     */
    public function getDescriptionProjet()
    {
        return $this->description_projet;
    }

    /**
     * @return mixed
     */
    public function getSemestre()
    {
        return $this->semestre;
    }

    /**
     * @param mixed $id_projet
     */
    public function setIdProjet($id_projet)
    {
        $this->id_projet = $id_projet;
    }

    /**
     * @param mixed $titre
     */
    public function setTitre($titre)
    {
        $this->titre = $titre;
    }

    /**
     * @param mixed $annee_universitaire
     */
    public function setAnneeUniversitaire($annee_universitaire)
    {
        $this->annee_universitaire = $annee_universitaire;
    }

    /**
     * @param mixed $description_projet
     */
    public function setDescriptionProjet($description_projet)
    {
        $this->description_projet = $description_projet;
    }

    /**
     * @param mixed $semestre
     */
    public function setSemestre($semestre)
    {
        $this->semestre = $semestre;
    }


}