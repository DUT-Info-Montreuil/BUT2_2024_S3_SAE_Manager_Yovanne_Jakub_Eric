<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>SAE Manager</title>
    <link href="style.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>
<header class="bg-primary text-white mb-4 d-flex align-items-center justify-content-between">
    <a href="index.php" class="d-flex align-items-center">
        <img src="assets/home.png" alt="Home" width="30" height="30">
    </a>

    <div class="d-flex justify-content-center flex-grow-1">
            <h1 class="mb-0">SAE MANAGER</h1>
    </div>
</header>

<div class="profil">
    <?php echo $menu->getAffichage(); ?>
</div>

<div>
    <?= $module_html ?>
</div>

<footer></footer>
</body>
<script type="text/javascript" src="script/addCritere.js"></script>
<script type="text/javascript" src="script/rechercheEtudiant.js"></script>
<script type="text/javascript" src="script/choixNotation.js"></script>
<script type="text/javascript" src="script/rechercheGerant.js"></script>
<script type="text/javascript" src="script/scriptDelegation.js"></script>
<script type="text/javascript" src="script/confirmationSuppr.js"></script>
</html>
