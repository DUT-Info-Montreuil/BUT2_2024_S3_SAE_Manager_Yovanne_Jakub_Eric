<?php
include_once "modules/admin/mod_gestuser/modele_gestuser.php";
include_once "modules/admin/mod_gestuser/vue_gestuser.php";
require_once "ModeleCommun.php";
require_once "ControllerCommun.php";
class ContGestUser
{
    private $modele;
    private $vue;
    private $action;

    public function __construct()
    {
        $this->modele = new ModeleGestUser();
        $this->vue = new VueGestUser();
    }

    public function exec()
    {
        $this->action = isset($_GET['action']) ? $_GET['action'] : "menuGestUser";
        if (ControllerCommun::estAdmin()) {
            switch ($this->action) {
                case "menuGestUser":
                    $this->menuGestUser();
                    break;
                case "versModifierDesUsers":
                    $this->versModifierDesUsers();
                    break;
                case "modifierUser" :
                    $this->modifierUser();
                    break;
                case "enregistrerModifications" :
                    $this->enregistrerModifications();
                    break;
                case "addUser" :
                    $this->addUser();
                    break;
                case "ajouterUser" :
                    $this->ajouterUser();
                    break;
                case "modifierUserCSV" :
                    $this->modifierUserCSV();
                    break;
                case 'ajouterUserCSV' :
                    $this->ajouterUserCSV();
                    break;
                case "telechargerTemplateUpdate" :
                    $this->telechargerTemplateUpdate();
                    break;
                case "telechargerTemplateAjout" :
                    $this->telechargerTemplateAjout();
                    break;
                case "supprimerUser" :
                    $this->supprimerUser();
                    break;
            }
        }else{
            echo "Accès interdit. Vous devez être administrateur pour accéder à cette page.";
        }

    }

    public function menuGestUser()
    {
        $this->vue->afficherMenuGestionuser();
    }

    public function supprimerUser() {
        if (isset($_GET['id_utilisateur'])) {
            $idUtilisateur = intval($_GET['id_utilisateur']);
            $modele = new ModeleGestUser();
            $modele->supprimerUtilisateur($idUtilisateur);
            // Redirection après suppression
            header('Location: index.php?module=gestuser&action=versModifierDesUsers');
            exit;
        } else {
            // Gestion d'une tentative sans ID
            header('Location: index.php?module=gestuser');
            exit;
        }
    }


    //Bug script template CSV
    public function telechargerTemplateUpdate() {
        $templateFichier = $_SERVER['DOCUMENT_ROOT'] . '/template';
        $templatePath = $templateFichier . '/template_modif.csv';

        // Vérification si le dossier 'template' existe
        if (!is_dir($templateFichier)) {
            // Création du dossier si nécessaire
            if (!mkdir($templateFichier, 0755, true)) {
                die("Le dossier $templateFichier n'a pas pu être créé.");
            }
        }

        // Vérification si le fichier existe
        if (!file_exists($templatePath)) {
            // Création d'un fichier exemple si nécessaire
            $example = "login,nouveau_nom,nouveau_prenom,nouveau_email,nouveau_password,nouveau_type_d_utilisateur\n";
            file_put_contents($templatePath, $example);
        }

        // Configuration des en-têtes pour le téléchargement
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="template_modif.csv"');
        header('Content-Length: ' . filesize($templatePath));

        // Lecture et envoi du fichier
        readfile($templatePath);
        exit;
    }

    //Bug script template CSV
    public function telechargerTemplateAjout() {
        $templateDir = $_SERVER['DOCUMENT_ROOT'] . '/template';
        $templatePath = $templateDir . '/template_ajout.csv';

        // Vérification si le dossier 'template' existe
        if (!is_dir($templateDir)) {
            // Tentative de création du dossier
            if (!mkdir($templateDir, 0755, true)) {
                die("Le dossier $templateDir n'a pas pu être créé.");
            }
        }
        // Vérification si le fichier existe
        if (!file_exists($templatePath)) {
            // Création d'un fichier exemple si nécessaire
            $example = "login,nom,prenom,email,password,type_d_utilisateur\n";
            file_put_contents($templatePath, $example);
        }

        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="template_ajout.csv"');
        header('Content-Length: ' . filesize($templatePath));
        readfile($templatePath);
        exit;
    }


    public function versModifierDesUsers()
    {
        $tabUser = $this->modele->getAllUser();
        $this->vue->afficherTableauAllUser($tabUser);
    }

    public function modifierUser()
    {
        if (isset($_POST['id_utilisateur'])) {
            $id_utilisateur = $_POST['id_utilisateur'];
            $user = $this->modele->getUserById($id_utilisateur);
            $this->vue->afficherInfoUser($user);
        }
    }

    public function enregistrerModifications()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id_utilisateur = $_POST['id_utilisateur'];
            $nom = htmlspecialchars($_POST['nom']);
            $prenom = htmlspecialchars($_POST['prenom']);
            $email = htmlspecialchars($_POST['email']);
            $login = htmlspecialchars($_POST['login']);
//            $password = !empty($_POST['password']) ? password_hash($_POST['password'], PASSWORD_BCRYPT) : null;
            $password = htmlspecialchars($_POST['password']);
            $type = htmlspecialchars($_POST['type']);
            $this->modele->updateUser($id_utilisateur, $nom, $prenom, $email, $login, $password, $type);
        }
        $this->versModifierDesUsers();
    }

    public function addUser()
    {
        $this->vue->formulaireAjoutUser();
    }

    public function ajouterUser()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nom = htmlspecialchars($_POST['nom']);
            $prenom = htmlspecialchars($_POST['prenom']);
            $email = htmlspecialchars($_POST['email']);
            $login = htmlspecialchars($_POST['login']);
//            $password = !empty($_POST['password']) ? password_hash($_POST['password'], PASSWORD_BCRYPT) : null;
            $password = htmlspecialchars($_POST['password']);
            $type = htmlspecialchars($_POST['type']);
            $this->modele->addUser($nom, $prenom, $email, $login, $password, $type);
        }
        $this->menuGestUser();
    }

    public function modifierUserCSV() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST'&& isset($_FILES['csv_file'])) {
            $csvFile = $_FILES['csv_file'];
            if ($csvFile['error'] === UPLOAD_ERR_OK) {
                $filePath = $csvFile['tmp_name'];
                $this->modele->updateUserCSV($filePath);
                $this->versModifierDesUsers();
            }
        }
    }

    public function ajouterUserCSV() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $csvFile = $_FILES['csv_file'];
            if ($csvFile['error'] === UPLOAD_ERR_OK) {
                $filePath = $csvFile['tmp_name'];
                $this->modele->addUserCSV($filePath);
                $this->versModifierDesUsers();
            }
        }

    }


}