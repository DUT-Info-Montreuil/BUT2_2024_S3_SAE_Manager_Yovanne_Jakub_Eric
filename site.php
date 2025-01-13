<?php
include "headerController.php";
?>

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
        <?php if (isset($headerUrl)): ?>
            <a href="<?= $headerUrl ?>" class="text-white text-decoration-none">
                <h1 class="mb-0">SAE MANAGER</h1>
            </a>
        <?php else: ?>
            <h1 class="mb-0">SAE MANAGER</h1>
        <?php endif; ?>
    </div>
</header>

<div class="profil">
    <?php echo $menu->getAffichage(); ?>
</div>

<div>
    <?= $module_html ?>
</div>

<footer></footer>
<script src="scriptNotation.js" defer></script>
<script src="scriptDelegation.js" defer></script>
<script src="scriptConfirmationSuppr.js"></script>
</body>
</html>
