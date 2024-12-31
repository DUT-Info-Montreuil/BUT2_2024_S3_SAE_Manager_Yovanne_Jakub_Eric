<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>SAE Manager</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        .profil {
            position: absolute;
            top: 25px;
            right: 40px;
        }
        header {
            position: relative;
        }
    </style>
</head>
<body>
<header class="text-center m-0 bg-primary text-white py-3 mb-4">
    <h1>SAE MANAGER</h1>
</header>
<div class="profil">
    <?php echo $menu->getAffichage(); ?>
</div>

<div>
    <?= $module_html ?>
</div>

<footer></footer>
<script src="scriptNotation.js" defer></script>     <!-- defer = scripts exécutés dans l'ordre après le chargement du DOM -->
<script src="scriptConfirmationEvaluateur.js" defer></script>
</body>
</html>
