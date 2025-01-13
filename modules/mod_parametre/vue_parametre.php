<?php

include_once 'generique/vue_generique.php';
Class VueParametre extends VueGenerique
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
            <h2 style="text-align:center;">Informations du compte</h2>

            <!-- Formulaire pour modifier les informations de l'utilisateur -->
            <div style="display: flex; justify-content: center; align-items: center; height: 100vh;">
                <div style="border: 1px solid #ccc; padding: 20px; background-color: #f9f9f9;">
                    <form action="modification.php" method="POST">
                        <table border="1" style="border-collapse: collapse; width: 100%; text-align: left;">
                            <!-- Nom -->
                            <tr>
                                <th style="padding: 8px;">Nom</th>
                                <td style="padding: 8px;">
                                    <input type="text" name="nom" value="<?php echo htmlspecialchars($compte[0]['nom']); ?>" required>
                                </td>
                            </tr>
                            <!-- Prénom -->
                            <tr>
                                <th style="padding: 8px;">Prénom</th>
                                <td style="padding: 8px;">
                                    <input type="text" name="prenom" value="<?php echo htmlspecialchars($compte[0]['prenom']); ?>" required>
                                </td>
                            </tr>
                            <!-- Email -->
                            <tr>
                                <th style="padding: 8px;">Email</th>
                                <td style="padding: 8px;">
                                    <input type="email" name="email" value="<?php echo htmlspecialchars($compte[0]['email']); ?>" required>
                                </td>
                            </tr>
                            <!-- Login -->
                            <tr>
                                <th style="padding: 8px;">Login</th>
                                <td style="padding: 8px;">
                                    <input type="text" name="login_utilisateur" value="<?php echo htmlspecialchars($compte[0]['login_utilisateur']); ?>" required>
                                </td>
                            </tr>
                            <!-- Mot de passe -->
                            <tr>
                                <th style="padding: 8px;">Mot de passe</th>
                                <td style="padding: 8px;">
                                    <input type="password" name="password_utilisateur" value="" placeholder="Nouveau mot de passe">
                                </td>
                            </tr>
                        </table>
                        <br>
                        <!-- Bouton pour soumettre le formulaire -->
                        <button type="submit" style="padding: 10px; background-color: #4CAF50; color: white;">Mettre à jour les informations</button>
                    </form>
                </div>
            </div>
            <?php
        } else {
            echo "<p>Aucun compte trouvé.</p>";
        }
    }
}
?>