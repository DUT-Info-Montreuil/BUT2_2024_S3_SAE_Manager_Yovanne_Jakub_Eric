<?php

include_once 'generique/vue_generique.php';

class VueParametre extends VueGenerique
{
    public function __construct()
    {
        parent::__construct();
    }

    public function afficherCompte($compte)
    {
        // Vérifie si les données du compte sont présentes
        if (!empty($compte)) {
            ?>

            <div class="container mt-5">
                <h2 class="text-center mb-4">Informations du compte</h2>

                <div class="row justify-content-center">
                    <div class="col-md-6">
                        <div class="card shadow-sm">
                            <div class="card-body">
                                <form action="index.php?module=parametre&action=modifierCompte" method="POST" enctype="multipart/form-data">
                                    <div style="margin-bottom: 20px; text-align: center">

                                        <?php
                                        // Vérifie si le champ 'logo' est vide
                                        if (!empty($compte[0]['logo'])):
                                            ?>
                                            <label for="logoFile">
                                                <img height="75" width="75" src="<?php echo htmlspecialchars($compte[0]['logo']); ?>" style="cursor: pointer;">
                                            </label>
                                            <input type="file" id="logoFile" name="logo" accept="image/jpeg, image/jpg, image/png" style="display:none;">
                                        <?php else: ?>
                                            <label for="logoFile">
                                                <img height="75" width="75" src="path/to/default_image.png" style="cursor: pointer;">
                                            </label>
                                            <input type="file" id="logoFile" name="logo" accept="image/jpeg, image/jpg, image/png" style="display:none;">
                                        <?php endif; ?>
                                    </div>

                                    <div class="mb-3">
                                        <label for="nom" class="form-label">Nom</label>
                                        <input type="text" class="form-control" id="nom" name="nom"
                                               value="<?php echo htmlspecialchars($compte[0]['nom']); ?>" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="prenom" class="form-label">Prénom</label>
                                        <input type="text" class="form-control" id="prenom" name="prenom"
                                               value="<?php echo htmlspecialchars($compte[0]['prenom']); ?>" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="email" class="form-label">Email</label>
                                        <input type="email" class="form-control" id="email" name="email"
                                               value="<?php echo htmlspecialchars($compte[0]['email']); ?>" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="login_utilisateur" class="form-label">Login</label>
                                        <input type="text" class="form-control" id="login_utilisateur" name="login_utilisateur"
                                               value="<?php echo htmlspecialchars($compte[0]['login_utilisateur']); ?>" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="password_utilisateur" class="form-label">Mot de passe</label>
                                        <input type="password" class="form-control" id="password_utilisateur" name="password_utilisateur"
                                               placeholder="Nouveau mot de passe">
                                    </div>
                                    <div class="d-grid gap-2">
                                        <button type="submit" class="btn btn-primary">Mettre à jour les informations</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php
        } else {
            echo "<div class='container mt-5'><p class='text-center text-danger'>Aucun compte trouvé.</p></div>";
        }
    }
}
?>
