<?php

include_once 'generique/vue_generique.php';
Class VueGestUser extends VueGenerique
{
    public function __construct()
    {
        parent::__construct();
    }

    public function afficherMenuGestionuser(){
        ?>
        <div class="container mt-5">
            <div class="row justify-content-center g-4">
                <div class="col-md-4 col-lg-3 d-flex justify-content-center">
                    <div class="card shadow border-0"
                         style="width: 250px; height: 250px; border-radius: 15px;
                         background-color: #f8f9fa; display: flex; flex-direction: column;
                         justify-content: center; align-items: center; text-align: center;">
                        <a class="text-decoration-none" href="index.php?module=gestuser&action=versModifierDesUsers"
                           style="color: #495057;">
                            <h3 style="font-weight: 600; font-size: 1.2rem;">
                                Modifier des Utilisateurs
                            </h3>
                        </a>
                    </div>
                </div>
                <div class="col-md-4 col-lg-3 d-flex justify-content-center">
                    <div class="card shadow border-0"
                         style="width: 250px; height: 250px; border-radius: 15px;
                         background-color: #f8f9fa; display: flex; flex-direction: column;
                         justify-content: center; align-items: center; text-align: center;">
                        <a class="text-decoration-none" href="index.php?module=gestuser&action=addUser"
                           style="color: #495057;">
                            <h3 style="font-weight: 600; font-size: 1.2rem;">
                                Ajouter des Utilisateurs
                            </h3>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }

    public function afficherTableauAllUser($tabUser) {
        ?>
        <div class="container mt-5">
            <h2 class="text-center">Liste des Utilisateurs</h2>
            <form method="post" action="index.php?module=gestuser&action=modifierUser">
                <table class="table table-striped table-hover mt-4">
                    <thead class="thead-dark">
                    <tr>
                        <th scope="col">Login</th>
                        <th scope="col">Nom</th>
                        <th scope="col">Prénom</th>
                        <th scope="col">Email</th>
                        <th scope="col">Type</th>
                        <th scope="col">Modifier</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($tabUser as $user) { ?>
                        <tr>
                            <th scope="row"><?= $user['login_utilisateur'] ?></th>
                            <td><?= htmlspecialchars($user['nom']) ?></td>
                            <td><?= htmlspecialchars($user['prenom']) ?></td>
                            <td><?= htmlspecialchars($user['email']) ?></td>
                            <td><?= ucfirst($user['type_utilisateur']) ?></td>
                            <td>
                                <button type="submit" name="id_utilisateur" value="<?= $user['id_utilisateur'] ?>"
                                        class="btn btn-primary btn-sm">
                                    Modifier
                                </button>
                            </td>
                        </tr>
                    <?php } ?>
                    </tbody>
                </table>
            </form>
        </div>
        <?php
    }

    public function afficherInfoUser($user) {
        ?>
        <div class="container mt-5">
            <h2 class="text-center">Modifier les Informations de l'Utilisateur</h2>
            <form method="post" action="index.php?module=gestuser&action=enregistrerModifications">
                <div class="mb-3">
                    <label for="nom" class="form-label">Nom</label>
                    <input type="text" class="form-control" id="nom" name="nom" value="<?= htmlspecialchars($user['nom']) ?>">
                </div>
                <div class="mb-3">
                    <label for="prenom" class="form-label">Prénom</label>
                    <input type="text" class="form-control" id="prenom" name="prenom" value="<?= htmlspecialchars($user['prenom']) ?>">
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" name="email" value="<?= htmlspecialchars($user['email']) ?>">
                </div>
                <div class="mb-3">
                    <label for="login" class="form-label">Login</label>
                    <input type="text" class="form-control" id="login" name="login" value="<?= htmlspecialchars($user['login_utilisateur']) ?>">
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Mot de passe</label>
                    <input type="password" class="form-control" id="password" name="password" placeholder="********">
                </div>
                <div class="mb-3">
                    <label for="type" class="form-label">Type d'utilisateur</label>
                    <select class="form-select" id="type" name="type" required>
                        <option value="professeur" <?= $user['type_utilisateur'] === 'professeur' ? 'selected' : '' ?>>Professeur</option>
                        <option value="etudiant" <?= $user['type_utilisateur'] === 'etudiant' ? 'selected' : '' ?>>Étudiant</option>
                        <option value="intervenant" <?= $user['type_utilisateur'] === 'intervenant' ? 'selected' : '' ?>>Intervenant</option>
                    </select>
                </div>
                <input type="hidden" name="id_utilisateur" value="<?= $user['id_utilisateur'] ?>">
                <div class="text-center">
                    <button type="submit" class="btn btn-success">Enregistrer</button>
                    <a href="index.php?module=gestuser&action=versModifierDesUsers" class="btn btn-secondary">Annuler</a>
                </div>
            </form>
        </div>
        <?php
    }

    public function formulaireAjoutUser(){
        ?>
        <div class="container mt-5">
            <h2 class="text-center">Ajouter un Nouvel Utilisateur</h2>
            <form method="post" action="index.php?module=gestuser&action=ajouterUser">
                <div class="mb-3">
                    <label for="nom" class="form-label">Nom</label>
                    <input type="text" class="form-control" id="nom" name="nom" required>
                </div>
                <div class="mb-3">
                    <label for="prenom" class="form-label">Prénom</label>
                    <input type="text" class="form-control" id="prenom" name="prenom" required>
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" name="email" required>
                </div>
                <div class="mb-3">
                    <label for="login" class="form-label">Login</label>
                    <input type="text" class="form-control" id="login" name="login" required>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Mot de passe</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                </div>
                <div class="mb-3">
                    <label for="type" class="form-label">Type d'utilisateur</label>
                    <select class="form-select" id="type" name="type" required>
                        <option value="professeur">Professeur</option>
                        <option value="etudiant">Étudiant</option>
                        <option value="intervenant">Intervenant</option>
                    </select>
                </div>
                <div class="text-center">
                    <button type="submit" class="btn btn-primary">Ajouter</button>
                    <a href="index.php?module=gestuser" class="btn btn-secondary">Annuler</a>
                </div>
            </form>
        </div>
        <?php
    }

}