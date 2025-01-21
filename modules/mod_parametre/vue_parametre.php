<?php

include_once 'generique/vue_generique.php';

class VueParametre extends VueGenerique
{
    public function __construct()
    {
        parent::__construct();
    }

    public function afficherCompte($compte, $imagePath, $anneesScolaires)
    {
        if (!empty($compte)) {
            ?>
            <div class="container mt-5">
                <h2 class="text-center mb-4">Informations du compte</h2>

                <div class="row justify-content-center">
                    <div class="col-md-6">
                        <div class="card shadow-sm">
                            <div class="card-body">
                                <form action="index.php?module=parametre&action=modifierCompte" method="POST"
                                      enctype="multipart/form-data">
                                    <input type="hidden" name="token" value="<?= $_SESSION['token'] ?>">
                                    <div style="margin-bottom: 20px; text-align: center">
                                        <?php if (!empty($compte[0]['profil_picture'])): ?>
                                            <label for="logoFile">
                                                <img height="75" width="75" style="cursor: pointer;"
                                                     src="<?php echo htmlspecialchars($imagePath[0]); ?>">
                                            </label>
                                            <input type="file" id="logoFile" name="logoFile"
                                                   accept="image/jpeg, image/jpg, image/png" style="display:none;">
                                        <?php else: ?>
                                            <label for="logoFile">
                                                <img height="75" width="75" style="cursor: pointer;"
                                                     src="<?php echo htmlspecialchars($imagePath[0]); ?>">
                                            </label>
                                            <input type="file" id="logoFile" name="logoFile"
                                                   accept="image/jpeg, image/jpg, image/png" style="display:none;">
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
                                        <input type="text" class="form-control" id="login_utilisateur"
                                               name="login_utilisateur"
                                               value="<?php echo htmlspecialchars($compte[0]['login_utilisateur']); ?>"
                                               required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="password_utilisateur" class="form-label">Mot de passe</label>
                                        <input type="password" class="form-control" id="password_utilisateur"
                                               name="password_utilisateur"
                                               placeholder="Nouveau mot de passe">
                                    </div>

                                    <?php if (!empty($anneesScolaires)) : ?>
                                        <div class="mb-3">
                                            <label for="annee_scolaire" class="form-label">Année scolaire</label>
                                            <div class="d-flex">
                                                <div class="me-3">
                                                    <label for="annee_debut" class="form-label">Année de début</label>
                                                    <input type="number" class="form-control" id="annee_debut"
                                                           name="annee_debut"
                                                           value="<?php echo !empty($anneesScolaires[0]) ? htmlspecialchars($anneesScolaires[0]['annee_debut']) : ''; ?>"
                                                           required>
                                                </div>

                                                <div class="me-3">
                                                    <label for="annee_fin" class="form-label">Année de fin</label>
                                                    <input type="number" class="form-control" id="annee_fin"
                                                           name="annee_fin"
                                                           value="<?php echo !empty($anneesScolaires[0]) ? htmlspecialchars($anneesScolaires[0]['annee_fin']) : ''; ?>"
                                                           required>
                                                </div>

                                                <div>
                                                    <label for="semestre" class="form-label">Semestre</label>
                                                    <input type="number" class="form-control" id="semestre"
                                                           name="semestre"
                                                           value="<?php echo !empty($anneesScolaires[0]) ? htmlspecialchars($anneesScolaires[0]['semestre']) : ''; ?>"
                                                           required>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endif; ?>



                                    <div class="d-grid gap-2">
                                        <button type="submit" class="btn btn-primary">Mettre à jour les informations
                                        </button>
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

